@extends('master.layout')

@section('title', 'Menu Details')

@section('content')
    <!-- Breadcrumb -->
    <div class="sticky top-12 inset-x-0 z-10 px-4 sm:px-6 lg:px-8">
        <div class="flex items-center py-3">
            <ol class="flex items-center whitespace-nowrap">
                <li class="flex items-center text-base text-gray-800">
                    <a href="{{ route('menu-index') }}" class="hover:text-blue-600 font-medium">Menus</a>
                    <svg class="shrink-0 mx-3 overflow-visible size-3 text-gray-400" width="16" height="16"
                        viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5 1L10.6869 7.16086C10.8637 7.35239 10.8637 7.64761 10.6869 7.83914L5 14"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                    </svg>
                </li>
                <li class="text-base font-semibold text-gray-800 truncate" aria-current="page">
                    {{ $menu->menu_name }}
                </li>
            </ol>
        </div>
    </div>

    <!-- Menu Details -->
    <div class="max-w-4xl mx-auto mt-8 px-4 sm:px-6 lg:px-8">
        <div class="w-full bg-white rounded-3xl shadow-xl p-8 space-y-6">
            <!-- Header -->
            <div class="text-center">
                <div class="flex justify-center items-center mb-4">
                    @if($menu->menu_icon)
                        <i class="{{ $menu->menu_icon }} text-4xl text-blue-600 mr-3"></i>
                    @endif
                    <svg class="h-12 w-12 text-blue-600" xmlns="http://www.w3.org/2000/svg" width="24"
                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <rect width="3" height="3" x="9" y="9" rx="0.5"/>
                        <rect width="3" height="3" x="9" y="15" rx="0.5"/>
                        <rect width="3" height="3" x="15" y="9" rx="0.5"/>
                        <rect width="3" height="3" x="15" y="15" rx="0.5"/>
                        <rect width="3" height="3" x="3" y="9" rx="0.5"/>
                        <rect width="3" height="3" x="3" y="15" rx="0.5"/>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-800">{{ $menu->menu_name }}</h2>
                <p class="text-sm text-gray-500">Menu Details</p>
            </div>

            <!-- Menu Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Basic Information -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-800 border-b border-gray-200 pb-2">Basic Information</h3>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Menu Name</label>
                        <p class="mt-1 text-sm text-gray-900 bg-gray-50 rounded-lg px-3 py-2">{{ $menu->menu_name }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600">Menu Type</label>
                        <p class="mt-1">
                            <span class="px-3 py-1 text-sm font-medium rounded-full
                                {{ $menu->menu_type === 'header' ? 'bg-purple-100 text-purple-800' : 
                                   ($menu->menu_type === 'menu' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                {{ ucfirst($menu->menu_type) }}
                            </span>
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600">Menu Slug</label>
                        <p class="mt-1 text-sm text-gray-900 bg-gray-50 rounded-lg px-3 py-2 font-mono">{{ $menu->menu_slug }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600">Menu Order</label>
                        <p class="mt-1">
                            <span class="bg-gray-100 px-3 py-1 rounded-full text-sm font-medium">
                                {{ $menu->menu_urutan }}
                            </span>
                        </p>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-800 border-b border-gray-200 pb-2">Additional Information</h3>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Menu Icon</label>
                        @if($menu->menu_icon)
                            <div class="mt-1 flex items-center space-x-3">
                                <i class="{{ $menu->menu_icon }} text-2xl text-blue-600"></i>
                                <p class="text-sm text-gray-900 bg-gray-50 rounded-lg px-3 py-2 font-mono">{{ $menu->menu_icon }}</p>
                            </div>
                        @else
                            <p class="mt-1 text-sm text-gray-500 italic">No icon specified</p>
                        @endif
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600">Menu Link</label>
                        @if($menu->menu_link)
                            <p class="mt-1 text-sm text-gray-900 bg-gray-50 rounded-lg px-3 py-2 font-mono">{{ $menu->menu_link }}</p>
                        @else
                            <p class="mt-1 text-sm text-gray-500 italic">No link specified</p>
                        @endif
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600">Parent Menu</label>
                        @if($menu->menu_parent)
                            @php
                                $parent = App\Models\Menu::find($menu->menu_parent);
                            @endphp
                            @if($parent)
                                <div class="mt-1 flex items-center space-x-2">
                                    @if($parent->menu_icon)
                                        <i class="{{ $parent->menu_icon }} text-sm text-gray-600"></i>
                                    @endif
                                    <p class="text-sm text-gray-900 bg-gray-50 rounded-lg px-3 py-2">{{ $parent->menu_name }}</p>
                                </div>
                            @else
                                <p class="mt-1 text-sm text-red-500">Parent menu not found</p>
                            @endif
                        @else
                            <p class="mt-1 text-sm text-gray-500 italic">Root Menu (No Parent)</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Audit Information -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 border-b border-gray-200 pb-2 mb-4">Audit Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Created</label>
                        <div class="mt-1 space-y-1">
                            <p class="text-sm text-gray-900">
                                {{ $menu->created_at ? $menu->created_at->format('M d, Y H:i:s') : 'N/A' }}
                            </p>
                            @if($menu->creator)
                                <p class="text-sm text-gray-600">by {{ $menu->creator->name }}</p>
                            @endif
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Last Updated</label>
                        <div class="mt-1 space-y-1">
                            <p class="text-sm text-gray-900">
                                {{ $menu->updated_at ? $menu->updated_at->format('M d, Y H:i:s') : 'Never' }}
                            </p>
                            @if($menu->updater)
                                <p class="text-sm text-gray-600">by {{ $menu->updater->name }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sub Menus -->
            @php
                $subMenus = App\Models\Menu::where('menu_parent', $menu->menu_id)->orderBy('menu_urutan')->get();
            @endphp
            @if($subMenus->count() > 0)
                <div class="bg-blue-50 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 border-b border-blue-200 pb-2 mb-4">Sub Menus</h3>
                    <div class="space-y-2">
                        @foreach($subMenus as $subMenu)
                            <div class="flex items-center justify-between bg-white rounded-lg p-3 shadow-sm">
                                <div class="flex items-center space-x-3">
                                    @if($subMenu->menu_icon)
                                        <i class="{{ $subMenu->menu_icon }} text-blue-600"></i>
                                    @endif
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $subMenu->menu_name }}</p>
                                        <p class="text-xs text-gray-500">{{ $subMenu->menu_link ?: 'No link' }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="bg-gray-100 px-2 py-1 rounded text-xs font-medium">{{ $subMenu->menu_urutan }}</span>
                                    <a href="{{ route('menu-show', $subMenu->menu_id) }}" 
                                       class="text-blue-600 hover:text-blue-800 text-sm">View</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Action Buttons -->
            <div class="flex justify-center space-x-4 pt-6">
                <a href="{{ route('menu-index') }}"
                    class="bg-gray-200 text-gray-700 font-semibold py-3 px-6 rounded-xl hover:bg-gray-300 transition duration-200">
                    Back to List
                </a>
                <a href="{{ route('menu-edit', $menu->menu_id) }}"
                    class="bg-yellow-600 text-white font-semibold py-3 px-6 rounded-xl hover:bg-yellow-700 transition duration-200">
                    Edit Menu
                </a>
            </div>
        </div>
    </div>
@endsection
