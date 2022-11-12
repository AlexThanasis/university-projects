<x-guest-layout>
    <x-slot name="title">
        Új címke
    </x-slot>
    <div class="container mx-auto p-3 overflow-hidden min-h-screen">
        <div class="mb-5">
            <h1 class="font-semibold text-3xl mb-4">Új címke</h1>
            <p class="mb-2">Ezen az oldalon tudsz új címkéket létrehozni, mint admin. A tárgyakat úgy tudod
                hozzárendelni, ha
                a címke létrehozása után módosítod a bejegyzést, és ott bejelölöd ezt a címkét is. Csak akkor jelenik
                meg egy címke,
                ha be van állítva a megjeleníthetősége. A címkének színt is be tudsz állítani. </p>
            <a href="{{ route('home') }}" class="text-blue-400 hover:text-blue-600 hover:underline"><i
                    class="fas fa-long-arrow-alt-left"></i> Vissza a bejegyzésekhez</a>
        </div>

        <form x-data="{ labelName: '{{ old('name', '') }}', color: '{{ old('color', '#ff9910ff') }}' }" x-init="() => {
            new Picker({
                color: color,
                popup: 'bottom',
                parent: $refs.colorPicker,
                onDone: (color) => color = color.hex
            });
            {{-- new Picker({
                color: textColor,
                popup: 'bottom',
                parent: $refs.textColorPicker,
                onDone: (color) => textColor = color.hex
            }); --}}
        }" action="{{ route('labels.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-4 gap-6">
                <div class="col-span-4 lg:col-span-2 grid grid-cols-2 gap-3">
                    <div class="col-span-2">
                        <label for="name" class="block font-medium text-gray-700">Címke neve</label>
                        <input type="text" name="name" id="name"
                            class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300"
                            x-model="labelName">
                        @error('name')
                            <div class="font-medium text-red-500">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-span-2 lg:col-span-1">
                        <label class="block text-sm font-medium text-gray-700">Háttér színe</label>
                        <div x-ref="colorPicker" id="color-picker" class="mt-1 h-8 w-full border border-black"
                            :style="`background-color: ${color};`"></div>
                        <p x-text="color"></p>
                        @error('name')
                            <div class="font-medium text-red-500">{{ $message }}</div>
                        @enderror
                    </div>
                    {{-- <div class="col-span-2 lg:col-span-1">
                        <label class="block text-sm font-medium text-gray-700">Display</label>
                        <div x-ref="textColorPicker" id="text-color-picker" class="mt-1 h-8 w-full border border-black"
                            :style="`background-color: ${textColor};`"></div>
                        <p x-text="textColor"></p>
                    </div> --}}
                    {{-- <div class="col-span-2 lg:col-span-1">
                        <label class="block text-sm font-medium text-gray-700">Szöveg színe</label>
                        <div x-ref="textColorPicker" id="text-color-picker" class="mt-1 h-8 w-full border border-black"
                            :style="`background-color: ${textColor};`"></div>
                        <p x-text="textColor"></p>
                    </div> --}}
                </div>
                <div class="col-span-4 lg:col-span-2">
                    <div x-show="labelName.length > 0">
                        <label class="block font-medium text-gray-700 mb-1">Előnézet</label>
                        <span x-text="labelName" :style="`background-color: ${color}; color: white`"
                            class="py-0.5 px-1.5 font-semibold"></span>
                    </div>
                </div>
            </div>

            <input type="hidden" id="color" name="color" x-model="color" />
            {{-- <input type="hidden" id="text-color" name="text-color" x-model="textColor" /> --}}
            <input type="checkbox" id="display" name="display" checked />

            <button type="submit"
                class="mt-6 bg-blue-500 hover:bg-blue-600 text-gray-100 font-semibold px-2 py-1 text-xl">Létrehozás</button>
        </form>
    </div>
</x-guest-layout>
