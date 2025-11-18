<x-app-layout title="Profile">
    <main class="flex flex-col gap-4 grow max-w-lg m-auto">
        <!-- HEADER -->
        <div class="relative h-42 overflow-hidden">
            <img src="{{ asset('assets/header.png') }}" alt="Header Background" class="w-full h-full object-cover" />

            <a href="{{ route('home') }}"
                class="absolute top-4 left-4 w-9 h-9 flex items-center justify-center cursor-pointer">
                <img src="{{ asset('assets/icon/back-2.png') }}" alt="Back" class="w-full h-full" />
            </a>

            <button onclick="openModal()"
                class="absolute top-4 right-4 transition px-4 py-2 rounded-full flex items-center space-x-2 cursor-pointer">
                <img src="{{ asset('assets/icon/coin.png') }}" alt="" class="size-7" />
                <span
                    class="text-white font-medium text-2xl">{{ number_format(auth()->user()->wallet->coin_balance, 0, ',', '.') }}
                </span>
            </button>
        </div>

        <!-- PROFILE AVATAR -->
        <form action="{{ route('profile.updatePicture') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="relative flex flex-col items-center -mt-20 z-10">
                <label
                    class="group relative w-[140px] h-[142px] rounded-full border-[6px] border-[#13E441] overflow-hidden bg-black cursor-pointer">
                    <img id="profileImage"
                        src="{{ auth()->user()->profile_picture
                            ? asset('storage/' . auth()->user()->profile_picture)
                            : asset('assets/profile.png') }}"
                        alt="Profile" class="object-cover w-full h-full transition-all duration-300" />
                    <div
                        class="absolute inset-0 bg-black/0 group-hover:bg-black/50 flex items-center justify-center transition-all">
                        <img src="{{ asset('assets/icon/camera.svg') }}"
                            class="size-12 opacity-0 group-hover:opacity-100 transition-all" />
                    </div>
                    <input type="file" id="profileInput" name="profile_picture" class="hidden" accept="image/*"
                        onchange="this.form.submit()">
                </label>
                <h2 class="text-white text-2xl font-medium mt-2">{{ auth()->user()->name }}</h2>
                @error('profile_picture')
                    <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>
        </form>

        <!-- MAIN CONTENT -->
        <div class="flex-1 px-8 mt-8 space-y-6 overflow-y-auto">
            <button class="flex items-center justify-between w-full text-white text-xl font-medium cursor-pointer">
                <div class="flex items-center space-x-4">
                    <img src="{{ asset('assets/icon/user-2.png') }}" alt="" />
                    <span>Profile Settings</span>
                </div>
                <img src="{{ asset('assets/icon/next.png') }}" alt="" />
            </button>

            <div class="flex items-start space-x-4 text-white">
                <img src="{{ asset('assets/icon/video.png') }}" alt="" />
                <div class="flex-1">
                    <p class="text-xl font-medium">Total Anime Streamed</p>
                    <div class="w-full bg-gray-700 rounded-full h-2 mt-2">
                        <div class="bg-[#13E441] h-2 rounded-full" style="width: 70%"></div>
                    </div>
                    <p class="text-gray-400 text-base text-end mt-1">2540 streamed</p>
                </div>
            </div>

            <div class="flex items-start space-x-4 text-white">
                <img src="{{ asset('assets/icon/storage.png') }}" alt="" />
                <div class="flex-1">
                    <p class="text-xl font-medium">Storage</p>
                    <div class="w-full bg-gray-700 rounded-full h-2 mt-2">
                        <div class="bg-[#13E441] h-2 rounded-full" style="width: 40%"></div>
                    </div>
                    <p class="text-gray-400 text-base text-end mt-1">
                        670 mb remaining
                    </p>
                </div>
            </div>
        </div>

        <div class="px-8 mt-6 mb-6">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="w-full bg-[#F63A3A] text-white py-6 text-2xl font-medium rounded-xl shadow-[0_4px_15px_rgba(255,61,61,0.4)] hover:bg-[#ff5050] transition cursor-pointer">
                    Exit
                </button>
            </form>
        </div>

    </main>

    <!-- Navbar -->
    <x-navigation />

    <!-- Top Up Modal -->
    <div id="coinModal" class="fixed inset-0 flex items-center justify-center backdrop-blur-sm z-50 hidden">
        <div
            class="bg-black rounded-2xl p-5 w-[85%] max-w-xs mx-auto text-white shadow-xl border border-white/10 animate-scale">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-medium">Top Up Coins</h2>
                <button onclick="closeModal()"
                    class="w-8 h-8 rounded-lg hover:bg-white/10 flex items-center justify-center transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="space-y-3 mb-4">
                <!-- Option 1 -->
                <button
                    class="w-full rounded-xl p-3 flex items-center justify-between bg-white/5 hover:bg-white/10 transition-all">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 rounded-lg bg-linear-to-br from-yellow-400 to-orange-500 flex items-center justify-center">
                            <span class="text-xl">💰</span>
                        </div>
                        <div class="text-left">
                            <p class="font-medium text-md">500 Coins</p>
                            <p class="text-base text-gray-400">$4.99</p>
                        </div>
                    </div>
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path d="M9 5l7 7-7 7" />
                    </svg>
                </button>

                <button
                    class="w-full rounded-xl p-3 flex items-center justify-between bg-white/5 hover:bg-white/10 transition-all">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 rounded-lg bg-linear-to-br from-yellow-400 to-orange-500 flex items-center justify-center relative">
                            <span class="text-xl">💰</span>
                            <span
                                class="absolute -top-1 -right-1 px-1.5 py-0.5 bg-red-500 text-white text-[10px] font-bold rounded-full">+20%</span>
                        </div>
                        <div class="text-left">
                            <p class="font-bold text-md">1,200 Coins</p>
                            <p class="text-base text-gray-400">$9.99</p>
                        </div>
                    </div>
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>

            <button
                class="w-full py-4 bg-[#13E441] rounded-lg font-medium text-lg text-white shadow-lg hover:shadow-xl hover:scale-105 transition-all">
                Continue to Payment
            </button>
        </div>
    </div>

    @push('script')
        <script>
            document.getElementById("profileInput").addEventListener("change", function(event) {
                const file = event.target.files[0];
                if (file) {
                    document.getElementById("profileImage").src = URL.createObjectURL(file);
                }
            });

            function openModal() {
                document.getElementById("coinModal").classList.remove("hidden");
                document.body.style.overflow = "hidden";
            }

            function closeModal() {
                document.getElementById("coinModal").classList.add("hidden");
                document.body.style.overflow = "auto";
            }

            document.getElementById("coinModal")
                .addEventListener("click", function(e) {
                    if (e.target === this) {
                        closeModal();
                    }
                });
        </script>
    @endpush
</x-app-layout>
