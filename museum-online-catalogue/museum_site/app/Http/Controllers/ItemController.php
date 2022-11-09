<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Item;
use App\Models\Label;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Queue\RedisQueue;
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
            // 'items' => Item::all()
            // 'items' => Item::with('comments')->get(),
            'items' => Item::with('comments')->paginate(9),
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
            return redirect() -> route('login');
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
        if (!Auth::user())
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function edit(Item $item)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Item $item)
    {
        //
    }
}
