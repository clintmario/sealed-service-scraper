<div id="testimonials" data-vc-full-width="true" data-vc-full-width-init="false" class="vc_row wpb_row vc_row-fluid white_text">
    <div class="no_side_padding_in_responsive_column wpb_column vc_column_container vc_col-sm-12"><div class="vc_column-inner vc_custom_1426426069052"><div class="wpb_wrapper"><h2 class="boc_heading  center  "  style="margin-bottom: 28px;margin-top: 0px;color: #ffffff;"><span>What Our <strong>Clients</strong> Say</span></h2><div class="boc_divider_holder"><div class="boc_divider  "  style="margin-top: 20px;margin-bottom: 20px;width: 60px;margin-left: auto; margin-right: auto;height: 2px;background: #f4f4f4;"></div></div><div class="boc_spacing " style="height: 20px"></div><!-- Testimonials -->
                <div class="testimonials">
                    <div id="testimonial_carousel_74214" style="opacity: 0;" class="testimonials_carousel  owl_has_dot_nav testimonial_style_big">	<div class="testimonial_quote boc_owl_lazy">
                            <div class="quote_content">
                                <p>&#8220;Bogex LLC plays an integral role in Grovo's day-to-day development. They have contributed to the largest release in our company&#8217;s history.&#8221;</p>
                                <span class="quote_arrow"></span>
                            </div>
                            <div class="quote_author heading_font"><img width="400" height="400" src="//{{ Config::get('module.app_segment') }}/fortuna/images/jeff-f.jpg" class="attachment-full size-full" alt="jeff-f" /><div class="icon_testimonial">Jeff Fernandez</div><span class="quote_author_description">CEO, Grovo</span>	</div>
                        </div>	<div class="testimonial_quote boc_owl_lazy">
                            <div class="quote_content">
                                <p>&#8220;The Bogex team was an absolute pleasure to work with. Clint and his team understood our needs, added value to the planning process and implemented our vision of the product &#8211; they exceeded our expectations on all fronts.&#8221;</p>
                                <span class="quote_arrow"></span>
                            </div>
                            <div class="quote_author heading_font"><img width="140" height="140" src="//{{ Config::get('module.app_segment') }}/fortuna/images/dana-l.jpg" class="attachment-full size-full" alt="dana-l" /><div class="icon_testimonial">Dana Lampert</div><span class="quote_author_description">CEO, Wiggio</span>	</div>
                        </div>	<div class="testimonial_quote boc_owl_lazy">
                            <div class="quote_content">
                                <p>&#8220;I have known Clint and Bogex since 2006. Clint has taken total responsibility of the work and helped Ariem to build the web technology, infrastructure and the team. Anyday I will recommend Bogex for partnership.&#8221;</p>
                                <span class="quote_arrow"></span>
                            </div>
                            <div class="quote_author heading_font"><img width="200" height="200" src="//{{ Config::get('module.app_segment') }}/fortuna/images/ravi-b.jpg" class="attachment-full size-full" alt="ravi-b" /><div class="icon_testimonial">Ravi Bhat</div><span class="quote_author_description">CEO, Ariem</span>	</div>
                        </div>	<div class="testimonial_quote boc_owl_lazy">
                            <div class="quote_content">
                                <p>&#8220;Bogex has been integral to the continued development of our site and the advancement of our company &#8211; all of the features that I believed to be important and unique to the project were integrated.&#8221;</p>
                                <span class="quote_arrow"></span>
                            </div>
                            <div class="quote_author heading_font"><img width="200" height="200" src="//{{ Config::get('module.app_segment') }}/fortuna/images/yao-t.jpg" class="attachment-full size-full" alt="yao-t" /><div class="icon_testimonial">Yao Tyus</div><span class="quote_author_description">President, SteppingStones</span>	</div>
                        </div>	<div class="testimonial_quote boc_owl_lazy">
                            <div class="quote_content">
                                <p>&#8220;Bogex has helped me to both start and develop my business.&#8221;</p>
                                <span class="quote_arrow"></span>
                            </div>
                            <div class="quote_author heading_font"><img width="333" height="333" src="//{{ Config::get('module.app_segment') }}/fortuna/images/damien-i.jpg" class="attachment-full size-full" alt="damien-i" /><div class="icon_testimonial">Damien Isabella</div><span class="quote_author_description">CEO, Seussi</span>	</div>
                        </div></div>
                </div>
                <!-- Testimonials::END -->

                <!-- Testimonials Carousel JS -->
                <script type="text/javascript">

                    jQuery(document).ready(function($) {

                        var carousel = $("#testimonial_carousel_74214");

                        var args = {
                            items: 1,
                            autoplay:			true,
                            autoplayTimeout:	8000,
                            loop: 				true,
                            nav: 				true,
                            dots: 				true,
                            autoHeight: 			false,
                            navText:				["<span class='icon  icon-angle-left-circle'></span>","<span class='icon icon-angle-right-circle'></span>"],
                            navRewind: false,
                            rtl: 				false,
                            smartSpeed:			600,
                            margin: 				10,
                            animateOut: 		false,
                            animateIn: 			false,
                            onInitialized:  		bocShowTestimonialCarousel_74214
                        };

                        carousel.owlCarousel(args);

                        var initital_width = carousel.css("width");

                        /* Refresh it for full width rows */
                        $(window).load(function(){
                            if(carousel.css("width") != initital_width) {
                                carousel.trigger("destroy.owl.carousel").removeClass("owl-carousel owl-loaded");
                                carousel.find(".owl-stage-outer").children().unwrap();
                                carousel.owlCarousel(args);
                            }
                        });

                        /* Show once loaded */
                        function bocShowTestimonialCarousel_74214(){
                            carousel.fadeTo(0,1);
                            jQuery("#testimonial_carousel_74214 .owl-item .boc_owl_lazy").css("opacity","1");
                        }

                    });

                </script>
                <!-- Testimonials Carousel JS: END -->


            </div></div></div>
</div>
<div class="vc_row-full-width vc_clearfix"></div>
<div class="upb_bg_img" data-ultimate-bg="url(//{{ Config::get('module.app_segment') }}/fortuna/images/dude-laptop.jpg)" data-image-id="23530" data-ultimate-bg-style="vcpb-vz-jquery" data-bg-img-repeat="no-repeat" data-bg-img-size="cover" data-bg-img-position="" data-parallx_sense="50" data-bg-override="0" data-bg_img_attach="scroll" data-upb-overlay-color="rgba(12,13,13,0.7)" data-upb-bg-animation="" data-fadeout="" data-bg-animation="left-animation" data-bg-animation-type="h" data-animation-repeat="repeat" data-fadeout-percentage="30" data-parallax-content="" data-parallax-content-sense="30" data-row-effect-mobile-disable="true" data-img-parallax-mobile-disable="false" data-rtl="false"  data-custom-vc-row=""  data-vc="4.12"  data-is_old_vc=""  data-theme-support=""   data-overlay="true" data-overlay-color="rgba(12,13,13,0.7)" data-overlay-pattern="" data-overlay-pattern-opacity="0.5" data-overlay-pattern-size="" data-overlay-pattern-attachment="fixed"    >
</div>