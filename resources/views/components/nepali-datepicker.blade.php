{{-- Nepali (BS) Date Picker Component --}}
<div x-data="nepaliCalendar()" class="p-4">
    <!-- Calendar Header -->
    <div class="flex items-center justify-between mb-4">
        <button type="button" @click="prevMonth()" class="p-2 hover:bg-gray-100 rounded-lg">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </button>
        <div class="text-center">
            <div class="font-bold text-gray-900" x-text="monthNames[currentMonth - 1] + ' ' + currentYear"></div>
            <div class="text-xs text-gray-500" x-text="'(' + adMonthDisplay + ')'"></div>
        </div>
        <button type="button" @click="nextMonth()" class="p-2 hover:bg-gray-100 rounded-lg">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </button>
    </div>

    <!-- Selecting label -->
    <div class="text-center text-xs text-primary-600 font-medium mb-3">
        <span x-show="!selectingEnd">Select Start Date</span>
        <span x-show="selectingEnd" x-cloak>Select End Date</span>
    </div>

    <!-- Day headers -->
    <div class="grid grid-cols-7 mb-2">
        <template x-for="d in ['Su','Mo','Tu','We','Th','Fr','Sa']">
            <div class="text-center text-xs font-medium text-gray-500 py-1" x-text="d"></div>
        </template>
    </div>

    <!-- Days -->
    <div class="grid grid-cols-7 gap-0.5" id="bs-calendar-days">
        <!-- Empty cells for offset -->
        <template x-for="n in startOffset"><div></div></template>

        <!-- Day cells -->
        <template x-for="day in daysInMonth" :key="day">
            <button type="button"
                @click="selectDay(day)"
                :disabled="isDayBooked(day) || isPastDay(day)"
                :class="getDayClass(day)"
                class="aspect-square rounded-lg text-xs font-medium transition-colors flex items-center justify-center">
                <span x-text="day"></span>
            </button>
        </template>
    </div>

    <!-- Selection display -->
    <div class="mt-4 border-t border-gray-100 pt-3 space-y-2">
        <div class="flex justify-between text-sm">
            <span class="text-gray-500">Start:</span>
            <span class="font-medium text-gray-900" x-text="startBs ? startBs + ' BS (' + startAd + ')' : 'Not selected'"></span>
        </div>
        <div class="flex justify-between text-sm">
            <span class="text-gray-500">End:</span>
            <span class="font-medium text-gray-900" x-text="endBs ? endBs + ' BS (' + endAd + ')' : 'Not selected'"></span>
        </div>
        <button type="button" x-show="startBs && endBs" @click="clearSelection()"
                class="text-xs text-red-500 hover:text-red-700">Clear Selection</button>
    </div>
</div>

<script>
const BS_DATA = {
    2078:[31,31,32,31,31,31,30,29,30,29,30,30],
    2079:[31,31,32,31,31,31,30,29,30,29,30,30],
    2080:[31,32,31,32,31,30,30,29,30,29,30,30],
    2081:[31,31,32,31,31,30,30,30,29,30,30,30],
    2082:[31,32,31,32,31,31,29,30,29,30,29,31],
    2083:[31,31,32,31,31,31,30,29,30,29,30,30],
};

// Extended data for calendar
const BS_DATA_FULL = @json(\App\Services\NepaliCalendarService::$bsData ?? []);
const TODAY_BS = @json(\App\Services\NepaliCalendarService::todayBs());
const BOOKED_RANGES = @json($bookedRanges ?? []);

function bsToAd(y, m, d) {
    // Simple approximation: 1 Baisakh ~= April 13/14
    // Use server-side for accuracy; here we do a client-side approach
    // Count days from epoch: BS 1970/1/1 = AD 1913/4/13
    const bsEpochAD = new Date(1913, 3, 13); // Apr 13 1913
    let totalDays = 0;
    const data = BS_DATA_FULL;
    for (let yr in data) {
        yr = parseInt(yr);
        if (yr >= y) break;
        totalDays += data[yr].reduce((a,b) => a+b, 0);
    }
    if (data[y]) {
        for (let mo = 0; mo < m - 1; mo++) {
            totalDays += data[y][mo];
        }
    }
    totalDays += d - 1;
    const adDate = new Date(bsEpochAD);
    adDate.setDate(adDate.getDate() + totalDays);
    return adDate;
}

function adToBs(adDate) {
    const bsEpoch = new Date(1913, 3, 13);
    let totalDays = Math.floor((adDate - bsEpoch) / 86400000);
    const data = BS_DATA_FULL;
    let bsYear = null, bsMonth = 1, bsDay = 1;
    for (let yr in data) {
        yr = parseInt(yr);
        const yearTotal = data[yr].reduce((a,b) => a+b, 0);
        if (totalDays < yearTotal) { bsYear = yr; break; }
        totalDays -= yearTotal;
    }
    if (bsYear === null) return null;
    for (let mo = 0; mo < 12; mo++) {
        if (totalDays < data[bsYear][mo]) { bsMonth = mo+1; bsDay = totalDays+1; break; }
        totalDays -= data[bsYear][mo];
    }
    return { year: bsYear, month: bsMonth, day: bsDay };
}

function formatDate(d) {
    return d.getFullYear() + '-' + String(d.getMonth()+1).padStart(2,'0') + '-' + String(d.getDate()).padStart(2,'0');
}

function nepaliCalendar() {
    const todayBs = TODAY_BS;
    return {
        currentYear: todayBs.year,
        currentMonth: todayBs.month,
        selectingEnd: false,
        startBs: '', startAd: '',
        endBs: '', endAd: '',
        monthNames: ['Baisakh','Jestha','Ashadh','Shrawan','Bhadra','Ashwin','Kartik','Mangsir','Poush','Magh','Falgun','Chaitra'],

        get daysInMonth() {
            const data = BS_DATA_FULL[this.currentYear];
            return data ? data[this.currentMonth - 1] : 30;
        },

        get startOffset() {
            const adDate = bsToAd(this.currentYear, this.currentMonth, 1);
            return adDate ? adDate.getDay() : 0;
        },

        get adMonthDisplay() {
            const adDate = bsToAd(this.currentYear, this.currentMonth, 1);
            if (!adDate) return '';
            return adDate.toLocaleString('en', { month: 'short', year: 'numeric' });
        },

        prevMonth() {
            if (this.currentMonth === 1) { this.currentMonth = 12; this.currentYear--; }
            else this.currentMonth--;
        },

        nextMonth() {
            if (this.currentMonth === 12) { this.currentMonth = 1; this.currentYear++; }
            else this.currentMonth++;
        },

        getAdForDay(day) {
            return bsToAd(this.currentYear, this.currentMonth, day);
        },

        isDayBooked(day) {
            const adDate = this.getAdForDay(day);
            if (!adDate) return false;
            const adStr = formatDate(adDate);
            return BOOKED_RANGES.some(r => adStr >= r.start && adStr <= r.end);
        },

        isPastDay(day) {
            const adDate = this.getAdForDay(day);
            if (!adDate) return true;
            const today = new Date(); today.setHours(0,0,0,0);
            return adDate < today;
        },

        isInRange(day) {
            if (!this.startAd || !this.endAd) return false;
            const adDate = this.getAdForDay(day);
            if (!adDate) return false;
            const adStr = formatDate(adDate);
            return adStr > this.startAd && adStr < this.endAd;
        },

        isStart(day) {
            const adDate = this.getAdForDay(day);
            if (!adDate) return false;
            return formatDate(adDate) === this.startAd;
        },

        isEnd(day) {
            const adDate = this.getAdForDay(day);
            if (!adDate) return false;
            return formatDate(adDate) === this.endAd;
        },

        getDayClass(day) {
            if (this.isDayBooked(day) || this.isPastDay(day)) return 'text-gray-300 cursor-not-allowed';
            if (this.isStart(day) || this.isEnd(day)) return 'bg-primary-600 text-white';
            if (this.isInRange(day)) return 'bg-primary-100 text-primary-700';
            return 'hover:bg-gray-100 text-gray-700';
        },

        selectDay(day) {
            const adDate = this.getAdForDay(day);
            if (!adDate) return;
            const adStr = formatDate(adDate);
            const bsStr = `${this.currentYear}-${String(this.currentMonth).padStart(2,'0')}-${String(day).padStart(2,'0')}`;

            if (!this.selectingEnd) {
                this.startBs = bsStr; this.startAd = adStr;
                this.endBs = ''; this.endAd = '';
                this.selectingEnd = true;
                // Update parent
                this.$dispatch('bs-start-selected', { bs: bsStr, ad: adStr });
            } else {
                if (adStr < this.startAd) {
                    this.startBs = bsStr; this.startAd = adStr;
                    this.selectingEnd = true;
                    this.$dispatch('bs-start-selected', { bs: bsStr, ad: adStr });
                } else {
                    this.endBs = bsStr; this.endAd = adStr;
                    this.selectingEnd = false;
                    this.$dispatch('bs-end-selected', { bs: bsStr, ad: adStr });
                    // Trigger availability check
                    this.$dispatch('dates-selected', { startAd: this.startAd, endAd: this.endAd });
                }
            }
        },

        clearSelection() {
            this.startBs = ''; this.startAd = '';
            this.endBs = ''; this.endAd = '';
            this.selectingEnd = false;
        }
    }
}
</script>
