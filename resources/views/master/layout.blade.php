<!doctype html>
<html>

<head>
    <title>Admin Dashboard</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <style>
        .animate-fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .hover-scale {
            transition: transform 0.2s ease-in-out;
        }
        
        .hover-scale:hover {
            transform: scale(1.02);
        }
        
        .loading-shimmer {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
        }
        
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
    </style>
</head>

<body class="bg-gray-50 font-sans antialiased">
    <!-- Navbar -->
    <nav class="bg-white border-b border-gray-200 shadow-sm fixed top-0 left-0 right-0 z-20 w-full">
        <div class="flex items-center justify-between px-4 sm:px-6 lg:px-8 h-12">
            <!-- Kiri: Logo -->
            <div class="flex items-center">
                <a href="#" class="flex items-center space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-700" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h18v4H3V3zM3 9h6v12H3V9zm8 0h10v8H11V9z" />
                    </svg>
                    <span class="font-semibold text-gray-700">Admin Panel</span>
                </a>
            </div>

            <!-- Right: User Dropdown -->
            <div class="relative flex items-center">
                <button id="userDropdown" class="flex items-center text-gray-700 hover:text-gray-900 focus:outline-none"
                    aria-haspopup="true" aria-expanded="false">
                    <span class="mr-2 text-sm">{{ Auth::user()->name }}</span>
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <!-- Dropdown Menu dengan Shadow -->
                <div id="dropdownMenu"
                    class="hidden absolute right-0 top-full mt-2 w-48 bg-white rounded-lg shadow-lg text-gray-700 py-2 z-30">
                    <a href="{{ route('profile-index') }}" class="block px-4 py-2 hover:bg-blue-50 text-sm">Profile</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="block w-full text-left px-4 py-2 hover:bg-blue-50 text-sm">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main id="content" class="pt-3 lg:ml-64 min-h-screen">
        <!-- Breadcrumb -->
        <div class="sticky top-12 inset-x-0 z-10 bg-white border-y border-gray-200 px-4 sm:px-6 lg:px-8 lg:hidden">
            <div class="flex items-center py-2">
                <!-- Navigation Toggle -->
                <button type="button"
                    class="size-8 flex justify-center items-center gap-x-2 border border-gray-200 text-gray-800 hover:text-gray-500 rounded-lg focus:outline-none focus:text-gray-500"
                    aria-haspopup="dialog" aria-expanded="false" aria-controls="hs-application-sidebar"
                    aria-label="Toggle navigation" id="toggleSidebar">
                    <span class="sr-only">Toggle Navigation</span>
                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <rect width="18" height="18" x="3" y="3" rx="2" />
                        <path d="M15 3v18" />
                        <path d="m8 9 3 3-3 3" />
                    </svg>
                </button>
                <!-- End Navigation Toggle -->

                <!-- Breadcrumb -->
                <ol class="ms-3 flex items-center whitespace-nowrap">
                    <li class="flex items-center text-sm text-gray-800">
                        Application Layout
                        <svg class="shrink-0 mx-3 overflow-visible size-2.5 text-gray-400" width="16" height="16"
                            viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M5 1L10.6869 7.16086C10.8637 7.35239 10.8637 7.64761 10.6869 7.83914L5 14"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                        </svg>
                    </li>
                    <li class="text-sm font-semibold text-gray-800 truncate" aria-current="page">
                        Dashboard
                    </li>
                </ol>
                <!-- End Breadcrumb -->
            </div>
        </div>
        <!-- End Breadcrumb -->

        <!-- Sidebar -->
        <div id="hs-application-sidebar"
            class="fixed top-12 left-0 h-[calc(100%-3rem)] w-64 z-10 bg-white border-r border-gray-200 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">
            <div class="relative flex flex-col h-full max-h-full">
                <!-- Content -->
                <div
                    class="h-full overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300">
                    <nav class="p-3 w-full flex flex-col flex-wrap">
                        <ul class="flex flex-col space-y-1">
                            @if(isset($menus))
                                @foreach($menus as $menu)
                                    @if($menu->menu_type === 'main' || $menu->menu_type === 'parent')
                                        <li @if($menu->children->count() > 0) class="accordion" id="{{ $menu->menu_slug }}-accordion" @endif>
                                            @if($menu->children->count() > 0)
                                                <!-- Parent Menu with Children -->
                                                <button type="button"
                                                    class="accordion-toggle w-full text-start flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                                    aria-expanded="false" aria-controls="{{ $menu->menu_slug }}-accordion-child">
                                                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24"
                                                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        {!! \App\Helpers\IconHelper::getFontAwesomeToSvg($menu->menu_icon) !!}
                                                    </svg>
                                                    {{ $menu->menu_name }}
                                                    <svg class="accordion-active:block ms-auto hidden size-4"
                                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="m18 15-6-6-6 6" />
                                                    </svg>
                                                    <svg class="accordion-active:hidden ms-auto block size-4"
                                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="m6 9 6 6 6-6" />
                                                    </svg>
                                                </button>
                                                <div id="{{ $menu->menu_slug }}-accordion-child"
                                                    class="accordion-content w-full overflow-hidden transition-[height] duration-300 hidden"
                                                    role="region" aria-labelledby="{{ $menu->menu_slug }}-accordion">                                                    <ul class="ps-8 pt-1 space-y-1">
                                                        @foreach($menu->children->sortBy('menu_urutan') as $child)
                                                            <li>
                                                                @php
                                                                    $routeName = \App\Helpers\IconHelper::getRouteFromLink($child->menu_link);
                                                                @endphp
                                                                @if($routeName && $routeName !== '#' && \Illuminate\Support\Facades\Route::has($routeName))
                                                                    <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                                                        href="{{ route($routeName) }}">
                                                                        {{ $child->menu_name }}
                                                                    </a>
                                                                @else
                                                                    <span class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-400 rounded-lg cursor-not-allowed">
                                                                        {{ $child->menu_name }}
                                                                        <small class="text-xs">(Coming Soon)</small>
                                                                    </span>
                                                                @endif
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @else
                                                <!-- Single Menu -->
                                                @if($menu->menu_slug === 'logout')
                                                    <form method="POST" action="{{ route('logout') }}">
                                                        @csrf
                                                        <button type="submit"
                                                            class="w-full flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-none focus:bg-gray-100">
                                                            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg"
                                                                width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                                stroke-linejoin="round">
                                                                {!! \App\Helpers\IconHelper::getFontAwesomeToSvg($menu->menu_icon) !!}
                                                            </svg>
                                                            {{ $menu->menu_name }}
                                                        </button>
                                                    </form>                                                @else
                                                    @php
                                                        $routeName = \App\Helpers\IconHelper::getRouteFromLink($menu->menu_link);
                                                    @endphp
                                                    @if($routeName && $routeName !== '#' && \Illuminate\Support\Facades\Route::has($routeName))
                                                        <a class="w-full flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                                            href="{{ route($routeName) }}">
                                                            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                {!! \App\Helpers\IconHelper::getFontAwesomeToSvg($menu->menu_icon) !!}
                                                            </svg>
                                                            {{ $menu->menu_name }}
                                                        </a>
                                                    @else
                                                        <span class="w-full flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-400 rounded-lg cursor-not-allowed">
                                                            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                {!! \App\Helpers\IconHelper::getFontAwesomeToSvg($menu->menu_icon) !!}
                                                            </svg>
                                                            {{ $menu->menu_name }}
                                                            <small class="text-xs ml-2">(Coming Soon)</small>
                                                        </span>
                                                    @endif
                                                @endif
                                            @endif
                                        </li>
                                    @endif
                                @endforeach
                            @endif
                        </ul>
                    </nav>
                </div>
                <!-- End Content -->
            </div>
        </div>
        <!-- End Sidebar -->

        <!-- Main Content Area -->
        <div class="p-4 sm:p-6 lg:p-8 animate-fade-in">
            <!-- Header -->
            @yield('content')
        </div>
        <!-- End Main Content Area -->
    </main>

    <style>
        /* Animasi fade-in untuk konten */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.5s ease-out forwards;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('hs-application-sidebar');
            const toggleSidebar = document.getElementById('toggleSidebar');
            const userDropdown = document.getElementById('userDropdown');
            const dropdownMenu = document.getElementById('dropdownMenu');
            const accordions = document.querySelectorAll('.accordion-toggle');

            // Toggle sidebar on mobile
            toggleSidebar.addEventListener('click', function() {
                sidebar.classList.toggle('-translate-x-full');
                toggleSidebar.setAttribute('aria-expanded', sidebar.classList.contains(
                    '-translate-x-full') ? 'false' : 'true');
            });

            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(event) {
                if (!sidebar.contains(event.target) && !toggleSidebar.contains(event.target) && !sidebar
                    .classList.contains('-translate-x-full')) {
                    sidebar.classList.add('-translate-x-full');
                    toggleSidebar.setAttribute('aria-expanded', 'false');
                }
            });

            // Toggle user dropdown
            userDropdown.addEventListener('click', function() {
                dropdownMenu.classList.toggle('hidden');
                userDropdown.setAttribute('aria-expanded', dropdownMenu.classList.contains('hidden') ?
                    'false' : 'true');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(event) {
                if (!userDropdown.contains(event.target) && !dropdownMenu.contains(event.target)) {
                    dropdownMenu.classList.add('hidden');
                    userDropdown.setAttribute('aria-expanded', 'false');
                }
            });

            // Accordion functionality
            accordions.forEach(accordion => {
                accordion.addEventListener('click', function() {
                    const content = this.nextElementSibling;
                    const isOpen = !content.classList.contains('hidden');
                    const svgs = this.querySelectorAll('svg.ms-auto');

                    // Toggle visibility
                    content.classList.toggle('hidden');
                    this.setAttribute('aria-expanded', isOpen ? 'false' : 'true');

                    // Toggle SVG icons
                    svgs.forEach(svg => {
                        svg.classList.toggle('hidden');
                    });

                    // Set height for smooth transition
                    if (!isOpen) {
                        content.style.height = content.scrollHeight + 'px';
                    } else {
                        content.style.height = '0px';
                    }
                });

                // Initialize height for transition
                const content = accordion.nextElementSibling;
                if (!content.classList.contains('hidden')) {
                    content.style.height = content.scrollHeight + 'px';
                }
            });
        });
    </script>
</body>

</html>
