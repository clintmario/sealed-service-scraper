<!-- Header::START -->
<header id= "header"
        class= "has_subheader
					transparent_header					sticky_header					hide_subheader_on_scroll">

    @yield('top-header')

    <div class="rel_pos">

        <div class="container">

            <div class="section rel_pos ">


                <div id="logo">
                    <div class='logo_img transparent_logo_flip'>
                        <a href="{{ Config::get('app.url') }}" title="Bogex" rel="home">
                            <img src="//{{ Config::get('module.app_segment') }}/fortuna/images/classesmasses-logo.png" alt="Bogex"/>
                            <span id="transparent_logo"><img src="//{{ Config::get('module.app_segment') }}/fortuna/images/classesmasses-logo.png" alt="Bogex Fortuna"/></span>
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
                            <li id="menu-item-23810" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-23810"><a href="{{ Config::get('app.url') }}/home">Home</a></li>
                            <li id="menu-item-23812" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-23812">
                                <a href="{{ Config::get('app.url') }}/cms">CMS</a>
                                <ul class="sub-menu">
                                    <li id="menu-item-{{ (3100) }}" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-has-children menu-item-{{ (3100) }}">
                                        <a href="{{ Config::get('app.url') }}/cms/commit">Revisioning<span></span></a>
                                        <ul class="sub-menu">
                                            <li id="menu-item-{{ (3000) }}" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-{{ (3000) }}">
                                                <a href="{{ Config::get('app.url') }}/cms/commit">Commit<span></span></a>
                                            </li>
                                            <li id="menu-item-{{ (2900) }}" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-{{ (2900) }}">
                                                <a href="" onclick="publishObjects(); return false;">Publish<span></span></a>
                                            </li>
                                        </ul>
                                    </li>
                                    @foreach ($menuItems as $key => $menuItem)
                                        <li id="menu-item-{{ (1800 + $menuItem) }}" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-has-children menu-item-{{ (1800 + $menuItem) }}">
                                            <a href="{{ Config::get('app.url') }}/cms/list?object_type={{ urlencode($key) }}">{{ $key }}<span></span></a>
                                            <ul class="sub-menu">
                                                <li id="menu-item-{{ (1700 + $menuItem) }}" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-{{ (1700 + $menuItem) }}">
                                                    <a href="{{ Config::get('app.url') }}/cms/list?object_type={{ urlencode($key) }}">List<span></span></a>
                                                </li>
                                                <li id="menu-item-{{ (1600 + $menuItem) }}" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-{{ (1600 + $menuItem) }}">
                                                    <a href="{{ Config::get('app.url') }}/cms/save?object_type={{ urlencode($key) }}">Add<span></span></a>
                                                </li>
                                                <li id="menu-item-{{ (1500 + $menuItem) }}" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-{{ (1500 + $menuItem) }}">
                                                    <a href="{{ Config::get('app.url') }}/cms/deleted?object_type={{ urlencode($key) }}">Deleted<span></span></a>
                                                </li>
                                            </ul>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                            <li id="menu-item-23910" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-23910"><a href="{{ Config::get('app.url') }}/admin">Admin</a></li>
                            <li id="menu-item-23813" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-23813"><a href="{{ Config::get('app.url') }}/logout" style="padding-right: 6px;">Logout</a></li>

                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div id="mobile_menu">
        <ul>
            <li id="menu-item-22810" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-22810"><a href="{{ Config::get('app.url') }}/home">Home</a></li>
            <li id="menu-item-22812" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-22812">
                <a href="{{ Config::get('app.url') }}/cms">CMS</a>
                <ul class="sub-menu">
                    <li id="menu-item-{{ (4100) }}" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-has-children menu-item-{{ (4100) }}">
                        <a href="{{ Config::get('app.url') }}/cms/commit">Revisioning<span></span></a>
                        <ul class="sub-menu">
                            <li id="menu-item-{{ (4000) }}" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-{{ (4000) }}">
                                <a href="{{ Config::get('app.url') }}/cms/commit">Commit</a>
                            </li>
                            <li id="menu-item-{{ (3900) }}" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-{{ (3900) }}">
                                <a href="" onclick="publishObjects(); return false;">Publish</a>
                            </li>
                        </ul>
                    </li>
                    @foreach ($menuItems as $key => $menuItem)
                    <li id="menu-item-{{ (2200 + $menuItem) }}" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-has-children menu-item-{{ (2200 + $menuItem) }}">
                        <a href="{{ Config::get('app.url') }}/cms/list?object_type={{ urlencode($key) }}">{{ $key }}<span></span></a>
                        <ul class="sub-menu">
                            <li id="menu-item-{{ (2100 + $menuItem) }}" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-{{ (2100 + $menuItem) }}">
                                <a href="{{ Config::get('app.url') }}/cms/list?object_type={{ urlencode($key) }}">List</a>
                            </li>
                            <li id="menu-item-{{ (2000 + $menuItem) }}" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-{{ (2000 + $menuItem) }}">
                                <a href="{{ Config::get('app.url') }}/cms/save?object_type={{ urlencode($key) }}">Add</a>
                            </li>
                            <li id="menu-item-{{ (1900 + $menuItem) }}" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-{{ (1900 + $menuItem) }}">
                                <a href="{{ Config::get('app.url') }}/cms/deleted?object_type={{ urlencode($key) }}">Deleted</a>
                            </li>
                        </ul>
                    </li>
                    @endforeach
                </ul>
            </li>
            <li id="menu-item-22910" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-22910"><a href="{{ Config::get('app.url') }}/admin">Admin</a></li>
            <li id="menu-item-22813" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-22813"><a href="{{ Config::get('app.url') }}/logout" style="padding-right: 6px;">Logout</a></li>
        </ul>
    </div>

</header>
<!-- Header::END -->
