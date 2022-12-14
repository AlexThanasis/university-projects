<x-guest-layout>
    <x-slot name="title">
        {{ $item->name }} műtárgy
    </x-slot>
    <div class="container mx-auto p-3 lg:px-36">
        <div class="grid grid-cols-4 gap-6">
            <div class="col-span-4 lg:col-span-3">
                <div md:flex bg-slate-100 rounded-xl p-8 md:p-0 dark:bg-slate-800>
                    <h1 class="font-bold my-4 text-4xl">{{ $item->name }}</h1>
                </div>
                @if (Session::has('comment-created'))
                    <div class="col-span-3 bg-green-200 text-center rounded-lg py-1">
                        A(z) {{ Session::get('comment-created') }} felhasználó új hozzászólása fel lett véve és
                        eltárolódott
                    </div>
                @endif
                @if (Session::has('comment-updated'))
                    <div class="col-span-3 bg-green-200 text-center rounded-lg py-1">
                        A(z) {{ Session::get('comment-created') }} felhasználó hozzászólása frissült
                    </div>
                @endif
                @if (Session::has('comment-deleted'))
                    <div class="col-span-3 bg-green-200 text-center rounded-lg py-1">
                        A(z) {{ Session::get('comment-created') }} felhasználó új hozzászólása törölve lett
                    </div>
                @endif
                <div>
                    <a href="/" class="text-blue-400 hover:text-blue-600 hover:underline"><i
                            class="fas fa-long-arrow-alt-left"></i> Vissza a bejegyzésekhez</a>

                    <h3 class="text-xl mb-0.5 font-semibold">
                        {{ $item->name }}
                    </h3>
                    <h4 class="text-gray-400">
                        <span class="mr-2"><i class="fas fa-user"></i>{{ $item->obtained }}</span>
                    </h4>
                    @if ($item->image === null)
                        <img class="w-50 h-50 rounded-xl mx-auto"
                            src="https://cdn.rebrickable.com/media/thumbs/parts/elements/300121.jpg/250x250p.jpg?1658326879.2519205">
                        {{-- <img src="{{ Storage::url('images/basic_lego_if_no_picture_uploaded.jpeg') }}"> --}}
                    @elseif ($item->image !== null && strpos($item->image, 'http') !== false)
                        <img class="w-50 h-50 rounded-xl mx-auto" src={{ $item->image }}>
                    @else
                        <img class="w-50 h-50 rounded-xl mx-auto" src={{ Storage::url('images/' . $item->image) }}>
                    @endif
                    <p class="text-gray-600 mt-1">
                        {!! str_replace('\n\n', '<br>', $item->description) !!}
                    </p>
                </div>

                @auth
                    <br>
                    <div class="flex-auto flex space-x-4">
                        <button
                            onclick="event.preventDefault(); document.querySelector('#new-comment').classList.toggle('hidden');"
                            class="bg-slate-700
                            hover:bg-slate-800 rounded-full px-2 py-1 text-white">
                            <i class="fas fa-comments"></i> Hozzászólok a kiállított tárgyhoz</button>

                        @can('delete', $item)
                            <a href="{{ route('items.edit', $item) }}"
                                class="bg-amber-500 hover:bg-amber-700 rounded-full px-2 py-1 text-white"><i
                                    class="fas fa-edit"></i> Kiállított tárgy
                                szerkesztése</a>

                            <form action="{{ route('items.destroy', $item) }}" method="post" id="delete-form">
                                @csrf
                                @method('DELETE')
                                <a href="{{ route('items.destroy', $item) }}"
                                    onclick="event.preventDefault(); document.querySelector('#delete-form').submit();"
                                    class="bg-red-700 hover:bg-red-800 rounded-full px-2 py-1 text-white"><i
                                        class="fas fa-trash"></i> Kiállított
                                    tárgy törlése</a>
                            </form>
                        @endcan
                    </div>
                    <br>
                @endauth

                <div class="border px-2.5 py-2 border-gray-400">
                    <h3 class="mb-0.5 text-xl font-semibold">
                        Hozzászólások
                    </h3>

                    <div class="flex flex-row flex-wrap gap-1 mt-3 hidden" id="new-comment">
                        <form action="{{ route('items.comments.store', $item) }}" method="post">
                            @csrf
                            <textarea placeholder="Új hozzászólását írja ide" name="text" id="new-comment-textarea" cols="60"
                                rows="5"></textarea>
                            <div class="text-end col-sm-1">
                                <button type="submit"
                                    class="bg-green-500 hover:bg-green-700 rounded-full px-2 py-1 text-white">
                                    <i class="fas fa-save"></i>
                                    Elmentés
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="flex flex-row flex-wrap gap-1 mt-3">
                        <div class="comments-section">
                            @forelse ($item->comments->sortBy('created_at') as $comment)
                                <div class="border px-2.5 py-2 min-w-fit">
                                    <h4 class="text-xl font-semibold">
                                        {{ $comment->user->name }}
                                    </h4>
                                    <h5>
                                        {{ $comment->created_at }}
                                    </h5>
                                    <div id="comment-{{ $loop -> iteration }}-text">
                                        <p class="col-span-3 bg-gray-100 text-justify rounded-lg py-1">
                                            {!! str_replace('\n\n', '<br>', $comment->text) !!}
                                        </p>
                                        <div class="hidden">
                                            <form action="{{ route('comments.update', $comment) }}" method="post" id="update-{{ $loop -> iteration }}-comment-textarea">
                                                <textarea name="text" id="update-{{ $loop -> iteration }}-comment-textarea" cols="60" rows="5"> {!! str_replace('\n\n', '<br>', $comment->text) !!}</textarea>
                                                @csrf
                                                @method('PATCH')
                                                <a href="{{ route('comments.update', $comment) }}"
                                                    onclick="event.preventDefault(); document.querySelector('#update-{{ $loop -> iteration }}-comment-textarea').submit();"
                                                    class="bg-green-500 hover:bg-green-700 rounded-full px-2 py-1 text-white">
                                                    Elmentés </a>
                                            </form>
                                        </div>
                                        <br>
                                    </div>
                                    <div class="flex-auto flex space-x-4">

                                        @can('update', $comment)
                                            <button
                                                onclick="event.preventDefault(); document.querySelector('#comment-{{ $loop -> iteration }}-text > p').classList.toggle('hidden'); document.querySelector('#comment-{{ $loop -> iteration }}-text > div').classList.toggle('hidden');"
                                                class="bg-amber-500 hover:bg-amber-700 rounded-full px-2 py-1 text-white"><i
                                                    class="fas fa-trash"></i> Hozzászólás szerkesztése</button>
                                        @endcan
                                        @can('delete', $comment)
                                            <form action="{{ route('comments.destroy', $comment) }}" method="post"
                                                id="delete-{{ $loop -> iteration }}-comment-form">
                                                @csrf
                                                @method('DELETE')
                                                <a href="{{ route('comments.destroy', $comment) }}"
                                                    onclick="event.preventDefault(); document.querySelector('#delete-{{ $loop -> iteration }}-comment-form').submit();"
                                                    class="bg-red-700 hover:bg-red-800 rounded-full px-2 py-1 text-white"><i
                                                        class="fas fa-edit"></i> Hozzászólás törlése</a>
                                            </form>
                                        @endcan
                                    </div>
                                </div>
                                <br>
                            @empty
                                <div class="col-span-3 bg-red-200 text-center rounded-lg py-1">
                                    Ehhez a műtárgyhoz nincsek hozzászólások
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-4 lg:col-span-1">
                <div class="border px-2.5 py-2 border-gray-400">
                    <h3 class="mb-0.5 text-xl font-semibold">
                        Címkék
                    </h3>
                    <div class="flex flex-row flex-wrap gap-1 mt-3">
                        @forelse ($shown_labels as $label)
                            <a href="{{ route('labels.show', $label) }}"
                                class="py-0.5 px-1.5 font-semibold text-white text-sm"
                                style="background-color: {{ $label->color }};">{{ $label->name }}</a>
                        @empty
                            <div class="col-span-3 bg-red-200 text-center rounded-lg py-1">
                                Ehhez a műtárgyhoz nincsek megjeleníthető címkék
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-guest-layout>
