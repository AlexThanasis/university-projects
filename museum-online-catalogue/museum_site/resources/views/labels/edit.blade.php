<x-guest-layout>
    <x-slot name="title">
        Címke szerkesztése
    </x-slot>
    <div class="container mx-auto p-3 overflow-hidden min-h-screen">
        <div class="mb-5">
            <div class="flex-auto flex space-x-4">
                <h1 class="font-semibold text-3xl mb-4">Címke szerkesztése</h1>
                <form action="{{ route('labels.destroy', $label) }}" method="post" id="delete-label-form">
                    @csrf
                    @method('DELETE')
                    <a href="{{ route('labels.destroy', $label) }}"
                        onclick="event.preventDefault(); document.querySelector('#delete-label-form').submit();"
                        class="bg-red-500 hover:bg-red-600 rounded-full px-2 py-1 text-white"><i
                            class="fas fa-trash"></i> Címke törlése</a>
                </form>
            </div>
            <p class="mb-2">Ezen az oldalon tudod a címkéket szerkeszteni. Tudod törölni is az adott címkét</p>
            <a href="{{ route('home') }}" class="text-blue-400 hover:text-blue-600 hover:underline"><i
                    class="fas fa-long-arrow-alt-left"></i> Vissza a bejegyzésekhez</a>
        </div>

        <form x-data="{ labelName: '{{ $label->name }}', bgColor: '{{ $label->color }}', display: '{{ $label->display }}' }" x-init="() => {
            new Picker({
                color: bgColor,
                popup: 'bottom',
                parent: $refs.bgColorPicker,
                onDone: (color) => bgColor = color.hex
            });
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
                        <div x-ref="bgColorPicker" id="bg-color-picker" class="mt-1 h-8 w-full border border-black"
                            :style="`background-color: ${bgColor};`"></div>
                        <p x-text="bgColor"></p>
                        @error('name')
                            <div class="font-medium text-red-500">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-span-2 lg:col-span-1">
                        <label class="block text-sm font-medium text-gray-700">Címke látható legyen</label>
                        <input type="checkbox" id="display" name="display" x-model="display" checked>

                    </div>
                </div>
                <div class="col-span-4 lg:col-span-2">
                    <div x-show="labelName.length > 0">
                        <label class="block font-medium text-gray-700 mb-1">Előnézet</label>
                        <span x-text="labelName" :style="`background-color: ${bgColor}; color: white`"
                            class="py-0.5 px-1.5 font-semibold"></span>
                    </div>
                </div>
            </div>

            <input type="hidden" id="bg-color" name="bg-color" x-model="bgColor" />

            <button type="submit"
                class="mt-6 bg-green-500 hover:bg-green-600 text-gray-100 rounded-xl font-semibold px-2 py-1 text-xl">Elmentés</button>
        </form>
    </div>
</x-guest-layout>
