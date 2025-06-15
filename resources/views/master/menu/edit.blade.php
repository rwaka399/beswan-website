@extends('master.layout')

@section('title', 'Edit Menu')

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
                    Edit Menu
                </li>
            </ol>
        </div>
    </div>

    <!-- Form Edit Menu -->
    <div class="max-w-4xl mx-auto mt-8 px-4 sm:px-6 lg:px-8">
        <div class="w-full bg-white rounded-3xl shadow-xl p-8 space-y-6">
            <!-- Header -->
            <div class="text-center">
                <svg class="mx-auto h-12 w-12 text-yellow-600" xmlns="http://www.w3.org/2000/svg" width="24"
                    height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 20h9"/>
                    <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/>
                </svg>
                <h2 class="mt-3 text-2xl font-bold text-gray-800">Edit Menu</h2>
                <p class="text-sm text-gray-500">Update the menu details</p>
            </div>

            <!-- Flash Message -->
            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative transition-opacity duration-300"
                    role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Error Validasi -->
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative transition-opacity duration-300"
                    role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form -->
            <form action="{{ route('menu-update', $menu->menu_id) }}" method="POST" class="space-y-4" aria-label="Edit menu form">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Menu Name -->
                    <div>
                        <label for="menu_name" class="block text-sm font-semibold text-gray-700">Menu Name *</label>
                        <input type="text" name="menu_name" id="menu_name" value="{{ old('menu_name', $menu->menu_name) }}"
                            class="mt-2 block w-full rounded-xl border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent py-2 px-3 text-gray-800"
                            placeholder="Enter menu name" required>
                        @error('menu_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Menu Type -->
                    <div>
                        <label for="menu_type" class="block text-sm font-semibold text-gray-700">Menu Type *</label>
                        <select name="menu_type" id="menu_type"
                            class="mt-2 block w-full rounded-xl border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent py-2 px-3 text-gray-800" required>
                            <option value="">Select menu type</option>
                            <option value="header" {{ old('menu_type', $menu->menu_type) === 'header' ? 'selected' : '' }}>Header</option>
                            <option value="menu" {{ old('menu_type', $menu->menu_type) === 'menu' ? 'selected' : '' }}>Menu</option>
                            <option value="submenu" {{ old('menu_type', $menu->menu_type) === 'submenu' ? 'selected' : '' }}>Submenu</option>
                        </select>
                        @error('menu_type')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Menu Icon -->
                    <div>
                        <label for="menu_icon" class="block text-sm font-semibold text-gray-700">Menu Icon</label>
                        <input type="text" name="menu_icon" id="menu_icon" value="{{ old('menu_icon', $menu->menu_icon) }}"
                            class="mt-2 block w-full rounded-xl border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent py-2 px-3 text-gray-800"
                            placeholder="e.g., fas fa-home, bi bi-house">
                        <p class="text-xs text-gray-500 mt-1">Use Font Awesome or Bootstrap Icons classes</p>
                        @if($menu->menu_icon)
                            <div class="mt-2 flex items-center">
                                <span class="text-sm text-gray-600">Preview: </span>
                                <i class="{{ $menu->menu_icon }} ml-2 text-blue-600"></i>
                            </div>
                        @endif
                        @error('menu_icon')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Menu Order -->
                    <div>
                        <label for="menu_urutan" class="block text-sm font-semibold text-gray-700">Menu Order *</label>
                        <input type="number" name="menu_urutan" id="menu_urutan" value="{{ old('menu_urutan', $menu->menu_urutan) }}"
                            class="mt-2 block w-full rounded-xl border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent py-2 px-3 text-gray-800"
                            placeholder="1" min="1" required>
                        @error('menu_urutan')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Menu Link -->
                <div>
                    <label for="menu_link" class="block text-sm font-semibold text-gray-700">Menu Link</label>
                    <input type="text" name="menu_link" id="menu_link" value="{{ old('menu_link', $menu->menu_link) }}"
                        class="mt-2 block w-full rounded-xl border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent py-2 px-3 text-gray-800"
                        placeholder="e.g., /dashboard, #, /users">
                    <p class="text-xs text-gray-500 mt-1">Leave empty for header-type menus or use # for placeholder links</p>
                    @error('menu_link')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Parent Menu -->
                    <div>
                        <label for="menu_parent" class="block text-sm font-semibold text-gray-700">Parent Menu</label>
                        <select name="menu_parent" id="menu_parent"
                            class="mt-2 block w-full rounded-xl border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent py-2 px-3 text-gray-800">
                            <option value="">Root Menu (No Parent)</option>
                            @foreach($parentMenus as $parent)
                                <option value="{{ $parent->menu_id }}" {{ old('menu_parent', $menu->menu_parent) == $parent->menu_id ? 'selected' : '' }}>
                                    {{ $parent->menu_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('menu_parent')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Menu Slug -->
                    <div>
                        <label for="menu_slug" class="block text-sm font-semibold text-gray-700">Menu Slug *</label>
                        <input type="text" name="menu_slug" id="menu_slug" value="{{ old('menu_slug', $menu->menu_slug) }}"
                            class="mt-2 block w-full rounded-xl border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent py-2 px-3 text-gray-800"
                            placeholder="menu-slug" required>
                        <p class="text-xs text-gray-500 mt-1">Unique identifier for the menu (lowercase, hyphen-separated)</p>
                        @error('menu_slug')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Meta Information -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-sm font-semibold text-gray-700 mb-2">Menu Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                        <div>
                            <span class="font-medium">Created:</span> 
                            {{ $menu->created_at ? $menu->created_at->format('M d, Y H:i') : 'N/A' }}
                            @if($menu->creator)
                                by {{ $menu->creator->name }}
                            @endif
                        </div>
                        <div>
                            <span class="font-medium">Last Updated:</span> 
                            {{ $menu->updated_at ? $menu->updated_at->format('M d, Y H:i') : 'Never' }}
                            @if($menu->updater)
                                by {{ $menu->updater->name }}
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end space-x-4 pt-4">
                    <a href="{{ route('menu-index') }}"
                        class="w-1/2 bg-gray-200 text-gray-700 font-semibold py-3 px-4 rounded-xl hover:bg-gray-300 transition duration-200 text-center">
                        Cancel
                    </a>
                    <button type="submit"
                        class="w-1/2 bg-yellow-600 text-white font-semibold py-3 px-4 rounded-xl hover:bg-yellow-700 transition duration-200">
                        Update Menu
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Auto generate slug from menu name (only if editing and slug field is empty)
        document.getElementById('menu_name').addEventListener('input', function() {
            const slugField = document.getElementById('menu_slug');
            const name = this.value;
            
            // Only auto-generate if slug field is empty or matches the pattern of an auto-generated slug
            if (!slugField.value || slugField.value.includes('-')) {
                const slug = name.toLowerCase()
                    .replace(/[^a-z0-9\s-]/g, '') // Remove special characters
                    .replace(/\s+/g, '-') // Replace spaces with hyphens
                    .replace(/-+/g, '-') // Replace multiple hyphens with single
                    .trim('-'); // Remove leading/trailing hyphens
                
                slugField.value = slug;
            }
        });
    </script>
@endsection
