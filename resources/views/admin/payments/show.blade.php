@extends('layouts.admin')
@section('title', 'Payment #' . $payment->id)

@section('content')
<div class="max-w-3xl">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.payments.index') }}" class="text-gray-400 hover:text-gray-600">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Payment #{{ $payment->id }}</h1>
        <span class="ml-auto px-3 py-1 rounded-full text-sm font-medium bg-{{ $payment->status_badge_color }}-100 text-{{ $payment->status_badge_color }}-700">
            {{ ucfirst($payment->status) }}
        </span>
    </div>

    @if(session('success'))
        <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Payment Details -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h3 class="font-semibold text-gray-900 mb-3">Payment Details</h3>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between"><span class="text-gray-500">Amount</span><span class="font-bold">₨{{ number_format($payment->amount) }}</span></div>
                <div class="flex justify-between"><span class="text-gray-500">Method</span><span class="font-medium uppercase">{{ $payment->payment_method }}</span></div>
                <div class="flex justify-between"><span class="text-gray-500">Type</span><span class="capitalize">{{ $payment->payment_type }}</span></div>
                <div class="flex justify-between"><span class="text-gray-500">Status</span>
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-{{ $payment->status_badge_color }}-100 text-{{ $payment->status_badge_color }}-700">{{ $payment->status }}</span>
                </div>
                @if($payment->transaction_id)
                <div class="flex justify-between"><span class="text-gray-500">Transaction ID</span><span class="font-mono text-xs break-all">{{ $payment->transaction_id }}</span></div>
                @endif
                @if($payment->verified_at)
                <div class="flex justify-between"><span class="text-gray-500">Verified At</span><span>{{ $payment->verified_at->format('M d, Y H:i') }}</span></div>
                @endif
                @if($payment->remarks)
                <div class="flex justify-between"><span class="text-gray-500">Remarks</span><span class="text-right max-w-xs">{{ $payment->remarks }}</span></div>
                @endif
                <div class="flex justify-between"><span class="text-gray-500">Created</span><span>{{ $payment->created_at->format('M d, Y H:i') }}</span></div>
            </div>
        </div>

        <!-- Booking & Customer -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h3 class="font-semibold text-gray-900 mb-3">Booking & Customer</h3>
            <div class="space-y-3 text-sm">
                <div>
                    <div class="text-gray-500 mb-0.5">Booking</div>
                    <a href="{{ route('admin.bookings.show', $payment->booking_id) }}" class="text-primary-600 hover:underline font-medium">
                        Booking #{{ $payment->booking_id }}
                    </a>
                    @if($payment->booking)
                        <span class="ml-2 text-xs text-gray-500">— {{ ucfirst($payment->booking->status) }}</span>
                    @endif
                </div>
                @if($payment->booking && $payment->booking->dress)
                <div>
                    <div class="text-gray-500 mb-0.5">Dress</div>
                    <div class="font-medium text-gray-900">{{ $payment->booking->dress->name }}</div>
                </div>
                @endif
                <div>
                    <div class="text-gray-500 mb-0.5">Customer</div>
                    <div class="font-medium text-gray-900">{{ $payment->user->name }}</div>
                    <div class="text-gray-500 text-xs">{{ $payment->user->email }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    @if($payment->status === 'pending' && $payment->payment_method === 'cash')
    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5 mt-6">
        <h3 class="font-semibold text-gray-900 mb-3">Approve Offline Payment</h3>
        <form method="POST" action="{{ route('admin.payments.approve', $payment) }}" class="flex items-end gap-3">
            @csrf
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">Remarks (optional)</label>
                <input type="text" name="remarks" placeholder="e.g. Cash received at shop"
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 outline-none">
            </div>
            <button type="submit" onclick="return confirm('Approve this cash payment?')"
                    class="bg-green-500 hover:bg-green-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition-colors">
                Approve Payment
            </button>
        </form>
    </div>
    @endif

    @if($payment->status === 'completed' && $payment->payment_type === 'advance')
    <div class="bg-purple-50 border border-purple-200 rounded-2xl p-5 mt-6">
        <h3 class="font-semibold text-gray-900 mb-3">Refund Deposit</h3>
        <form method="POST" action="{{ route('admin.payments.refund', $payment) }}" class="flex items-end gap-3">
            @csrf
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">Remarks (optional)</label>
                <input type="text" name="remarks" placeholder="e.g. Deposit returned after dress return"
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 outline-none">
            </div>
            <button type="submit" onclick="return confirm('Issue deposit refund?')"
                    class="bg-purple-500 hover:bg-purple-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition-colors">
                Refund Deposit
            </button>
        </form>
    </div>
    @endif

    <!-- Update Payment Form -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mt-6">
        <h3 class="font-semibold text-gray-900 mb-4">Update Payment</h3>
        <form method="POST" action="{{ route('admin.payments.update', $payment) }}" class="space-y-4">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 outline-none">
                        @foreach(['pending','completed','failed','refunded'] as $s)
                            <option value="{{ $s }}" {{ $payment->status == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                    <select name="payment_method" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 outline-none">
                        @foreach(['cash','esewa','khalti'] as $m)
                            <option value="{{ $m }}" {{ $payment->payment_method == $m ? 'selected' : '' }}>{{ strtoupper($m) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Amount (₨)</label>
                    <input type="number" name="amount" value="{{ $payment->amount }}" min="0" step="0.01"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Transaction ID</label>
                    <input type="text" name="transaction_id" value="{{ $payment->transaction_id }}"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 outline-none"
                           placeholder="Leave blank for cash payments">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Remarks</label>
                <textarea name="remarks" rows="2"
                          class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 outline-none"
                          placeholder="Admin notes about this payment">{{ old('remarks', $payment->remarks) }}</textarea>
            </div>
            <button type="submit" class="bg-primary-600 text-white px-6 py-2.5 rounded-xl font-medium hover:bg-primary-700 transition-colors">
                Update Payment
            </button>
        </form>
    </div>
</div>
@endsection
