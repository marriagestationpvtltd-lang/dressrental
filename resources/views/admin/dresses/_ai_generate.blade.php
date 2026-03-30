@if(\App\Models\Setting::get('gemini_api_key'))
<button type="button" id="ai-generate-btn"
        class="mt-2 inline-flex items-center gap-2 bg-violet-600 hover:bg-violet-700 text-white text-sm font-medium px-4 py-2 rounded-xl transition-colors">
    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
    AI Generate Description
</button>
<p id="ai-status" class="text-xs mt-1 hidden"></p>
@endif

@push('scripts')
@if(\App\Models\Setting::get('gemini_api_key'))
<script>
document.addEventListener('DOMContentLoaded', function () {
    const btn       = document.getElementById('ai-generate-btn');
    const fileInput = document.getElementById('dress-images');
    const status    = document.getElementById('ai-status');

    if (!btn) return;

    btn.addEventListener('click', async function () {
        if (!fileInput.files.length) {
            status.textContent = '✗ Please select at least one image first.';
            status.className   = 'text-xs mt-1 text-red-600';
            status.classList.remove('hidden');
            return;
        }

        btn.disabled = true;
        btn.innerHTML = '<svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg> Generating…';
        status.textContent = 'Analysing image…';
        status.className   = 'text-xs mt-1 text-gray-500';
        status.classList.remove('hidden');

        const formData = new FormData();
        formData.append('image', fileInput.files[0]);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

        try {
            const res  = await fetch('{{ route('admin.ai.describe-image') }}', { method: 'POST', body: formData });
            const json = await res.json();

            if (json.success) {
                const d = json.data;
                if (d.description) document.querySelector('[name="description"]').value = d.description;
                if (d.color)       document.querySelector('[name="color"]').value       = d.color;
                if (d.brand)       document.querySelector('[name="brand"]').value       = d.brand;
                if (d.name)        document.querySelector('[name="name"]').value        = d.name;
                status.textContent = '✓ Description generated successfully!';
                status.className   = 'text-xs mt-1 text-green-600';
            } else {
                status.textContent = '✗ ' + (json.message || 'Failed to generate description.');
                status.className   = 'text-xs mt-1 text-red-600';
            }
        } catch (e) {
            status.textContent = '✗ An error occurred. Please try again.';
            status.className   = 'text-xs mt-1 text-red-600';
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg> AI Generate Description';
        }
    });
});
</script>
@endif
@endpush
