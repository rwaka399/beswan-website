@extends('master.layout')

@section('title', 'Menu Management')

@section('content')
    <div class="mt-4 max-w-full mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Menu Management</h1>
            <a href="{{ route('menu-create') }}"
                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200">
                <i class="fas fa-plus mr-2"></i>Add New Menu
            </a>
        </div>

        <!-- Flash Message -->
        @if (session('success'))
            <div class="flash-message transition-opacity duration-500 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6"
                role="alert">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="flash-message transition-opacity duration-500 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6"
                role="alert">
                {{ session('error') }}
            </div>
        @endif

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <i class="fas fa-list text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Menus</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $query->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <i class="fas fa-layer-group text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Parent Menus</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $query->whereNull('menu_parent')->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                        <i class="fas fa-sitemap text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Sub Menus</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $query->whereNotNull('menu_parent')->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Menu Table -->
        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Menu Name
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Type
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Icon
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Link
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Order
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Parent
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($query as $menu)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        @if ($menu->menu_parent)
                                            <span class="text-gray-400 mr-2">└─</span>
                                        @endif
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $menu->menu_name }}</div>
                                            <div class="text-sm text-gray-500">{{ $menu->menu_slug }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                        @if ($menu->menu_type === 'parent') bg-blue-100 text-blue-800
                                        @elseif($menu->menu_type === 'child') bg-green-100 text-green-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst($menu->menu_type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if ($menu->menu_icon)
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 text-gray-600 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                {!! \App\Helpers\IconHelper::getFontAwesomeToSvg($menu->menu_icon) !!}
                                            </svg>
                                            <span class="text-gray-500 text-xs">{{ $menu->menu_icon }}</span>
                                        </div>
                                    @else
                                        <span class="text-gray-400">No icon</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if ($menu->menu_link)
                                        <code class="bg-gray-100 px-2 py-1 rounded text-xs">{{ $menu->menu_link }}</code>
                                    @else
                                        <span class="text-gray-400">No link</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <span
                                        class="inline-flex items-center px-2 py-1 bg-gray-100 rounded-full text-xs font-medium">
                                        {{ $menu->menu_urutan }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if ($menu->parent)
                                        <span class="text-blue-600">{{ $menu->parent->menu_name }}</span>
                                    @else
                                        <span class="text-gray-400">Root Menu</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('menu-show', $menu->menu_id) }}"
                                            class="inline-block bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 mr-2">
                                            Show
                                        </a>
                                        <a href="{{ route('menu-edit', $menu->menu_id) }}"
                                            class="inline-block bg-yellow-500 text-white px-4 py-2 rounded-md hover:bg-yellow-600 mr-2">
                                            Edit
                                        </a>
                                        <form action="{{ route('menu-destroy', $menu->menu_id) }}" method="POST"
                                            class="inline-block"
                                            onsubmit="return confirm('Are you sure you want to delete this menu?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                class="inline-block bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-list text-4xl text-gray-300 mb-4"></i>
                                        <p class="text-gray-500 text-lg">No menus found</p>
                                        <p class="text-gray-400 text-sm">Start by creating your first menu</p>
                                        <a href="{{ route('menu-create') }}"
                                            class="mt-4 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200">
                                            <i class="fas fa-plus mr-2"></i>Create Menu
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Menu Hierarchy Tree -->
        @if ($query->count() > 0)
            <div class="mt-8 bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Menu Hierarchy</h3>
                    <p class="text-sm text-gray-600">Visual representation of menu structure</p>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach ($query->whereNull('menu_parent')->sortBy('menu_urutan') as $parentMenu)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        @if ($parentMenu->menu_icon)
                                            <svg class="w-5 h-5 text-blue-600 mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                {!! \App\Helpers\IconHelper::getFontAwesomeToSvg($parentMenu->menu_icon) !!}
                                            </svg>
                                        @endif
                                        <div>
                                            <h4 class="font-medium text-gray-900">{{ $parentMenu->menu_name }}</h4>
                                            <p class="text-sm text-gray-500">{{ $parentMenu->menu_type }} • Order:
                                                {{ $parentMenu->menu_urutan }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        @if ($parentMenu->children->count() > 0)
                                            <span
                                                class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">
                                                {{ $parentMenu->children->count() }} sub-menu(s)
                                            </span>
                                        @endif
                                        <a href="{{ route('menu-edit', $parentMenu->menu_id) }}"
                                            class="text-indigo-600 hover:text-indigo-900">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </div>

                                @if ($parentMenu->children->count() > 0)
                                    <div class="mt-4 ml-8 space-y-2">
                                        @foreach ($parentMenu->children->sortBy('menu_urutan') as $childMenu)
                                            <div
                                                class="flex items-center justify-between py-2 px-3 bg-gray-50 rounded border-l-4 border-blue-300">
                                                <div class="flex items-center">
                                                    <span class="text-gray-400 mr-2">└─</span>
                                                    <div>
                                                        <span
                                                            class="text-sm font-medium text-gray-700">{{ $childMenu->menu_name }}</span>
                                                        <span class="text-xs text-gray-500 ml-2">Order:
                                                            {{ $childMenu->menu_urutan }}</span>
                                                    </div>
                                                </div>
                                                <a href="{{ route('menu-edit', $childMenu->menu_id) }}"
                                                    class="text-indigo-600 hover:text-indigo-900">
                                                    <i class="fas fa-edit text-sm"></i>
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>

    <script>
        // Auto-hide flash messages after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const flashMessages = document.querySelectorAll('.flash-message');
            flashMessages.forEach(function(message) {
                setTimeout(function() {
                    message.style.opacity = '0';
                    setTimeout(function() {
                        message.remove();
                    }, 500);
                }, 5000);
            });
        });
    </script>
@endsection
