<x-guest-layout>
    <x-slot name="title">
        Főoldal
    </x-slot>
    <div class="container mx-auto p-3 lg:px-36">
        <div class="grid grid-cols-1 lg:grid-cols-2 mb-4">
            <div>
                <h1 class="font-bold my-4 text-4xl">Múzeum kiállított tárgyainak listája</h1>
            </div>

            @auth()
                @if (Auth::user()->is_admin)
                    <div class="flex items-center gap-2 lg:justify-end">
                        <a href="{{ route('labels.create') }}"
                            class="bg-slate-800 rounded-lg hover:bg-slate-800 px-2 py-1 text-white"><i
                                class="fas fa-plus-circle"></i> Új címke</a>
                        <a href="{{ route('items.create') }}"
                            class="bg-slate-800 rounded-lg hover:bg-slate-700 px-2 py-1 text-white"><i
                                class="fas fa-plus-circle"></i> Új bejegyzés</a>
                    </div>
                @endif
            @endauth

        </div>
        <div class="grid grid-cols-4 gap-6">
            <div class="col-span-4 lg:col-span-3">
                <h2 class="font-semibold text-3xl my-2">Kiállított tárgyak</h2>
                <div class="grid grid-cols-3 gap-3">
                    @if (Session::has('label-created'))
                        <div class="col-span-3 bg-green-200 text-center rounded-lg py-1">
                            A(z) {{ Session::get('label-created') }} címke létrejött és eltárolódott
                        </div>
                    @endif
                    @if (Session::has('item-created'))
                        <div class="col-span-3 bg-green-200 text-center rounded-lg py-1">
                            A(z) {{ Session::get('item-created') }} kiállított tárgy fel lett véve és eltárolódott
                        </div>
                    @endif
                    @if (Session::has('item-updated'))
                        <div class="col-span-3 bg-green-200 text-center rounded-lg py-1">
                            A(z) {{ Session::get('item-updated') }} kiállított tárgy változtatva lettek sikeresen
                        </div>
                    @endif
                    @foreach ($items as $item)
                        <div class="col-span-3 lg:col-span-1">
                            @if ($item->image === null)
                                <img class="w-40 h-40 rounded-full mx-auto"
                                    src="{{ Storage::url('images/basic_lego_if_no_picture_uploaded.jpeg') }}">
                            @elseif ($item->image !== null && strpos($item->image, 'http') !== false)
                                <img class="w-40 h-40 rounded-full mx-auto" src={{ $item->image }}>
                            @else
                                <img class="w-40 h-40 rounded-full mx-auto"
                                    src={{ Storage::url('images/' . $item->image) }}>
                            @endif
                            <div class="px-2.5 py-2 border-r border-l border-b border-gray-400 ">
                                <h3 class="text-xl mb-0.5 font-semibold">
                                    {{ $item->name }}
                                </h3>
                                <h4 class="text-gray-400">
                                    <span class="mr-2"><i class="fas fa-calendar-days"></i>
                                        {{ $item->obtained }}</span>
                                </h4>
                                <p class="text-gray-600 mt-1">
                                    {{ Str::limit($item->description, 120) }}
                                </p>
                                <button
                                    class="bg-slate-800 hover:bg-slate-700 px-1.5 py-1 text-white mt-3 rounded-lg font-semibold">
                                    <a href="{{ route('items.show', $item) }}"
                                        class="py-0.5 px-1.5 font-semibold text-white text-sm">
                                        Megnézem</a>

                                    <i class="fas fa-angle-right"></i></button>
                            </div>
                        </div>
                    @endforeach
                    <br>
                    {{ $items->links() }}
                </div>
            </div>
            <div class="col-span-4 lg:col-span-1">
                <h2 class="font-semibold text-3xl my-2">Menü</h2>
                <div class="grid grid-cols-1 gap-3">
                    <div class="border px-2.5 py-2 border-gray-400">
                        <h3 class="mb-0.5 text-xl font-semibold">
                            Címkék
                        </h3>
                        <div class="flex flex-row flex-wrap gap-1 mt-3">
                            @foreach ($labels as $label)
                                @if ($label->display)
                                    <a href="{{ route('labels.show', $label) }}"
                                        class="py-0.5 px-1.5 font-semibold text-white text-sm"
                                        style="background-color: {{ $label->color }};">{{ $label->name }}</a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="border px-2.5 py-2 border-gray-400">
                        <h3 class="mb-0.5 text-xl font-semibold">
                            Statisztika
                        </h3>
                        <ul class="fa-ul">
                            <li><span class="fa-li"><i class="fas fa-user"></i></span>Felhasználók:
                                {{ $user_count }}
                            </li>
                            <li><span class="fa-li"><i class="fas fa-file-alt"></i></span>Kiállított tárgyak:
                                {{ $item_count }}</li>
                            <li><span class="fa-li"><i class="fas fa-comments"></i></span>Hozzászólások:
                                {{ $comments_count }}</li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
