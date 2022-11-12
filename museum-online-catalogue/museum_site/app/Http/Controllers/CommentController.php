<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Item $item)
    {
        // return view('comments.create', ['item' => $item]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Item $item)
    {
        // $this->authorize('create', Comment::class);

        $validated = $request->validate([
            'text' => 'required'
        ]);

        $request->user()->comments()->create($validated)
            ->item()->associate($item)
            ->save();

        Session::flash('comment-created', $request->user()->name);

        return redirect(route('items.show', $item));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(Comment $comment)
    {
        $this->authorize('update', $comment);
        $item = $comment->item;
        $comment_edit = $comment;
        $comments = $item->comments()
            ->orderByDesc('id')
            ->paginate(5);
        $labels = $item->labels()->where('display', true)->get();
        return view('items.show', compact('item', 'labels', 'comments', 'comment_edit'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        $this->authorize('update', $comment);

        $validated = $request->validate([
            'text' => 'required'
        ]);

        $comment->update($validated);

        Session::flash('comment-updated', $comment->user->name);

        return redirect(route('items.show', $comment->item));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);

        $comment->delete();

        Session::flash('comment-deleted', $comment->user);

        return redirect(route('items.show', $comment->item));
    }
}
