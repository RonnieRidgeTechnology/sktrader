<x-mail::message>
# Order Confirmation - {{ $order->number }}

Hi {{ $order->guest_name ?? 'Customer' }},

Thank you for your order! We've received your request and are preparing it. 

Here are the details of your order:

<x-mail::table>
| Product | Quantity | Price |
| :------ | :------: | ----: |
@foreach($order->items as $item)
| {{ $item->product_name }} | {{ $item->quantity }} | {{ $order->currency }} {{ number_format($item->price, 2) }} |
@endforeach
</x-mail::table>

**Subtotal:** {{ $order->currency }} {{ number_format($order->subtotal, 2) }}
**Total:** {{ $order->currency }} {{ number_format($order->total, 2) }}

### Payment Method
{{ match($order->payment_method) {
    'cod' => 'Cash on Delivery',
    'bank_transfer' => 'Bank Transfer',
    'jazzcash' => 'JazzCash',
    'easypaisa' => 'Easypaisa',
    'zynlepay' => 'Online Payment (ZynlePay)',
    'paypal' => 'PayPal',
    default => 'Standard Checkout'
} }}

<x-mail::button :url="url('/track')">
Track Your Order
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
