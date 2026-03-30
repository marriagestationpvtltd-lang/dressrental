<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Status Updated</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f7; margin: 0; padding: 0; color: #333; }
        .wrapper { max-width: 600px; margin: 40px auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,.08); }
        .header { background: #7c3aed; padding: 32px 40px; text-align: center; }
        .header h1 { color: #fff; margin: 0; font-size: 22px; }
        .header p { color: #ede9fe; margin: 6px 0 0; font-size: 14px; }
        .body { padding: 32px 40px; }
        .greeting { font-size: 16px; margin-bottom: 20px; }
        .section { margin-bottom: 24px; }
        .section-title { font-size: 13px; font-weight: bold; text-transform: uppercase; letter-spacing: .5px; color: #7c3aed; border-bottom: 1px solid #ede9fe; padding-bottom: 6px; margin-bottom: 14px; }
        .info-row { display: flex; justify-content: space-between; font-size: 14px; margin-bottom: 8px; }
        .info-row .label { color: #6b7280; }
        .info-row .value { font-weight: 600; }
        .status-change { background: #f3f4f6; border-radius: 8px; padding: 16px 20px; display: flex; align-items: center; gap: 16px; margin-bottom: 20px; font-size: 14px; }
        .status-badge { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; }
        .status-pending   { background: #fef3c7; color: #92400e; }
        .status-paid      { background: #dbeafe; color: #1e40af; }
        .status-active    { background: #d1fae5; color: #065f46; }
        .status-returned  { background: #ede9fe; color: #5b21b6; }
        .status-completed { background: #f3f4f6; color: #374151; }
        .status-cancelled { background: #fee2e2; color: #991b1b; }
        .arrow { font-size: 18px; color: #9ca3af; }
        .amounts-table { width: 100%; border-collapse: collapse; font-size: 14px; }
        .amounts-table td { padding: 7px 0; }
        .amounts-table .label { color: #6b7280; }
        .amounts-table .value { text-align: right; }
        .btn { display: inline-block; background: #7c3aed; color: #fff; text-decoration: none; padding: 12px 28px; border-radius: 6px; font-weight: bold; font-size: 14px; margin-top: 8px; }
        .footer { background: #f9fafb; padding: 20px 40px; text-align: center; font-size: 12px; color: #9ca3af; }
        .fine-row td { color: #dc2626; font-weight: bold; }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="header">
        <h1>Booking Status Updated</h1>
        <p>{{ config('app.name') }} - Booking #{{ $booking->id }}</p>
    </div>

    <div class="body">
        <p class="greeting">Hello {{ $booking->user->name }},</p>
        <p style="font-size:14px;color:#6b7280;">Your booking status has been updated. Here are the latest details.</p>

        <div class="status-change">
            <span class="status-badge status-{{ $previousStatus }}">{{ ucfirst($previousStatus) }}</span>
            <span class="arrow">→</span>
            <span class="status-badge status-{{ $booking->status }}">{{ ucfirst($booking->status) }}</span>
        </div>

        <div class="section">
            <div class="section-title">Booking Details</div>
            <div class="info-row">
                <span class="label">Booking ID</span>
                <span class="value">#{{ $booking->id }}</span>
            </div>
            <div class="info-row">
                <span class="label">Dress</span>
                <span class="value">{{ $booking->dress->name }}</span>
            </div>
            <div class="info-row">
                <span class="label">Rental Period</span>
                <span class="value">{{ $booking->start_date->format('M d, Y') }} – {{ $booking->end_date->format('M d, Y') }}</span>
            </div>
            <div class="info-row">
                <span class="label">Total Days</span>
                <span class="value">{{ $booking->total_days }} days</span>
            </div>
        </div>

        <div class="section">
            <div class="section-title">Payment Summary</div>
            <table class="amounts-table">
                <tr>
                    <td class="label">Rental Amount</td>
                    <td class="value">₨{{ number_format($booking->rental_amount, 2) }}</td>
                </tr>
                <tr>
                    <td class="label">Deposit Amount</td>
                    <td class="value">₨{{ number_format($booking->deposit_amount, 2) }}</td>
                </tr>
                @if($booking->fine_amount > 0)
                <tr class="fine-row">
                    <td class="label" style="color:#dc2626;">Fine Amount</td>
                    <td class="value">₨{{ number_format($booking->fine_amount, 2) }}</td>
                </tr>
                @endif
                <tr style="border-top:1px solid #e5e7eb;font-weight:bold;">
                    <td style="padding-top:10px;">Total Amount</td>
                    <td class="value" style="padding-top:10px;">₨{{ number_format($booking->total_amount + $booking->fine_amount, 2) }}</td>
                </tr>
            </table>
        </div>

        @if($booking->notes)
        <div class="section">
            <div class="section-title">Notes</div>
            <p style="font-size:14px;color:#374151;background:#f9fafb;padding:10px 14px;border-radius:4px;">{{ $booking->notes }}</p>
        </div>
        @endif

        <div style="text-align:center;margin-top:24px;">
            <a href="{{ route('bookings.show', $booking) }}" class="btn">View Booking Details</a>
        </div>
    </div>

    <div class="footer">
        <p>This email was sent by {{ config('app.name') }}. Please do not reply to this email.</p>
        <p>If you have any questions, please contact us.</p>
    </div>
</div>
</body>
</html>
