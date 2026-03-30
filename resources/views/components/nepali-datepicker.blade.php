{{-- Nepali (BS) Date Picker Component --}}
<div x-data="nepaliCalendar()" class="p-3 sm:p-4">

    <!-- Calendar Header -->
    <div class="flex items-center justify-between mb-3">
        <button type="button" @click="prevMonth()"
                class="p-2 hover:bg-gray-200 rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </button>
        <div class="text-center">
            <div class="font-bold text-gray-900 text-sm"
                 x-text="nepaliMonths[currentMonth - 1] + ' ' + toNepali(currentYear)"></div>
            <div class="text-xs text-gray-400" x-text="adMonthDisplay"></div>
        </div>
        <button type="button" @click="nextMonth()"
                class="p-2 hover:bg-gray-200 rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </button>
    </div>

    <!-- Prompt label -->
    <div class="text-center text-xs text-primary-600 font-medium mb-2 min-h-[1.25rem]">
        <span x-show="!selectingEnd">सुरु मिति छान्नुहोस्</span>
        <span x-show="selectingEnd" x-cloak>अन्त्य मिति छान्नुहोस्</span>
    </div>

    <!-- Day-of-week headers (Nepali) -->
    <div class="grid grid-cols-7 mb-1">
        <template x-for="d in nepaliDays">
            <div class="text-center text-xs font-semibold text-gray-400 py-1" x-text="d"></div>
        </template>
    </div>

    <!-- Day cells -->
    <div class="grid grid-cols-7 gap-0.5">
        <!-- Leading empty cells to align first day -->
        <template x-for="n in startOffset"><div></div></template>

        <!-- Each day of the month -->
        <template x-for="day in daysInMonth" :key="day">
            <button type="button"
                @click="selectDay(day)"
                :disabled="isDayBooked(day) || isPastDay(day)"
                :class="getDayClass(day)"
                class="relative aspect-square text-xs font-medium transition-colors flex items-center justify-center rounded-lg">
                <span x-text="toNepali(day)"></span>
                <!-- Dot under today's date -->
                <span x-show="isToday(day)"
                      class="absolute bottom-0.5 left-1/2 -translate-x-1/2 w-1 h-1 bg-amber-400 rounded-full pointer-events-none"></span>
            </button>
        </template>
    </div>

    <!-- Legend -->
    <div class="mt-2 flex items-center gap-3 text-xs text-gray-400">
        <span class="flex items-center gap-1"><span class="inline-block w-3 h-3 rounded bg-primary-600"></span> छानिएको</span>
        <span class="flex items-center gap-1"><span class="inline-block w-3 h-3 rounded bg-primary-100"></span> दायरा</span>
        <span class="flex items-center gap-1"><span class="inline-block w-2 h-2 rounded-full bg-amber-400"></span> आज</span>
        <span class="flex items-center gap-1"><span class="inline-block w-3 h-3 rounded bg-red-100 line-through text-red-300 text-center leading-3">ब</span> बुक</span>
    </div>

    <!-- Selection summary -->
    <div class="mt-3 border-t border-gray-100 pt-3 space-y-1">
        <div class="flex justify-between items-center text-xs">
            <span class="text-gray-500 font-medium">सुरु:</span>
            <span class="font-semibold text-gray-800"
                  x-text="startBs ? toNepaliDate(startBs) + ' (' + startAd + ')' : '—'"></span>
        </div>
        <div class="flex justify-between items-center text-xs">
            <span class="text-gray-500 font-medium">अन्त्य:</span>
            <span class="font-semibold text-gray-800"
                  x-text="endBs ? toNepaliDate(endBs) + ' (' + endAd + ')' : '—'"></span>
        </div>
        <button type="button" x-show="startBs" @click="clearSelection()"
                class="text-xs text-red-500 hover:text-red-700 transition-colors mt-1">
            ✕ मेटाउनुहोस्
        </button>
    </div>
</div>

<script>
// ── BS calendar data (served from NepaliCalendarService) ──────────────────────
const BS_DATA_FULL   = @json(\App\Services\NepaliCalendarService::getBsData());
const BS_EPOCH_YEAR  = @json(\App\Services\NepaliCalendarService::getEpochInfo()['bsEpochYear']);
const BS_EPOCH_AD_STR = @json(\App\Services\NepaliCalendarService::getEpochInfo()['adEpoch']);
// Parse epoch as local date (YYYY-MM-DD → year, month-1, day) to avoid UTC shift
const _ep = BS_EPOCH_AD_STR.split('-').map(Number);
const BS_EPOCH_AD = new Date(_ep[0], _ep[1] - 1, _ep[2]);

const TODAY_BS      = @json(\App\Services\NepaliCalendarService::todayBs());
const BOOKED_RANGES = @json($bookedRanges ?? []);

// ── Nepali numeral helpers ────────────────────────────────────────────────────
const NP_DIGITS = ['०','१','२','३','४','५','६','७','८','९'];
function toNepaliNum(n) {
    // Replace each ASCII digit with its Devanagari equivalent
    return String(n).replace(/[0-9]/g, d => NP_DIGITS[d]);
}
function toNepaliDateStr(bsStr) {
    // Convert "2082-01-15" → "२०८२-०१-१५" (segments already zero-padded)
    return bsStr.split('-').map(p => toNepaliNum(p)).join('-');
}

// ── BS ↔ AD conversion (epoch: BS 2000/1/1 = AD 1943-04-21) ─────────────────
function bsToAd(y, m, d) {
    if (!BS_DATA_FULL[y]) return null;
    let days = 0;
    for (const yr in BS_DATA_FULL) {
        const yi = parseInt(yr, 10);
        if (yi < BS_EPOCH_YEAR) continue;
        if (yi >= y) break;
        days += BS_DATA_FULL[yr].reduce((a, b) => a + b, 0);
    }
    for (let mo = 0; mo < m - 1; mo++) days += BS_DATA_FULL[y][mo];
    days += d - 1;
    const ad = new Date(BS_EPOCH_AD.getFullYear(), BS_EPOCH_AD.getMonth(), BS_EPOCH_AD.getDate());
    ad.setDate(ad.getDate() + days);
    return ad;
}

function adToBs(adDate) {
    let days = Math.floor(
        (new Date(adDate.getFullYear(), adDate.getMonth(), adDate.getDate()) - BS_EPOCH_AD) / 86400000
    );
    if (days < 0) return null;
    let bsYear = null, bsMonth = 1, bsDay = 1;
    for (const yr in BS_DATA_FULL) {
        const yi = parseInt(yr, 10);
        if (yi < BS_EPOCH_YEAR) continue;
        const yt = BS_DATA_FULL[yr].reduce((a, b) => a + b, 0);
        if (days < yt) { bsYear = yi; break; }
        days -= yt;
    }
    if (bsYear === null) return null;
    for (let mo = 0; mo < 12; mo++) {
        if (days < BS_DATA_FULL[bsYear][mo]) { bsMonth = mo + 1; bsDay = days + 1; break; }
        days -= BS_DATA_FULL[bsYear][mo];
    }
    return { year: bsYear, month: bsMonth, day: bsDay };
}

function fmtDate(d) {
    return d.getFullYear() + '-' +
        String(d.getMonth() + 1).padStart(2, '0') + '-' +
        String(d.getDate()).padStart(2, '0');
}

// ── Alpine component ──────────────────────────────────────────────────────────
function nepaliCalendar() {
    return {
        currentYear:  TODAY_BS.year,
        currentMonth: TODAY_BS.month,
        selectingEnd: false,
        startBs: '', startAd: '',
        endBs:   '', endAd:   '',

        nepaliMonths: ['बैशाख','जेठ','असार','साउन','भदौ','असोज','कार्तिक','मंसिर','पुष','माघ','फागुन','चैत'],
        nepaliDays:   ['आइत','सोम','मंगल','बुध','बिहि','शुक्र','शनि'],

        toNepali(n) { return toNepaliNum(n); },
        toNepaliDate(bs) { return toNepaliDateStr(bs); },

        get daysInMonth() {
            const d = BS_DATA_FULL[this.currentYear];
            return d ? d[this.currentMonth - 1] : 30;
        },

        get startOffset() {
            const ad = bsToAd(this.currentYear, this.currentMonth, 1);
            return ad ? ad.getDay() : 0;
        },

        get adMonthDisplay() {
            const ad = bsToAd(this.currentYear, this.currentMonth, 1);
            if (!ad) return '';
            return ad.toLocaleString('en', { month: 'short', year: 'numeric' });
        },

        prevMonth() {
            if (this.currentMonth === 1) { this.currentMonth = 12; this.currentYear--; }
            else this.currentMonth--;
        },

        nextMonth() {
            if (this.currentMonth === 12) { this.currentMonth = 1; this.currentYear++; }
            else this.currentMonth++;
        },

        getAdForDay(day) { return bsToAd(this.currentYear, this.currentMonth, day); },

        isToday(day) {
            return this.currentYear  === TODAY_BS.year  &&
                   this.currentMonth === TODAY_BS.month &&
                   day === TODAY_BS.day;
        },

        isPastDay(day) {
            const ad = this.getAdForDay(day);
            if (!ad) return true;
            const t = new Date(); t.setHours(0, 0, 0, 0);
            return ad < t;
        },

        isDayBooked(day) {
            const ad = this.getAdForDay(day);
            if (!ad) return false;
            const s = fmtDate(ad);
            return BOOKED_RANGES.some(r => s >= r.start && s <= r.end);
        },

        isStart(day) {
            const ad = this.getAdForDay(day);
            return ad ? fmtDate(ad) === this.startAd : false;
        },

        isEnd(day) {
            const ad = this.getAdForDay(day);
            return ad ? fmtDate(ad) === this.endAd : false;
        },

        isInRange(day) {
            if (!this.startAd || !this.endAd) return false;
            const ad = this.getAdForDay(day);
            if (!ad) return false;
            const s = fmtDate(ad);
            return s > this.startAd && s < this.endAd;
        },

        getDayClass(day) {
            if (this.isDayBooked(day))
                return 'bg-red-50 text-red-300 cursor-not-allowed line-through';
            if (this.isPastDay(day))
                return 'text-gray-300 cursor-not-allowed';
            if (this.isStart(day) || this.isEnd(day))
                return 'bg-primary-600 text-white font-bold';
            if (this.isInRange(day))
                return 'bg-primary-100 text-primary-700';
            if (this.isToday(day))
                return 'ring-2 ring-amber-400 text-gray-800 font-bold hover:bg-amber-50';
            return 'hover:bg-gray-100 text-gray-700';
        },

        selectDay(day) {
            const ad = this.getAdForDay(day);
            if (!ad) return;
            const adStr = fmtDate(ad);
            const bsStr = this.currentYear + '-' +
                String(this.currentMonth).padStart(2, '0') + '-' +
                String(day).padStart(2, '0');

            if (!this.selectingEnd) {
                this.startBs = bsStr; this.startAd = adStr;
                this.endBs   = '';    this.endAd   = '';
                this.selectingEnd = true;
                this.$dispatch('bs-start-selected', { bs: bsStr, ad: adStr });
            } else {
                if (adStr < this.startAd) {
                    // Clicked a date before start — restart selection from here
                    this.startBs = bsStr; this.startAd = adStr;
                    this.endBs   = '';    this.endAd   = '';
                    this.$dispatch('bs-start-selected', { bs: bsStr, ad: adStr });
                } else {
                    // Same day or later — allow single-day or multi-day booking
                    this.endBs = bsStr; this.endAd = adStr;
                    this.selectingEnd = false;
                    this.$dispatch('bs-end-selected', { bs: bsStr, ad: adStr });
                    this.$dispatch('dates-selected', { startAd: this.startAd, endAd: this.endAd });
                }
            }
        },

        clearSelection() {
            this.startBs = ''; this.startAd = '';
            this.endBs   = ''; this.endAd   = '';
            this.selectingEnd = false;
            this.$dispatch('dates-cleared', {});
        },
    };
}
</script>
