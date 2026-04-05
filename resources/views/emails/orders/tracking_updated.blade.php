<x-mail::message>
# Order Tracking Update - {{ $order->number }}

Hi {{ $order->guest_name ?? 'Customer' }},

Your order status has been updated to: **{{ ucfirst($order->status) }}**.

@if($order->courier_name || $order->tracking_number)
Great news! We have added tracking details to your order.

**Courier:** {{ $order->courier_name ?? 'N/A' }}
**Tracking Number:** {{ $order->tracking_number ?? 'N/A' }}

@if($order->tracking_url)
<x-mail::button :url="$order->tracking_url">
Track Shipment
</x-mail::button>
@endif

@endif

<x-mail::button :url="url('/track')">
View Order Details
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
