<x-guest-layout>
    <x-slot name="title">
        Új kiállított tárgy felvétele
    </x-slot>
    <div class="container mx-auto p-3 overflow-hidden min-h-screen">
        <div class="mb-5">
            <h1 class="font-semibold text-3xl mb-4">Új kiállított tárgy felvétele</h1>
            <p class="mb-2">Ezen az oldalon tudsz új kiállított tárgyat létrehozni.</p>
            <a href="/" class="text-blue-400 hover:text-blue-600 hover:underline"><i
                    class="fas fa-long-arrow-alt-left"></i> Vissza a bejegyzésekhez</a>
        </div>

        <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="container mx-auto p-3">
                <div class="flex flex-col">
                    <div class="w-full">
                        <label for="name" class="block font-medium text-gray-700">Kiállított tárgy neve</label>
                        <input type="text" name="name"
                            class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300">
                    </div>
                    <div class="w-full">
                        <label class="block font-medium text-gray-700">Kiállított tárgy leírása</label>
                        <textarea name="description"
                            class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300"></textarea>
                    </div>
                    <div class="w-full">
                        <label class="block font-medium text-gray-700">Kiállított tárgy képe</label>
                        <input type="file" name="image"
                            class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300">
                    </div>
                </div>
                <div class="w-full">
                    <label class="block font-medium text-gray-700">Kiállított tárgy katalógusbeli felvételi
                        időpontja</label>
                    <input type="date" id="obtained" name="obtained" value="2022-07-22"
                        class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300">
                </div>
            </div>
            <div class="w-full">
                <label class="block font-medium text-gray-700">Címkék</label>
                <div class="flex flex-row pb-1">
                    @foreach ($labels as $l)
                        <input type="checkbox" class="my-0.5 mx-1" name="labels[]" value="{{ $l->id }}">
                        <div class="py-0.5 px-1.5 font-semibold text-sm"
                            style="background-color: {{ $l->color }}; color: ;">{{ $l->name }}</div>
                    @endforeach
                </div>
            </div>
            <button type="submit"
                class="mt-6 bg-green-500 hover:bg-green-600 text-gray-100 rounded-xl font-semibold px-2 py-1 text-xl">Létrehozás</button>
    </div>

    </form>
    </div>
</x-guest-layout>
