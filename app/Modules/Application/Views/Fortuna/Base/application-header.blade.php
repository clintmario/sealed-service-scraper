<!-- Header::START -->
<header id= "header"
        class= "has_subheader
					transparent_header					sticky_header					hide_subheader_on_scroll">

    @yield('top-header')

    <div class="rel_pos">

        <div class="container" style="height: 80px;">

            <div class="section rel_pos ">


                <div id="logo">
                    <div class='logo_img transparent_logo_flip' style="padding-top: 25px;">
                        <a href="{{ Auth::check() ? url('home') : url('/') }}" title="BoHRIS" rel="home">
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

                <div class="custom_menu_4 main_menu_underline_effect" style="padding-top: 25px;">
                    <div id="menu" class="menu-bogex-menu-container">
                        <div style="position:absolute; left: 5px; top: 50px;">
                            <!-- span style="font-family: 'Montserrat, Arial, Helvetica, sans-serif'; color: #f51149; font-size: 17px;">Self-serve HRIS Integration</span -->
                        </div>
                        <ul>
                            @if(Auth::check())
                            <li id="menu-item-23810" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-23810"><a href="{{ Config::get('app.url') }}/home">Home</a></li>
                            @else
                            <li id="menu-item-23810" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-23810"><a href="{{ Config::get('app.url') }}">Home</a></li>
                            @endif
                            <li id="menu-item-23910" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-23910"><a href="{{ Config::get('app.url') }}/library">Library</a></li>
                            @if(!empty($isUserAdmin))
                            <li id="menu-item-23910" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-23910"><a href="{{ Config::get('app.url') }}/cms">CMS</a></li>
                            <li id="menu-item-23910" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-23910"><a href="{{ Config::get('app.url') }}/admin">Admin</a></li>
                            @endif
                            @if(Auth::check())
                            <li id="menu-item-23910" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-23910"><a href="{{ Config::get('app.url') }}/contact">Contact</a></li>
                            <li id="menu-item-23813" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-23813"><a href="{{ Config::get('app.url') }}/logout" style="padding-right: 6px;">Logout</a></li>
                            @else
                            <li id="menu-item-23910" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-23910"><a href="{{ Config::get('app.url') }}/register">Register</a></li>
                            <li id="menu-item-23813" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-23813"><a href="{{ Config::get('app.url') }}/login" style="padding-right: 6px;">Login</a></li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div id="mobile_menu">
        <ul>
            <li id="menu-item-22810" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-22810"><a href="{{ url('admin') }}">Home</a></li>
            <li id="menu-item-22810" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-22810"><a href="{{ url('library') }}">Library</a></li>
            @if(!empty($isUserAdmin))
            <li id="menu-item-22910" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-22910"><a href="{{ url('cms') }}">CMS</a></li>
            <li id="menu-item-22910" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-22910"><a href="{{ url('admin') }}">Admin</a></li>
            @endif
            @if(Auth::check())
            <li id="menu-item-22810" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-22810"><a href="{{ url('contact') }}">Contact</a></li>
            <li id="menu-item-22813" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-22813"><a href="{{ url('logout') }}" style="padding-right: 6px;">Logout</a></li>
            @else
            <li id="menu-item-22810" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-22810"><a href="{{ url('register') }}">Register</a></li>
            <li id="menu-item-22813" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-22813"><a href="{{ url('login') }}" style="padding-right: 6px;">Login</a></li>
            @endif
        </ul>
    </div>

</header>
<!-- Header::END -->
