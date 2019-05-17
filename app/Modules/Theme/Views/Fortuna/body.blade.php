<!-- Page Wrapper::START -->
<div id="wrapper" class="full_width_wrapper page_title_bgr responsive ">
@yield('header')
    <!-- Page content::START -->
    <div class="content_body">
        <!-- Page template :: START -->
        <div class="post-23797 page type-page status-publish hentry" id="post-23797" >
            <div class="container">
                <div class="section">
                    <div class='post_content'>
                            @yield('sections')
                    </div>
                </div>
            </div>
        </div>
        @include('Fortuna::admin-reports')
        <!-- Page template :: END -->
    </div>
    <!-- Page content::END -->
@yield('footer')
</div>
<!-- Page wrapper::END -->
@yield('body-assets')
