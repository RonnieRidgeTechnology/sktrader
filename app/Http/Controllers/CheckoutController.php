<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Setting;
use App\Models\OrderItem;
use App\Services\CartService;
use App\Support\StoreCurrency;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Mail\OrderConfirmed;
use Illuminate\Support\Facades\Mail;

class CheckoutController extends Controller
{
    public function __construct(
        private CartService $cart
    ) {}

    public function show(Request $request)
    {
        $items = $this->cart->getItems();
        if (empty($items)) {
            return redirect()->route('cart')->with('error', 'Your cart is empty.');
        }
        $subtotal = $this->cart->getSubtotal();

        $paymentMethods = [
            ['id' => Order::PAYMENT_COD, 'label' => 'Cash on Delivery (COD)', 'description' => 'Pay with cash when your order is delivered.'],
            ['id' => Order::PAYMENT_BANK_TRANSFER, 'label' => 'Direct Bank Transfer', 'description' => 'Transfer money to our bank account. Deposit receipt required.'],
            ['id' => Order::PAYMENT_JAZZCASH, 'label' => 'JazzCash', 'description' => 'Pay securely via your JazzCash account online.'],
        ];

        $user = $request->user();

        return Inertia::render('Checkout/Index', [
            'title' => 'Checkout',
            'cart' => [
                'items' => $items,
                'count' => $this->cart->getCount(),
                'subtotal' => round($subtotal, 2),
            ],
            'payment_methods' => $paymentMethods,
            'currency' => StoreCurrency::code(),
            'user' => $user ? ['name' => $user->name, 'email' => $user->email] : null,
            'bank_account_details' => Setting::get('bank_account_details', 'Bank: HBL, Account Title: SK Traders, Account No: 0000-0000-0000-0000'),
        ]);
    }

    public function store(Request $request)
    {
        $items = $this->cart->getItems();
        if (empty($items)) {
            return redirect()->route('cart')->with('error', 'Your cart is empty.');
        }

        $paymentMethodRules = 'required|in:'.Order::PAYMENT_COD.','.Order::PAYMENT_BANK_TRANSFER.','.Order::PAYMENT_JAZZCASH;

        $validated = $request->validate([
            'payment_method' => $paymentMethodRules,
            'guest_name' => 'required|string|max:255',
            'guest_email' => 'required|email',
            'guest_phone' => 'required|string|max:50',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'region' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'required|string|max:100',
            'order_notes' => 'nullable|string|max:1000',
            'payment_proof' => 'required_if:payment_method,' . Order::PAYMENT_BANK_TRANSFER . '|nullable|image|mimes:jpeg,png,jpg|max:5120',
        ], [
            'payment_proof.required_if' => 'Please upload a screenshot or receipt of your bank transfer.',
            'payment_proof.image' => 'The payment proof must be an image file (jpeg, png, jpg).',
        ]);

        $subtotal = $this->cart->getSubtotal();
        $total = $subtotal;
        $paymentMethod = $validated['payment_method'];

        $shippingAddress = [
            'name' => $validated['guest_name'],
            'phone' => $validated['guest_phone'],
            'address_line_1' => $validated['address_line_1'],
            'address_line_2' => $validated['address_line_2'] ?? '',
            'city' => $validated['city'],
            'region' => $validated['region'] ?? '',
            'postal_code' => $validated['postal_code'] ?? '',
            'country' => $validated['country'],
        ];

        $proofPath = null;
        if ($request->hasFile('payment_proof')) {
            $proofPath = $request->file('payment_proof')->store('payment_proofs', 'public');
        }

        $order = DB::transaction(function () use ($request, $validated, $paymentMethod, $shippingAddress, $subtotal, $total, $items, $proofPath) {
            $order = Order::create([
                'user_id' => $request->user()?->id,
                'number' => Order::generateNumber(),
                'guest_email' => $validated['guest_email'],
                'guest_name' => $validated['guest_name'],
                'guest_phone' => $validated['guest_phone'],
                'status' => Order::STATUS_PENDING,
                'payment_method' => $paymentMethod,
                'payment_proof' => $proofPath,
                'shipping_address' => $shippingAddress,
                'subtotal' => $subtotal,
                'total' => $total,
                'currency' => StoreCurrency::code(),
                'order_notes' => $validated['order_notes'] ?? null,
            ]);

            foreach ($items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'product_name' => $item['name'],
                    'product_slug' => $item['slug'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                ]);
            }

            return $order;
        });

        if ($paymentMethod === Order::PAYMENT_JAZZCASH) {
            return redirect()->route('checkout.jazzcash.pay', ['order' => $order->number]);
        }

        $offlineMethods = [Order::PAYMENT_COD, Order::PAYMENT_BANK_TRANSFER];
        $isOffline = in_array($paymentMethod, $offlineMethods);

        if ($isOffline) {
            $this->cart->clear();

            try {
                Mail::to($order->guest_email)->send(new \App\Mail\OrderConfirmed($order));
            } catch (\Exception $e) {
                // Silently bypass email failures to not break checkout
            }

            return redirect()
                ->route('checkout.thank-you', ['order' => $order->number])
                ->with('success', 'Order placed successfully.');
        }

        // Just a fallback
        return redirect()->route('checkout.thank-you', ['order' => $order->number]);
    }

    public function jazzcashPay(Request $request, string $orderNumber)
    {
        $order = Order::where('number', $orderNumber)->first();
        if (! $order || $order->payment_method !== Order::PAYMENT_JAZZCASH) {
            return redirect()->route('cart')->with('error', 'Order not found.');
        }

        if ($order->status !== Order::STATUS_PENDING) {
            $this->cart->clear();
            return redirect()->route('checkout.thank-you', ['order' => $order->number])
                ->with('success', 'This order is already complete.');
        }

        // Logic to build JazzCash post redirect (usually via a form auto-submit)
        $merchantId = \App\Models\Setting::get('jazzcash_merchant_id', '');
        $password = \App\Models\Setting::get('jazzcash_password', '');
        $salt = \App\Models\Setting::get('jazzcash_salt', '');
        
        $price = $order->total * 100; // formatted as cents usually
        $txnRefNo = 'T' . date('YmdHis');
        
        // This is a mockup of the necessary attributes, the actual JazzCash
        // payment structure usually requires a secure hash generated using salt + data.
        
        $postData = [
            'pp_Version' => '1.1',
            'pp_TxnType' => 'MWALLET',
            'pp_Language' => 'EN',
            'pp_MerchantID' => $merchantId,
            'pp_SubMerchantID' => '',
            'pp_Password' => $password,
            'pp_BankID' => 'TBANK',
            'pp_ProductID' => 'RETL',
            'pp_TxnRefNo' => $txnRefNo,
            'pp_Amount' => $price,
            'pp_TxnCurrency' => 'PKR',
            'pp_TxnDateTime' => date('YmdHis'),
            'pp_BillReference' => 'billRef',
            'pp_Description' => 'Order payment',
            'pp_TxnExpiryDateTime' => date('YmdHis', strtotime('+1 Days')),
            'pp_ReturnURL' => route('checkout.jazzcash.return', $order->number),
            'pp_SecureHash' => '', // Will be calculated securely using salt
        ];
        
        return Inertia::render('Checkout/JazzCashPay', [
            'title' => 'Pay with JazzCash',
            'order' => $order,
            'postData' => $postData,
            'endpoint' => 'https://sandbox.jazzcash.com.pk/CustomerPortal/transactionmanagement/merchantform/' // or live
        ]);
    }

    public function jazzcashReturn(Request $request, string $orderNumber)
    {
        $order = Order::where('number', $orderNumber)->first();
        if (! $order) {
            return redirect()->route('home')->with('error', 'Order not found.');
        }
        
        // Normally, JazzCash POSTs the response data here
        // And we verify the hash.
        $status = $request->input('pp_ResponseCode');
        $txnRef = $request->input('pp_TxnRefNo');
        
        if ($status === '000') {
            $order->update([
                'status' => Order::STATUS_CONFIRMED,
                'payment_reference' => $txnRef
            ]);
            $this->cart->clear();
            
            try {
                Mail::to($order->guest_email)->send(new OrderConfirmed($order));
            } catch (\Exception $e) {}

            return redirect()->route('checkout.thank-you', ['order' => $order->number])
                ->with('success', 'Payment successful. Thank you!');
        }
        
        return redirect()->route('checkout.thank-you', ['order' => $order->number])
            ->with('error', 'Payment failed or cancelled.');
    }

    public function thankYou(Request $request, string $order)
    {
        $orderModel = Order::where('number', $order)->with('items')->first();
        if (! $orderModel) {
            return redirect()->route('home')->with('error', 'Order not found.');
        }

        return Inertia::render('Checkout/ThankYou', [
            'title' => 'Thank You',
            'order' => [
                'number' => $orderModel->number,
                'status' => $orderModel->status,
                'total' => (float) $orderModel->total,
                'currency' => $orderModel->currency,
                'payment_method' => $orderModel->payment_method,
                'guest_name' => $orderModel->guest_name,
                'guest_email' => $orderModel->guest_email,
                'items' => $orderModel->items->map(fn (OrderItem $i) => [
                    'product_name' => $i->product_name,
                    'quantity' => $i->quantity,
                    'price' => (float) $i->price,
                    'line_total' => $i->line_total,
                ])->all(),
            ],
        ]);
    }
}
