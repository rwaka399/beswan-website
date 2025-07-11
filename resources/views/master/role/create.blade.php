@extends('master.layout')

@section('title', 'Create Role')

@section('content')
    <!-- Breadcrumb -->
    <div class="sticky top-12 inset-x-0 z-10 px-4 sm:px-6 lg:px-8">
        <div class="flex items-center py-3">
            <ol class="flex items-center whitespace-nowrap">
                <li class="flex items-center text-base text-gray-800">
                    <a href="{{ route('role-index') }}" class="hover:text-blue-600 font-medium">Roles</a>
                    <svg class="shrink-0 mx-3 overflow-visible size-3 text-gray-400" width="16" height="16"
                        viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5 1L10.6869 7.16086C10.8637 7.35239 10.8637 7.64761 10.6869 7.83914L5 14"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                    </svg>
                </li>
                <li class="text-base font-semibold text-gray-800 truncate" aria-current="page">
                    Create Role
                </li>
            </ol>
        </div>
    </div>

    <!-- Form Create Role -->
    <div class="max-w-6xl mx-auto mt-8 px-4 sm:px-6 lg:px-8">
        <div class="w-full bg-white rounded-3xl shadow-xl p-8 space-y-6">
            <!-- Header -->
            <div class="text-center">
                <svg class="mx-auto h-12 w-12 text-blue-600" xmlns="http://www.w3.org/2000/svg" width="24"
                    height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 2a5 5 0 0 0-5 5v3a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-8a2 2 0 0 0-2-2V7a5 5 0 0 0-5-5z" />
                    <path d="M9 15l2 2 4-4" />
                </svg>
                <h2 class="mt-3 text-2xl font-bold text-gray-800">Create Role & Permissions</h2>
                <p class="text-sm text-gray-500">Create a new role and configure menu permissions</p>
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
            <form action="{{ route('role-store') }}" method="POST" class="space-y-6" aria-label="Create role form">
                @csrf

                <!-- Basic Information -->
                <div class="bg-gray-50 rounded-2xl p-6 space-y-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Basic Information</h3>
                    
                    <!-- Role Name -->
                    <div>
                        <label for="role_name" class="block text-sm font-semibold text-gray-700">Role Name</label>
                        <input type="text" name="role_name" id="role_name" value="{{ old('role_name') }}"
                            class="mt-2 block w-full rounded-xl border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent py-2 px-3 text-gray-800"
                            placeholder="Enter role name" required>
                        @error('role_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Role Description -->
                    <div>
                        <label for="role_description" class="block text-sm font-semibold text-gray-700">Role Description</label>
                        <textarea name="role_description" id="role_description" rows="3"
                            class="mt-2 block w-full rounded-xl border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent py-2 px-3 text-gray-800 resize-none">{{ old('role_description') }}</textarea>
                        @error('role_description')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Menu & Permissions -->
                <div class="bg-gray-50 rounded-2xl p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Menu Access & Permissions</h3>
                    <p class="text-sm text-gray-600 mb-6">Select which menus this role can access and configure permissions for each menu.</p>
                    
                    <div class="space-y-4">
                        @foreach($menus as $menu)
                            <div class="border border-gray-200 rounded-xl p-4 bg-white">
                                <!-- Parent Menu -->
                                <div class="flex items-start space-x-3">
                                    <input type="checkbox" 
                                           name="menus[]" 
                                           value="{{ $menu->menu_id }}" 
                                           id="menu_{{ $menu->menu_id }}"
                                           class="mt-1 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded menu-checkbox"
                                           {{ in_array($menu->menu_id, old('menus', [])) ? 'checked' : '' }}>
                                    <div class="flex-1">
                                        <label for="menu_{{ $menu->menu_id }}" class="text-sm font-medium text-gray-900 cursor-pointer">
                                            {{ $menu->menu_name }}
                                        </label>
                                        <p class="text-xs text-gray-500">{{ $menu->menu_link ?: 'No route' }}</p>
                                        
                                        <!-- Permissions for Parent Menu -->
                                        <div class="mt-3 permission-section" style="display: {{ in_array($menu->menu_id, old('menus', [])) ? 'block' : 'none' }};">
                                            <p class="text-xs font-medium text-gray-700 mb-2">Permissions:</p>
                                            <div class="grid grid-cols-4 gap-2">
                                                @foreach($availablePermissions as $slug => $label)
                                                    <label class="inline-flex items-center">
                                                        <input type="checkbox" 
                                                               name="permissions[{{ $menu->menu_id }}][]" 
                                                               value="{{ $slug }}"
                                                               class="h-3 w-3 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                                               {{ in_array($slug, old("permissions.{$menu->menu_id}", [])) ? 'checked' : '' }}>
                                                        <span class="ml-1 text-xs text-gray-600">{{ $label }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Child Menus -->
                                @if($menu->children->count() > 0)
                                    <div class="mt-4 ml-6 space-y-3 border-l-2 border-gray-200 pl-4">
                                        @foreach($menu->children as $child)
                                            <div class="flex items-start space-x-3">
                                                <input type="checkbox" 
                                                       name="menus[]" 
                                                       value="{{ $child->menu_id }}" 
                                                       id="menu_{{ $child->menu_id }}"
                                                       class="mt-1 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded menu-checkbox"
                                                       {{ in_array($child->menu_id, old('menus', [])) ? 'checked' : '' }}>
                                                <div class="flex-1">
                                                    <label for="menu_{{ $child->menu_id }}" class="text-sm text-gray-800 cursor-pointer">
                                                        {{ $child->menu_name }}
                                                    </label>
                                                    <p class="text-xs text-gray-500">{{ $child->menu_link ?: 'No route' }}</p>
                                                    
                                                    <!-- Permissions for Child Menu -->
                                                    <div class="mt-2 permission-section" style="display: {{ in_array($child->menu_id, old('menus', [])) ? 'block' : 'none' }};">
                                                        <p class="text-xs font-medium text-gray-700 mb-2">Permissions:</p>
                                                        <div class="grid grid-cols-4 gap-2">
                                                            @foreach($availablePermissions as $slug => $label)
                                                                <label class="inline-flex items-center">
                                                                    <input type="checkbox" 
                                                                           name="permissions[{{ $child->menu_id }}][]" 
                                                                           value="{{ $slug }}"
                                                                           class="h-3 w-3 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                                                           {{ in_array($slug, old("permissions.{$child->menu_id}", [])) ? 'checked' : '' }}>
                                                                    <span class="ml-1 text-xs text-gray-600">{{ $label }}</span>
                                                                </label>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('role-index') }}"
                        class="px-6 py-3 bg-gray-200 text-gray-700 font-semibold rounded-xl hover:bg-gray-300 transition duration-200">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition duration-200">
                        Create Role
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Flash Message auto disappear
            const flashes = document.querySelectorAll('[role="alert"]');
            flashes.forEach(el => {
                setTimeout(() => {
                    el.classList.add('opacity-0');
                    setTimeout(() => el.remove(), 300);
                }, 3000);
            });

            // Menu checkbox toggle permissions
            const menuCheckboxes = document.querySelectorAll('.menu-checkbox');
            menuCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const permissionSection = this.closest('.border').querySelector('.permission-section');
                    if (permissionSection) {
                        permissionSection.style.display = this.checked ? 'block' : 'none';
                        if (!this.checked) {
                            // Uncheck all permissions for this menu
                            const permissionCheckboxes = permissionSection.querySelectorAll('input[type="checkbox"]');
                            permissionCheckboxes.forEach(cb => cb.checked = false);
                        }
                    }
                });
            });
        });
    </script>
@endsection