<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Setting;
use App\Models\OrderItem;
use App\Services\CartService;
use Illuminate\Support\Facades\DB;
use App\Services\PayPalService;
use App\Services\ZynlePayService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CheckoutController extends Controller
{
    public function __construct(
        private CartService $cart,
        private ZynlePayService $zynlePay,
        private PayPalService $payPal
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
        ];
        if (ZynlePayService::isConfigured()) {
            $paymentMethods[] = ['id' => Order::PAYMENT_ZYNLEPAY, 'label' => 'Pay online with ZynlePay', 'description' => 'Mobile Money or card. You will complete payment on the next step.'];
        }
        if (PayPalService::isConfigured()) {
            $paymentMethods[] = ['id' => Order::PAYMENT_PAYPAL, 'label' => 'Pay with PayPal', 'description' => 'Secure checkout with PayPal (card or PayPal balance).'];
        }

        $user = $request->user();

        return Inertia::render('Checkout/Index', [
            'title' => 'Checkout',
            'cart' => [
                'items' => $items,
                'count' => $this->cart->getCount(),
                'subtotal' => round($subtotal, 2),
            ],
            'payment_methods' => $paymentMethods,
            'currency' => 'ZMW',
            'user' => $user ? ['name' => $user->name, 'email' => $user->email] : null,
        ]);
    }

    public function store(Request $request)
    {
        $items = $this->cart->getItems();
        if (empty($items)) {
            return redirect()->route('cart')->with('error', 'Your cart is empty.');
        }

        $paymentMethodRules = 'required|in:'.Order::PAYMENT_COD;
        if (ZynlePayService::isConfigured()) {
            $paymentMethodRules .= ','.Order::PAYMENT_ZYNLEPAY;
        }
        if (PayPalService::isConfigured()) {
            $paymentMethodRules .= ','.Order::PAYMENT_PAYPAL;
        }

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

        $order = DB::transaction(function () use ($request, $validated, $paymentMethod, $shippingAddress, $subtotal, $total, $items) {
            $order = Order::create([
                'user_id' => $request->user()?->id,
                'number' => Order::generateNumber(),
                'guest_email' => $validated['guest_email'],
                'guest_name' => $validated['guest_name'],
                'guest_phone' => $validated['guest_phone'],
                'status' => Order::STATUS_PENDING,
                'payment_method' => $paymentMethod,
                'shipping_address' => $shippingAddress,
                'subtotal' => $subtotal,
                'total' => $total,
                'currency' => 'ZMW',
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

        if ($paymentMethod === Order::PAYMENT_COD) {
            $this->cart->clear();

            return redirect()
                ->route('checkout.thank-you', ['order' => $order->number])
                ->with('success', 'Order placed successfully.');
        }

        if ($paymentMethod === Order::PAYMENT_PAYPAL) {
            return redirect()
                ->route('checkout.paypal.start', ['order' => $order->number]);
        }

        // ZynlePay: redirect to payment page; do not clear cart until payment is confirmed
        return redirect()
            ->route('checkout.pay', ['order' => $order->number])
            ->with('success', 'Order created. Complete payment below.');
    }

    /**
     * Create PayPal order and send the customer to PayPal to approve payment.
     */
    public function paypalStart(Request $request, string $orderNumber)
    {
        $order = Order::where('number', $orderNumber)->first();
        if (! $order) {
            return redirect()->route('cart')->with('error', 'Order not found.');
        }
        if ($order->payment_method !== Order::PAYMENT_PAYPAL) {
            return redirect()->route('checkout.thank-you', ['order' => $order->number]);
        }
        if ($order->status !== Order::STATUS_PENDING) {
            $this->cart->clear();

            return redirect()->route('checkout.thank-you', ['order' => $order->number])
                ->with('success', 'This order is already complete.');
        }
        if (! PayPalService::isConfigured()) {
            return redirect()->route('checkout')->with('error', 'PayPal is not available. Choose another payment method or contact us.');
        }

        $returnUrl = route('checkout.paypal.return', ['order' => $order->number], true);
        $cancelUrl = route('checkout.paypal.cancel', ['order' => $order->number], true);

        $result = $this->payPal->createCheckoutOrder($order, $returnUrl, $cancelUrl);
        if (! ($result['success'] ?? false)) {
            return redirect()->route('checkout')->with('error', $result['message'] ?? 'Could not start PayPal checkout.');
        }

        $order->update(['payment_reference' => $result['paypal_order_id']]);

        return redirect()->away($result['approval_url']);
    }

    /**
     * PayPal redirects here after the buyer approves the payment.
     */
    public function paypalReturn(Request $request)
    {
        $orderNumber = $request->query('order');
        $token = $request->query('token');
        if (! $orderNumber || ! $token) {
            return redirect()->route('cart')->with('error', 'Invalid PayPal response.');
        }

        $order = Order::where('number', $orderNumber)->first();
        if (! $order || $order->payment_method !== Order::PAYMENT_PAYPAL) {
            return redirect()->route('cart')->with('error', 'Order not found.');
        }

        if ($order->status !== Order::STATUS_PENDING) {
            $this->cart->clear();

            return redirect()->route('checkout.thank-you', ['order' => $order->number])
                ->with('success', 'Your order is already confirmed.');
        }

        if ((string) $order->payment_reference !== (string) $token) {
            return redirect()->route('cart')->with('error', 'Payment session does not match this order.');
        }

        $capture = $this->payPal->captureOrder($token);
        if (! ($capture['success'] ?? false) || ! ($capture['captured'] ?? false)) {
            return redirect()->route('checkout.paypal.cancel', ['order' => $order->number])
                ->with('error', $capture['message'] ?? 'Payment could not be completed.');
        }

        $order->update([
            'status' => Order::STATUS_CONFIRMED,
            'payment_reference' => $capture['capture_id'] ?? $token,
        ]);
        $this->cart->clear();

        return redirect()->route('checkout.thank-you', ['order' => $order->number])
            ->with('success', 'Payment successful. Thank you!');
    }

    /**
     * Buyer cancelled on PayPal; order stays pending so they can retry or contact support.
     */
    public function paypalCancel(Request $request, string $orderNumber)
    {
        $order = Order::where('number', $orderNumber)->first();
        if (! $order || $order->payment_method !== Order::PAYMENT_PAYPAL) {
            return redirect()->route('cart')->with('error', 'Order not found.');
        }

        return Inertia::render('Checkout/PayPalCancel', [
            'title' => 'Payment cancelled',
            'order' => [
                'number' => $order->number,
                'total' => (float) $order->total,
                'currency' => $order->currency,
            ],
        ]);
    }

    public function pay(Request $request, string $orderNumber)
    {
        $order = Order::where('number', $orderNumber)->with('items')->first();
        if (! $order) {
            return redirect()->route('cart')->with('error', 'Order not found.');
        }
        if ($order->payment_method !== Order::PAYMENT_ZYNLEPAY) {
            return redirect()->route('checkout.thank-you', ['order' => $order->number]);
        }
        if ($order->status !== Order::STATUS_PENDING) {
            return redirect()->route('checkout.thank-you', ['order' => $order->number])
                ->with('success', 'This order has already been paid.');
        }

        $zynlepayChannel = Setting::get('zynlepay_channel', 'momo');

        return Inertia::render('Checkout/Pay', [
            'title' => 'Pay with ZynlePay',
            'zynlepay_channel' => $zynlepayChannel,
            'order' => [
                'number' => $order->number,
                'total' => (float) $order->total,
                'currency' => $order->currency,
                'guest_phone' => $order->guest_phone,
            ],
        ]);
    }

    public function initiatePayment(Request $request)
    {
        $channel = Setting::get('zynlepay_channel', 'momo');
        $phoneRule = $channel === 'card' ? 'nullable|string|max:30' : 'required|string|max:30';

        $validated = $request->validate([
            'order_number' => 'required|string|max:32',
            'phone' => $phoneRule,
        ]);

        $order = Order::where('number', $validated['order_number'])->first();
        if (! $order || $order->payment_method !== Order::PAYMENT_ZYNLEPAY || $order->status !== Order::STATUS_PENDING) {
            return back()->with('error', 'Invalid order or order already paid.');
        }

        $senderPhone = $validated['phone'] ?? $order->guest_phone ?? '';
        $result = $this->zynlePay->runBillPayment(
            $senderPhone,
            $order->number,
            (float) $order->total,
            'Order ' . $order->number
        );

        if (! empty($result['transaction_id'])) {
            $order->update(['payment_reference' => $result['transaction_id']]);
        }

        if ($result['success'] ?? false) {
            $message = $channel === 'card'
                ? ($result['message'] ?? 'Payment initiated. Complete payment on the next step.')
                : ($result['message'] ?? 'Payment initiated. Check your phone to confirm.');
            return back()->with('success', $message);
        }

        return back()->with('error', $result['message'] ?? 'Payment could not be initiated.');
    }

    public function paymentStatus(Request $request, string $orderNumber)
    {
        $order = Order::where('number', $orderNumber)->first();
        if (! $order || $order->payment_method !== Order::PAYMENT_ZYNLEPAY) {
            return redirect()->route('cart')->with('error', 'Order not found.');
        }
        if ($order->status !== Order::STATUS_PENDING) {
            $this->cart->clear();

            return redirect()->route('checkout.thank-you', ['order' => $order->number]);
        }

        $status = $this->zynlePay->checkPaymentStatus($order->number);
        if (($status['success'] ?? false) && ($status['paid'] ?? false)) {
            $order->update(['status' => Order::STATUS_CONFIRMED]);
            $this->cart->clear();

            return redirect()->route('checkout.thank-you', ['order' => $order->number])
                ->with('success', 'Payment confirmed. Thank you!');
        }

        return back()->with('error', $status['message'] ?? 'Payment not yet confirmed. Please complete the payment on your phone and try again.');
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
