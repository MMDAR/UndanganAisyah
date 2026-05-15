@extends('layouts.app')

@section('content')
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">

    <style>
        .font-wedding {
            font-family: 'Great Vibes', cursive;
        }

        .bg-soft-maroon {
            background-color: #2D0A0A;
        }

        .fade-in {
            animation: fadeIn 2s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }
    </style>

    <div class="min-h-screen bg-soft-maroon flex items-center justify-center px-6">
        <div
            class="max-w-md w-full text-center p-10 rounded-3xl border border-yellow-900/30 bg-black/20 backdrop-blur-sm fade-in">
            <h1 class="font-wedding text-yellow-500 text-6xl mb-6">Mohon Maaf...</h1>

            <div class="w-20 h-px bg-yellow-600/50 mx-auto mb-8"></div>

            <p class="text-rose-100 text-lg leading-relaxed mb-8 opacity-90">
                Nama Anda tidak ditemukan dalam daftar tamu undangan kami.
                Halaman ini khusus ditujukan bagi tamu yang telah terdaftar secara resmi.
            </p>

            <p class="text-yellow-600/70 text-sm italic mb-10">
                Jika menurut Anda ini adalah kesalahan, silakan hubungi pihak mempelai untuk konfirmasi lebih lanjut.
            </p>

            <a href="/"
                class="px-8 py-3 bg-yellow-600 text-black rounded-full font-bold hover:bg-yellow-500 transition shadow-lg">
                Kembali ke Beranda
            </a>
        </div>
    </div>
@endsection