<x-app-layout>
    <main class="flex flex-col max-w-lg m-auto">

        <!-- VIDEO PLAYER -->
        <div class="relative bg-black aspect-video">
            <a href="{{ route('home') }}" class="absolute top-2 left-1 z-20 transition">
                <img src="{{ asset('assets/icon/back.png') }}" class="size-14" />
            </a>
            <video src="{{ asset('storage/' . $episode->video) }}" class="w-full h-full object-cover" controls></video>
        </div>

        <div class="px-6 py-4 pb-20">

            <!-- Episode Info -->
            <div class="mb-6">
                <h1 class="text-2xl font-medium mb-2">
                    Episode {{ $episode->episode_number }}: {{ $episode->title }}
                </h1>
                <div class="flex items-center space-x-3 text-gray-400 text-lg mb-4">
                    <span class="text-green-500">{{ $anime->title }},</span>
                    <span>{{ $episode->duration ?? '24:00' }},</span>
                    <span>{{ $anime->episodes_count }} Episodes</span>
                </div>
                <p class="text-gray-400 leading-relaxed">
                    {!! Str::limit($episode->synopsis, 150, '...') !!}
                </p>
            </div>

            <!-- Quick Actions -->
            <div class="overflow-x-auto scrollbar-hide mb-6">
                <div class="flex items-center justify-start gap-6 w-max mx-auto px-2">
                    <button class="flex flex-col items-center text-center hover:scale-105 transition">
                        <img src="{{ asset('assets/icon/like.svg') }}" class="w-8 h-8 mb-1" />
                        <span class="text-sm text-white/80">28,3K</span>
                    </button>

                    <button class="flex flex-col items-center text-center hover:scale-105 transition">
                        <img src="{{ asset('assets/icon/bookmark-2.svg') }}" class="w-8 h-8 mb-1" />
                        <span class="text-sm text-white/80">Favorite</span>
                    </button>

                    <button class="flex flex-col items-center text-center hover:scale-105 transition">
                        <img src="{{ asset('assets/icon/download.svg') }}" class="w-8 h-8 mb-1" />
                        <span class="text-sm text-white/80">Download</span>
                    </button>

                    <button class="flex flex-col items-center text-center hover:scale-105 transition">
                        <img src="{{ asset('assets/icon/share.svg') }}" class="w-8 h-8 mb-1" />
                        <span class="text-sm text-white/80">Share</span>
                    </button>
                </div>
            </div>

            <!-- All Episodes -->
            <div class="mb-6 text-white">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-medium">All Episodes</h2>
                </div>

                <div class="flex space-x-3 overflow-x-auto pb-3 scrollbar-hide">
                    @foreach ($episodes as $ep)
                        @if ($ep->is_unlocked_for_user)
                            <!-- Episode Terbuka -->
                            <a href="{{ route('anime.play', [$anime->slug, $ep->slug]) }}" class="shrink-0 w-16">
                                <div
                                    class="relative h-16 rounded-xl overflow-hidden bg-linear-to-br from-orange-500 to-yellow-600 flex items-center justify-center text-2xl font-bold">
                                    <span>{{ $ep->episode_number }}</span>
                                </div>
                            </a>
                        @else
                            <!-- Episode Terkunci -->
                            <button onclick="openLockedModal({{ $ep->id }})" class="shrink-0 w-16">
                                <div
                                    class="relative h-16 rounded-xl overflow-hidden bg-gray-800 flex items-center justify-center text-2xl font-bold text-gray-400">
                                    <span>{{ $ep->episode_number }}</span>
                                    <img src="{{ asset('assets/icon/lock.svg') }}"
                                        class="absolute top-2 right-2 w-3 h-3 opacity-80" />
                                </div>
                            </button>
                        @endif
                    @endforeach
                </div>
            </div>

            <!-- Comments Section -->
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-medium">Comments</h2> <span class="text-gray-400 text-md">1.2K</span>
                </div> <!-- Comment Input -->
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-10 h-10 rounded-full bg-linear-to-br from-[#13E441] to-green-600 shrink-0"> </div>
                    <input type="text" placeholder="Add a comment..."
                        class="flex-1 bg-[#1E1E1E] text-white placeholder-gray-500 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#13E441]" />
                </div> <!-- Comment List -->
                <div class="space-y-4"> <!-- Comment 1 -->
                    <div class="flex items-start space-x-3">
                        <div class="w-10 h-10 rounded-full bg-linear-to-br from-orange-500 to-red-600 shrink-0"> </div>
                        <div class="flex-1">
                            <div class="flex items-center space-x-2 mb-1"> <span
                                    class="font-medium text-sm">Luffy_Fan2024</span> <span
                                    class="text-gray-400 text-xs">2 hours ago</span> </div>
                            <p class="text-gray-300 text-sm mb-2"> This episode is legendary! Can't wait to see more of
                                Luffy's journey! 🔥 </p>
                            <div class="flex items-center space-x-4 text-gray-400 text-xs"> <button
                                    class="hover:text-[#13E441] transition"> Like · 156 </button> <button
                                    class="hover:text-[#13E441] transition">Reply</button> </div>
                        </div>
                    </div> <!-- Comment 2 -->
                    <div class="flex items-start space-x-3">
                        <div class="w-10 h-10 rounded-full bg-linear-to-br from-blue-500 to-purple-600 shrink-0"> </div>
                        <div class="flex-1">
                            <div class="flex items-center space-x-2 mb-1"> <span
                                    class="font-medium text-sm">AnimeKing</span> <span class="text-gray-400 text-xs">5
                                    hours ago</span> </div>
                            <p class="text-gray-300 text-sm mb-2"> The animation quality is amazing! One of the best
                                anime ever made! </p>
                            <div class="flex items-center space-x-4 text-gray-400 text-xs"> <button
                                    class="hover:text-[#13E441] transition"> Like · 89 </button> <button
                                    class="hover:text-[#13E441] transition">Reply</button> </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </main>

    <!--  MODAL UNLOCK  -->
    <div id="lockedModal"
        class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-[9999] hidden">

        <div class="bg-[#1F1F1F] w-80 rounded-2xl p-6 text-center shadow-xl border border-white/10">

            <div class="w-14 h-14 mx-auto mb-4 flex items-center justify-center bg-red-500/10 rounded-full">
                <img src="/assets/icon/lock.svg" class="w-7 h-7 opacity-90" />
            </div>

            <h2 class="text-lg font-medium text-white mb-2" id="modalTitle">Buka Episode</h2>

            <p class="text-sm text-gray-300 mb-6">Episode ini masih terkunci. Buka untuk melanjutkan menonton.</p>

            <div class="flex items-center justify-between bg-[#272727] px-4 py-3 rounded-xl mb-6">
                <div class="text-left">
                    <p class="text-gray-300 text-sm">Harga Episode</p>
                    <div class="flex items-center gap-2 mt-1">
                        <img src="/assets/icon/coin.png" class="w-5 h-5">
                        <p class="text-white font-medium" id="modalPrice"></p>
                    </div>
                </div>

                <div class="text-right">
                    <p class="text-gray-300 text-sm">Koin Kamu</p>
                    <div class="flex items-center justify-end gap-2 mt-1">
                        <img src="/assets/icon/coin.png" class="w-5 h-5">
                        <p class="text-green-400 font-medium">{{ Auth::user()->wallet->coin_balance }}</p>
                    </div>
                </div>
            </div>

            <!-- Tombol Jika Cukup Koin -->
            <div id="enoughCoin" class="hidden">
                <div class="flex items-center justify-center gap-3">
                    <button onclick="closeLockedModal()"
                        class="px-4 py-2 bg-gray-700 hover:bg-gray-600 rounded-lg text-sm text-white">
                        Batal
                    </button>

                    <form id="unlockForm" method="POST">
                        @csrf
                        <button
                            class="px-4 py-2 bg-red-500 hover:bg-red-600 rounded-lg text-sm text-white font-medium">
                            Buka Episode
                        </button>
                    </form>
                </div>
            </div>

            <!-- Jika Koin Kurang -->
            <div id="notEnoughCoin" class="hidden text-center">
                <p class="mb-4 text-gray-300">Koin Kamu kurang, Pilihan Top Up:</p>

                <div class="grid grid-cols-2 gap-3">
                    @foreach ($coinPackages as $coinPackage)
                        <a href="javascript:void(0)" onclick="startTopUp({{ $coinPackage->id }})"
                            class="flex items-center justify-center gap-2 px-4 py-3 bg-gray-700 hover:bg-gray-600 rounded-lg text-white font-medium text-sm">
                            <img src="{{ asset('assets/icon/coin.png') }}" class="w-5 h-5">
                            <span>{{ $coinPackage->coin_amount }} Koin</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    @push('script')
        <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.clientKey') }}">
        </script>

        <script>
            // Data episode dikirim ke JS
            const episodeData = @json($episodes);
            const userCoin = {{ Auth::user()->wallet->coin_balance }};

            function openLockedModal(epId) {

                let episode = episodeData.find(e => e.id === epId);

                // Set title & price
                document.getElementById("modalTitle").innerHTML =
                    "Buka Episode " + episode.episode_number;

                document.getElementById("modalPrice").innerHTML =
                    episode.unlock_cost + " Koin";

                // Set form action
                document.getElementById("unlockForm").action =
                    "/anime/{{ $anime->slug }}/unlock/" + epId;

                // CEK KOIN USER
                if (userCoin >= episode.unlock_cost) {
                    document.getElementById("enoughCoin").classList.remove("hidden");
                    document.getElementById("notEnoughCoin").classList.add("hidden");
                } else {
                    document.getElementById("enoughCoin").classList.add("hidden");
                    document.getElementById("notEnoughCoin").classList.remove("hidden");
                }

                // Tampilkan modal
                document.getElementById("lockedModal").classList.remove("hidden");
            }

            function closeLockedModal() {
                document.getElementById("lockedModal").classList.add("hidden");
            }

            // ========== CLOSE MODAL SAAT KLIK AREA LUAR ==========
            document.getElementById("lockedModal").addEventListener("click", function(event) {
                const content = document.querySelector("#lockedModal > div");

                if (!content.contains(event.target)) {
                    closeLockedModal();
                }
            });

            // ========== MIDTRANS TOPUP ==========
            function startTopUp(packageId) {
                fetch("/topup/coin/" + packageId, {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Content-Type": "application/json",
                        },
                    })
                    .then((res) => res.json())
                    .then((data) => {

                        snap.pay(data.snap_token, {
                            onSuccess: function(result) {
                                alert("Pembayaran berhasil!");
                                location.reload();
                            },
                            onPending: function(result) {
                                alert("Menunggu pembayaran...");
                            },
                            onError: function(result) {
                                alert("Pembayaran gagal!");
                            },
                            onClose: function() {
                                console.log("Popup ditutup.");
                            }
                        });

                    });
            }
        </script>
    @endpush
</x-app-layout>
