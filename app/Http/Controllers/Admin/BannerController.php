<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroBanner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class BannerController extends Controller
{
    public function index(): View
    {
        $banners = HeroBanner::orderBy('sort_order')->orderBy('id')->get();

        return view('admin.banners.index', compact('banners'));
    }

    public function create(): View
    {
        return view('admin.banners.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title'       => 'nullable|string|max:255',
            'media_type'  => 'required|in:image,youtube',
            'image'       => 'required_if:media_type,image|file|mimes:jpg,jpeg,png,gif,webp|max:10240',
            'youtube_url' => 'required_if:media_type,youtube|nullable|string|max:500',
            'sort_order'  => 'nullable|integer|min:0',
            'is_active'   => 'nullable|boolean',
        ]);

        if ($request->input('media_type') === 'image') {
            $data['media_value'] = $request->file('image')->store('banners', 'public');
        } else {
            $id = HeroBanner::extractYoutubeId($request->input('youtube_url', ''));
            if (! $id) {
                return back()->withErrors(['youtube_url' => 'Invalid YouTube URL or video ID.'])->withInput();
            }
            $data['media_value'] = $id;
        }

        unset($data['image'], $data['youtube_url']);
        $data['is_active']  = $request->boolean('is_active', true);
        $data['sort_order'] = $request->input('sort_order', 0);

        HeroBanner::create($data);

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner added successfully.');
    }

    public function edit(HeroBanner $banner): View
    {
        return view('admin.banners.edit', compact('banner'));
    }

    public function update(Request $request, HeroBanner $banner): RedirectResponse
    {
        $data = $request->validate([
            'title'       => 'nullable|string|max:255',
            'media_type'  => 'required|in:image,youtube',
            'image'       => 'nullable|file|mimes:jpg,jpeg,png,gif,webp|max:10240',
            'youtube_url' => 'nullable|string|max:500',
            'sort_order'  => 'nullable|integer|min:0',
            'is_active'   => 'nullable|boolean',
        ]);

        if ($request->input('media_type') === 'image') {
            if ($request->hasFile('image')) {
                // Delete old image if it was an image type
                if ($banner->media_type === 'image') {
                    Storage::disk('public')->delete($banner->media_value);
                }
                $data['media_value'] = $request->file('image')->store('banners', 'public');
            } else {
                // Keep existing image value when type stays image
                $data['media_value'] = $banner->media_value;
            }
        } else {
            $youtubeInput = $request->input('youtube_url', '');
            $id = HeroBanner::extractYoutubeId($youtubeInput);
            if (! $id) {
                return back()->withErrors(['youtube_url' => 'Invalid YouTube URL or video ID.'])->withInput();
            }
            // Delete old image file if switching from image to youtube
            if ($banner->media_type === 'image') {
                Storage::disk('public')->delete($banner->media_value);
            }
            $data['media_value'] = $id;
        }

        unset($data['image'], $data['youtube_url']);
        $data['is_active']  = $request->boolean('is_active', true);
        $data['sort_order'] = $request->input('sort_order', $banner->sort_order);

        $banner->update($data);

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner updated successfully.');
    }

    public function destroy(HeroBanner $banner): RedirectResponse
    {
        if ($banner->media_type === 'image') {
            Storage::disk('public')->delete($banner->media_value);
        }

        $banner->delete();

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner deleted.');
    }
}
