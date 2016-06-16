@extends("layout.frontend.master")

@section("content")
    <div class="about-intro">
        <div class="site-content">
            <h2>Our simple process</h2>
            <div class="text-box">
                Getting the keys to a new vehicle that suits your needs is simple.
            </div>
            <div class="our-process">
                <div class="row">
                    <div class="box blue">
                        <div class="center">
                            <span class="title">WE HAGGLE THE DEAL</span>
                            <p>
                                We identify the best cars for small business owners and
                                then roll up our sleeves and negotiate the best price for
                                you through our network of dealerships.
                            </p>
                        </div>
                    </div>
                    <div class="box fig">
                        <img src="{{ asset('frontend/images/process-img-1.jpg')}}" alt="Our Process" title="Our Process" />
                    </div>
                </div>
                <div class="row">
                    <div class="box blue">
                        <div class="center">
                            <span class="title">DO YOUR RESEARCH</span>
                            <p>
                                We’ve also compiled the vehicles’ key info and specs so you can
                                research and compare your options. Need something further?
                                We’re just a phone call away and will make sure you have all
                                the facts you need to decide which vehicle is right for you.
                            </p>
                        </div>
                    </div>
                    <div class="box fig">
                        <img src="{{ asset('frontend/images/process-img-2.jpg')}}" alt="Our Process" title="Our Process" />
                    </div>
                </div>
                <div class="row">
                    <div class="box blue">
                        <div class="center">
                            <span class="title">ORGANISE A TEST DRIVE</span>
                            <p>
                                Get in contact and we’ll sort out a test drive. We’ll also go
                                through the purchasing process and answer any questions you
                                have – without the smothering sales pitch.
                            </p>
                        </div>
                    </div>
                    <div class="box fig">
                        <img src="{{ asset('frontend/images/process-img-3.jpg')}}" alt="Our Process" title="Our Process" />
                    </div>
                </div>
                <div class="row">
                    <div class="box blue">
                        <div class="center">
                            <span class="title">FINALISE THE PURCHASE & DRIVE AWAY</span>
                            <p>
                                Once you’ve decided on a vehicle, you just need to confirm
                                your finance and insurance and it will be ready to drive
                                out of your local dealership.
                            </p>
                        </div>
                    </div>
                    <div class="box fig">
                        <img src="{{ asset('frontend/images/process-img-4.jpg')}}" alt="Our Process" title="Our Process" />
                    </div>
                </div>
            </div>
            <div class="make-enquiry-btn process">
                <a href="<?php echo route('frontend_contact', array( 'promotion_id' => isset( $showNav2 ) ? getPromotionKey(1) : getPromotionKey(0) )); ?>">MAKE AN ENQUIRY</a>
            </div>
        </div>
    </div>

    @include('layout.frontend.partials.call-us-banner')
@stop
