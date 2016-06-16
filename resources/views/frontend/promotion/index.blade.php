@extends("layout.frontend.master")

@section("content")
    <div class="promotion-index">
        <div class="slide">
            <div class="site-content">
                <div class="text-content">
                    <div class="slide-title">
                        Save $2000 plus on new vehicles
                    </div>
                    <span class="end-offer">Offer ends 30th June 2016</span>
                    <span class="call-button">
                        <a href="<?php echo route('frontend_contact', array( 'promotion_id' => getPromotionKey( $showNav2 ? 1 : 0 ) ) ); ?>">MAKE AN ENQUIRY</a>
                    </span>
                </div>
            </div>
            <span class="note">*Only available to select NRMA customers in southern New South Wales</span>
        </div>
    </div>

    <div class="the-offer">
        <div class="site-content">
            <div class="logo">
                <a href="http://www.nrma.com.au/" target="_blank" >
                    <img src="{{ asset('frontend/images/nrma-logo.png')}}" alt="NRMA Insurance" title="NRMA Insurance" />
                </a>
            </div>
            <h2>Why we built the offer</h2>
            <div class="text-box">
                <p>We understand small business owners and what’s important in buying a new vehicle.</p>
                <p>Plus, we’re always keen to improve the ways in which we deliver value and make customers like you happy.</p>
                <p>
                    We’ve used our size and dealer networks to offer you a simpler vehicle purchasing process with
                    fantastic savings. No obligation and no pushy sales staff.
                </p>
            </div>
            <span class="call-button">
                <?php if ( !isset( $showNav2 ) ): ?>
                    <a href="<?php echo route('frontend_promotion_process', array( 'promotion_id' => getPromotionKey(0) )); ?>">SEE OUR PROCESS</a>
                <?php else: ?>
                    <a href="<?php echo route('frontend_promotion_process', array( 'promotion_id' => getPromotionKey(1) )); ?>">SEE OUR PROCESS</a>
                <?php endif; ?>
            </span>
        </div>
    </div>

    <div class="our-range">
        <div class="site-content">
            <h2>Our Range</h2>
            <div class="car-item">
                <div class="top-part">
                    <div class="item-img">
                        <a href="<?php echo route('frontend_product_index', array( 'product_id' => getProductKey(1, 3), 'product_slug' => getProductSlug(1, 3) )); ?>">
                            <img src="{{ asset('frontend/images/holden-commodore-small.jpg')}}" />
                        </a>
                    </div>
                    <ul class="item-info">
                        <li class="item-name">
                            <a href="<?php echo route('frontend_product_index', array( 'product_id' => getProductKey(1, 3), 'product_slug' => getProductSlug(1, 3) )); ?>">HOLDEN COMMODORE SV6</a>
                        </li><br />
                        <li class="crossed">MSRP $39,490</li>
                        <li class="offer">Offer $39,490</li>
                        <li class="save">Save $2000</li>
                        <li class="motto">Power ahead</li><br />
                    </ul>
                </div>
                <div class="btm-part">
                    <ul class="item-details">
                        <li class="line">3.6L, V6 Petrol</li>
                        <li class="line">9.5 litres/100km</li>
                        <li class="line">210 kW @6,400 rpm</li>
                        <li class="line">5 Seats</li>
                        <li class="line"><a href="<?php echo route('frontend_product_index', array( 'product_id' => getProductKey(1, 3), 'product_slug' => getProductSlug(1, 3) )); ?>" class="more" >MORE</a></li>
                    </ul>
                </div>
            </div>
            <div class="car-item">
                <div class="top-part">
                    <div class="item-img">
                        <a href="<?php echo route('frontend_product_index', array( 'product_id' => getProductKey(1, 4), 'product_slug' => getProductSlug(1, 4) )); ?>"  >
                            <img src="{{ asset('frontend/images/grand-cherokee-small.jpg')}}" />
                        </a>
                    </div>
                    <ul class="item-info">
                        <li class="item-name">
                            <a href="<?php echo route('frontend_product_index', array( 'product_id' => getProductKey(1, 4), 'product_slug' => getProductSlug(1, 4) )); ?>">JEEP GRAND CHEROKEE LIMITED (4x4)</a>
                        </li>
                        <li class="crossed">MSRP $69,000</li>
                        <li class="offer">Offer $69,000</li>
                        <li class="save">Save $2000</li>
                        <li class="motto">Well-equipped at</li>
                        <li class="motto">every level</li>
                    </ul>
                </div>
                <div class="btm-part">
                    <ul class="item-details">
                        <li class="line">3.0L, V6 Turbo Diesel</li>
                        <li class="line">7.5 litres/100km</li>
                        <li class="line">177 kW @4,000 rpm</li>
                        <li class="line">5 Seats</li>
                        <li class="line"><a href="<?php echo route('frontend_product_index', array( 'product_id' => getProductKey(1, 4), 'product_slug' => getProductSlug(1, 4) )); ?>" class="more" >MORE</a></li>
                    </ul>
                </div>
            </div>
            <div class="car-item other-cars-item">
                <div class="top-part">
                    <div class="item-img">
                        <img src="{{ asset('frontend/images/car-icon.png')}}" />
                    </div>
                    <ul class="item-info">
                        <li class="item-name margin">Want a different model?</li>
                        <li class="save">Save up to</li>
                        <li class="save">$2000 on other</li>
                        <li class="save">selected models:</li><br />
                    </ul>
                </div>
                <div class="btm-part">
                    <ul class="item-details">
                        <li class="line">
                            <a href="<?php echo route('frontend_product_index', array( 'product_id' => getProductKey(1, 0), 'product_slug' => getProductSlug(1, 0) )); ?>">Toyota Land Cruiser</a>
                        </li>
                        <li class="line">
                            <a href="<?php echo route('frontend_product_index', array( 'product_id' => getProductKey(1, 1), 'product_slug' => getProductSlug(1, 1) )); ?>">Toyota Prado</a>
                        </li>
                        <li class="line">
                            <a href="<?php echo route('frontend_product_index', array( 'product_id' => getProductKey(1, 2), 'product_slug' => getProductSlug(1, 2) )); ?>">Toyota Kluger</a>
                        </li>
                        <li class="line">
                            <a href="<?php echo route('frontend_product_compare', array( 'promotion_id' => getPromotionKey(1) )); ?>" class="view-button more">COMPARE MODELS</a>
                        </li>
                    </ul>
                </div>
            </div>
            <span class="our-range-note margin-bottom">* Based on standard model (chassis, manual)</span>
        </div>
    </div>
    <!--
    <div class="compare">
        <div class="site-content large-width price-configurator">
            <h2>Personalise your options</h2>
            <div class="left price-config">
                @include('layout.frontend.partials.price-configurator')
            </div>
            <div class="right">
                <div class="row">
                    <div class="ute-item">
                        <div class="ute-img">
                            <a href="<?php echo route('frontend_product_index', array( 'product_id' => getProductKey(1, 0), 'product_slug' => getProductSlug(1, 0) )); ?>">
                                <img src="{{ asset('frontend/images/land-cruiser-gxl-small.jpg')}}" />
                            </a>
                        </div>
                        <span class="ute-name">TOYOTA<br />LAND CRUISER GXL</span>
                        <span class="ute-price ute-price-result" data-attr-decimals="true" data-attr-product="toyota-land-cruiser"></span>
                        {{--$33,490<span class="decimals">.00</span>--}}
                    </div>
                    <div class="ute-item">
                        <div class="ute-img">
                            <a href="<?php echo route('frontend_product_index', array( 'product_id' => getProductKey(1, 1), 'product_slug' => getProductSlug(1, 1) )); ?>">
                                <img src="{{ asset('frontend/images/toyota-prado-gxl-small.jpg')}}" />
                            </a>
                        </div>
                        <span class="ute-name">TOYOTA<br />PRADO GXL</span>
                        <span class="ute-price ute-price-result" data-attr-decimals="true" data-attr-product="toyota-prado"></span>
                    </div>
                    <div class="ute-item">
                        <div class="ute-img">
                            <a href="<?php echo route('frontend_product_index', array( 'product_id' => getProductKey(1, 2), 'product_slug' => getProductSlug(1, 2) )); ?>">
                                <img src="{{ asset('frontend/images/toyota-kluger-small.jpg')}}" />
                            </a>
                        </div>
                        <span class="ute-name">TOYOTA<br />KLUGER GXL</span>
                        <span class="ute-price ute-price-result" data-attr-decimals="true" data-attr-product="toyota-kluger"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="ute-item">
                        <div class="ute-img">
                            <a href="<?php echo route('frontend_product_index', array( 'product_id' => getProductKey(1, 3), 'product_slug' => getProductSlug(1, 3) )); ?>">
                                <img src="{{ asset('frontend/images/holden-commodore-small.jpg')}}" />
                            </a>
                        </div>
                        <span class="ute-name">HOLDEN<br />COMMODORE</span>
                        <span class="ute-price ute-price-result" data-attr-decimals="true" data-attr-product="holden-commodore"></span>
                    </div>
                    <div class="ute-item">
                        <div class="ute-img">
                            <a href="<?php echo route('frontend_product_index', array( 'product_id' => getProductKey(1, 4), 'product_slug' => getProductSlug(1, 4) )); ?>">
                                <img src="{{ asset('frontend/images/grand-cherokee-small.jpg')}}" />
                            </a>
                        </div>
                        <span class="ute-name">JEEP<br />GRAND CHEROKEE</span>
                        <span class="ute-price ute-price-result" data-attr-decimals="true" data-attr-product="jeep-cherokee"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    -->
    <div class="specifications-chart promotion-chart">
        <span class="title">Want more information?</span>
        <span class="info">We’ve got all the details and specs so you can match your requirements with a new vehicle.</span>
        <a href="<?php echo route('frontend_product_compare', array( 'promotion_id' => getPromotionKey(1) )); ?>" class="view-button">FULL CAR DETAILS</a>
    </div>

    <div class="insurance-brands">
        <div class="site-content">
            <h2>NRMA Members offers</h2>
            <h3>Part of the iag family</h3>
            <p class="intro">
                IAG is Australia’s largest general insurance provider and the name behind some of the country’s most respected and trusted insurance brands,
                including NRMA Insurance and Swann Insurance.
            </p>
            <div class="brands">
                <div class="brand-col">
                    <div class="brand-picture">
                        <a href="http://www.iag.com.au/" target="_blank" >
                            <img src="{{ asset('frontend/images/iag-logo.png')}}" alt="IAG" title="IAG" />
                        </a>
                    </div>
                    <span class="brand-description">
                        Underwrite over $11.4 billion of premium per annum
                    </span>
                </div>
                <div class="brand-col">
                    <div class="brand-picture">
                        <a href="http://www.nrma.com.au/" target="_blank" >
                            <img src="{{ asset('frontend/images/nrma-logo.png')}}" alt="NRMA Insurance" title="NRMA Insurance" />
                        </a>
                    </div>
                    <span class="brand-description">
                        Insurance protection for over half a million properties, one million motor vehicles
                    </span>
                </div>
                <div class="brand-col">
                    <div class="brand-picture">
                        <a href="https://www.swanninsurance.com.au/home/" target="_blank" >
                            <img src="{{ asset('frontend/images/swann-logo.png')}}" alt="Swann Insurance" title="Swann Insurance" />
                        </a>
                    </div>
                    <span class="brand-description">
                        Leading provider of motorcycle insurance - over 750,000 customers
                    </span>
                </div>
            </div>
        </div>
    </div>

    @include('layout.frontend.partials.call-us-banner')
@stop
