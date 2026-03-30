{{-- Image preview area: appears as soon as files are chosen --}}
<div id="image-preview-area" class="hidden mt-3">
    <p class="text-xs text-gray-500 mb-2 font-medium">
        Selected images <span class="text-gray-400">(first image highlighted — AI will analyze this one)</span>
    </p>
    <div id="image-preview-grid" class="flex flex-wrap gap-2"></div>
</div>

@if(\App\Models\Setting::get('gemini_api_key'))
<div class="mt-3 flex items-center gap-3 flex-wrap">
    <button type="button" id="ai-generate-btn"
            class="inline-flex items-center gap-2 bg-violet-600 hover:bg-violet-700 text-white text-sm font-medium px-4 py-2 rounded-xl transition-colors">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
        AI Analyze &amp; Fill Details
    </button>
    <p id="ai-status" class="text-xs hidden"></p>
</div>
@endif

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const fileInput   = document.getElementById('dress-images');
    const previewArea = document.getElementById('image-preview-area');
    const previewGrid = document.getElementById('image-preview-grid');
    const btn         = document.getElementById('ai-generate-btn');
    const status      = document.getElementById('ai-status');

    // ── Image preview ──────────────────────────────────────────────────────────
    if (fileInput && previewArea && previewGrid) {
        fileInput.addEventListener('change', function () {
            previewGrid.innerHTML = '';

            if (!fileInput.files.length) {
                previewArea.classList.add('hidden');
                return;
            }

            Array.from(fileInput.files).forEach(function (file, index) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const wrapper = document.createElement('div');
                    wrapper.className = 'relative';

                    const img = document.createElement('img');
                    img.src       = e.target.result;
                    img.alt       = file.name;
                    img.className = 'w-16 h-16 object-cover rounded-lg border-2 ' +
                        (index === 0 ? 'border-violet-500 shadow-md' : 'border-gray-200');
                    img.title = index === 0 ? 'Primary image — AI will analyze this' : file.name;

                    if (index === 0) {
                        const badge = document.createElement('span');
                        badge.textContent = 'AI';
                        badge.className   = 'absolute -top-1.5 -right-1.5 bg-violet-600 text-white text-[10px] font-bold px-1 py-0.5 rounded-full leading-none';
                        wrapper.appendChild(badge);
                    }

                    wrapper.appendChild(img);
                    previewGrid.appendChild(wrapper);
                };
                reader.readAsDataURL(file);
            });

            previewArea.classList.remove('hidden');
        });
    }

    @if(\App\Models\Setting::get('gemini_api_key'))
    // ── AI analysis ────────────────────────────────────────────────────────────
    if (!btn) return;

    btn.addEventListener('click', async function () {
        if (!fileInput || !fileInput.files.length) {
            status.textContent = '✗ Please select at least one image first.';
            status.className   = 'text-xs text-red-600';
            status.classList.remove('hidden');
            return;
        }

        btn.disabled  = true;
        btn.innerHTML = '<svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg> Analysing image…';
        status.textContent = 'Sending image to AI for analysis…';
        status.className   = 'text-xs text-gray-500';
        status.classList.remove('hidden');

        const formData = new FormData();
        formData.append('image', fileInput.files[0]);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

        try {
            const res  = await fetch('{{ route('admin.ai.describe-image') }}', { method: 'POST', body: formData });
            const json = await res.json();

            if (json.success) {
                const d = json.data;
                if (d.name)        document.querySelector('[name="name"]').value        = d.name;
                if (d.description) document.querySelector('[name="description"]').value = d.description;
                if (d.color)       document.querySelector('[name="color"]').value       = d.color;
                if (d.brand)       document.querySelector('[name="brand"]').value       = d.brand;
                status.textContent = '✓ Fields filled from AI analysis!';
                status.className   = 'text-xs text-green-600';
            } else {
                status.textContent = '✗ ' + (json.message || 'Failed to analyse image.');
                status.className   = 'text-xs text-red-600';
            }
        } catch (e) {
            status.textContent = '✗ An error occurred. Please try again.';
            status.className   = 'text-xs text-red-600';
        } finally {
            btn.disabled  = false;
            btn.innerHTML = '<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg> AI Analyze &amp; Fill Details';
        }
    });
    @endif
});
</script>
@endpush
