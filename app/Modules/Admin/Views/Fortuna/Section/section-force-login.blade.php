<div class="vc_row wpb_row vc_row-fluid vc_custom_1427986969472"><div class="wpb_column vc_column_container vc_col-sm-6"><div class="vc_column-inner "><div class="wpb_wrapper"><div class="boc_spacing " style="height: 20px"></div>
                <div class="wpb_single_image wpb_content_element vc_align_left">
                    @include("Fortuna.Admin::Base.admin-sidebar")
                </div>
                <div class="boc_spacing " style="height: 10px"></div></div></div></div><div class="dark_links wpb_column vc_column_container vc_col-sm-6"><div class="vc_column-inner vc_custom_1472575963813"><div class="wpb_wrapper"><h2 class="boc_heading no_text_transform letter_spacing_negative al_left  "  style="margin-bottom: 20px;margin-top: 0px;font-size: 30px;"><span><strong style="color: #003366;">Force Login</strong></span></h2>
                <div class="boc_divider_holder"><div class="boc_divider  "  style="margin-top: 10px;margin-bottom: 10px;width: 100px;height: 2px;background: #eeeeee;"></div></div>
                <div class="wpb_text_column wpb_content_element " style="margin-bottom:15px;">
                    <div class="wpb_wrapper">
                        <p>Enter email of the user to force login as.</p>
                    </div>
                </div>
                <div role="form" class="wpcf7" id="wpcf7-f10-p23915-o1" lang="en-US" dir="ltr">
                    <div class="screen-reader-response"></div>
                    <form class="form-horizontal" method="POST" action="{{ url('/admin/force_login') }}">
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

                        <div class="flash-message">
                            @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                                @if(Session::has('alert-' . $msg))
                                    <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</p>
                                @endif
                            @endforeach
                        </div>

                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="button  btn_large btn_royalblue btn_rounded icon_pos_before">
                                    <i class="icon icon-paperplane2"></i><span>&nbsp;&nbsp;Force Login</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div><div class="boc_spacing " style="height: 20px"></div></div></div></div>
</div>