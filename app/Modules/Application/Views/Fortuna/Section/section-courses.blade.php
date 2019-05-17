<div class="vc_row wpb_row vc_row-fluid vc_custom_1427986969472"><div class="wpb_column vc_column_container vc_col-sm-6" style="width:25%"><div class="vc_column-inner "><div class="wpb_wrapper"><div class="boc_spacing " style="height: 20px"></div>
                <div class="wpb_single_image wpb_content_element vc_align_left">
                    @include("Fortuna.Application::Base.landing-sidebar")
                </div>
                <div class="boc_spacing " style="height: 10px"></div></div></div></div><div class="dark_links wpb_column vc_column_container vc_col-sm-6" style="width: 75%"><div class="vc_column-inner vc_custom_1472575963813"><div class="wpb_wrapper"><h2 class="boc_heading no_text_transform letter_spacing_negative al_left"  style="margin-bottom: 20px;margin-top: 0px;font-size: 30px;"><span><strong style="color: #003366;">Courses <!-- span style="color: #f51149">Integrations</span --></strong></span>
                    &nbsp;&nbsp;&nbsp;&nbsp;<!-- button class="button  btn_large btn_royalblue btn_rounded icon_pos_before" onclick="window.location.href='{{ url('/select-hris') }}';">
                        <i class="icon icon-magic-wand"></i><span>&nbsp; Start HRIS Integration Wizard</span>
                    </button --></h2>
                <div class="boc_divider_holder"><div class="boc_divider  "  style="margin-top: 10px;margin-bottom: 10px;width: 100px;height: 2px;background: #eeeeee;"></div></div>
                <div class="wpb_text_column wpb_content_element " style="margin-bottom:15px;">
                    <div class="wpb_wrapper">
                        <!-- p>This is the landing page for all your integrations.<br/><small>Click the blue button above to start a new integration.</small></p -->
                        <div class="flash-message">
                            @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                                @if(Session::has('alert-' . $msg))
                                    <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</p>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>

                <div id="portfolio_grid_82f2910067cbb5ca14a1e4e4d2e3d656" class="grid_holder animated_items big_spacing " style="position: relative; margin-bottom:10px;">
                    @foreach($courses as $course)
                        @php
                            $tagColor = "#005ed3";
                            $tagText = $course->subject;
                            $boxStyle = "border-bottom: 1px solid #f51149; padding-top:65px; padding-bottom: 35px";
                            $trialText = "";
                        @endphp
                    <div class="col span_1_of_3 info_item isotope_element web-development wordpress-themes">
                            <div class="portfolio_animator_class boc_anim_hidden boc_bottom-to-top boc_start_animation" style="transition-delay: 2750ms; border: 1px solid gray">
                                <div class="pic_info type4" style="height: 350px;">
                                    <a href="//{{ Config::get('module.app_segment') }}/assignments?course_id={{ $course->course_id }}" title="" class="pic_info_link_type4" style="text-decoration: none;">
                                        <div style="text-align: center; position: absolute; top: 0px; right: 0px; background: {{ $tagColor }}; width: 120px; height: 25px; padding: 10px;"><span style="color: white;">{{ $tagText }}</span></div>
                                        <div style="text-align: left; position: absolute; top: 0px; left: 0px; background: white; width: 125px; height: 25px; padding: 10px;"><span style="color: #f51149;"><small>{{ $trialText }}</small></span></div>
                                        <div class="pic  img_hover_effect4" style="{{ $boxStyle }}"><div class="plus_overlay"></div><div class="plus_overlay_icon"></div>
                                            <img width="1200" height="800" src="//{{ Config::get('module.app_segment') }}/fortuna/images/open-book.png" class="attachment-full size-full wp-post-image" alt="{{ $course->course }}" title="{{ $course->course }}" >
                                        </div>
                                    </a>
                                    <div class="info_overlay">
                                        <div class="info_overlay_padding">
                                            <div class="info_desc">
                                                <h3 style="text-transform: none; font-weight: bold;">
                                                    <a href="//{{ Config::get('module.app_segment') }}/assignments?course_id={{ $course->course_id }}" title=""  style="text-decoration: none;">{{ $course->course }}</a>
                                                </h3>
                                                <p>
                                                <span style="color: #003366; font-size: 14px;">
                                                    <small>Stats: </small>
                                                </span>
                                                <span style="margin-left:2px; color: darkgreen; font-size: 14px;">
                                                    <small>{{ $course->statsString }}</small>
                                                </span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                    @endforeach
                </div>

                <div role="form" class="wpcf7" id="wpcf7-f10-p23915-o1" lang="en-US" dir="ltr">
                    <div class="screen-reader-response"></div>
                    {{ csrf_field() }}

                    <br/>
                </div><div class="boc_spacing " style="height: 20px"></div>
            </div>
        </div>
    </div>
</div>