<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TagController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $tags = $user->tags()->withCount('files')->get();

        return view('tags.index', compact('tags'));
    }

    public function create()
    {
        return view('tags.index.blade.php');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'required|string|regex:/^#[a-fA-F0-9]{6}$/',
        ]);

        $user = Auth::user();

        $tag = Tag::create([
            'name' => $request->input('name'),
            'user_id' => $user->id,
            'color' => $request->input('color'),
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'tag' => $tag
            ]);
        }

        return redirect()->route('tags.index')
            ->with('success', 'Tag créé avec succès.');
    }

    public function show(Tag $tag)
    {
        $this->authorize('view', $tag);

        $files = $tag->files()->paginate(12);

        return view('tags.show', compact('tag', 'files'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tag $tag)
    {
        $this->authorize('update', $tag);

        return view('tags.edit', compact('tag'));
    }

    public function update(Request $request, Tag $tag)
    {
        $this->authorize('update', $tag);

        $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'required|string|regex:/^#[a-fA-F0-9]{6}$/',
        ]);

        $tag->name = $request->input('name');
        $tag->color = $request->input('color');
        $tag->save();

        return redirect()->route('tags.index')
            ->with('success', 'Tag mis à jour avec succès.');
    }

    public function destroy(Tag $tag)
    {
        $this->authorize('delete', $tag);

        $tag->delete();

        return redirect()->route('tags.index')
            ->with('success', 'Tag supprimé avec succès.');
    }
}
