<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    /**
     * List all pages.
     */
    public function index()
    {
        return Page::select(['id', 'slug', 'title', 'updated_at'])
            ->orderBy('updated_at', 'desc')
            ->get();
    }

    /**
     * Get a single page by slug.
     */
    public function show(string $slug)
    {
        $page = Page::where('slug', $slug)->firstOrFail();

        return response()->json([
            'id' => $page->id,
            'slug' => $page->slug,
            'title' => $page->title,
            'content' => $page->content,
            'meta_title' => $page->meta_title,
            'meta_description' => $page->meta_description,
        ]);
    }

    /**
     * Create a new page.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages,slug',
            'content' => 'required|array',
            'content.blocks' => 'required|array',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
        ]);

        // Auto-generate slug if not provided
        if (!isset($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);

            // Ensure uniqueness
            $count = 1;
            $originalSlug = $validated['slug'];
            while (Page::where('slug', $validated['slug'])->exists()) {
                $validated['slug'] = $originalSlug . '-' . $count;
                $count++;
            }
        }

        $page = Page::create($validated);

        return response()->json($page, 201);
    }

    /**
     * Update an existing page.
     */
    public function update(Request $request, string $slug)
    {
        $page = Page::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'content' => 'sometimes|array',
            'content.blocks' => 'sometimes|array',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
        ]);

        $page->update($validated);

        return response()->json($page);
    }

    /**
     * Delete a page.
     */
    public function destroy(string $slug)
    {
        $page = Page::where('slug', $slug)->firstOrFail();
        $page->delete();

        return response()->json(['message' => 'Page deleted successfully'], 200);
    }
}
