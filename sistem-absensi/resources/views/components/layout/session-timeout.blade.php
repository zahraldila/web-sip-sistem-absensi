{{-- ===================== --}}
{{-- SESSION TIMEOUT HANDLER --}}
{{-- Mirrors the server-side SessionTimeout middleware. --}}
{{-- Auto-redirects to login when session expires — no modal. --}}
{{-- ===================== --}}

@php
    // Sync with App\Http\Middleware\SessionTimeout::$timeout
    $sessionTimeoutSeconds = 1800; // set kecil untuk testing, ubah ke 1800 saat production
@endphp

<script>
(function () {
    var SESSION_TIMEOUT_MS = {{ $sessionTimeoutSeconds }} * 1000;
    var LOGIN_URL          = '/login';
    var logoutTimer        = null;

    function redirectToLogin() {
        // Submit POST /logout to properly invalidate the server session
        var form = document.getElementById('_session_timeout_logout_form');
        if (form) {
            form.submit();
        } else {
            window.location.href = '/login';
        }
    }

    function scheduleTimer() {
        clearTimeout(logoutTimer);
        logoutTimer = setTimeout(redirectToLogin, SESSION_TIMEOUT_MS);
    }

    // Reset timer on any user activity (debounced to max once per minute)
    var lastReset = Date.now();
    function onActivity() {
        var now = Date.now();
        if (now - lastReset < 60000) return;
        lastReset = now;
        scheduleTimer();
    }

    ['click', 'keydown', 'scroll', 'mousemove', 'touchstart'].forEach(function (evt) {
        document.addEventListener(evt, onActivity, { passive: true });
    });

    scheduleTimer();
})();
</script>

{{-- Hidden form untuk POST /logout (invalidate session di server) --}}
<form id="_session_timeout_logout_form" method="POST" action="{{ route('logout') }}" style="display:none;">
    @csrf
</form>
