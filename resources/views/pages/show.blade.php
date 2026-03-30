@extends('layouts.app')

@section('title', $page->title)
@section('meta_description', strip_tags(\Illuminate\Support\Str::limit($page->content, 160)))

@section('content')
<div class="max-w-3xl mx-auto px-4 py-12">
    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-sm text-gray-400 mb-6">
        <a href="{{ route('home') }}" class="hover:text-primary-600 transition-colors">Home</a>
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-gray-600 font-medium">{{ $page->title }}</span>
    </div>

    <!-- Page card -->
    <div class="bg-white rounded-2xl shadow-card border border-gray-100 overflow-hidden">
        <!-- Header -->
        <div class="gradient-bg px-8 py-8">
            <h1 class="text-2xl md:text-3xl font-extrabold text-white">{{ $page->title }}</h1>
            <p class="text-violet-200 text-sm mt-1">Last updated: {{ $page->updated_at->format('F j, Y') }}</p>
        </div>

        <!-- Content -->
        <div class="px-8 py-8 prose prose-gray max-w-none
                    prose-headings:font-bold prose-headings:text-gray-900
                    prose-a:text-primary-600 prose-a:no-underline hover:prose-a:underline
                    prose-strong:text-gray-900
                    prose-li:text-gray-700
                    prose-p:text-gray-700 prose-p:leading-relaxed">
            {!! $page->content !!}
        </div>
    </div>
</div>
@endsection
