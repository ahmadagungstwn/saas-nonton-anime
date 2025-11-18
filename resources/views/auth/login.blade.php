<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Righteous&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('style')
</head>

<body class="bg-[#141414] text-white min-h-screen flex items-center justify-center">
    <main class="w-full max-w-lg px-6 py-8">
        <!-- Logo/Brand -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-medium mb-2">Welcome Back</h1>
            <p class="text-gray-400 text-md">Sign In to continue watching</p>
        </div>

        <!-- Register Form -->
        <form action="{{ route('login.store') }}" method="POST" class="space-y-2 mb-6">
            @csrf

            <!-- Email -->
            <div>
                <label class="block text-md font-medium mb-2 text-gray-300">Email Address</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <img src="{{ asset('assets/icon/email.svg') }}" alt="" class="size-6" />
                    </div>

                    <input type="email" name="email" placeholder="Enter your email" value="{{ old('email') }}"
                        class="w-full bg-[#1E1E1E] text-white placeholder-gray-500 rounded-xl 
                pl-12 pr-4 py-4 text-base focus:outline-none focus:ring-2 
                focus:ring-[#13E441] transition" />
                </div>
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label class="block text-md font-medium mb-2 text-gray-300">Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <img src="{{ asset('assets/icon/lock-2.svg') }}" alt="" class="size-6" />
                    </div>
                    <input type="password" name="password" placeholder="Enter your password"
                        class="w-full bg-[#1E1E1E] text-white placeholder-gray-500 rounded-xl pl-12 pr-12 py-4 text-base focus:ring-[#13E441] transition" />
                </div>
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <button
                class="w-full bg-green-600 hover:bg-[#10b534] text-white py-4 rounded-xl font-medium text-xl transition shadow-lg mt-2 cursor-pointer">
                Sign In
            </button>
        </form>

        <!-- Sign Up Link -->
        <div class="text-center">
            <p class="text-gray-400">
                Don't have an account?
                <a href="{{ route('register') }}" class="text-[#13E441] hover:text-[#10b534] font-bold transition ml-1">
                    Sign Up
                </a>
            </p>
        </div>

        <!-- Terms & Privacy -->
        <div class="text-center mt-8">
            <p class="text-xs text-gray-500">
                By continuing, you agree to our
                <button class="text-gray-400 hover:text-white transition underline">
                    Terms of Service
                </button>
                and
                <button class="text-gray-400 hover:text-white transition underline">
                    Privacy Policy
                </button>
            </p>
        </div>
    </main>
</body>

</html>
