<div class="vc_row wpb_row vc_row-fluid vc_custom_1427986969472"><div class="wpb_column vc_column_container vc_col-sm-6"><div class="vc_column-inner "><div class="wpb_wrapper"><div class="boc_spacing " style="height: 20px"></div>
                <div class="wpb_single_image wpb_content_element vc_align_left">

                    <figure class="wpb_wrapper vc_figure">
                        <div class="vc_single_image-wrapper   vc_box_border_grey">
                            <img width="1200" height="1200" src="{{ url('/fortuna/images/laptop.jpg') }}" class="vc_single_image-img attachment-full" alt="consulting" style="height: 500px;" />
                        </div>
                    </figure>
                </div>
                <div class="boc_spacing " style="height: 10px"></div></div></div></div><div class="dark_links wpb_column vc_column_container vc_col-sm-6"><div class="vc_column-inner vc_custom_1472575963813"><div class="wpb_wrapper"><h2 class="boc_heading no_text_transform letter_spacing_negative al_left  "  style="margin-bottom: 20px;margin-top: 0px;font-size: 30px;"><span><strong style="color: #003366;">Contact Us</strong></span></h2>
                <div class="boc_divider_holder"><div class="boc_divider  "  style="margin-top: 10px;margin-bottom: 10px;width: 100px;height: 2px;background: #eeeeee;"></div></div>
                <div class="wpb_text_column wpb_content_element " style="margin-bottom:15px;">
                    <div class="wpb_wrapper">
                        <p>Reach out to us by submitting this form.</p>
                        <div class="flash-message">
                            @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                                @if(Session::has('alert-' . $msg))
                                    <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</p>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>

                <div role="form" class="wpcf7" id="wpcf7-f10-p23797-o1" lang="en-US" dir="ltr">
                    <div class="screen-reader-response"></div>
                    <form id="bogex-contact-form" action="{{ url('contact') }}" method="post" class="wpcf7-form">
                        {{ csrf_field() }}
                        <p><label> Your Name<br />
                                <span class="wpcf7-form-control-wrap your-name"><input type="text" name="name" value="{{ empty($posted) ? Auth::user()->name : '' }}" size="40" class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required" aria-required="true" aria-invalid="false" required /></span> </label></p>
                        <p><label> Your Email</span><br />
                                <span class="wpcf7-form-control-wrap your-email"><input type="email" name="email" value="{{ empty($posted) ? Auth::user()->email : '' }}" size="40" class="wpcf7-form-control wpcf7-text wpcf7-email wpcf7-validates-as-required wpcf7-validates-as-email" aria-required="true" aria-invalid="false" required /></span> </label></p>
                        <p><label> Subject<br />
                                <span class="wpcf7-form-control-wrap your-subject"><input type="text" name="subject" value="" size="40" class="wpcf7-form-control wpcf7-text" aria-invalid="false" required /></span> </label></p>
                        <p><label> Your Message<br />
                                <span class="wpcf7-form-control-wrap your-message"><textarea name="message" cols="40" rows="10" class="wpcf7-form-control wpcf7-textarea" aria-invalid="false" required></textarea></span> </label></p>
                        <p><button type="submit" class="button  btn_large btn_royalblue btn_rounded icon_pos_before"><i class="icon  icon-paperplane2"></i><span>&nbsp;&nbsp;Send</span></button>
                            <img id="bogex-loader" style="margin-left: 10px;" alt="Bogex Loader" class="wpcf7-display-none" src="{{ url('fortuna/images/ajax-loader.gif') }}" /><span id="bogex-contact-response" class="wpcf7-display-none" style="font-size: 12px; font-weight: bold; margin-left: 20px;"></span>
                        </p>
                    </form>
                </div>

                <div class="boc_spacing " style="height: 20px"></div></div></div></div>
</div>