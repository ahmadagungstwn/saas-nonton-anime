<x-app-layout>
    <main class="flex flex-col max-w-lg m-auto pb-20">
        <!-- HEADER IMAGE -->
        <div class="relative h-[400px] overflow-hidden mb-8">
            <div class="absolute inset-0 bg-linear-to-br from-orange-500 via-red-600 to-purple-700 z-0"></div>

            <div class="absolute inset-0 bg-linear-to-t from-[#141414] via-transparent to-transparent z-10">
                <img src="{{ asset('storage/' . $anime->thumbnail) }}" alt="" class="w-full h-full object-cover" />
            </div>

            <!-- BACK BUTTON -->
            <a href="{{ route('home') }}"
                class="absolute top-6 left-6 w-10 h-10 flex items-center justify-center cursor-pointer z-50">
                <img src="{{ asset('assets/icon/back.png') }}" alt="back" class="size-14" />
            </a>

            <!-- BOOKMARK -->
            <button class="absolute top-6 right-6 w-10 h-10 flex items-center justify-center z-50">
                <img src="{{ asset('assets/icon/bookmark.png') }}" alt="bookmark" class="size-7" />
            </button>

            <!-- PLAY BUTTON -->
            <div class="absolute inset-0 flex items-center justify-center z-30">
                <a href="{{ route('anime.play', [$anime->slug, $anime->episodes->first()->slug]) }}"
                    class="w-20 h-20 flex items-center justify-center hover:scale-110 transition transform">
                    <img src="{{ asset('assets/icon/play.png') }}" alt="play" class="size-16" />
                </a>
            </div>
        </div>


        <!-- CONTENT -->
        <div class="px-6 -mt-8 relative z-10 pt-6">
            <div class="mb-6">
                <h1 class="text-3xl font-medium mb-3">{{ $anime->title }}</h1>
                <div class="flex items-center space-x-4 text-gray-400 text-md mb-3">
                    <span>{{ $anime->release_year }},</span>
                    <span>⭐{{ $anime->rating }} Rating</span>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="text-gray-400">{{ $anime->tags->pluck('name')->join(', ') }}</span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center space-x-3 mb-6">
                <a href="{{ route('anime.play', [$anime->slug, $anime->episodes->first()->slug]) }}"
                    class="flex-1
                    bg-[#13E441] hover:bg-[#10b534] text-black py-4 rounded-xl font-bold text-lg transition shadow-lg
                    flex items-center justify-center space-x-2">
                    <img src="{{ asset('assets/icon/play-2.svg') }}" alt="" class="size-8" />
                    <span class="font-medium text-md">Play Now</span>
                </a>
                <button
                    class="w-14 h-14 bg-[#1E1E1E] hover:bg-[#252525] rounded-xl flex items-center justify-center transition">
                    <img src="{{ asset('assets/icon/plus.svg') }}" alt="plus" class="size-9" />
                </button>
                <button
                    class="w-14 h-14 bg-[#1E1E1E] hover:bg-[#252525] rounded-xl flex items-center justify-center transition">
                    <img src="{{ asset('assets/icon/share.svg') }}" alt="share" class="size-7" />
                </button>
            </div>

            <!-- Synopsis -->
            <div class="mb-6">
                <h2 class="text-xl font-medium mb-3">Synopsis</h2>
                <div class="text-gray-400 leading-relaxed">
                    {!! Str::limit($anime->description, 150, '...') !!}
                </div>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-3 gap-3 mb-6">
                <div class="bg-[#1E1E1E] p-4 rounded-xl text-center">
                    <p class="text-gray-400 text-md mb-1">Episodes</p>
                    <p class="text-xl font-medium text-[#13E441]">{{ $anime->episodes_count }}</p>
                </div>
                <div class="bg-[#1E1E1E] p-4 rounded-xl text-center">
                    <p class="text-gray-400 text-md mb-1">Views</p>
                    <p class="text-xl font-medium text-[#13E441]">24.6K</p>
                </div>
                <div class="bg-[#1E1E1E] p-4 rounded-xl text-center">
                    <p class="text-gray-400 text-md mb-1">Status</p>
                    <p class="text-xl font-medium text-[#13E441]">Ongoing</p>
                </div>
            </div>

            <!-- rekomendasi -->
            <section>
                <div class="flex justify-between items-center mb-3 mt-1">
                    <h2 class="text-white text-2xl">Rekomendasi</h2>
                    <button class="text-gray-600 text-2xl font-medium hover:underline">
                        Show all
                    </button>
                </div>

                <div class="space-y-3">
                    @foreach ($recommendations as $item)
                        <!-- Card Latest -->
                        <a href="{{ route('anime.show', $item->slug) }}" class="block">
                            <div class="flex items-center gap-3">
                                <div
                                    class="relative w-32 h-24 rounded-2xl overflow-hidden shrink-0 group cursor-pointer">
                                    <img src="{{ $item->thumbnail ? asset('storage/' . $item->thumbnail) : asset('assets/17.png') }}"
                                        alt="{{ $item->title }}" class="w-full h-full object-cover rounded-2xl" />
                                    <div
                                        class="absolute inset-0 bg-black/10 group-hover:bg-black/40 transition-all duration-300 flex items-center justify-center">
                                        <img src="{{ asset('assets/icon/play.png') }}"
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
                                    <img src="{{ asset('assets/icon/next.png') }}" alt="Next" class="size-10" />
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
        </div>
    </main>
</x-app-layout>
