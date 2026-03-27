{{-- eSewa auto-submit form --}}
<!DOCTYPE html>
<html>
<head>
    <title>Redirecting to eSewa...</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen">
    <div class="text-center">
        <div class="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center mx-auto mb-4 animate-pulse">
            <span class="text-4xl">💚</span>
        </div>
        <h2 class="text-lg font-bold text-gray-900 mb-2">Redirecting to eSewa...</h2>
        <p class="text-gray-500 text-sm mb-4">Please wait, do not close this page.</p>

        <form id="esewaForm" method="POST" action="{{ $formData['action'] ?? 'https://rc-epay.esewa.com.np/api/epay/main/v2/form' }}">
            @foreach($formData as $key => $val)
                @if($key !== 'action')
                    <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                @endif
            @endforeach
        </form>
    </div>
    <script>document.getElementById('esewaForm').submit();</script>
</body>
</html>
