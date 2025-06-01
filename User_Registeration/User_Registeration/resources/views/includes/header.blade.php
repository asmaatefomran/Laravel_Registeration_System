<header>
    <div class="navbar">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo">
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
        <h2>Wpbeginner</h2>
        <nav>
            <ul>
                <li><a href="#"><i class="fas fa-info-circle"></i> {{ __('messages.about') }}</a></li>
                <li><a href="#"><i class="fas fa-home"></i> {{ __('messages.home') }}</a></li>
                <li><a href="#"><i class="fas fa-sign-in-alt"></i> {{ __('messages.login') }}</a></li>
                <li><a href="#"><i class="fas fa-user-plus"></i> {{ __('messages.register') }}</a></li>
                <!-- Language Switcher -->
                <li class="language-switcher">
                    @if(app()->getLocale() == 'en')
                        <a href="{{ route('lang.switch', 'ar') }}" class="lang-btn">
                            <i class="fas fa-globe"></i> العربية
                        </a>
                    @else
                        <a href="{{ route('lang.switch', 'en') }}" class="lang-btn">
                            <i class="fas fa-globe"></i> English
                        </a>
                    @endif
                </li>
            </ul>
        </nav>
    </div>
</header>