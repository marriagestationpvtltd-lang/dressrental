<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $booking->id }} — {{ setting('site_name', 'DressRental') }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; font-size: 13px; color: #1f2937; background: #fff; }
        .page { max-width: 760px; margin: 0 auto; padding: 40px 32px; }
        .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 32px; }
        .brand { font-size: 22px; font-weight: 800; color: #7c3aed; }
        .brand-sub { font-size: 11px; color: #6b7280; margin-top: 2px; }
        .invoice-meta { text-align: right; }
        .invoice-meta h2 { font-size: 28px; font-weight: 700; color: #111827; letter-spacing: -0.5px; }
        .invoice-meta .inv-num { font-size: 13px; color: #6b7280; }
        .invoice-meta .inv-date { font-size: 12px; color: #9ca3af; margin-top: 4px; }
        .status-badge { display: inline-block; padding: 3px 10px; border-radius: 999px; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-top: 6px; }
        .status-pending   { background: #fef3c7; color: #92400e; }
        .status-paid      { background: #dbeafe; color: #1e40af; }
        .status-active    { background: #d1fae5; color: #065f46; }
        .status-returned  { background: #ede9fe; color: #5b21b6; }
        .status-completed { background: #f3f4f6; color: #374151; }
        .status-cancelled { background: #fee2e2; color: #991b1b; }
        hr.divider { border: none; border-top: 1px solid #e5e7eb; margin: 20px 0; }
        .two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px; }
        .info-box h4 { font-size: 10px; font-weight: 700; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 8px; }
        .info-box p { font-size: 13px; color: #374151; line-height: 1.6; }
        .info-box strong { color: #111827; }
        .dress-row { display: flex; align-items: center; gap: 14px; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 10px; padding: 12px; margin-bottom: 24px; }
        .dress-img { width: 64px; height: 64px; object-fit: cover; border-radius: 8px; border: 1px solid #e5e7eb; }
        .dress-img-placeholder { width: 64px; height: 64px; background: #f3f4f6; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #d1d5db; font-size: 24px; border: 1px solid #e5e7eb; }
        .dress-info strong { font-size: 15px; color: #111827; display: block; }
        .dress-info span { font-size: 12px; color: #6b7280; }
        table.breakdown { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        table.breakdown th { background: #f3f4f6; text-align: left; padding: 8px 12px; font-size: 11px; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; }
        table.breakdown td { padding: 9px 12px; border-bottom: 1px solid #f3f4f6; font-size: 13px; }
        table.breakdown tr:last-child td { border-bottom: none; }
        .total-row td { font-weight: 700; font-size: 15px; background: #f9fafb; color: #111827; }
        .discount-row td { color: #16a34a; }
        .fine-row td { color: #dc2626; }
        .advance-row td { color: #7c3aed; }
        table.payments { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        table.payments th { background: #f3f4f6; text-align: left; padding: 8px 12px; font-size: 11px; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; }
        table.payments td { padding: 8px 12px; border-bottom: 1px solid #f3f4f6; font-size: 12px; }
        table.payments tr:last-child td { border-bottom: none; }
        .section-title { font-size: 12px; font-weight: 700; color: #374151; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 10px; }
        .footer { text-align: center; font-size: 11px; color: #9ca3af; margin-top: 40px; padding-top: 20px; border-top: 1px solid #e5e7eb; }
        @media print {
            body { print-color-adjust: exact; -webkit-print-color-adjust: exact; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>
<div class="page">

    {{-- Print / Close button --}}
    <div class="no-print" style="text-align:right; margin-bottom:16px;">
        <button onclick="window.print()" style="background:#7c3aed;color:#fff;border:none;padding:8px 20px;border-radius:8px;font-size:13px;cursor:pointer;font-weight:600;margin-right:8px;">🖨 Print / Save PDF</button>
        <button onclick="window.close()" style="background:#f3f4f6;color:#374151;border:none;padding:8px 16px;border-radius:8px;font-size:13px;cursor:pointer;">✕ Close</button>
    </div>

    {{-- Header --}}
    <div class="header">
        <div>
            <div class="brand">{{ setting('site_name', 'DressRental') }}</div>
            <div class="brand-sub">{{ setting('site_tagline', 'Premium Dress Rental Service') }}</div>
            @if(setting('contact_phone'))
                <div class="brand-sub" style="margin-top:4px;">📞 {{ setting('contact_phone') }}</div>
            @endif
            @if(setting('contact_address'))
                <div class="brand-sub">📍 {{ setting('contact_address') }}</div>
            @endif
        </div>
        <div class="invoice-meta">
            <h2>INVOICE</h2>
            <div class="inv-num">#{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</div>
            <div class="inv-date">Issued: {{ now()->format('M d, Y') }}</div>
            <div>
                <span class="status-badge status-{{ $booking->status }}">{{ ucfirst($booking->status) }}</span>
            </div>
        </div>
    </div>

    <hr class="divider">

    {{-- Customer & Rental Period --}}
    <div class="two-col">
        <div class="info-box">
            <h4>Bill To</h4>
            <p>
                <strong>{{ $booking->user->name }}</strong><br>
                {{ $booking->user->email }}<br>
                @if($booking->user->phone){{ $booking->user->phone }}<br>@endif
                @if($booking->user->address){{ $booking->user->address }}@endif
            </p>
        </div>
        <div class="info-box">
            <h4>Rental Period</h4>
            <p>
                <strong>From:</strong>
                {{ $booking->bs_start_date ? $booking->bs_start_date . ' (' . $booking->start_date->format('M d, Y') . ')' : $booking->start_date->format('M d, Y') }}<br>
                <strong>To:</strong>
                {{ $booking->bs_end_date ? $booking->bs_end_date . ' (' . $booking->end_date->format('M d, Y') . ')' : $booking->end_date->format('M d, Y') }}<br>
                <strong>Duration:</strong> {{ $booking->total_days }} day{{ $booking->total_days > 1 ? 's' : '' }}<br>
                @if($booking->returned_at)
                    <strong>Returned:</strong> {{ $booking->returned_at->format('M d, Y') }}
                @endif
            </p>
        </div>
    </div>

    {{-- Dress Details --}}
    <div class="section-title">Booked Item</div>
    <div class="dress-row">
        @php $dressImage = $booking->dress->primaryImage(); @endphp
        @if($dressImage)
            <img src="{{ $dressImage->url }}" alt="{{ $booking->dress->name }}" class="dress-img">
        @else
            <div class="dress-img-placeholder">👗</div>
        @endif
        <div class="dress-info">
            <strong>{{ $booking->dress->name }}</strong>
            <span>Size: {{ $booking->dress->size }}
                @if($booking->dress->color) · Color: {{ $booking->dress->color }}@endif
                @if($booking->dress->brand) · Brand: {{ $booking->dress->brand }}@endif
            </span>
            <span>Price per day: ₨{{ number_format($booking->dress->price_per_day) }}</span>
        </div>
    </div>

    {{-- Price Breakdown --}}
    <div class="section-title">Price Breakdown</div>
    <table class="breakdown">
        <thead>
            <tr>
                <th>Description</th>
                <th style="text-align:right">Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Rental ({{ $booking->total_days }} day{{ $booking->total_days > 1 ? 's' : '' }} × ₨{{ number_format($booking->dress->price_per_day) }})</td>
                <td style="text-align:right">₨{{ number_format($booking->rental_amount) }}</td>
            </tr>
            @if($booking->deposit_amount > 0)
            <tr>
                <td>Security Deposit</td>
                <td style="text-align:right">₨{{ number_format($booking->deposit_amount) }}</td>
            </tr>
            @endif
            @if($booking->discount_type !== 'none' && $booking->discount_amount > 0)
            <tr class="discount-row">
                <td>Discount
                    @if($booking->discount_type === 'percentage')({{ $booking->discount_amount }}%)@endif
                    @if($booking->discount_type === 'fixed')(Fixed)@endif
                </td>
                <td style="text-align:right">−₨{{ number_format($booking->discount_applied) }}</td>
            </tr>
            @endif
            @if($booking->fine_amount > 0)
            <tr class="fine-row">
                <td>Late Return Fine</td>
                <td style="text-align:right">₨{{ number_format($booking->fine_amount) }}</td>
            </tr>
            @endif
            <tr class="total-row">
                <td>Total Amount</td>
                <td style="text-align:right">₨{{ number_format($booking->total_amount) }}</td>
            </tr>
            <tr class="advance-row">
                <td>Advance Paid</td>
                <td style="text-align:right">₨{{ number_format($booking->advance_amount) }}</td>
            </tr>
        </tbody>
    </table>

    {{-- Payment History --}}
    @if($booking->payments->count())
    <div class="section-title">Payment History</div>
    <table class="payments">
        <thead>
            <tr>
                <th>#</th>
                <th>Date</th>
                <th>Type</th>
                <th>Method</th>
                <th>Transaction ID</th>
                <th style="text-align:right">Amount</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($booking->payments as $i => $payment)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $payment->created_at->format('M d, Y') }}</td>
                <td style="text-transform:capitalize">{{ str_replace('_', ' ', $payment->payment_type) }}</td>
                <td style="text-transform:uppercase">{{ $payment->payment_method }}</td>
                <td style="font-size:11px;color:#6b7280">{{ $payment->transaction_id ?? '—' }}</td>
                <td style="text-align:right;font-weight:600">₨{{ number_format($payment->amount) }}</td>
                <td style="text-transform:capitalize">{{ $payment->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    {{-- Notes --}}
    @if($booking->notes)
    <div style="background:#fffbeb;border:1px solid #fde68a;border-radius:8px;padding:12px;margin-bottom:24px;">
        <div class="section-title" style="margin-bottom:4px;">Notes</div>
        <p style="font-size:13px;color:#374151">{{ $booking->notes }}</p>
    </div>
    @endif

    {{-- Footer --}}
    <div class="footer">
        <p>Thank you for choosing <strong>{{ setting('site_name', 'DressRental') }}</strong>.</p>
        @if(setting('contact_email'))
            <p style="margin-top:4px;">Questions? Contact us at {{ setting('contact_email') }}</p>
        @endif
        <p style="margin-top:8px;">This is a computer-generated invoice and does not require a signature.</p>
    </div>

</div>
</body>
</html>
