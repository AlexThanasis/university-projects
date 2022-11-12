<x-guest-layout>
    <x-slot name="title">
        Kiállított tárgy szerkesztése
    </x-slot>
    <div class="container mx-auto p-3 overflow-hidden min-h-screen">
        <div class="mb-5">
            <h1 class="font-semibold text-3xl mb-4">Kiállított tárgy szerkesztése</h1>
            <p class="mb-2">Ezen az oldalon tudsz szerkeszteni egy kiállított tárgyat.</p>
            <a href="{{ route('home') }}" class="text-blue-400 hover:text-blue-600 hover:underline"><i
                    class="fas fa-long-arrow-alt-left"></i> Vissza a bejegyzésekhez</a>
        </div>

        <form enctype="multipart/form-data" action="{{ route('items.update', $item) }}" method="POST">
            @csrf
            @method('PATCH')

            <div class="container mx-auto p-3">
                <div class="flex flex-col">
                    <div class="w-full">
                        <label for="name" class="block font-medium text-gray-700">Kiállított tárgy neve</label>
                        <input type="text" name="name" value="{{ $item->name }}"
                            class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300">
                    </div>
                    <div class="w-full">
                        <label class="block font-medium text-gray-700">Kiállított tárgy leírása</label>
                        <textarea name="description"
                            class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300">{{ $item->description }}</textarea>
                    </div>
                    <div class="w-full">
                        <label class="block font-medium text-gray-700">Kiállított tárgy képe</label>
                        <input type="file" name="image" value="{{ $item->image }}"
                            class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300">
                    </div>
                </div>
                <div class="w-full">
                    <label class="block font-medium text-gray-700">Kiállított tárgy katalógusbeli felvételi
                        időpontja</label>
                    <input type="date" id="obtained" name="obtained" value="{{ $item->obtained }}"
                        class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300">
                </div>
            </div>
            <div class="w-full">
                <label class="block font-medium text-gray-700">Címkék</label>
                <div class="flex flex-row pb-1">
                    @foreach ($item->labels as $l)
                        <input type="checkbox" class="my-0.5 mx-1" name="labels[]" value="{{ $l->id }}" checked>
                        <div class="py-0.5 px-1.5 font-semibold text-sm"
                            style="background-color: {{ $l->color }}; color: ;">{{ $l->name }}</div>
                    @endforeach
                </div>
            </div>
            <div class="w-full">
                <label class="block font-medium text-gray-700">Hozzászólások</label>
                <div class="flex flex-row pb-1">
                    @forelse ($item->comments as $comment)
                        <input type="checkbox" class="my-0.5 mx-1" name="comments[]" value="{{ $comment->id }}" checked>
                        <div class="border px-2.5 py-2 border-gray-400">
                            <h4 class="text-xl font-semibold">
                                {{ $comment->user_id }}
                            </h4>
                            <h5>
                                {{ $comment->created_at }}
                            </h5>
                            <p class="col-span-3 bg-gray-100 text-justify rounded-lg py-1">
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

    <button type="submit"
        class="mt-6 bg-blue-500 hover:bg-blue-600 text-gray-100 font-semibold px-2 py-1 text-xl">Létrehozás</button>
    </form>

    {{-- <form x-data="{ labelName: '{{ old('name', $item->name) }}', bgColor: '{{ old('bg-color', $label->bg_color) }}', textColor: '{{ old('text-color', $label->text_color) }}' }" x-init="() => {
        new Picker({
            color: bgColor,
            popup: 'bottom',
            parent: $refs.bgColorPicker,
            onDone: (color) => bgColor = color.hex
        });
        new Picker({
            color: textColor,
            popup: 'bottom',
            parent: $refs.textColorPicker,
            onDone: (color) => textColor = color.hex
        });
    }" actions="{{ route('labels.update') }}" method="POST">
        @csrf
        @method('PATCH')
        <div class="grid grid-cols-4 gap-6">
            <div class="col-span-4 lg:col-span-2 grid grid-cols-2 gap-3">
                <div class="col-span-2">
                    <label for="name" class="block font-medium text-gray-700">Cimke neve</label>
                    <input type="text" name="name" id="name"
                        class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300"
                        x-model="labelName">
                    @error('name')
                        <div class="font-medium text-red-500">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-span-2 lg:col-span-1">
                    <label class="block text-sm font-medium text-gray-700">Háttér színe</label>
                    <div x-ref="bgColorPicker" id="bg-color-picker" class="mt-1 h-8 w-full border border-black"
                        :style="`background-color: ${bgColor};`"></div>
                    <p x-text="bgColor"></p>
                </div>
                <div class="col-span-2 lg:col-span-1">
                    <label class="block text-sm font-medium text-gray-700">Szöveg színe</label>
                    <div x-ref="textColorPicker" id="text-color-picker" class="mt-1 h-8 w-full border border-black"
                        :style="`background-color: ${textColor};`"></div>
                    <p x-text="textColor"></p>
                </div>
            </div>
            <div class="col-span-4 lg:col-span-2">
                <div x-show="labelName.length > 0">
                    <label class="block font-medium text-gray-700 mb-1">Előnézet</label>
                    <span x-text="labelName" :style="`background-color: ${bgColor}; color: ${textColor}`"
                        class="py-0.5 px-1.5 font-semibold"></span>
                </div>
            </div>
        </div>

        <input type="hidden" id="bg-color" name="bg-color" x-model="bgColor" />
        <input type="hidden" id="text-color" name="text-color" x-model="textColor" />
        <input type="checkbox" id="display" name="display" checked />

        <button type="submit"
            class="mt-6 bg-blue-500 hover:bg-blue-600 text-gray-100 font-semibold px-2 py-1 text-xl">Létrehozás</button>
    </form> --}}
    </div>
</x-guest-layout>
