<div class="vc_row wpb_row vc_row-fluid vc_custom_1427986969472"><div class="dark_links wpb_column vc_column_container"><div class="vc_column-inner vc_custom_1472575963813"><div class="wpb_wrapper"><h2 class="boc_heading no_text_transform letter_spacing_negative al_left"  style="margin-bottom: 20px;margin-top: 0px;font-size: 30px;"><span><strong style="color: #003366;">My <span style="color: #f51149">Learning</span></strong></span>
                    &nbsp;&nbsp;&nbsp;&nbsp;<!-- button class="button  btn_large btn_royalblue btn_rounded icon_pos_before" onclick="window.location.href='{{ url('/library') }}';">
                        <i class="icon icon-magic-wand"></i><span>&nbsp; Browse</span>
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

                <div class="boc_spacing " style="height: 12px"></div>

                <div class="newtabs clearfix horizontal  minimal_style" style="display: block; width: 100%; margin: 0px; opacity: 1;">
                    <ul class="resp-tabs-list">
                        <li onclick="location.href='/home';" class="resp-tab-item resp-tab-active" aria-controls="tab_item-0" role="tab" style="background: #005ed3;"><a href="{{ url("/home") }}" style="text-decoration: none;"><span class="icon icon-note2" style="color: white;"></span> <span style="color: white;">Pending Assignments</span></a></li>
                        <li onclick="location.href='/home/completed_assignments';" class="resp-tab-item" aria-controls="tab_item-1" role="tab"><a href="{{ url("/home/completed_assignments") }}" style="text-decoration: none;"><span class="icon icon-check"></span> Completed Assignments</a></li>
                        <!-- li id="unique-style" class="resp-tab-item" aria-controls="tab_item-2" role="tab"><span class="icon icon-star4"></span> Unique Style</li -->
                    </ul>
                </div>
                <div class="boc_spacing " style="height: 20px"></div>

                <table class="fortuna_table" width="100%">
                    <tbody>
                    <tr>
                        <th>No</th>
                        <th>Assignment Name</th>
                        <th>Subject</th>
                        <th>Contents</th>
                        <th>Progress</th>
                        <th>Actions</th>
                    </tr>
                    @php
                        $index = 0;
                    @endphp
                    @foreach($assignments as $assignment)
                        @php
                            $index++;
                            $tagColor = "#005ed3";
                            $tagText = $assignment->subject;
                            $buttonColor = 'btn_red';
                            $buttonText = 'Start';
                            if ($assignment->is_started) {
                                $buttonColor = 'btn_royalblue';
                                $buttonText = 'Continue';
                            }
                        @endphp
                        <tr @if($index % 2 == 0) class="odd" @endif>
                            <td>{{ $index }}.</td>
                            <td width="25%">{{ $assignment->assignment_name }}</td>
                            <td>{{ $assignment->subject }}</td>
                            <td>{{ $assignment->statsString }}</td>
                            <td width="15%">
                            <div class="circ_numbers_holder  center small_counter ">
                                <div class="circ_counter" data-color="#005ed3" data-angle="" data-size="120" data-white_text="0">
                                    <canvas width="80" height="80" data-end-nu="{{ $assignment->progress }}"></canvas>
                                    <div class="circ_counter_text_holder" style="font-size: 30px; top: 20px;"><span class="circ_counter_text" id="circ_counter_text{{ $assignment->assignment_id }}">{{ $assignment->progress }}</span><span class="counter_percent_sign heading_font shown">%</span></div>
                                    <!-- div class="circ_counter_desc">Web Design</div -->
                                </div>
                            </div>
                            </td>
                            <td width="20%">
                                <button class="button  btn_small {{ $buttonColor }} btn_rounded icon_pos_before" onclick="window.location.href='{{ url('/next_lesson?assignment_id=' . $assignment->assignment_id) }}';">
                                    <i class="icon icon-arrow-right"></i><span> {{ $buttonText }}</span>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <div role="form" class="wpcf7" id="wpcf7-f10-p23915-o1" lang="en-US" dir="ltr">
                    <div class="screen-reader-response"></div>
                    {{ csrf_field() }}

                    <br/>
                </div><div class="boc_spacing " style="height: 20px"></div>
            </div>
        </div>
    </div>
</div>