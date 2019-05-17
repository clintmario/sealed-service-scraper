<div class="vc_row wpb_row vc_row-fluid vc_custom_1427986969472"><div class="dark_links wpb_column vc_column_container"><div class="vc_column-inner vc_custom_1472575963813"><div class="wpb_wrapper">
                <!-- div class="boc_divider_holder"><div class="boc_divider  "  style="margin-top: 10px;margin-bottom: 10px;width: 100px;height: 2px;background: #eeeeee;"></div></div -->
                <div class="wpb_text_column wpb_content_element " style="margin-bottom:15px;">
                    <div class="wpb_wrapper">
                        <!-- p>This is the landing page for all your integrations.<br/><small>Click the blue button above to start a new integration.</small></p -->
                        <div class="flash-message">
                            @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                                @if(Session::has('alert-' . $msg))
                                    <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</p>
                                @endif
                            @endforeach
                                <!-- p class="alert alert-success">You have started a new assignment.</p -->
                        </div>
                    </div>
                </div>

                <div class="boc_spacing " style="height: 12px"></div>

                <div class="wpb_column vc_column_container vc_col-sm-6" style="width:60%">
                    <video id='cm-video' class='video-js' controls preload='auto' autoplay width='640' height='480'
                           data-setup='{}' style="border: 1px solid gray;">
                        <source src='https://classesmasses.s3.amazonaws.com/{{ $lesson->file_name }}' type='video/mp4'>
                        <p class='vjs-no-js'>
                            To view this video please enable JavaScript, and consider upgrading to a web browser that
                            <a href='https://videojs.com/html5-video-support/' target='_blank'>supports HTML5 video</a>
                        </p>
                    </video>

                    <div class="wpb_wrapper">
                        <div class="boc_spacing " style="height: 10px"></div>
                        <div class="col span_2_of_3" style="margin-left: 0px;">
                            <h4 style="text-transform: none; font-weight: bold;">{{ $lesson->lesson_name }}</h4>
                        </div>
                        <div class="col span_1_of_4" style="float: right;">
                            <button class="button btn_small btn_royalblue btn_rounded icon_pos_before" style="margin-left: 8px;"
                                    onclick="window.location.href='{{ url('/next_lesson_in_assignment?assignment_id=' . $assignment->assignment_id . '&lesson_id=' . $assignment->next_lesson_id) }}';">
                                <i class="icon icon-arrow-right"></i><span> Next</span>
                            </button>
                        </div>
                        <div style="clear:both;"></div>
                    </div>

                    <div class="wpb_wrapper">
                        <div style="float:left;">
                            <h4 style="text-transform: none; font-weight: bold;">Tags: </h4>
                        </div>
                        <div style="float:left;">
                            @foreach($lesson->tags AS $tag)
                                @if(in_array($tag->tag_group, ['Primary Skill', 'Broad Objective', 'Browse Category']))
                                    <button class="button btn_small btn_red btn_rounded btn_outline icon_pos_before" style="margin-left: 8px; text-transform: none" onclick="window.location.href='{{ url('/lessons?tag_id=' . $tag->tag_id) }}';">
                                        <i class="icon icon-ribbon"></i><span> {{ $tag->tag }}</span>
                                    </button>
                                @endif
                            @endforeach
                        </div>
                        <div style="clear: both;"></div>
                    </div>

                </div>

                <div class="wpb_column vc_column_container vc_col-sm-6" style="width:40%;">
                    <div class="circ_numbers_holder  center small_counter ">
                        <div class="circ_counter" data-color="#005ed3" data-angle="" data-size="120" data-white_text="0">
                            <canvas width="80" height="80" data-end-nu="{{ $assignment->progress }}"></canvas>
                            <div class="circ_counter_text_holder" style="font-size: 30px; top: 20px;">
                                <span class="circ_counter_text" id="circ_counter_text4520">{{ $assignment->progress }}</span><span class="counter_percent_sign heading_font shown">%</span>
                            </div>
                            <div class="circ_counter_desc"><small>Assignment Progress</small></div>
                        </div>
                    </div>
                    <h3 style="font-weight: bold; text-transform: none;">{{ $assignment->assignment_name }}</h3>
                    <div class="acc_holder rounded with_bgr">
                        @foreach($assignment->tracks as $track)
                        <div class="acc_item">
                            <h4 class="accordion @if($track->has_current_lesson == true) active_acc @endif">
                                <span class="acc_control @if($track->has_current_lesson == true) acc_is_open @endif"></span>
                                <span class="acc_heading" style="text-transform: none;">{{ $track->track_name }}</span></h4>
                            <div class="accordion_content" @if($track->has_current_lesson == true) style="display: block;" @endif>
                                <ul class="theme_color_ul">
                                    @foreach($track->lessons as $lessonObj)
                                        <li>
                                            @if($lessonObj->is_current)
                                                <span style="font-weight: bold; color: #005ed3;"><i class="icon icon-arrow-right"></i>
                                            @elseif($lessonObj->has_watched_lesson)
                                                <span style="color: darkgreen;"><i class="icon icon-checkmark"></i>
                                            @endif
                                            <a href="/lesson?assignment_id={{ $assignment->assignment_id }}&lesson_id={{ $lessonObj->lesson_id }}">{{ $lessonObj->lesson_name }}</a>
                                            @if($lessonObj->is_current || $lessonObj->has_watched_lesson)</span>@endif
                                        </li>
                                    @endforeach
                                </ul>
                                <p></p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div role="form" class="wpcf7" id="wpcf7-f10-p23915-o1" lang="en-US" dir="ltr">
                    <div class="screen-reader-response"></div>
                    {{ csrf_field() }}

                    <br/>
                    <input id="lesson-id" type="hidden" value="{{ $lesson->lesson_id }}" />
                </div><div class="boc_spacing " style="height: 20px"></div>
            </div>
        </div>
    </div>
</div>