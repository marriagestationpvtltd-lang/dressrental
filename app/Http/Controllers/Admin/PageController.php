<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::orderBy('sort_order')->orderBy('title')->get();

        return view('admin.pages.index', compact('pages'));
    }

    public function create()
    {
        return view('admin.pages.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'      => 'required|string|max:255',
            'slug'       => 'nullable|string|max:255|unique:pages',
            'content'    => 'required|string',
            'status'     => 'required|in:active,inactive',
            'sort_order' => 'nullable|integer',
        ]);

        $data['slug']       = $data['slug'] ? Str::slug($data['slug']) : Str::slug($data['title']);
        $data['sort_order'] = $data['sort_order'] ?? 0;

        Page::create($data);

        return redirect()->route('admin.pages.index')
            ->with('success', 'Page created successfully!');
    }

    public function edit(Page $page)
    {
        return view('admin.pages.edit', compact('page'));
    }

    public function update(Request $request, Page $page)
    {
        $data = $request->validate([
            'title'      => 'required|string|max:255',
            'slug'       => 'required|string|max:255|unique:pages,slug,' . $page->id,
            'content'    => 'required|string',
            'status'     => 'required|in:active,inactive',
            'sort_order' => 'nullable|integer',
        ]);

        $data['slug']       = Str::slug($data['slug']);
        $data['sort_order'] = $data['sort_order'] ?? 0;

        $page->update($data);

        return redirect()->route('admin.pages.index')
            ->with('success', 'Page updated successfully!');
    }

    public function destroy(Page $page)
    {
        $page->delete();

        return redirect()->route('admin.pages.index')
            ->with('success', 'Page deleted.');
    }
}
