<!-- Header::START -->
<header id= "header"
        class= "has_subheader
					transparent_header					sticky_header					hide_subheader_on_scroll">

    @yield('top-header')

    <div class="rel_pos">

        <div class="container" style="margin-top: 25px;">

            <div class="section rel_pos ">


                <div id="logo">
                    <div class='logo_img transparent_logo_flip'>
                        <a href="{{ Auth::check() ? url('home') : url('/') }}" title="ClassesMasses" rel="home">
                            <img src="//{{ Config::get('module.app_segment') }}/fortuna/images/classesmasses-logo.png" alt="ClassesMasses"/>
                            <span id="transparent_logo"><img src="//{{ Config::get('module.app_segment') }}/fortuna/images/classesmasses-logo.png" alt="ClassesMasses"/></span>
                        </a>
                    </div>

                </div>

                <div id="mobile_menu_toggler">
                    <div id="m_nav_menu" class="m_nav">
                        <div class="m_nav_ham button_closed" id="m_ham_1"></div>
                        <div class="m_nav_ham button_closed" id="m_ham_2"></div>
                        <div class="m_nav_ham button_closed" id="m_ham_3"></div>
                    </div>
                </div>


                <div class="custom_menu_4 main_menu_underline_effect">
                    <div id="menu" class="menu-bogex-menu-container">
                        <ul>
                            <li id="menu-item-23810" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-23810"><a href="{{ Config::get('app.url') }}">Home</a></li>
                            <li id="menu-item-23812" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-23812"><a href="{{ Config::get('app.url') }}/library">Library</a></li>
                            <li id="menu-item-23811" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-23811"><a href="{{ Config::get('app.url') }}/register">Register</a></li>
                            <li id="menu-item-23911" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-23911"><a href="{{ Config::get('app.url') }}/login">Login</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div id="mobile_menu">
        <ul>
            <li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-23810"><a href="{{ Config::get('app.url') }}">Home</a></li>
            <li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-23812"><a href="{{ Config::get('app.url') }}/library">Library</a></li>
            <li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-23811"><a href="{{ Config::get('app.url') }}/register">Register</a></li>
            <li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-23911"><a href="{{ Config::get('app.url') }}/login">Login</a></li>
        </ul>
    </div>

</header>
<!-- Header::END -->
