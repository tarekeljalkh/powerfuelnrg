<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $order->id }}</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .invoice-box { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; }
        .invoice-table { width: 100%; line-height: inherit; text-align: left; }
        .invoice-table th { background: #eee; border-bottom: 1px solid #ddd; font-weight: bold; }
        .invoice-table td { padding: 5px; vertical-align: top; }
    </style>
</head>
<body>
    <div class="invoice-box">
        <table class="invoice-table">
            <tr>
                <th colspan="2">Invoice #{{ $order->id }}</th>
                <th>Date: {{ $order->order_date->format('Y-m-d') }}</th>
            </tr>
            <tr>
                <td colspan="2">
                    <strong>Client:</strong><br>
                    {{ $order->client->first_name }} {{ $order->client->last_name }}<br>
                    {{ $order->client->email }}
                </td>
                <td>
                    <strong>Fuel Type:</strong><br>
                    {{ $order->inventory->fuel_type }}
                </td>
            </tr>
            <tr>
                <td>
                    <strong>Quantity:</strong><br>
                    {{ $order->quantity }} liters
                </td>
                <td>
                    <strong>Price per Unit:</strong><br>
                    ${{ number_format($order->price, 2) }}
                </td>
                <td>
                    <strong>Total Price:</strong><br>
                    ${{ number_format($order->total, 2) }}
                </td>
            </tr>
            <tr>
                <td colspan="3" style="padding-top: 20px;">
                    <strong>Status:</strong> {{ ucfirst($order->status) }}
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
