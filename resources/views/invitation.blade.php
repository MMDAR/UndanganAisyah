@extends('layouts.app')

@section('content')

                <!-- 1. Opening Overlay -->
                <div id="opening"
                    class="fixed inset-0 z-[100] flex flex-col items-center justify-center bg-black transition-all duration-1000 ease-in-out">
                    <video autoplay muted loop class="absolute w-full h-full object-cover opacity-40">
                        <source src="{{ asset('videos/bg.mp4') }}" type="video/mp4">
                    </video>
                    <div class="relative text-center text-white p-6">
                        <p class="tracking-[8px] text-yellow-500 mb-4 uppercase text-sm">The Wedding Of</p>
                        <h1 class="text-6xl font-serif mb-6">Aisyah</h1>
                        <p class="text-2xl font-bold mt-2 text-yellow-400">dan</p>
                        <h1 class="text-6xl font-serif mb-6">Fadil</h1>
                        <div class="mb-8">
                            <p class="text-sm italic opacity-80">Kepada Yth. Bapak/Ibu/Saudara/i:</p>
                            {{-- Benar --}}
                            <h2 class="text-2xl font-bold mt-2 text-yellow-400">
                                {{ $namaTamu }}
                            </h2>
                        </div>
                        <button onclick="openInvitation()"
                            class="px-8 py-3 border-2 border-yellow-500 text-yellow-500 hover:bg-yellow-500 hover:text-black transition-all duration-300 font-semibold rounded-full">
                            Buka Undangan
                        </button>
                    </div>
                </div>

                <!-- 2. Music & Audio Control -->
                <audio id="music" loop>
                    <source src="{{ asset('music/song.mp3') }}" type="audio/mpeg">
                </audio>
                <button id="musicControl" onclick="toggleMusic()"
                    class="fixed bottom-5 right-5 z-50 bg-yellow-500 text-black p-3 rounded-full shadow-lg hidden">
                    <svg id="musicIcon" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                    </svg>
                </button>

                <!-- 3. Hero Section -->
                <section class="min-h-screen flex items-center justify-center relative overflow-hidden bg-gray-900 text-white">
                    <img src="{{ asset('images/bg.jpg') }}"
                        class="absolute w-full h-full object-cover opacity-40 scale-105 animate-pulse-slow">
                    <div class="relative text-center px-4" data-aos="zoom-in">
                        <p class="text-yellow-400 tracking-[6px] uppercase mb-4">Save the Date</p>
                        <h1 class="text-7xl md:text-9xl font-serif my-5">Aisyah & Fadil</h1>
                        <p class="text-xl md:text-2xl font-light">Sabtu, 12 Desember 2026</p>
                        <div id="countdown" class="flex gap-4 mt-10 justify-center text-white"></div>
                    </div>
                </section>

                <!-- 4. Story Timeline -->
                <section class="py-32 bg-white text-gray-800 relative z-10">
                    <div class="max-w-4xl mx-auto px-6 text-center">
                        <h2 class="text-5xl font-serif mb-20 text-yellow-600" data-aos="fade-up">Our Journey</h2>
                        <div class="relative border-l-2 border-yellow-200 ml-4 md:ml-0 md:mx-auto text-left">
                            <div class="mb-12 ml-6 pl-8 transition hover:scale-105" data-aos="fade-right">
                                <span class="absolute -left-[11px] w-5 h-5 mt-2 bg-yellow-500 rounded-full"></span>
                                <h3 class="text-2xl font-bold">First Meet</h3>
                                <p class="text-yellow-600 font-semibold mb-2">Januari 2019</p>
                                <p class="text-gray-600">Berawal dari pertemuan tidak sengaja di rumah sakit</p>
                            </div>
                            <div class="mb-12 ml-6 pl-8 transition hover:scale-105" data-aos="fade-left">
                                <span class="absolute -left-[11px] w-5 h-5 mt-2 bg-yellow-500 rounded-full"></span>
                                <h3 class="text-2xl font-bold">Relationship</h3>
                                <p class="text-yellow-600 font-semibold mb-2">Maret 2021</p>
                                <p class="text-gray-600">Kami memutuskan untuk melangkah bersama dalam sebuah komitmen.</p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- 5. Venue & Map -->
                <section class="py-20 bg-gray-50 relative z-10">
                    <div class="max-w-5xl mx-auto px-6 text-center">
                        <h2 class="text-4xl font-serif mb-10 text-yellow-600" data-aos="fade-up">Lokasi Acara</h2>
                        <div class="grid md:grid-cols-2 gap-10 items-center">
                            <div class="text-left space-y-4" data-aos="fade-right">
                                <h3 class="text-2xl font-bold text-gray-800">Akad & Resepsi</h3>
                                <p class="text-gray-600">Gedung Serbaguna Indah<br>Jl. Melati No. 123, Jakarta Selatan</p>
                                <a href="https://maps.google.com" target="_blank"
                                    class="inline-block px-6 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition">Petunjuk
                                    Jalan</a>
                            </div>
                            <div class="rounded-xl overflow-hidden shadow-2xl h-[300px]" data-aos="fade-left">
                                <iframe class="w-full h-full" src="https://www.google.com/maps/embed?pb=..." style="border:0;"
                                    allowfullscreen="" loading="lazy"></iframe>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- 6. RSVP -->
                <section class="py-20 bg-white text-center relative z-10">
                    <div class="max-w-md mx-auto px-6" data-aos="fade-up">
                        <h2 class="text-3xl font-serif mb-6 text-yellow-600">Konfirmasi Kehadiran</h2>
                        <p class="mb-8 text-gray-500">Kabar bahagia bagi kami jika Anda dapat hadir.</p>
                    @php
                        // Gunakan variabel $namaTamu yang dikirim dari Route
                        $displayNama = $namaTamu;
                        $pesanWA = "Halo Aisyah %26 Fadil, Saya " . urlencode($displayNama) . " ingin mengonfirmasi kehadiran...";
                        $linkWA = "https://wa.me/6285157702192?text=" . $pesanWA;
                    @endphp
                    <a href="{{ $linkWA }}" target="_blank"
                        class="px-6 py-3 bg-green-600 text-white rounded-full font-bold inline-flex items-center gap-2 hover:bg-green-700 transition">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.588-5.946 0-6.556 5.332-11.888 11.888-11.888 3.176 0 6.161 1.237 8.404 3.48s3.481 5.229 3.481 8.406c0 6.555-5.332 11.887-11.888 11.887-2.007 0-3.974-.506-5.717-1.464l-6.269 1.644zm5.046-3.593c1.612.953 3.397 1.456 5.229 1.456 5.728 0 10.391-4.663 10.391-10.391 0-2.775-1.081-5.383-3.043-7.344-1.962-1.961-4.57-3.042-7.348-3.042-5.728 0-10.391 4.663-10.391 10.391 0 1.834.485 3.626 1.403 5.242l-1.041 3.804 3.902-1.023z" />
                        </svg>
                        Konfirmasi Kehadiran (RSVP)
                    </a>
                    </div>
                </section>

                <!-- Scripts -->
    <!-- Pastikan Library AOS dimuat di sini -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <script>
        // 1. Inisialisasi AOS setelah library dipastikan ada
        document.addEventListener("DOMContentLoaded", function () {
            if (typeof AOS !== 'undefined') {
                AOS.init({ duration: 1000, once: true });
            }
        });

        const music = document.getElementById('music');
        const musicBtn = document.getElementById('musicControl');
        const opening = document.getElementById('opening');

        function openInvitation() {
            opening.classList.add('-translate-y-full', 'opacity-0');

            // Memastikan audio berputar
            let playPromise = music.play();
            if (playPromise !== undefined) {
                playPromise.then(_ => {
                    console.log("Musik berputar");
                }).catch(error => {
                    console.log("Autoplay diblokir, butuh interaksi user");
                });
            }

            // Memunculkan tombol musik
            musicBtn.classList.remove('hidden');
            musicBtn.classList.add('flex'); // Pastikan terlihat jika menggunakan flex icon

            document.body.style.overflow = 'visible';
            document.documentElement.style.overflow = 'visible';

            setTimeout(() => {
                opening.style.display = 'none';
            }, 1100);
        }

        function toggleMusic() {
            const icon = document.getElementById('musicIcon');
            if (music.paused) {
                music.play();
                icon.classList.remove('opacity-50');
            } else {
                music.pause();
                icon.classList.add('opacity-50');
            }
        }

        // Lock scroll di awal
        document.body.style.overflow = 'hidden';
        document.documentElement.style.overflow = 'hidden';

        // Countdown Timer (Tetap sama)
        const targetDate = new Date("Dec 12, 2026 08:00:00").getTime();
        setInterval(() => {
            const now = new Date().getTime();
            const gap = targetDate - now;
            const d = Math.floor(gap / (1000 * 60 * 60 * 24));
            const h = Math.floor((gap % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const m = Math.floor((gap % (1000 * 60 * 60)) / (1000 * 60));
            const s = Math.floor((gap % (1000 * 60)) / 1000);

            const el = document.getElementById('countdown');
            if (el) {
                el.innerHTML = `
                        <div class="bg-yellow-500/20 backdrop-blur-md p-3 rounded-lg min-w-[65px] border border-yellow-500/30">
                            <span class="text-xl font-bold block text-yellow-400">${d}</span> <span class="text-[9px] uppercase">Hari</span>
                        </div>
                        <div class="bg-yellow-500/20 backdrop-blur-md p-3 rounded-lg min-w-[65px] border border-yellow-500/30">
                            <span class="text-xl font-bold block text-yellow-400">${h}</span> <span class="text-[9px] uppercase">Jam</span>
                        </div>
                        <div class="bg-yellow-500/20 backdrop-blur-md p-3 rounded-lg min-w-[65px] border border-yellow-500/30">
                            <span class="text-xl font-bold block text-yellow-400">${m}</span> <span class="text-[9px] uppercase">Menit</span>
                        </div>
                        <div class="bg-yellow-500/20 backdrop-blur-md p-3 rounded-lg min-w-[65px] border border-yellow-500/30">
                            <span class="text-xl font-bold block text-yellow-400">${s}</span> <span class="text-[9px] uppercase">Detik</span>
                        </div>
                    `;
            }
        }, 1000);
    </script>
                <style>
                    html {
                        scroll-behavior: smooth;
                    }

                    .animate-pulse-slow {
                        animation: pulse-slow 8s infinite ease-in-out;
                    }

                    @keyframes pulse-slow {

                        0%,
                        100% {
                            transform: scale(1);
                        }

                        50% {
                            transform: scale(1.1);
                        }
                    }

                    /* Custom scrollbar agar lebih cantik */
                    ::-webkit-scrollbar {
                        width: 8px;
                    }

                    ::-webkit-scrollbar-track {
                        background: #f1f1f1;
                    }

                    ::-webkit-scrollbar-thumb {
                        background: #d4af37;
                        border-radius: 10px;
                    }
                </style>
@endsection