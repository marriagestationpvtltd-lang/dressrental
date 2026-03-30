{{--
    Reusable category + subcategory dynamic selector for the dress form.
    Required variable: $categories  – top-level DressCategory collection with activeSubcategories loaded
    Optional variable: $currentCategoryId – the currently selected category_id (top-level OR sub)
--}}
@php $initId = ($currentCategoryId ?? null) ? (int) $currentCategoryId : null; @endphp

<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Category *</label>
    <select id="main-category-select"
            class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 outline-none @error('category_id') border-red-400 @enderror">
        <option value="">— Select Category —</option>
        @foreach($categories as $cat)
            <option value="{{ $cat->id }}">{{ $cat->icon ? $cat->icon . ' ' : '' }}{{ $cat->name }}</option>
        @endforeach
    </select>
    @error('category_id')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>

<div id="subcategory-wrapper" class="hidden">
    <label class="block text-sm font-medium text-gray-700 mb-1">Subcategory</label>
    <select id="sub-category-select"
            class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500 outline-none">
        <option value="">— Select Subcategory (Optional) —</option>
    </select>
</div>

{{-- Hidden input that actually carries category_id in the form submission --}}
<input type="hidden" name="category_id" id="category-id-input">

@php
    $categorySelectData = $categories->map(fn ($c) => [
        'id'   => $c->id,
        'name' => $c->name,
        'icon' => $c->icon,
        'subs' => $c->activeSubcategories->map(fn ($s) => [
            'id'   => $s->id,
            'name' => $s->name,
            'icon' => $s->icon,
        ])->values()->all(),
    ])->values()->all();
@endphp
<script>
(function () {
    const data = @json($categorySelectData);

    const mainSel = document.getElementById('main-category-select');
    const subWrap = document.getElementById('subcategory-wrapper');
    const subSel  = document.getElementById('sub-category-select');
    const hidden  = document.getElementById('category-id-input');

    // Build fast lookup sets / maps
    const topIds = new Set(data.map(c => c.id));
    const subMap = {};   // subcategoryId => parentCategoryId
    data.forEach(c => c.subs.forEach(s => { subMap[s.id] = c.id; }));

    /** Populate the subcategory <select> for the given parent id */
    function rebuildSubs(parentId) {
        const id   = Number(parentId);
        const cat  = data.find(c => c.id === id);
        const subs = cat ? cat.subs : [];

        subSel.innerHTML = '<option value="">— Select Subcategory (Optional) —</option>';
        subs.forEach(s => {
            const o   = document.createElement('option');
            o.value   = s.id;
            o.textContent = (s.icon ? s.icon + ' ' : '') + s.name;
            subSel.appendChild(o);
        });

        if (subs.length > 0) {
            subWrap.classList.remove('hidden');
        } else {
            subWrap.classList.add('hidden');
        }
    }

    /** Keep the hidden category_id input in sync */
    function sync() {
        hidden.value = subSel.value || mainSel.value;
    }

    mainSel.addEventListener('change', function () {
        rebuildSubs(this.value);
        subSel.value = '';
        sync();
    });

    subSel.addEventListener('change', sync);

    // ── Bootstrap: restore the previously selected value ──────────
    const init = @json($initId);
    if (init) {
        if (topIds.has(init)) {
            // top-level category is selected directly
            mainSel.value = init;
            rebuildSubs(init);
        } else if (subMap[init]) {
            // a subcategory is selected — restore both dropdowns
            mainSel.value = subMap[init];
            rebuildSubs(subMap[init]);
            subSel.value = init;
        }
        sync();
    }

    // ── Client-side required check before submit ───────────────────
    const form = mainSel.closest('form');
    if (form) {
        form.addEventListener('submit', function (e) {
            if (!hidden.value) {
                e.preventDefault();
                mainSel.classList.add('border-red-400');
                mainSel.focus();
            } else {
                mainSel.classList.remove('border-red-400');
            }
        });
    }
}());
</script>
