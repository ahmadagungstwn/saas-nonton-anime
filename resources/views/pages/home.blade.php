<x-app-layout title="Home">
    <main class="flex flex-col overflow max-w-lg mx-auto px-4 pt-6 pb-16">
        <!-- Header -->
        <section class="flex justify-between items-center mb-6">
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 rounded-full border border-white/20 overflow-hidden">
                    @auth
                        <img src="{{ Auth::user()->profile_picture
                            ? asset('storage/' . Auth::user()->profile_picture)
                            : asset('assets/profile.png') }}"
                            class="w-full h-full object-cover" />
                    @else
                        <img src="{{ asset('assets/profile.png') }}" class="w-full h-full object-cover" />
                    @endauth
                </div>
                <div>
                    @auth
                        <p class="text-gray-400 text-xl">Hello,</p>
                        <p class="text-[#13E441] text-2xl font-bold leading-5">
                            {{ Auth::user()->name }}
                        </p>
                    @else
                        <p class="text-gray-400 text-xl">Hello,</p>
                        <p class="text-[#13E441] text-2xl font-bold leading-5">
                            Guest
                        </p>
                    @endauth
                </div>
            </div>

            <button class="relative w-7 h-7 rounded-2xl flex justify-center items-center mr-2">
                <img src="{{ asset('assets/icon/notification.png') }}" alt="" />

                <!-- Badge Notifikasi -->
                <span class="absolute -top-1 right-0 w-3 h-3 bg-green-400 rounded-full border border-black"></span>
            </button>
        </section>

        <!-- trending Section -->
        <section>
            <div class="flex justify-between items-center mb-2">
                <h2 class="text-white text-2xl">Trending</h2>
                <button class="text-gray-600 text-2xl font-medium hover:underline">
                    Show all
                </button>
            </div>

            <div class="flex space-x-4 overflow-x-auto scrollbar-hide mb-3">
                @foreach ($trending as $item)
                    <!-- Card Trending -->
                    <a href="{{ route('anime.show', $item->slug) }}" class="block">
                        <div class="relative w-34 h-32 rounded-2xl shrink-0 overflow-hidden group">
                            <img src="{{ $item->thumbnail ? asset('storage/' . $item->thumbnail) : asset('assets/17.png') }}"
                                alt="{{ $item->title }}"
                                class="w-full h-full object-cover rounded-2xl transition-transform duration-300 group-hover:scale-110" />
                            <div
                                class="absolute inset-0 bg-black/60 flex flex-col justify-center items-center text-white opacity-0 group-hover:opacity-100 transition-all duration-300">
                                <img src="assets/icon/play.png" alt="" class="size-10" />
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>

        <!-- Top Choice Section -->
        <section>
            <div class="flex justify-between items-center mb-2 mt-1">
                <h2 class="text-white text-2xl">Top Choice</h2>
                <button class="text-gray-600 text-2xl font-medium hover:underline">
                    Show all
                </button>
            </div>

            <div class="flex space-x-4 overflow-x-auto scrollbar-hide mb-3">
                @foreach ($topChoice as $item)
                    <!-- Card Top Choice -->
                    <a href="{{ route('anime.show', $item->slug) }}" class="block">
                        <div class="relative w-34 h-32 rounded-2xl shrink-0 overflow-hidden group">
                            <img src="{{ $item->thumbnail ? asset('storage/' . $item->thumbnail) : asset('assets/17.png') }}"
                                alt="{{ $item->title }}"
                                class="w-full h-full object-cover rounded-2xl transition-transform duration-300 group-hover:scale-110" />
                            <div
                                class="absolute inset-0 bg-black/60 flex flex-col justify-center items-center text-white opacity-0 group-hover:opacity-100 transition-all duration-300">
                                <img src="{{ asset('assets/icon/play.png') }}" alt="" class="size-10" />
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>

        <!-- Latest Section -->
        <section>
            <div class="flex justify-between items-center mb-3 mt-1">
                <h2 class="text-white text-2xl">Latest</h2>
                <button class="text-gray-600 text-2xl font-medium hover:underline">
                    Show all
                </button>
            </div>

            <div class="space-y-3">
                @foreach ($latest as $item)
                    <!-- Card Latest -->
                    <a href="{{ route('anime.show', $item->slug) }}" class="block">
                        <div class="flex items-center gap-3">
                            <div class="relative w-32 h-24 rounded-2xl overflow-hidden shrink-0 group cursor-pointer">
                                <img src="{{ $item->thumbnail ? asset('storage/' . $item->thumbnail) : asset('assets/17.png') }}"
                                    alt="{{ $item->title }}" class="w-full h-full object-cover rounded-2xl" />
                                <div
                                    class="absolute inset-0 bg-black/10 group-hover:bg-black/40 transition-all duration-300 flex items-center justify-center">
                                    <img src="assets/icon/play.png"
                                        class="size-12 opacity-0 group-hover:opacity-100 transform scale-75 transition-all duration-300" />
                                </div>
                            </div>

                            <div class="flex flex-col grow">
                                <p class="text-white text-2xl font-medium leading-tight">
                                    {{ $item->title }}
                                </p>
                                <p class="text-gray-400 text-sm mb-1">
                                    {{ $item->tags->pluck('name')->join(', ') }}
                                </p>
                                <p class="text-green-400 text-base mb-1">{{ $item->episodes_count }} Episodes</p>
                            </div>
                            <div class="ml-auto">
                                <img src="assets/icon/next.png" alt="Next" class="size-10" />
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    </main>

    <!-- navbar -->
    <x-navigation />
</x-app-layout>
