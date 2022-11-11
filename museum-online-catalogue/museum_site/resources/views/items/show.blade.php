<x-guest-layout>
    <x-slot name="title">
        {{ $item->name }} műtárgy
    </x-slot>
    <div class="container mx-auto p-3 lg:px-36">
        <div class="grid grid-cols-4 gap-6">
            <div class="col-span-4 lg:col-span-3">
                <div>
                    <h1 class="font-bold my-4 text-4xl">{{ $item->name }}</h1>
                </div>
                <div>
                    <h3 class="text-xl mb-0.5 font-semibold">
                        {{ $item->name }}
                    </h3>
                    <h4 class="text-gray-400">
                        <span class="mr-2"><i class="fas fa-user"></i>{{ $item->obtained }}</span>
                    </h4>
                    @if ($item->image === null)
                        <img src="{{ Storage::url('images/basic_lego_if_no_picture_uploaded.jpeg') }}">
                    @elseif ($item->image !== null && strpos($item->image, 'http') !== false)
                        <img src={{ $item->image }}>
                    @else
                        <img src={{ Storage::url('images/' . $item->image) }}>
                    @endif
                    <p class="text-gray-600 mt-1">
                        {!! str_replace('\n\n', '<br>', $item->description) !!}
                    </p>
                </div>

                @can('delete', $item)
                    {{-- @auth --}}
                    <form action="{{ route('items.destroy', $item) }}" method="post" id="delete-form">
                        @csrf
                        @method('DELETE')
                        <a href="{{ route('items.destroy', $item) }}"
                            onclick="event.preventDefault(); document.querySelector('#delete-form').submit();"
                            class="bg-red-500 hover:bg-red-700 px-2 py-1 text-grey"><i class="fas fa-trash"></i> Kiállított
                            tárgy törlése</a>
                    </form>
                    {{-- @endauth --}}
                @endcan

                <div class="border px-2.5 py-2 border-gray-400">
                    <h3 class="mb-0.5 text-xl font-semibold">
                        Hozzászólások
                    </h3>
                    <div class="flex flex-row flex-wrap gap-1 mt-3">
                        @forelse ($item->comments as $comment)
                        <div class="border px-2.5 py-2 border-gray-400">
                            <h4 class="text-xl font-semibold">
                                {{ $comment -> user_id }}
                            </h4>
                            <h5>
                                {{ $comment -> created_at }}
                            </h5>
                            <p class="col-span-3 bg-gray-100 text-center rounded-lg py-1">
                                {!! str_replace('\n\n', '<br>', $comment->text) !!}
                            </p>
                        </div>
                        @empty
                            <div class="col-span-3 bg-red-200 text-center rounded-lg py-1">
                                Ehhez a műtárgyhoz nincsek hozzászólások
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
            <div class="col-span-4 lg:col-span-1">
                <div class="border px-2.5 py-2 border-gray-400">
                    <h3 class="mb-0.5 text-xl font-semibold">
                        Címkék
                    </h3>
                    <div class="flex flex-row flex-wrap gap-1 mt-3">
                        @forelse ($item->labels as $label)
                            <a href="{{ route('labels.show', $label) }}"
                                class="py-0.5 px-1.5 font-semibold text-white text-sm"
                                style="background-color: {{ $label->color }};">{{ $label->name }}</a>
                        @empty
                            <div class="col-span-3 bg-red-200 text-center rounded-lg py-1">
                                Ehhez a műtárgyhoz nincsek címkék
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-guest-layout>
