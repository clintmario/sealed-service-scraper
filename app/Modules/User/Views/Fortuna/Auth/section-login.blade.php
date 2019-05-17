<div class="vc_row wpb_row vc_row-fluid vc_custom_1427986969472"><div class="wpb_column vc_column_container vc_col-sm-6"><div class="vc_column-inner "><div class="wpb_wrapper"><div class="boc_spacing " style="height: 20px"></div>
                <div class="wpb_single_image wpb_content_element vc_align_left">

                    <figure class="wpb_wrapper vc_figure">
                        <div class="vc_single_image-wrapper   vc_box_border_grey"><img width="1200" height="800" src="//{{ Config::get('module.app_segment') }}/fortuna/images/login.jpg" class="vc_single_image-img attachment-full" alt="consulting" /></div>
                    </figure>
                </div>
                <div class="boc_spacing " style="height: 10px"></div></div></div></div><div class="dark_links wpb_column vc_column_container vc_col-sm-6"><div class="vc_column-inner vc_custom_1472575963813"><div class="wpb_wrapper"><h2 class="boc_heading no_text_transform letter_spacing_negative al_left  "  style="margin-bottom: 20px;margin-top: 0px;color: #333333;font-size: 30px;"><span><strong>Login</strong></span></h2>
                <div class="boc_divider_holder"><div class="boc_divider  "  style="margin-top: 10px;margin-bottom: 10px;width: 100px;height: 2px;background: #eeeeee;"></div></div>
                <div class="wpb_text_column wpb_content_element " style="margin-bottom:15px;">
                    <div class="wpb_wrapper">
                        <p>Enter your email and password to login.</p>
                    </div>
                </div>
                <div role="form" class="wpcf7" id="wpcf7-f10-p23915-o1" lang="en-US" dir="ltr">
                    <div class="screen-reader-response"></div>
                    <form class="form-horizontal" method="POST" action="{{ url('/login') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">Email</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Password</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember"> Remember Me
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="button  btn_large btn_royalblue btn_rounded icon_pos_before">
                                    <i class="icon icon-paperplane2"></i><span>&nbsp;&nbsp;Login</span>
                                </button>


                                <a class="btn btn-link" href="{{ url('/password/reset') }}">
                                    Forgot Password?
                                </a>
                            </div>
                        </div>
                    </form>
                </div><div class="boc_spacing " style="height: 20px"></div></div></div></div>
</div>