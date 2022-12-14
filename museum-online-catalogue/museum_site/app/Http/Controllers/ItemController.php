<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Item;
use App\Models\Label;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('items.index', [
            'items' => Item::orderBy('obtained', 'desc')->paginate(9),
            'labels' => Label::all(),
            'user_count' => User::count(),
            'item_count' => Item::count(),
            'comments_count' => Comment::count(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth::user())
            return redirect()->route('login');
        if (Auth::user() && !Auth::user()->is_admin)
            return abort(403);
        return view('items.create', ['labels' => Label::all()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Auth::user() || Auth::user() && !Auth::user()->is_admin)
            return abort(403);
        $validated = $request->validate(
            [
                'name' => 'required',
                'description' => 'required',
                'obtained' => 'required',
                'labels' => 'nullable',
                'labels.*' => 'integer|distinct|exists:labels,id',
                'image' => 'nullable|image',
            ]
        );

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            // hashed unique name for file
            $fname = $file->hashName();
            Storage::disk('public')->put('images/' . $fname, $file->get());
            $validated['image'] = $fname;
        }

        $i = Item::create($validated);
        $i->labels()->sync($request->labels);

        Session::flash('item-created', $i->name);

        return redirect()->route('home');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function show(Item $item)
    {
        $shown_labels = [];
        foreach ($item->labels as $label) {
            if ($label->display)
                array_push($shown_labels, $label);
        };

        // $out = new \Symfony\Component\Console\Output\ConsoleOutput();
        // $out->writeln($item->labels);
        return view('items.show', [
            'item' => $item,
            'labels_for_item' => $item->labels(),
            'shown_labels' => $shown_labels,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function edit(Item $item)
    {
        if (!Auth::user() || Auth::user() && !Auth::user()->is_admin)
            return abort(403);

        return view(
            'items.edit',
            [
                'item' => $item,
                'all_labels' => Label::all(),
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Item $item)
    {
        $validated = $request->validate(
            [
                'name' => 'required|min:3',
                'description' => 'required',
                'obtained' => 'required',
                'labels' => 'nullable',
                'labels.*' => 'integer|distinct|exists:labels,id',
                // 'comments' => 'nullable',
                // 'comments.*' => 'integer|distinct|exists:comments,id',
                'image' => 'nullable|image',
            ],
            [
                'name.required' => 'A n??v kit??lt??se k??telez??!',
                'name.min' => 'A n??v legal??bb :min karakter legyen',
                'description.required' => 'A r??szletez??se a t??rgynak k??telez??',
                'obtained.required' => 'A ki??ll??t??sa d??tum??nak megad??sa k??telez??',
                'image.image' => 'A k??pnek k??p form??tum??nak kell lennie'
            ]
        );

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            // hashed unique name for file
            $fname = $file->hashName();
            Storage::disk('public')->put('images/' . $fname, $file->get());
            $validated['image'] = $fname;
        }


        if (isset($validated['labels'])) {
            $item->labels()->sync($request->labels);
        }
        if (isset($validated['comments'])) {
            $item->comments()->sync($request->comments);
        }

        $item->update($validated);
        Session::flash('item-updated', $item->name);

        return redirect()->route('home');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Item $item)
    {
        $this->authorize('delete', $item);
        $item->delete();
        return redirect()->route('home');
    }
}
