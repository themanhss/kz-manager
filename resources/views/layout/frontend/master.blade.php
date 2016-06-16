<!DOCTYPE html>
<html dir="ltr" lang="en-US" id="full-page">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, maximum-scale=1" />
    <meta name="_token" content="{{ Session::token() }}" />

    <title>IAG</title>
    <link rel="shortcut icon" href="/favicon.ico">
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/stylesheets/css/application.min.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/stylesheets/css/fonts/fonts.css')}}" />

    <script type="text/javascript" src="{{ asset('frontend/js/jquery-1.9.0.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('frontend/js/visitor.js')}}"></script>
    <script type="text/javascript" src="{{ asset('frontend/js/global.js')}}"></script>

    <script type="text/javascript">
        var productComparisonUrl = '<?php echo route('frontend_product_comparison'); ?>';
        var productComparisonUrl2 = '<?php echo route('frontend_product_comparison2'); ?>';

        $(document).ready(function(){
            var helper = $('<div/>', { 'text': $(window).width() });
            helper.css({
                'position': 'fixed',
                'right': '50%',
                'top': '0px',
                'z-index': 2132132131,
                'font-size': '20px',
                'padding': '10px',
                'background': '#000',
                'color': '#fff'
            });

//            $('body').append(helper);
            $(window).on('resize', function(){
                helper.text($(window).width());
            });
        });
    </script>

    <script type='text/javascript'>
        (function (d, t) {
            var bh = d.createElement(t), s = d.getElementsByTagName(t)[0];
            bh.type = 'text/javascript';
            bh.src = 'https://www.bugherd.com/sidebarv2.js?apikey=cbpftpptuiv2lcgqwcr46w';
            s.parentNode.insertBefore(bh, s);
        })(document, 'script');
    </script>

    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-77190861-1', 'auto');
        ga('send', 'pageview');

    </script>

    <!-- Start of Woopra Code -->
    <script>
        (function(){
            var t,i,e,n=window,o=document,a=arguments,s="script",r=["config","track","identify","visit","push","call","trackForm","trackClick"],c=function(){var t,i=this;for(i._e=[],t=0;r.length>t;t++)(function(t){i[t]=function(){return i._e.push([t].concat(Array.prototype.slice.call(arguments,0))),i}})(r[t])};for(n._w=n._w||{},t=0;a.length>t;t++)n._w[a[t]]=n[a[t]]=n[a[t]]||new c;i=o.createElement(s),i.async=1,i.src="//static.woopra.com/js/w.js",e=o.getElementsByTagName(s)[0],e.parentNode.insertBefore(i,e)
        })("woopra");

        <?php $woopraDomain = config('app.woopra_domain'); ?>

        woopra.config({
            domain: "<?php echo !is_null($woopraDomain) && is_string($woopraDomain) && trim($woopraDomain) != '' ? $woopraDomain : 'iag.saltandfuessel.com.au'; ?>"
        });

        woopra.track();
    </script>
    <!-- End of Woopra Code -->

</head>

<body>

<!-- WEB PAGE -->

<section id="page">

    <!-- HEADER -->
    <header id="header">
        <div class="site-content promo-2-header clearfix">
            <h1 id="logo">
                <?php if ( !isset( $showNav2 ) ): ?>
                    <a href="<?php echo route('frontend_promotion_index', [ getPromotionSlug(0), getPromotionKey(0) ]); ?>">
                        <img src="{{ asset('frontend/images/customer-specials-logo.png')}}" alt="NRMA Insurance" title="NRMA Insurance" />
                    </a>
                <?php else: ?>
                    <a href="<?php echo route('frontend_promotion_index', [ getPromotionSlug(1), getPromotionKey(1) ]); ?>">
                        <img src="{{ asset('frontend/images/customer-specials-logo.png')}}" alt="NRMA Insurance" title="NRMA Insurance" />
                    </a>
                <?php endif; ?>
            </h1>
            <div class="right-side">
                <div class="enquire-popup">
                    <a href="tel:1300 768 347">enquire now 1300 768 347</a>
                </div>
                <div id="top-nav-toggle" class="toggle">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>

                <?php if ( !isset( $showNav2 ) ): ?>
                    <ul id="toggle-nav" class="top-navigation nav-promo-1">
                        <li>
                            <a href="<?php echo route('frontend_promotion_index', [ getPromotionSlug(0), getPromotionKey(0) ]); ?>" class="hide">Home</a>
                        </li>
                        <li>
                            <a href="<?php echo route('frontend_product_index', array( 'product_id' => getProductKey(0, 1), 'product_slug' => getProductSlug(0, 1) )); ?>">Holden Colorado</a>
                        </li>
                        <li>
                            <a href="<?php echo route('frontend_product_index', array( 'product_id' => getProductKey(0, 2), 'product_slug' => getProductSlug(0, 2) )); ?>">Ford Ranger</a>
                        </li>
                        <li class="has-submenu">
                            Other Utes
                            <div class="sub-menu-wrapper">
                                <ul class="sub-menu">
                                    <li>
                                        <a href="<?php echo route('frontend_product_index', array( 'product_id' => getProductKey(0, 0), 'product_slug' => getProductSlug(0, 0) )); ?>">Mitsubishi Triton</a>
                                    </li>
                                    <li>
                                        <a href="<?php echo route('frontend_product_index', array( 'product_id' => getProductKey(0, 3), 'product_slug' => getProductSlug(0, 3) )); ?>">Toyota Hilux</a>
                                    </li>
                                 </ul>
                            </div>
                        </li>
                        <li>
                            <a href="<?php echo route('frontend_promotion_process', array( 'promotion_id' => getPromotionKey(0) )); ?>">Process</a>
                        </li>
                        <li>
                            <a href="<?php echo route('frontend_about', array( 'promotion_id' => getPromotionKey(0) )); ?>" class="hide">About</a>
                        </li>
                        <li>
                            <a href="<?php echo route('frontend_contact', array( 'promotion_id' => getPromotionKey(0) )); ?>" class="hide">Contact</a>
                        </li>
                    </ul>
                <?php else: ?>
                    <ul id="toggle-nav" class="top-navigation nav-promo-2">
                        <li>
                            <a href="<?php echo route('frontend_promotion_index', [ getPromotionSlug(1), getPromotionKey(1) ]); ?>" class="hide">Home</a>
                        </li>
                        <li>
                            <a href="<?php echo route('frontend_product_index', array( 'product_id' => getProductKey(1, 3), 'product_slug' => getProductSlug(1, 3) )); ?>">Holden Commodore</a>
                        </li>
                        <li>
                            <a href="<?php echo route('frontend_product_index', array( 'product_id' => getProductKey(1, 4), 'product_slug' => getProductSlug(1, 4) )); ?>">Jeep Grand Cherokee</a>
                        </li>
                        <li class="has-submenu">
                            Other Cars
                            <div class="sub-menu-wrapper">
                                <ul class="sub-menu">
                                    <li>
                                        <a href="<?php echo route('frontend_product_index', array( 'product_id' => getProductKey(1, 0), 'product_slug' => getProductSlug(1, 0) )); ?>">Toyota Land Cruiser</a>
                                    </li>
                                    <li>
                                        <a href="<?php echo route('frontend_product_index', array( 'product_id' => getProductKey(1, 1), 'product_slug' => getProductSlug(1, 1) )); ?>">Toyota Prado</a>
                                    </li>
                                    <li>
                                        <a href="<?php echo route('frontend_product_index', array( 'product_id' => getProductKey(1, 2), 'product_slug' => getProductSlug(1, 2) )); ?>">Toyota Kluger</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li>
                            <a href="<?php echo route('frontend_promotion_process', array( 'promotion_id' => getPromotionKey(1) )); ?>">Process</a>
                        </li>
                        <li>
                            <a href="<?php echo route('frontend_about', array( 'promotion_id' => getPromotionKey(1) )); ?>" class="hide">About</a>
                        </li>
                        <li>
                            <a href="<?php echo route('frontend_contact', array( 'promotion_id' => getPromotionKey(1) )); ?>" class="hide">Contact</a>
                        </li>
                    </ul>
                <?php endif; ?>
                <a href="<?php echo route('frontend_contact', array( 'promotion_id' => isset( $showNav2 ) ? getPromotionKey(1) : getPromotionKey(0) )); ?>" class="sticky-enquiry">MAKE ENQUIRY</a>
                <a href="tel:1300 768 347" class="sticky-call">CALL 1300 768 347</a>
            </div>
        </div>
    </header>
    <!-- END HEADER -->

    @yield('content')

    <!-- FOOTER -->
    <footer id="footer">
        <div class="site-content clearfix">
            <h2 id="footer-logo">
                <?php if ( !isset( $showNav2 ) ): ?>
                    <a href="<?php echo route('frontend_promotion_index', [ getPromotionSlug(0), getPromotionKey(0) ]); ?>">
                        <img src="{{ asset('frontend/images/nrma-logo.png')}}" alt="NRMA Insurance" title="NRMA Insurance" />
                    </a>
                <?php else: ?>
                    <a href="<?php echo route('frontend_promotion_index', [ getPromotionSlug(1), getPromotionKey(1) ]); ?>">
                        <img src="{{ asset('frontend/images/nrma-logo.png')}}" alt="NRMA Insurance" title="NRMA Insurance" />
                    </a>
                <?php endif; ?>
            </h2>
            <div id="bottom-nav">
                <div class="footer-col">
                    <ul>
                        <li>Company</li>
                        <?php if ( !isset( $showNav2 ) ): ?>
                            <li>
                                <a href="<?php echo route('frontend_about', array( 'promotion_id' => getPromotionKey(0) )); ?>">About</a>
                            </li>
                            <li>
                                <a href="<?php echo route('frontend_contact', array( 'promotion_id' => getPromotionKey(0) )); ?>">Contact</a>
                            </li>
                        <?php else: ?>
                            <li>
                                <a href="<?php echo route('frontend_about', array( 'promotion_id' => getPromotionKey(1) )); ?>">About</a>
                            </li>
                            <li>
                                <a href="<?php echo route('frontend_contact', array( 'promotion_id' => getPromotionKey(1) )); ?>">Contact</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
                <div class="footer-col">
                    <ul>
                        <li>Vehicles</li>
                        <?php if ( !isset( $showNav2 ) ): ?>
                            <li>
                                <a href="<?php echo route('frontend_product_index', array( 'product_id' => getProductKey(0, 1), 'product_slug' => getProductSlug(0, 1) )); ?>">Holden Colorado</a>
                            </li>
                            <li>
                                <a href="<?php echo route('frontend_product_index', array( 'product_id' => getProductKey(0, 2), 'product_slug' => getProductSlug(0, 2) )); ?>">Ford Ranger</a>
                            </li>
                            <li>
                                <a href="<?php echo route('frontend_product_index', array( 'product_id' => getProductKey(0, 0), 'product_slug' => getProductSlug(0, 0) )); ?>">Mitsubishi Triton</a>
                            </li>
                            <li>
                                <a href="<?php echo route('frontend_product_index', array( 'product_id' => getProductKey(0, 3), 'product_slug' => getProductSlug(0, 3) )); ?>">Toyota Hilux</a>
                            </li>
                        <?php else: ?>
                            <li>
                                <a href="<?php echo route('frontend_product_index', array( 'product_id' => getProductKey(1, 3), 'product_slug' => getProductSlug(1, 3) )); ?>">Holden Commodore</a>
                            </li>
                            <li>
                                <a href="<?php echo route('frontend_product_index', array( 'product_id' => getProductKey(1, 4), 'product_slug' => getProductSlug(1, 4) )); ?>">Jeep Grand Cherokee</a>
                            </li>
                            <li>
                                <a href="<?php echo route('frontend_product_index', array( 'product_id' => getProductKey(1, 0), 'product_slug' => getProductSlug(1, 0) )); ?>">Toyota Land Cruiser</a>
                            </li>
                            <li>
                                <a href="<?php echo route('frontend_product_index', array( 'product_id' => getProductKey(1, 1), 'product_slug' => getProductSlug(1, 1) )); ?>">Toyota Prado</a>
                            </li>
                            <li>
                                <a href="<?php echo route('frontend_product_index', array( 'product_id' => getProductKey(1, 2), 'product_slug' => getProductSlug(1, 2) )); ?>">Toyota Kluger</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
                <div class="footer-col">
                    <p>About the offer</p>
                    <p>
                        The offer on utilities (utes) is available only to select NRMA Insurance customers in southern New South Wales from participating
                        Swann authorised Intermediaries. To avail themselves of the offer, eligible NRMA customers must contact the Swann Insurance contact
                        number provided in this website. However, neither NRMA nor Swann nor their related bodies corporate shall bear any liability in
                        respect of any aspect of the offer.
                    </p>
                </div>
            </div>
        </div>
        <div class="we-are-here">
            <div class="site-content">
                <span class="we-are-here-title">We’re here to talk.</span>
                <p>Call us between Monday - Friday,<br class="display-br" /> 9am - 5pm,<br class="display-br" /> on <a href="tel:1300 768 347" class="call-link">1300 768 347</a></p>
            </div>
        </div>
        <div class="copyright">
            <span class="copy"> &copy; 2016 IAG Insurance Australia Group.<br class="display-br" /> All rights reserved.</span>
            <span class="privacy">
                <a href="javascript: void(0)" class="privacy open-privacy">Privacy</a>
            </span>
            <span class="separator"></span>
            <span class="terms">
                <a href="javascript: void(0)" class="terms open-terms">Terms</a>
            </span>
        </div>
    </footer>
    <!-- END FOOTER -->

</section>
<!-- END WEB PAGE -->

<div id="popup-overlay"></div>
@include('layout.frontend.partials.popup-compare')
@include('layout.frontend.partials.popup-compare-spec-chart')
@include('layout.frontend.partials.popup-privacy')
@include('layout.frontend.partials.popup-terms')
@include('layout.frontend.partials.request-call-popup')
@include('layout.frontend.partials.email-requested')

</body>

</html>

