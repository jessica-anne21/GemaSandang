<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Gema Sandang') }}</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- =======================
            PWA CONFIG
    ======================== -->

    <!-- Manifest -->
    <link rel="manifest" href="/manifest.json">

    <!-- Color Theme of Status Bar -->
    <meta name="theme-color" content="#ffffff">

    <!-- iOS Support -->
    <link rel="apple-touch-icon" href="/icon-192.png">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="Gema Sandang">

    <!-- Fallback favicon -->
    <link rel="icon" type="image/png" href="/icon-192.png">

</head>
<body>

    <div id="app">
        @include('layouts.partials.navbar')

        <main>
            @yield('content')
        </main>
    </div>



    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- =======================
             REGISTER SERVICE WORKER
    ======================== -->
    <script>
        if ("serviceWorker" in navigator) {
            window.addEventListener("load", function () {
                navigator.serviceWorker.register("/sw.js")
                    .then(function (registration) {
                        console.log("Service Worker registered with scope:", registration.scope);
                    })
                    .catch(function (error) {
                        console.log("Service Worker registration failed:", error);
                    });
            });
        }
    </script>
    <script>
    let deferredPrompt;

    window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault();
        deferredPrompt = e;

        // Show custom button
        const a2hsBtn = document.getElementById('a2hs-btn');
        if (a2hsBtn) {
            a2hsBtn.style.display = 'block';
        }
    });

    async function addToHomeScreen() {
        if (!deferredPrompt) return;

        deferredPrompt.prompt();
        const { outcome } = await deferredPrompt.userChoice;

        console.log('User choice:', outcome);

        deferredPrompt = null;

        // Hide button after installing
        const a2hsBtn = document.getElementById('a2hs-btn');
        if (a2hsBtn) a2hsBtn.style.display = 'none';
    }
</script>
<button id="a2hs-btn" 
    onclick="addToHomeScreen()" 
    style="
        display:none;
        position:fixed;
        bottom:20px;
        right:20px;
        background:#0d6efd;
        color:white;
        border:none;
        padding:12px 18px;
        border-radius:30px;
        box-shadow:0 4px 12px rgba(0,0,0,0.2);
        z-index:1000;
    ">
    <i class="bi bi-phone"></i> Add to Home Screen
</button>
<script>
    // iOS detection
    const isIos = () => {
        const ua = window.navigator.userAgent.toLowerCase();
        return /iphone|ipad|ipod/.test(ua);
    };
    const isInStandaloneMode = () =>
        ("standalone" in window.navigator) && window.navigator.standalone;

    if (isIos() && !isInStandaloneMode()) {
        setTimeout(() => {
            alert('Untuk menambahkan ke Home Screen:\n1. Tekan Share (ikon kotak + panah)\n2. Pilih "Add to Home Screen".');
        }, 1500);
    }
</script>



</body>
</html>
