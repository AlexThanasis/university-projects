<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Online LEGO Múzeum - {{ $title ?? 'Nincs cím' }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <nav class="flex items-center justify-between flex-wrap bg-slate-800 p-6">
        <div class="flex items-center flex-shrink-0 text-white mr-6">
            <span class="font-semibold text-xl tracking-tight">LEGO Múzeum</span>
        </div>
        <div class="block lg:hidden">
            <button
                class="flex items-center px-3 py-2 border rounded text-blue-200 border-blue-400 hover:text-white hover:border-white">
                <svg class="fill-current h-3 w-3" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <title>Menu</title>
                    <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z" />
                </svg>
            </button>
        </div>
        <div class="w-full block flex-grow lg:flex lg:items-center lg:w-auto">
            <div class="text-sm lg:flex-grow">
                @auth
                    <form action="{{ route('logout') }}" method="post" id="logout-form">
                        @csrf
                        <a class="inline-block text-sm px-4 py-2 leading-none border rounded text-white border-transparent hover:border-white lg:mt-0"

                            href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.querySelector('#logout-form').submit()">
                            Kijelentkezés
                        </a>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                        class="inline-block text-sm px-4 py-2 leading-none border rounded text-white border-transparent hover: border-white lg:mt-0e">
                        Bejelentkezés
                    </a>
                    <a href="{{ route('register') }}"
                        class="inline-block text-sm px-4 py-2 leading-none border rounded text-white border-transparent hover:border-white lg:mt-0">
                        Regisztráció
                    </a>

                @endauth

            </div>
            <div>
                @auth
                    <a href="#"
                        class="block mt-4 lg:inline-block lg:mt-0 text-blue-200 hover:text-white mr-4">Üdv,
                        {{ Auth::user()->name }}</a>
                @endauth

            </div>
        </div>
    </nav>
    <div class="font-sans text-gray-900 antialiased">
        {{ $slot }}
    </div>
</body>

</html>
