<div id="contact" class="vc_row wpb_row vc_row-fluid" style="padding-top: 20px; margin-top: 20px; margin-bottom: 20px;"><div class="wpb_column vc_column_container vc_col-sm-12"><div class="vc_column-inner "><div class="wpb_wrapper"><div class="boc_spacing " style="height: 0px"></div></div></div></div>
</div>
<div class="vc_row wpb_row vc_row-fluid vc_custom_1427986969472"><div class="dark_links wpb_column vc_column_container vc_col-sm-6"><div class="vc_column-inner vc_custom_1472575963813"><div class="wpb_wrapper"><h2 class="boc_heading no_text_transform letter_spacing_negative al_left  "  style="margin-bottom: 20px;margin-top: 0px;color: #333333;font-size: 30px;"><span>Contact <strong>Us</strong></span></h2><div class="boc_divider_holder"><div class="boc_divider  "  style="margin-top: 20px;margin-bottom: 20px;width: 100px;height: 2px;background: #eeeeee;"></div></div>
                <div class="wpb_text_column wpb_content_element ">
                    <div class="wpb_wrapper">
                        <p>We are a team of professionals who love and take pride in our work. Let&#8217;s work together!</p>

                    </div>
                </div>
                <div role="form" class="wpcf7" id="wpcf7-f10-p23797-o1" lang="en-US" dir="ltr">
                    <div class="screen-reader-response"></div>
                    <form id="bogex-contact-form" action="//{{ Config::get('module.app_segment') }}/contact" method="post" class="wpcf7-form">
                        <p><label> Your Name <span style="color:#ff8e17">*</span><br />
                                <span class="wpcf7-form-control-wrap your-name"><input type="text" name="name" value="" size="40" class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required" aria-required="true" aria-invalid="false" /></span> </label></p>
                        <p><label> Your Email <span style="color:#ff8e17">*</span><br />
                                <span class="wpcf7-form-control-wrap your-email"><input type="email" name="email" value="" size="40" class="wpcf7-form-control wpcf7-text wpcf7-email wpcf7-validates-as-required wpcf7-validates-as-email" aria-required="true" aria-invalid="false" /></span> </label></p>
                        <p><label> Subject<br />
                                <span class="wpcf7-form-control-wrap your-subject"><input type="text" name="subject" value="" size="40" class="wpcf7-form-control wpcf7-text" aria-invalid="false" /></span> </label></p>
                        <p><label> Your Message<br />
                                <span class="wpcf7-form-control-wrap your-message"><textarea name="message" cols="40" rows="10" class="wpcf7-form-control wpcf7-textarea" aria-invalid="false"></textarea></span> </label></p>
                        <p><a href="{{ Config::get('app.url') }}" onclick="sendMessage(); return false;" class="button  btn_large btn_royalblue btn_rounded icon_pos_before"><i class="icon  icon-paperplane2"></i><span>&nbsp;&nbsp;Send</span></a>
                            <img id="bogex-loader" style="margin-left: 10px;" alt="Bogex Loader" class="wpcf7-display-none" src="//{{ Config::get('module.app_segment') }}/fortuna/images/ajax-loader.gif" /><span id="bogex-contact-response" class="wpcf7-display-none" style="font-size: 12px; font-weight: bold; margin-left: 20px;"></span>
                        </p>
                    </form>
                </div>
                <div class="boc_spacing " style="height: 20px"></div></div></div></div><script type='text/javascript' src='//maps.google.com/maps/api/js?key=AIzaSyBizgNcPAW_ssdOFlneiSW3nMZX33wqLBU'></script>
    <div class="wpb_column vc_column_container vc_col-sm-6"><div class="vc_column-inner "><div class="wpb_wrapper"><div class="boc_spacing " style="height: 20px"></div><div id="map_9697" style="height:300px;" class="boc_google_map"></div>
                <script type="text/javascript">
                    var emailInProgress = 0;
                    function sendMessage() {
                        if (emailInProgress == 1) {
                            return;
                        }
                        emailInProgress = 1;

                        var contactForm = jQuery('#bogex-contact-form');
                        jQuery('#bogex-loader').removeClass('wpcf7-display-none');
                        jQuery('#bogex-contact-response').addClass('wpcf7-display-none');
                        jQuery.ajax({
                            type: contactForm.attr('method'),
                            url: contactForm.attr('action'),
                            data: contactForm.serialize(),
                            success: function(data) {
                                jQuery('#bogex-contact-response').removeClass('success');
                                jQuery('#bogex-contact-response').removeClass('warning_msg');
                                if (data.status == 0) {
                                    jQuery('#bogex-contact-response').addClass('warning_msg');
                                    jQuery('#bogex-contact-response').removeClass('wpcf7-display-none');
                                    jQuery('#bogex-contact-response').html(data.messages[Object.keys(data.messages)[0]][0]);
                                }
                                else {
                                    jQuery('#bogex-contact-response').addClass('success');
                                    jQuery('#bogex-contact-response').removeClass('wpcf7-display-none');
                                    jQuery('#bogex-contact-response').html('Your message has been sent. Thank you.');
                                    jQuery('#bogex-contact-form')[0].reset();
                                }

                                emailInProgress = 0;
                                jQuery('#bogex-loader').addClass('wpcf7-display-none');
                            }
                        });
                    }
                </script>
                <script type="text/javascript">
                    jQuery(window).load(function() {

                        var latlng = new google.maps.LatLng(0, 0);
                        var myOptions = {
                            zoom: 14,
                            center: latlng,
                            scrollwheel: false,
                            disableDefaultUI: true,
                            scaleControl: false,
                            panControl: false,
                            draggable: !boc_is_mobile,
                            streetViewControl: false,

                            mapTypeId: google.maps.MapTypeId.ROADMAP
                        };
                        var map_9697 = new google.maps.Map(document.getElementById("map_9697"), myOptions);

                        var geocoder_map_9697 = new google.maps.Geocoder();
                        var address = '775 9th St, Secaucus, NJ 07094';

                        geocoder_map_9697.geocode( { 'address': address}, function(results, status) {
                            if (status == google.maps.GeocoderStatus.OK) {
                                map_9697.setCenter(results[0].geometry.location);

                                var marker = new google.maps.Marker({
                                    map: map_9697,

                                    position: map_9697.getCenter()
                                });

                            } else {
                                alert("Geocode was not successful for the following reason: " + status);
                            }
                        });

                        var marker = new google.maps.Marker({
                            map: map_9697,

                            position: map_9697.getCenter()
                        });
                    });</script><div class="boc_divider_holder"><div class="boc_divider  "  style="margin-top: 20px;margin-bottom: 20px;height: 1px;background: #eeeeee;"></div></div><div id="map_6307" style="height:300px;" class="boc_google_map"></div>
                <script type="text/javascript">
                    jQuery(window).load(function() {

                        var latlng = new google.maps.LatLng(12.968307, 77.684379);
                        var myOptions = {
                            zoom: 14,
                            center: latlng,
                            scrollwheel: false,
                            disableDefaultUI: true,
                            scaleControl: false,
                            panControl: false,
                            draggable: !boc_is_mobile,
                            streetViewControl: false,

                            mapTypeId: google.maps.MapTypeId.ROADMAP
                        };
                        var map_6307 = new google.maps.Map(document.getElementById("map_6307"), myOptions);

                        var geocoder_map_6307 = new google.maps.Geocoder();
                        var address = '';

                        geocoder_map_6307.geocode( { 'location': latlng}, function(results, status) {
                            if (status == google.maps.GeocoderStatus.OK) {
                                map_6307.setCenter(results[0].geometry.location);

                                var marker = new google.maps.Marker({
                                    map: map_6307,

                                    position: map_6307.getCenter()
                                });

                            } else {
                                alert("Geocode was not successful for the following reason: " + status);
                            }
                        });
                    });</script><div class="boc_spacing " style="height: 10px"></div></div></div></div>
</div>