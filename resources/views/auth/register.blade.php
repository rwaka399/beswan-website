<!doctype html>
<html>
<head>
    <title>Register</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
</head>
<body class="bg-gradient-to-br from-blue-50 to-blue-100 flex items-center justify-center min-h-screen">
    <div class="w-full max-w-3xl bg-white rounded-3xl shadow-xl p-8 space-y-6">
        <div class="text-center">
            <svg class="mx-auto h-12 w-12 text-blue-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM4 21a8 8 0 0116 0H4z" />
            </svg>
            <h2 class="mt-3 text-2xl font-bold text-gray-800">Create Account</h2>
            <p class="text-sm text-gray-500">Sign up to get started</p>
        </div>

        <!-- Notifikasi Success -->
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative transition-opacity duration-300" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <!-- Notifikasi Error -->
        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative transition-opacity duration-300" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <!-- Error Validasi -->
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative transition-opacity duration-300" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register.post') }}" class="space-y-4" aria-label="Register form">
            @csrf
            <div>
                <label for="name" class="block text-sm font-semibold text-gray-700">Full Name</label>
                <input id="name" name="name" type="text" required autofocus
                       class="mt-2 block w-full rounded-xl border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent py-2 px-3 text-gray-800"
                       value="{{ old('name') }}" placeholder="Enter your full name">
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700">Email address</label>
                <input id="email" name="email" type="email" required
                       class="mt-2 block w-full rounded-xl border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent py-2 px-3 text-gray-800"
                       value="{{ old('email') }}" placeholder="Enter your email">
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="relative">
                <label for="password" class="block text-sm font-semibold text-gray-700">Password</label>
                <input id="password" name="password" type="password" required
                       class="mt-2 block w-full rounded-xl border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent py-2 px-3 pr-10 text-gray-800"
                       placeholder="Enter your password" aria-describedby="password-error">
                <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 flex items-center pr-3 mt-6 text-gray-500 hover:text-gray-700" aria-label="Toggle password visibility">
                    <svg id="eyeIconPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </button>
                @error('password')
                    <p id="password-error" class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="relative">
                <label for="password_confirmation" class="block text-sm font-semibold text-gray-700">Confirm Password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required
                       class="mt-2 block w-full rounded-xl border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent py-2 px-3 pr-10 text-gray-800"
                       placeholder="Confirm your password" aria-describedby="password-confirmation-error">
                <button type="button" id="togglePasswordConfirmation" class="absolute inset-y-0 right-0 flex items-center pr-3 mt-6 text-gray-500 hover:text-gray-700" aria-label="Toggle confirm password visibility">
                    <svg id="eyeIconPasswordConfirmation" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </button>
                @error('password_confirmation')
                    <p id="password-confirmation-error" class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="phone_number" class="block text-sm font-semibold text-gray-700">Phone Number</label>
                <input id="phone_number" name="phone_number" type="text" required
                       class="mt-2 block w-full rounded-xl border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent py-2 px-3 text-gray-800"
                       placeholder="+628123456789" value="{{ old('phone_number') }}">
                @error('phone_number')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="province" class="block text-sm font-semibold text-gray-700">Provinsi</label>
                    <select id="province" name="province" required
                            class="mt-2 block w-full rounded-xl border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent py-2 px-3 text-gray-800">
                        <option value="" disabled selected>Pilih Provinsi</option>
                        @foreach ($provinces as $province)
                            <option value="{{ $province['nama'] }}" data-id="{{ $province['id'] }}"
                                    {{ old('province') == $province['nama'] ? 'selected' : '' }}>
                                {{ $province['nama'] }}
                            </option>
                        @endforeach
                    </select>
                    @error('province')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="city" class="block text-sm font-semibold text-gray-700">Kota / Kabupaten</label>
                    <select id="city" name="city" required
                            class="mt-2 block w-full rounded-xl border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent py-2 px-3 text-gray-800">
                        <option value="" disabled selected>Pilih Kota</option>
                    </select>
                    @error('city')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="kecamatan" class="block text-sm font-semibold text-gray-700">Kecamatan</label>
                    <select id="kecamatan" name="kecamatan" required
                            class="mt-2 block w-full rounded-xl border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent py-2 px-3 text-gray-800">
                        <option value="" disabled selected>Pilih Kecamatan</option>
                    </select>
                    @error('kecamatan')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div>
                <label for="address" class="block text-sm font-semibold text-gray-700">Full Address</label>
                <textarea id="address" name="address" rows="3" required
                          class="mt-2 block w-full rounded-xl border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent py-2 px-3 text-gray-800 resize-none">{{ old('address') }}</textarea>
                @error('address')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <button type="submit"
                        class="w-full bg-blue-600 text-white font-semibold py-3 px-4 rounded-xl hover:bg-blue-700 transition duration-200">
                    Register
                </button>
            </div>
        </form>

        <!-- SSO Section -->
        <div class="mt-6">
            <div class="relative">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-white text-gray-500">Or sign up with</span>
                </div>
            </div>

            <div class="mt-6">
                <a href="{{ route('auth.provider', 'google') }}" 
                   class="w-full flex justify-center items-center py-3 px-4 border border-gray-300 rounded-xl shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 transition duration-200">
                    <svg class="w-5 h-5 mr-3" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    Continue with Google
                </a>
            </div>
        </div>

        <p class="text-center text-sm text-gray-500">
            Already have an account?
            <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Login here</a>
        </p>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Logika untuk dropdown provinsi, kota, dan kecamatan
            const provinceSelect = document.getElementById('province');
            const citySelect = document.getElementById('city');
            const districtSelect = document.getElementById('kecamatan');

            provinceSelect.addEventListener('change', function() {
                const provinceId = this.options[this.selectedIndex].dataset.id;
                if (!provinceId) return;

                fetch(`https://ibnux.github.io/data-indonesia/kota/${provinceId}.json`)
                    .then(response => {
                        if (!response.ok) throw new Error('Gagal memuat data kota');
                        return response.json();
                    })
                    .then(data => {
                        citySelect.innerHTML = '<option value="" disabled selected>Pilih Kota</option>';
                        districtSelect.innerHTML = '<option value="" disabled selected>Pilih Kecamatan</option>';
                        data.forEach(city => {
                            const option = new Option(city.nama, city.nama);
                            option.dataset.id = city.id;
                            citySelect.add(option);
                        });
                        if ("{{ old('city') }}") {
                            citySelect.value = "{{ old('city') }}";
                            citySelect.dispatchEvent(new Event('change'));
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Gagal memuat data kota. Silakan coba lagi.');
                        citySelect.innerHTML = '<option value="" disabled selected>Error: Tidak dapat memuat kota</option>';
                    });
            });

            citySelect.addEventListener('change', function() {
                const cityId = this.options[this.selectedIndex].dataset.id;
                if (!cityId) return;

                fetch(`https://ibnux.github.io/data-indonesia/kecamatan/${cityId}.json`)
                    .then(response => {
                        if (!response.ok) throw new Error('Gagal memuat data kecamatan');
                        return response.json();
                    })
                    .then(data => {
                        districtSelect.innerHTML = '<option value="" disabled selected>Pilih Kecamatan</option>';
                        data.forEach(district => {
                            const option = new Option(district.nama, district.nama);
                            districtSelect.add(option);
                        });
                        if ("{{ old('kecamatan') }}") {
                            districtSelect.value = "{{ old('kecamatan') }}";
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Gagal memuat data kecamatan. Silakan coba lagi.');
                        districtSelect.innerHTML = '<option value="" disabled selected>Error: Tidak dapat memuat kecamatan</option>';
                    });
            });

            if ("{{ old('province') }}") {
                provinceSelect.value = "{{ old('province') }}";
                provinceSelect.dispatchEvent(new Event('change'));
            }

            // Logika untuk toggle visibility password dan confirm password
            const passwordInput = document.getElementById('password');
            const togglePassword = document.getElementById('togglePassword');
            const eyeIconPassword = document.getElementById('eyeIconPassword');

            const passwordConfirmationInput = document.getElementById('password_confirmation');
            const togglePasswordConfirmation = document.getElementById('togglePasswordConfirmation');
            const eyeIconPasswordConfirmation = document.getElementById('eyeIconPasswordConfirmation');

            togglePassword.addEventListener('click', function () {
                const isPasswordVisible = passwordInput.type === 'text';
                passwordInput.type = isPasswordVisible ? 'password' : 'text';
                eyeIconPassword.innerHTML = isPasswordVisible
                    ? `<path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />`
                    : `<path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />`;
            });

            togglePasswordConfirmation.addEventListener('click', function () {
                const isPasswordVisible = passwordConfirmationInput.type === 'text';
                passwordConfirmationInput.type = isPasswordVisible ? 'password' : 'text';
                eyeIconPasswordConfirmation.innerHTML = isPasswordVisible
                    ? `<path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />`
                    : `<path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />`;
            });
        });
    </script>
</body>
</html>