@extends("layout.frontend.master")

@section("content")
    <div class="top-banner">
        <div class="slide">
            <div class="site-content">
                <div class="text-content">
                    <div class="slide-title">
                        As a NRMA customer, save $3000 or more on new utes
                    </div>
                    <span class="end-offer">Exclusive offer ends 30th June 2016</span>
                    <span class="call-button">
                        <a href="<?php echo route('frontend_contact', array( 'promotion_id' => getPromotionKey( isset($showNav2) ? 1 : 0 ) ) ); ?>">MAKE AN ENQUIRY</a>
                    </span>
                </div>
            </div>
            <span class="note">*Available only to NRMA customers in southern NSW. Savings/discounts are off manufacturer RRP.</span>
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
                <p>We understand small business owners and what’s important in buying a new ute.</p>
                <p>Plus, we’re always keen to improve the ways in which we deliver value and make customers like you happy.</p>
                <p>
                    So we’ve used our size and dealer networks to offer you a simpler vehicle purchasing process with
                    fantastic savings. No obligation and no pushy sales staff.
                </p>
            </div>
            <span class="car-logo"></span>
        </div>
    </div>

    <div class="our-range">
        <div class="site-content">
            <h2>Our Range</h2>
            <div class="car-item">
                <div class="top-part">
                    <div class="item-img">
                        <a href="<?php echo route('frontend_product_index', array( 'product_id' => getProductKey(0, 1), 'product_slug' => getProductSlug(0, 1) )); ?>">
                            <img src="{{ asset('frontend/images/holden-small.jpg')}}" />
                        </a>
                    </div>
                    <ul class="item-info">
                        <li class="item-name">
                            <a href="<?php echo route('frontend_product_index', array( 'product_id' => getProductKey(0, 1), 'product_slug' => getProductSlug(0, 1) )); ?>">Holden Colorado LS (4x2)</a>
                        </li>
                        <li class="offer">Offer $36,140</li>
                        <li class="save">Save $2000</li>
                        <li class="motto">It won’t look new</li>
                        <li class="motto">for long.</li>
                    </ul>
                </div>
                <div class="btm-part">
                    <ul class="item-details">
                        <li class="line">2.8L Diesel Turbo</li>
                        <li class="line">3,500kg Towing</li>
                        <li class="line">1,170kg Load</li>
                        <li class="line">7.6litres/100km</li>
                        <li class="line"><a href="<?php echo route('frontend_product_index', array( 'product_id' => getProductKey(0, 1), 'product_slug' => getProductSlug(0, 1) )); ?>" class="more" >MORE</a></li>
                    </ul>
                </div>
            </div>
            <div class="car-item">
                <div class="top-part">
                    <div class="item-img">
                        <a href="<?php echo route('frontend_product_index', array( 'product_id' => getProductKey(0, 2), 'product_slug' => getProductSlug(0, 2) )); ?>">
                            <img src="{{ asset('frontend/images/ford-small.jpg')}}" />
                        </a>
                    </div>
                    <ul class="item-info">
                        <li class="item-name">
                            <a href="<?php echo route('frontend_product_index', array( 'product_id' => getProductKey(0, 2), 'product_slug' => getProductSlug(0, 2) )); ?>">Ford Ranger XL 2.2 HI-RIDER (4x2)</a>
                        </li>
                        <li class="offer">Offer $36,390</li>
                        <li class="save">Save $2000</li>
                        <li class="motto">Built to perform.</li> <br />
                    </ul>
                </div>
                <div class="btm-part">
                    <ul class="item-details">
                        <li class="line">2.2L Diesel Turbo</li>
                        <li class="line">3,500kg Towing</li>
                        <li class="line">1,271kg Load</li>
                        <li class="line">6.9litres/100km</li>
                        <li class="line"><a href="<?php echo route('frontend_product_index', array( 'product_id' => getProductKey(0, 2), 'product_slug' => getProductSlug(0, 2) )); ?>" class="more" >MORE</a></li>
                    </ul>
                </div>
            </div>
            <div class="car-item other-cars-item">
                <div class="top-part">
                    <div class="item-img">
                        <img src="{{ asset('frontend/images/car-icon.png')}}" />
                    </div>
                    <ul class="item-info">
                        <li class="item-name">Want a different model?</li>
                        <li class="save">Save over </li>
                        <li class="save">$3000 on other</li>
                        <li class="save">selected models:</li><br />
                    </ul>
                </div>
                <div class="btm-part">
                    <ul class="item-details">
                        <li class="line">
                            <a href="<?php echo route('frontend_product_index', array( 'product_id' => getProductKey(0, 0), 'product_slug' => getProductSlug(0, 0) )); ?>">Mitsubishi Triton</a>
                        </li>
                        <li class="line">
                            <a href="<?php echo route('frontend_product_index', array( 'product_id' => getProductKey(0, 3), 'product_slug' => getProductSlug(0, 3) )); ?>">Toyota Hilux</a>
                        </li>
                        <li class="line">
                            <a href="<?php echo route('frontend_product_compare', array( 'promotion_id' => getPromotionKey(0) )); ?>" class="view-button more">COMPARE MODELS</a>
                        </li>
                    </ul>
                </div>
            </div>

            <span class="our-range-note margin-bottom">* Based on standard model (chassis, manual) and manufacturer suggested retail price (MSRP)</span>
        </div>
    </div>

    <div class="specifications-chart promotion-chart">
        <span class="title">Want more information?</span>
        <span class="info">We’ve got all the details and specs so you can match your requirements with a new vehicle.</span>
        <a href="<?php echo route('frontend_product_compare', array( 'promotion_id' => getPromotionKey(0) )); ?>" class="view-button">FULL CAR DETAILS</a>
    </div>

    <div class="compare">
        <div class="site-content price-configurator">
            <h2>Personalise your options</h2>
            <div class="left price-config">
                @include('layout.frontend.partials.price-configurator')
            </div>
            <div class="right">
                <div class="ute-item">
                    <div class="ute-img">
                        <a href="<?php echo route('frontend_product_index', array( 'product_id' => getProductKey(0, 1), 'product_slug' => getProductSlug(0, 1) )); ?>">
                            <img src="{{ asset('frontend/images/holden-small.jpg')}}" />
                        </a>
                    </div>
                    <span class="ute-name">Holden Colorado</span>
                    <span class="ute-price ute-price-result" data-attr-decimals="true" data-attr-product="holden"></span>
                </div>
                <div class="ute-item">
                    <div class="ute-img">
                        <a href="<?php echo route('frontend_product_index', array( 'product_id' => getProductKey(0, 2), 'product_slug' => getProductSlug(0, 2) )); ?>">
                            <img src="{{ asset('frontend/images/ford-small.jpg')}}" />
                        </a>
                    </div>
                    <span class="ute-name">Ford Ranger</span>
                    <span class="ute-price ute-price-result" data-attr-decimals="true" data-attr-product="ford"></span>
                </div>
                <div class="other-utes-items">
                    <span class="subtitle">OTHER MODELS</span>
                    <div class="no-image">
                        <img src="{{ asset('frontend/images/car-icon.png')}}" />
                    </div>
                    <div class="ute-item">
                        <div class="ute-img">
                            <a href="<?php echo route('frontend_product_index', array( 'product_id' => getProductKey(0, 0), 'product_slug' => getProductSlug(0, 0) )); ?>">
                                <img src="{{ asset('frontend/images/car-icon.png')}}" />
                            </a>
                        </div>
                        <span class="ute-name">Mitsubishi Triton</span>
                        <span class="ute-price ute-price-result" data-attr-decimals="true" data-attr-product="mitsubishi"></span>
                        {{--$33,490<span class="decimals">.00</span>--}}
                    </div>
                    <div class="ute-item">
                        <div class="ute-img">
                            <a href="<?php echo route('frontend_product_index', array( 'product_id' => getProductKey(0, 3), 'product_slug' => getProductSlug(0, 3) )); ?>">
                                <img src="{{ asset('frontend/images/car-icon.png')}}" />
                            </a>
                        </div>
                        <span class="ute-name">Toyota Hilux</span>
                        <span class="ute-price ute-price-result" data-attr-decimals="true" data-attr-product="toyota"></span>
                    </div>
                </div>
            </div>
        </div>
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
