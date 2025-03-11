<?php

namespace Nakornsoft\PageBuilder\App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Nakornsoft\PageBuilder\App\Models\Page;



class PageBuilderController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        return view('pagebuilder::builder');
    }



    public function show($slug)
    {
        $page = Page::where('slug', $slug)
                    ->where('published', true)
                    ->firstOrFail();

        return view('pages.show', compact('page'));
    }

    public function create()
    {
        return view('admin.pages.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages,slug',
            'content' => 'nullable|string',
            'published' => 'boolean',
        ]);

        Page::create($validated);

        return redirect()->route('admin.pages.index')
            ->with('success', 'Page created successfully');
    }

    public function edit(Page $page)
    {
        return view('admin.pages.edit', compact('page'));
    }

    public function update(Request $request, Page $page)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages,slug,' . $page->id,
            'content' => 'nullable|string',
            'published' => 'boolean',
        ]);

        $page->update($validated);

        return redirect()->route('admin.pages.index')
            ->with('success', 'Page updated successfully');
    }

    public function destroy(Page $page)
    {
        $page->delete();

        return redirect()->route('admin.pages.index')
            ->with('success', 'Page deleted successfully');
    }
}
