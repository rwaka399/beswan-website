@extends('master.layout')

@section('title', 'Create Menu')

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
                    Create Menu
                </li>
            </ol>
        </div>
    </div>

    <!-- Form Create Menu -->
    <div class="max-w-4xl mx-auto mt-8 px-4 sm:px-6 lg:px-8">
        <div class="w-full bg-white rounded-3xl shadow-xl p-8 space-y-6">
            <!-- Header -->
            <div class="text-center">
                <svg class="mx-auto h-12 w-12 text-blue-600" xmlns="http://www.w3.org/2000/svg" width="24"
                    height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <rect width="3" height="3" x="9" y="9" rx="0.5"/>
                    <rect width="3" height="3" x="9" y="15" rx="0.5"/>
                    <rect width="3" height="3" x="15" y="9" rx="0.5"/>
                    <rect width="3" height="3" x="15" y="15" rx="0.5"/>
                    <rect width="3" height="3" x="3" y="9" rx="0.5"/>
                    <rect width="3" height="3" x="3" y="15" rx="0.5"/>
                </svg>
                <h2 class="mt-3 text-2xl font-bold text-gray-800">Create Menu</h2>
                <p class="text-sm text-gray-500">Fill in the details to create a new menu</p>
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
            <form action="{{ route('menu-store') }}" method="POST" class="space-y-4" aria-label="Create menu form">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Menu Name -->
                    <div>
                        <label for="menu_name" class="block text-sm font-semibold text-gray-700">Menu Name *</label>
                        <input type="text" name="menu_name" id="menu_name" value="{{ old('menu_name') }}"
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
                            <option value="main" {{ old('menu_type') === 'main' ? 'selected' : '' }}>Main</option>
                            <option value="parent" {{ old('menu_type') === 'parent' ? 'selected' : '' }}>Parent</option>
                            <option value="child" {{ old('menu_type') === 'child' ? 'selected' : '' }}>Child</option>
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
                        <input type="text" name="menu_icon" id="menu_icon" value="{{ old('menu_icon') }}"
                            class="mt-2 block w-full rounded-xl border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent py-2 px-3 text-gray-800"
                            placeholder="e.g., fas fa-home, fas fa-users, fas fa-cog">
                        <div class="mt-2">
                            <p class="text-xs text-gray-500">Use FontAwesome classes. Available icons:</p>
                            <div class="mt-1 text-xs text-gray-400 space-x-2">
                                <span class="inline-block">fas fa-home</span>
                                <span class="inline-block">fas fa-users</span>
                                <span class="inline-block">fas fa-bars</span>
                                <span class="inline-block">fas fa-cog</span>
                                <span class="inline-block">fas fa-list</span>
                                <span class="inline-block">fas fa-user</span>
                                <span class="inline-block">fas fa-graduation-cap</span>
                                <span class="inline-block">fas fa-money-bill-wave</span>
                            </div>
                        </div>
                        @error('menu_icon')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Menu Order -->
                    <div>
                        <label for="menu_urutan" class="block text-sm font-semibold text-gray-700">Menu Order *</label>
                        <input type="number" name="menu_urutan" id="menu_urutan" value="{{ old('menu_urutan', 1) }}"
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
                    <input type="text" name="menu_link" id="menu_link" value="{{ old('menu_link') }}"
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
                                <option value="{{ $parent->menu_id }}" {{ old('menu_parent') == $parent->menu_id ? 'selected' : '' }}>
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
                        <input type="text" name="menu_slug" id="menu_slug" value="{{ old('menu_slug') }}"
                            class="mt-2 block w-full rounded-xl border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent py-2 px-3 text-gray-800"
                            placeholder="menu-slug" required>
                        <p class="text-xs text-gray-500 mt-1">Unique identifier for the menu (lowercase, hyphen-separated)</p>
                        @error('menu_slug')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end space-x-4 pt-4">
                    <a href="{{ route('menu-index') }}"
                        class="w-1/2 bg-gray-200 text-gray-700 font-semibold py-3 px-4 rounded-xl hover:bg-gray-300 transition duration-200 text-center">
                        Cancel
                    </a>
                    <button type="submit"
                        class="w-1/2 bg-blue-600 text-white font-semibold py-3 px-4 rounded-xl hover:bg-blue-700 transition duration-200">
                        Create Menu
                    </button>
                </div>
            </form>
        </div>
    </div>    <script>
        // Auto generate slug from menu name
        document.getElementById('menu_name').addEventListener('input', function() {
            const name = this.value;
            const slug = name.toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '') // Remove special characters
                .replace(/\s+/g, '-') // Replace spaces with hyphens
                .replace(/-+/g, '-') // Replace multiple hyphens with single
                .trim('-'); // Remove leading/trailing hyphens
            
            document.getElementById('menu_slug').value = slug;
        });

        // Handle menu type change to enable/disable parent menu field
        document.getElementById('menu_type').addEventListener('change', function() {
            const menuType = this.value;
            const parentMenuField = document.getElementById('menu_parent');
            const parentMenuLabel = document.querySelector('label[for="menu_parent"]');
            
            if (menuType === 'main' || menuType === 'parent') {
                // Disable parent menu field for main and parent types
                parentMenuField.disabled = true;
                parentMenuField.value = '';
                parentMenuField.classList.add('bg-gray-100', 'cursor-not-allowed');
                parentMenuLabel.classList.add('text-gray-400');
                
                // Add visual indication
                parentMenuField.setAttribute('title', 'Parent menu is not applicable for ' + menuType + ' menu type');
            } else if (menuType === 'child') {
                // Enable parent menu field for child type
                parentMenuField.disabled = false;
                parentMenuField.classList.remove('bg-gray-100', 'cursor-not-allowed');
                parentMenuLabel.classList.remove('text-gray-400');
                parentMenuField.removeAttribute('title');
            } else {
                // Reset to default state when no type is selected
                parentMenuField.disabled = false;
                parentMenuField.classList.remove('bg-gray-100', 'cursor-not-allowed');
                parentMenuLabel.classList.remove('text-gray-400');
                parentMenuField.removeAttribute('title');
            }
        });

        // Initialize the parent menu field state on page load
        document.addEventListener('DOMContentLoaded', function() {
            const menuType = document.getElementById('menu_type').value;
            if (menuType) {
                // Trigger the change event to set initial state
                document.getElementById('menu_type').dispatchEvent(new Event('change'));
            }
        });
    </script>
@endsection
