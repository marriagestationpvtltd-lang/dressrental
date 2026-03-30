@extends('layouts.admin')

@section('title', 'Settings')

@section('content')
<div class="max-w-4xl mx-auto">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Settings</h1>
        <p class="text-sm text-gray-500 mt-1">Manage all application settings from one place.</p>
    </div>

    <form method="POST" action="{{ route('admin.settings.update') }}">
        @csrf
        @method('PUT')

        @php
            $groupLabels = [
                'site'    => ['label' => 'Site Settings',    'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                'booking' => ['label' => 'Booking Settings', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                'payment' => ['label' => 'Payment Settings', 'icon' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z'],
                'ai'      => ['label' => 'AI Settings',      'icon' => 'M13 10V3L4 14h7v7l9-11h-7z'],
            ];
        @endphp

        @foreach($groupLabels as $groupKey => $meta)
            @if(!empty($grouped[$groupKey]))
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6 overflow-hidden">
                <!-- Group Header -->
                <div class="flex items-center gap-3 px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <div class="w-8 h-8 bg-primary-600 rounded-lg flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $meta['icon'] }}"/>
                        </svg>
                    </div>
                    <h2 class="text-base font-semibold text-gray-800">{{ $meta['label'] }}</h2>
                </div>

                <!-- Settings Rows -->
                <div class="divide-y divide-gray-100">
                    @foreach($grouped[$groupKey] as $setting)
                    <div class="px-6 py-4 grid grid-cols-1 sm:grid-cols-3 gap-3 items-start">
                        <div class="sm:col-span-1">
                            <label for="setting_{{ $setting['key'] }}" class="block text-sm font-medium text-gray-700">
                                {{ $setting['label'] }}
                            </label>
                            @if($setting['description'])
                                <p class="text-xs text-gray-400 mt-0.5">{{ $setting['description'] }}</p>
                            @endif
                        </div>
                        <div class="sm:col-span-2">
                            @if($setting['type'] === 'boolean')
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="hidden" name="{{ $setting['key'] }}" value="0">
                                    <input
                                        type="checkbox"
                                        id="setting_{{ $setting['key'] }}"
                                        name="{{ $setting['key'] }}"
                                        value="1"
                                        {{ filter_var($setting['value'], FILTER_VALIDATE_BOOLEAN) ? 'checked' : '' }}
                                        class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500"
                                    >
                                    <span class="text-sm text-gray-600">Enabled</span>
                                </label>
                            @elseif($setting['type'] === 'password')
                                <div x-data="{ show: false }" class="relative">
                                    <input
                                        :type="show ? 'text' : 'password'"
                                        id="setting_{{ $setting['key'] }}"
                                        name="{{ $setting['key'] }}"
                                        value="{{ old($setting['key'], $setting['value']) }}"
                                        placeholder="Paste your API key here"
                                        autocomplete="off"
                                        class="block w-full pr-20 rounded-lg border-gray-300 shadow-sm text-sm focus:ring-primary-500 focus:border-primary-500"
                                    >
                                    <button type="button" @click="show = !show"
                                            class="absolute inset-y-0 right-0 px-3 text-xs text-gray-500 hover:text-gray-700">
                                        <span x-text="show ? 'Hide' : 'Show'"></span>
                                    </button>
                                </div>
                            @elseif($setting['type'] === 'textarea')
                                <textarea
                                    id="setting_{{ $setting['key'] }}"
                                    name="{{ $setting['key'] }}"
                                    rows="3"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm text-sm focus:ring-primary-500 focus:border-primary-500"
                                >{{ old($setting['key'], $setting['value']) }}</textarea>
                            @elseif($setting['type'] === 'integer')
                                <input
                                    type="number"
                                    id="setting_{{ $setting['key'] }}"
                                    name="{{ $setting['key'] }}"
                                    value="{{ old($setting['key'], $setting['value']) }}"
                                    step="1"
                                    min="0"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm text-sm focus:ring-primary-500 focus:border-primary-500"
                                >
                            @elseif($setting['type'] === 'decimal')
                                <input
                                    type="number"
                                    id="setting_{{ $setting['key'] }}"
                                    name="{{ $setting['key'] }}"
                                    value="{{ old($setting['key'], $setting['value']) }}"
                                    step="0.01"
                                    min="0"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm text-sm focus:ring-primary-500 focus:border-primary-500"
                                >
                            @else
                                <input
                                    type="text"
                                    id="setting_{{ $setting['key'] }}"
                                    name="{{ $setting['key'] }}"
                                    value="{{ old($setting['key'], $setting['value']) }}"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm text-sm focus:ring-primary-500 focus:border-primary-500"
                                >
                            @endif
                            @error($setting['key'])
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        @endforeach

        <div class="flex justify-end pb-6">
            <button type="submit"
                    class="inline-flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white font-medium text-sm px-6 py-2.5 rounded-lg transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Save Settings
            </button>
        </div>
    </form>
</div>
@endsection
