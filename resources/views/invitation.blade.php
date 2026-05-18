@extends('layouts.app')

@section('content')
            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            <link
                href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Almendra:ital,wght@0,400;0,700;1,400;1,700&display=swap"
                rel="stylesheet">

            <style>
                /* Global Fix untuk mencegah scroll horizontal */
                html,
                body {
                    max-width: 100%;
                    overflow-x: hidden;
                    scroll-behavior: smooth;

                    /* Menghilangkan Scroll Bar untuk Firefox */
                    scrollbar-width: none;

                    /* Menghilangkan Scroll Bar untuk IE dan Edge */
                    -ms-overflow-style: none;
                }

                /* Menghilangkan Scroll Bar untuk Chrome, Safari, dan Opera */
                html::-webkit-scrollbar,
                body::-webkit-scrollbar {
                    display: none;
                }

                .font-wedding {
                    font-family: 'Great Vibes', cursive;
                }

                .font-secondary {
                    font-family: 'Almendra', serif;
                }

                .bg-soft-maroon {
                    background-color: #2D0A0A;
                }

                .section-blur-transition {
                    height: 100px;
                    background: linear-gradient(to bottom, transparent, #2D0A0A);
                    backdrop-filter: blur(10px);
                    margin-top: -100px;
                    position: relative;
                    z-index: 20;
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
                        transform: scale(1.05);
                    }
                }

                /* Scrollbar Styling */
                ::-webkit-scrollbar {
                    width: 6px;
                }

                ::-webkit-scrollbar-track {
                    background: #1a0505;
                }

                ::-webkit-scrollbar-thumb {
                    background: #d4af37;
                    border-radius: 10px;
                }

                /* Mencegah AOS memicu scroll horizontal pada mobile */
                [data-aos] {
                    pointer-events: none;
                }

                .aos-animate {
                    pointer-events: auto;
                }
            </style>

            <div class="overflow-x-hidden w-full">

                <div id="opening"
                    class="fixed inset-0 z-[100] flex flex-col items-center justify-center bg-black transition-all duration-1000 ease-in-out">
                    <img src="{{ asset('images/cover.jpg') }}" alt="Cover Image"
                        class="absolute w-full h-full object-cover opacity-50">
                    <div class="relative text-center text-white p-6 w-full max-w-lg">
                        <p class="tracking-[8px] text-yellow-500 mb-4 uppercase text-xs md:text-sm">The Wedding Of</p>
                        <h1 class="font-wedding text-yellow-400 text-6xl md:text-8xl mb-2">Aisyah</h1>
                        <p class="text-3xl font-wedding text-yellow-500">&</p>
                        <h1 class="font-wedding text-yellow-400 text-6xl md:text-8xl mb-8">Fadil</h1>

                        <div class="mb-8">
                            <p class="text-base md:text-lg font-light opacity-80">Kepada Yth. Bapak/Ibu/Saudara/i:</p>
                            <h2 class="text-2xl md:text-3xl font-bold mt-2 text-white drop-shadow-lg px-2">
                                {{ $namaTamu }}
                            </h2>
                        </div>

                        <button onclick="openInvitation()"
                            class="px-10 py-3 border border-yellow-500 text-yellow-500 hover:bg-yellow-500 hover:text-black transition-all duration-300 font-semibold rounded-full bg-black/40 backdrop-blur-sm">
                            Buka Undangan
                        </button>
                    </div>
                </div>

                <audio id="music" loop>
                    <source src="{{ asset('music/song.mp3') }}" type="audio/mpeg">
                </audio>
                <button id="musicControl" onclick="toggleMusic()"
                    class="fixed bottom-5 right-5 z-50 bg-yellow-600/80 backdrop-blur-md text-white p-3 rounded-full shadow-lg hidden transition-transform hover:scale-110">
                    <svg id="musicIcon" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                    </svg>
                </button>

                <section
                    class="min-h-screen w-full flex items-center justify-center relative overflow-hidden bg-soft-maroon text-white px-4">
                    <img src="{{ asset('images/cover.jpg') }}"
                        class="absolute w-full h-full object-cover opacity-30 animate-pulse-slow">
                    <div class="absolute inset-0 bg-gradient-to-t from-soft-maroon via-transparent to-black/60"></div>
                    <div class="relative text-center w-full max-w-4xl" data-aos="zoom-in">
                        <p class="text-yellow-500 tracking-[6px] uppercase mb-4 text-xs md:text-sm">Save the Date</p>
                        <h1 class="font-wedding text-yellow-400 text-6xl md:text-8xl mb-2">Aisyah</h1>
                        <p class="text-3xl font-wedding text-yellow-500">&</p>
                        <h1 class="font-wedding text-yellow-400 text-6xl md:text-8xl mb-8">Fadil</h1>
                        <p class="text-lg md:text-2xl font-light mb-1 opacity-90">Minggu, 7 Juni 2026</p>

                        <p class="text-base md:text-lg font-light opacity-70">
                            Pukul {{ Str::contains(strtolower($sesiTamu), '2') ? '12:30 - 14:00' : '10:30 - 12:00' }} WIB
                        </p>

                        <div id="countdown" class="flex gap-2 md:gap-4 mt-10 justify-center"></div>
                    </div>
                </section>

                <div class="section-blur-transition"></div>

                <section class="py-24 bg-soft-maroon text-rose-100 relative z-10 overflow-hidden">
                    <div class="max-w-4xl mx-auto px-6 text-center" data-aos="fade-up">
                        <div class="mb-6 text-yellow-500/50 text-4xl font-serif">"</div>
                        <p class="text-xl md:text-2xl font-serif italic mb-8 leading-relaxed">
                            وَمِنْ اٰيٰتِهٖٓ اَنْ خَلَقَ لَكُمْ مِّنْ اَنْفُسِكُمْ اَزْوَاجًا لِّتَسْكُنُوْٓا اِلَيْهَا وَجَعَلَ بَيْنَكُمْ
                            مَّوَدَّةً وَّرَحْمَةًۗ اِنَّ فِيْ ذٰلِكَ لَاٰيٰتٍ لِّقَوْمٍ يَّتَفَكَّرُوْنَ ۝٢١
                        </p>
                        <p class="mb-6 text-base md:text-lg opacity-80 leading-relaxed px-4">
                            Di antara tanda-tanda (kebesaran)-Nya ialah bahwa Dia menciptakan pasangan-pasangan untukmu dari (jenis)
                            dirimu sendiri
                            agar kamu merasa tenteram kepadanya. Dia menjadikan di antaramu rasa cinta dan kasih sayang.
                            Sesungguhnya pada yang
                            demikian itu benar-benar terdapat tanda-tanda (kebesaran Allah) bagi kaum yang berpikir.
                        </p>
                        <h3 class="font-wedding text-2xl text-white mb-10">Ar-Rum : 21</h3>

                        <div class="space-y-4">
                            <h4 class="text-yellow-500 text-3xl font-wedding">Doa untuk kedua Mempelai</h4>
                            <p class="text-xl md:text-2xl font-serif italic mb-4">
                                بَارَكَ اللَّهُ لَكَ وَبَارَكَ عَلَيْكَ وَجَمَعَ بَيْنَكُمَا فِي خَيْرٍ
                            </p>
                            <p class="text-sm md:text-base opacity-70 italic">“Barokallahu laka wabaroka ‘alaika wajama’a bainakumaa
                                fii khoirin.“</p>
                            <p class="text-base opacity-80 max-w-2xl mx-auto">“Semoga Allah Memberkahimu di waktu bahagia dan
                                memberkahimu di waktu susah, dan semoga Allah menyatukan kalian berdua dalam kebaikan.”</p>
                        </div>
                        <div class="mt-10 text-yellow-500/50 text-4xl font-serif">"</div>
                    </div>
                </section>

                <div class="h-24 bg-gradient-to-b from-soft-maroon to-white"></div>

                <section class="py-24 bg-white text-gray-800 relative z-10 overflow-hidden">
                    <div class="max-w-4xl mx-auto px-6 text-center">
                        <p class="mb-10 text-gray-600 italic text-sm md:text-base px-4" data-aos="fade-up">
                            Assalamu’alaikum warahmatullahi wabarakatuh<br> Maha Suci Allah yang telah menciptakan makhluk-Nya
                            berpasang-pasangan.
                            Kami bermaksud menyelenggarakan acara Walimatul Urusy putra putri kami :
                        </p>

                        <div class="space-y-8">
                            <div data-aos="fade-up">
                                <h2 class="font-wedding text-5xl md:text-6xl text-rose-900">Aisyah Amir</h2>
                                <p class="text-gray-600 italic mt-2">Putri kedua dari :<br>Almarhum Bapak Amir Budi Sulistijo<br>dan
                                    Ibu
                                    Choiriah Agung Padmi</p>
                            </div>

                            <h3 class="font-wedding text-4xl text-rose-800" data-aos="fade-up">dengan</h3>

                            <div data-aos="fade-up">
                                <h2 class="font-wedding text-5xl md:text-6xl text-rose-900">Ahmad Fadilah</h2>
                                <p class="text-gray-600 italic mt-2">Putra keempat dari :<br> Almarhum Bapak Solihin<br>dan
                                    Almarhumah Ibu
                                    Tunah</p>
                            </div>
                        </div>

                        <div class="mt-20 space-y-10">
                            <p class="text-gray-600 italic" data-aos="fade-up">
                                Insya Allah akan dilaksanakan pada:
                            </p>
                            <h2 class="font-wedding text-5xl md:text-6xl text-rose-900" data-aos="fade-up">Minggu, 7 Juni 2026</h2>

                            <p class="text-gray-600 italic text-2xl" data-aos="fade-up">
                                Pukul {{ Str::contains(strtolower($sesiTamu), '2') ? '12:30 - 14:00' : '10:30 - 12:00' }} WIB
                            </p>

                            <p class="text-gray-600 italic px-4" data-aos="fade-up">
                                Kehadiran dan doa restu Bapak/Ibu/Saudara sekalian sangat berarti bagi kami.<br>Wassalamu’alaikum
                                warahmatullahi wabarakatuh
                            </p>
                        </div>
                    </div>
                </section>

                <section class="py-24 bg-gray-50 text-gray-800 relative z-10 overflow-hidden">
                    <div class="max-w-5xl mx-auto px-6 text-center">
                        <h2 class="font-wedding text-5xl md:text-6xl text-rose-900 mb-12" data-aos="fade-up">Lokasi Acara</h2>

                        <div class="grid md:grid-cols-2 gap-8 md:gap-12 items-center">
                            <div class="text-left space-y-6 px-2" data-aos="fade-right">
                                <div class="border-l-4 border-rose-900 pl-6">
                                    <h2 class="text-xl md:text-2xl font-bold text-rose-950 uppercase tracking-widest">Resepsi
                                        Pernikahan</h2>
                                    <p class="text-gray-600 text-lg mt-4">Jl. Pemancar 3 Blok B no 3 RT.01 RW.13, Cisalak,
                                        Sukmajaya, Depok</p>
                                </div>
                                <a href="https://maps.google.com" target="_blank"
                                    class="inline-block px-8 py-3 bg-rose-900 text-white rounded-full hover:bg-rose-800 transition shadow-lg text-sm md:text-base">
                                    Buka Google Maps
                                </a>
                            </div>
                            <div class="rounded-3xl overflow-hidden shadow-2xl h-[300px] md:h-[350px] border-4 md:border-8 border-white"
                                data-aos="fade-left">
                                <iframe
                                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3964.9510600501117!2d106.8588472760231!3d-6.400307893590376!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69eba1d7eb5355%3A0x7113aeb00c532b98!2sJl.%20Pemancar%203%20Blok%20B%20No.3%2C%20RT.4%2FRW.1%2C%20Cisalak%2C%20Kec.%20Sukmajaya%2C%20Kota%20Depok%2C%20Jawa%20Barat%2016416!5e0!3m2!1sid!2sid!4v1778830819452!5m2!1sid!2sid"
                                    width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="py-24 bg-white text-center relative z-10 overflow-hidden">
                    <div class="max-w-md mx-auto px-6" data-aos="fade-up">
                        <div class="p-8 md:p-10 bg-white rounded-3xl shadow-2xl border border-rose-100">
                            <h2 class="text-3xl font-serif mb-4 text-rose-900">Konfirmasi Kehadiran</h2>
                            <p class="mb-8 text-gray-500 text-sm md:text-base leading-relaxed">Kabar bahagia bagi kami jika Anda
                                dapat hadir untuk memberikan doa restu secara langsung.</p>

                            <div class="flex flex-col gap-4">
                                <button onclick="sendRSVP('Hadir')"
                                    class="w-full px-6 py-4 bg-rose-900 text-white rounded-2xl hover:bg-rose-800 transition font-bold shadow-md">
                                    SAYA AKAN HADIR
                                </button>
                                <button onclick="sendRSVP('Tidak Hadir')"
                                    class="w-full px-6 py-4 border-2 border-rose-900 text-rose-900 rounded-2xl hover:bg-rose-50 transition font-bold">
                                    TIDAK DAPAT HADIR
                                </button>
                            </div>

                            <p id="rsvp-status" class="mt-6 text-rose-900 font-medium italic text-sm">Menunggu konfirmasi Anda...
                            </p>

                            <div class="mt-8 pt-8 border-t border-gray-100">
                                @php
    $pesanWA = "Halo Aisyah %26 Fadil, Saya " . urlencode($namaTamu) . " mengonfirmasi bahwa saya akan hadir di acara pernikahan Anda. Terima kasih!";
    $linkWA = "https://wa.me/6285157702192?text=" . $pesanWA;
                                @endphp
                                <a href="{{ $linkWA }}" target="_blank"
                                    class="px-6 py-3 bg-green-600 text-white rounded-full font-bold inline-flex items-center gap-2 hover:bg-green-700 transition text-sm">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.588-5.946 0-6.556 5.332-11.888 11.888-11.888 3.176 0 6.161 1.237 8.404 3.48s3.481 5.229 3.481 8.406c0 6.555-5.332 11.887-11.888 11.887-2.007 0-3.974-.506-5.717-1.464l-6.269 1.644zm5.046-3.593c1.612.953 3.397 1.456 5.229 1.456 5.728 0 10.391-4.663 10.391-10.391 0-2.775-1.081-5.383-3.043-7.344-1.962-1.961-4.57-3.042-7.348-3.042-5.728 0-10.391 4.663-10.391 10.391 0 1.834.485 3.626 1.403 5.242l-1.041 3.804 3.902-1.023z" />
                                    </svg>
                                    Konfirmasi via WhatsApp
                                </a>
                            </div>
                        </div>
                    </div>
                </section>

            </div>

            <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    AOS.init({ duration: 1000, once: true });
                });

                const music = document.getElementById('music');
                const musicBtn = document.getElementById('musicControl');
                const opening = document.getElementById('opening');

                function openInvitation() {
                    opening.classList.add('-translate-y-full', 'opacity-0');
                    music.play();
                    musicBtn.classList.remove('hidden');
                    document.body.style.overflow = 'visible';
                    setTimeout(() => { opening.style.display = 'none'; }, 1100);
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

               function sendRSVP(status) {
                    const statusUpperCase = status.toUpperCase();
                    const guestID = window.location.pathname.split('/').pop();
                    const statusEl = document.getElementById('rsvp-status');
                    statusEl.innerText = 'Mengirim konfirmasi...';

                    fetch('/update-attendance', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ id: guestID, status: statusUpperCase })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                statusEl.innerText = 'Terima kasih! Status: ' + data.status;
                                statusEl.classList.add('text-green-600');
                            }
                        })
                        .catch(err => {
                            statusEl.innerText = 'Gagal mengirim. Coba lagi.';
                        });
                }

                // Dinamis Target Countdown Berdasarkan Sesi
                const sesiAcara = "{{ $sesiTamu }}";
                const targetTime = sesiAcara.includes('2') ? "June 7, 2026 12:30:00" : "June 7, 2026 10:30:00";

                const targetDate = new Date(targetTime).getTime();
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
                                                        <div class="bg-white/10 backdrop-blur-md p-2 md:p-3 rounded-xl border border-white/20 min-w-[60px] md:min-w-[75px]">
                                                            <span class="text-xl md:text-2xl font-bold block">${d >= 0 ? d : 0}</span> <span class="text-[8px] md:text-[10px] uppercase">Hari</span>
                                                        </div>
                                                        <div class="bg-white/10 backdrop-blur-md p-2 md:p-3 rounded-xl border border-white/20 min-w-[60px] md:min-w-[75px]">
                                                            <span class="text-xl md:text-2xl font-bold block">${h >= 0 ? h : 0}</span> <span class="text-[8px] md:text-[10px] uppercase">Jam</span>
                                                        </div>
                                                        <div class="bg-white/10 backdrop-blur-md p-2 md:p-3 rounded-xl border border-white/20 min-w-[60px] md:min-w-[75px]">
                                                            <span class="text-xl md:text-2xl font-bold block">${m >= 0 ? m : 0}</span> <span class="text-[8px] md:text-[10px] uppercase">Menit</span>
                                                        </div>
                                                        <div class="bg-white/10 backdrop-blur-md p-2 md:p-3 rounded-xl border border-white/20 min-w-[60px] md:min-w-[75px]">
                                                            <span class="text-xl md:text-2xl font-bold block">${s >= 0 ? s : 0}</span> <span class="text-[8px] md:text-[10px] uppercase">Detik</span>
                                                        </div>
                                                    `;
                    }
                }, 1000);

                document.body.style.overflow = 'hidden';
            </script>
@endsection