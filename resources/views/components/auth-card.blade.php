<div class="auth-wrapper">
    @isset($logo)
        <div class="auth-logo">
            {{ $logo }}
        </div>
    @endisset

    <div class="auth-card">
        {{ $slot }}
    </div>
</div>
