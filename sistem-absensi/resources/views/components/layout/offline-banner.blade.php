{{-- ===================== --}}
{{-- OFFLINE WARNING BANNER --}}
{{-- Detects internet connectivity using browser events and navigator.onLine --}}
{{-- Shown as a fixed bar at the top of the screen when offline --}}
{{-- ===================== --}}

<div
    id="offline-banner"
    role="alert"
    aria-live="assertive"
    style="display:none;"
    class="fixed top-0 left-0 right-0 z-[9999] flex items-center justify-center gap-3 px-4 py-3 text-sm font-medium text-white shadow-lg"
    style="background: linear-gradient(90deg, #dc2626, #b91c1c);"
>
    <style>
        #offline-banner {
            background: linear-gradient(90deg, #dc2626, #b91c1c);
            animation: offlinePulse 2.5s ease-in-out infinite;
        }
        @keyframes offlinePulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.88; }
        }
        #offline-banner .offline-icon {
            animation: offlineIconBounce 1.5s ease-in-out infinite;
        }
        @keyframes offlineIconBounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-2px); }
        }
    </style>

    {{-- Icon --}}
    <svg class="offline-icon h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round"
            d="M18.364 5.636a9 9 0 0 1 0 12.728M15.536 8.464a5 5 0 0 1 0 7.072M2 2l20 20M8.464 8.464A5 5 0 0 0 6.343 12M5.636 5.636A9 9 0 0 0 3.515 12M12 18h.01" />
    </svg>

    {{-- Message --}}
    <span>
        Tidak ada koneksi internet. Beberapa fitur mungkin tidak tersedia. Silahkan periksa koneksi Anda.
    </span>

    {{-- Retry button --}}
    <button
        type="button"
        id="offline-retry-btn"
        class="ml-2 shrink-0 rounded-full border border-white/40 bg-white/20 px-3 py-1 text-xs font-semibold text-white hover:bg-white/30 transition focus:outline-none focus:ring-2 focus:ring-white/50"
        onclick="window.location.reload()"
    >
        Coba Lagi
    </button>
</div>

<script>
(function () {
    var banner = document.getElementById('offline-banner');
    if (!banner) return;

    function showBanner() {
        banner.style.display = 'flex';
        // Push page content down so it's not hidden under banner
        document.body.style.paddingTop = (banner.offsetHeight + 'px');
    }

    function hideBanner() {
        banner.style.display = 'none';
        document.body.style.paddingTop = '';
    }

    // Check initial state immediately when DOM is ready
    if (!navigator.onLine) {
        showBanner();
    }

    // Listen for browser connectivity events
    window.addEventListener('online', function () {
        hideBanner();
    });

    window.addEventListener('offline', function () {
        showBanner();
    });

    // Periodic heartbeat check — pings the server every 15s
    // to catch cases where the browser thinks it's online but
    // there's no actual internet (e.g., connected to router with no WAN)
    function checkRealConnectivity() {
        if (!navigator.onLine) {
            showBanner();
            return;
        }
        // Ping a tiny no-cache endpoint
        fetch('/up?_=' + Date.now(), {
            method: 'HEAD',
            cache: 'no-store',
            signal: AbortSignal.timeout(5000)
        })
        .then(function (res) {
            if (res.ok) {
                hideBanner();
            } else {
                showBanner();
            }
        })
        .catch(function () {
            // Network request failed → no connectivity
            showBanner();
        });
    }

    // Run once after a short delay (after layout renders)
    setTimeout(checkRealConnectivity, 1000);

    // Then periodically
    setInterval(checkRealConnectivity, 15000);
})();
</script>
