    <div class="offcanvas-menu-overlay"></div>
    <div class="offcanvas-menu-wrapper">
        <div class="offcanvas__close">+</div>
        <ul class="offcanvas__widget">
            <li><span class="icon_search search-switch"></span></li>
            <li><a href="#"><span class="icon_heart_alt"></span>
                <div class="tip">2</div>
            </a></li>
            <li><a href="#"><span class="icon_bag_alt"></span>
                <div class="tip">2</div>
            </a></li>
        </ul>
        <div class="offcanvas__logo">
            <a href="./index.html"><img src="img/logo.png" alt=""></a>
        </div>
        <div id="mobile-menu-wrap"></div>
        <div class="offcanvas__auth"> 
                              @if(Auth::check())
                                <!-- Show profile if the user is authenticated -->
                                <a href="{{ url('dashboard') }}">Dashbaord</a>
                            @else
                                <!-- Show login and register links if the user is not authenticated -->
                                <a href="{{ url('login') }}">Login</a>
                                <a href="{{ url('register') }}">Register</a>
                            @endif
        </div>
    </div>