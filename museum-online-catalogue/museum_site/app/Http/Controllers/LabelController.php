<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Label;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class LabelController extends Controller
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
    public function create()
    {
        return view('labels.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'name' => 'required|min:3|unique:labels,name',
                'display' => 'required',
                'bg-color' => 'required|regex:/^#([0-9a-f]{8})$/i',
            ],
            [
                'name.required' => 'A név kitöltése kötelező!',
                'name.min' => 'A név legalább :min karakter legyen',
                'name.unique' => 'Ilyen nevű kategória már létezik, a név legyen egyedi',
                'bg-color.regex' => 'A szín formátuma helytelen!'
            ]
        );

        // in case of kebab-case we must resave to snake-case like this:
        $validated['color'] = $validated['bg-color'];

        $l = Label::create($validated);

        Session::flash('label-created', $l->name);

        return redirect()->route('home');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Label  $label
     * @return \Illuminate\Http\Response
     */
    public function show(Label $label)
    {
        return view('labels.show', [
            'label' => $label,
            'items' => Item::orderBy('obtained', 'desc')->paginate(9),
            'labels' => Label::all(),
            'user_count' => User::count(),
            'item_count' => Item::count(),
            'comments_count' => Comment::count(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Label  $label
     * @return \Illuminate\Http\Response
     */
    public function edit(Label $label)
    {
        if (!Auth::user() || Auth::user() && !Auth::user()->is_admin)
        return abort(403);

        return view('labels.edit', ['label' => $label]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Label  $label
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Label $label)
    {
        $validated = $request->validate(
            [
                'name' => 'required|min:3|unique:labels,name',
                'display' => 'required',
            ],
            [
                'name.required' => 'A nev kitoltese kotelezo!',
                'name.min' => 'A nev legalabb :min karakter legyen',
                'name.unique' => 'Ilyen nevu kategoria mar letezik, a nev legyen egyedi'
            ]
        );

        // in case of kebab-case we must resave to snake-case like this:
        $validated['color'] = $validated['bg-color'];

        $label->update($validated);
        // Label::create($validated);
        return redirect()->route('home');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Label  $label
     * @return \Illuminate\Http\Response
     */
    public function destroy(Label $label)
    {
        if (!Auth::user() || Auth::user() && !Auth::user()->is_admin)
        return abort(403);
        // $this->authorize('delete', $label);
        $label->delete();
        return redirect()->route('home');
    }
}
