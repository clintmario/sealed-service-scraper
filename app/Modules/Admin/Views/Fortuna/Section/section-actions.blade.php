<div class="vc_row wpb_row vc_row-fluid vc_custom_1427986969472"><div class="wpb_column vc_column_container vc_col-sm-6"><div class="vc_column-inner "><div class="wpb_wrapper"><div class="boc_spacing " style="height: 20px"></div>
                <div class="wpb_single_image wpb_content_element vc_align_left">
                    @include("Fortuna.Admin::Base.admin-sidebar")
                </div>
                <div class="boc_spacing " style="height: 10px"></div></div></div></div><div class="dark_links wpb_column vc_column_container vc_col-sm-6"><div class="vc_column-inner vc_custom_1472575963813"><div class="wpb_wrapper"><h2 class="boc_heading no_text_transform letter_spacing_negative al_left  "  style="margin-bottom: 20px;margin-top: 0px;font-size: 30px;"><span><strong style="color: #003366;">Show Queries</strong></span></h2>
                <div class="boc_divider_holder"><div class="boc_divider  "  style="margin-top: 10px;margin-bottom: 10px;width: 100px;height: 2px;background: #eeeeee;"></div></div>
                <div class="wpb_text_column wpb_content_element " style="margin-bottom:15px;">
                    <div class="wpb_wrapper">
                        <p>Click to enable query reporting.</p>
                    </div>
                </div>
                <div role="form" class="wpcf7" id="wpcf7-f10-p23915-o1" lang="en-US" dir="ltr">
                    <div class="screen-reader-response"></div>
                    <form class="form-horizontal" method="POST" action="{{ url('/admin/report_queries') }}">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="report-query" @if(!empty(Session::get(App\Modules\Admin\Services\AdminService::ADMIN_SESSION_REPORT_QUERY_KEY))) checked="checked" @endif> Report Queries
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="button  btn_large btn_royalblue btn_rounded icon_pos_before">
                                    <i class="icon icon-paperplane2"></i><span>&nbsp;&nbsp;Save</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div><div class="boc_spacing " style="height: 20px"></div></div></div></div>
</div>