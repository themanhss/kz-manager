@extends("layout.frontend.master")

@section("content")
<div class="ute-page">
    <div class="ute-page-banner">
        <div class="content prado">
            <div class="site-content">
                <div class="text-content">
                    <div class="title">
                        TOYOTA PRADO PRADO GXL (4x4)
                    </div>
                    <span class="slogan">Combine work ethic with a touch of class</span>
                </div>
            </div>
        </div>
    </div>

    <div class="compare inside-page mitsubishi prado">
        <div class="site-content">
            <div class="left price-config">

                @include('layout.frontend.partials.price-configurator-2')

                <div class="row">
                    <span class="label">Colour</span>
                    <div class="options" id="options-color">
                        <span class="option active">Glacier White</span>
                        <span class="option">Ebony</span>
                    </div>
                </div>
            </div>
            <div class="right">
                <span class="price ute-price-result" data-attr-product="<?php echo $productSlug; ?>"></span>
                <div class="info">
                    <p>A great offer with experts ready and available to talk you through your needs.</p>
                    <p>A hassle free and no obligation enquiry process.</p>
                    <p>Offer ends 30th June 2016</p>
                </div>
                <span class="save">(Save $2,200 on manufacturer RRP)</span>
                <span class="call-button enquiry">
                    <a href="<?php echo route('frontend_contact', array( 'promotionId' => $promotionKey )); ?>">MAKE AN ENQUIRY</a>
                </span>
                <span class="call-button chat">
                    <a href="<?php echo route('frontend_product_printing'); ?>" target="_blank" class="print-summary-button">PRINT OFFER</a>
                </span>
            </div>
        </div>
    </div>

    <div class="specifications">
        <div class="intro">
            <div class="site-content">
                <h2>Specifications</h2>
                <div class="text-box">
                    Functionality and practicality are important. Here are a few of the vital specs to get you started and
                    make sure you get into the right ride.
                </div>
            </div>
        </div>
        <div class="specs-details">
            <div class="site-content">
                <div class="main-content">
                    <div class="ute-illustration">
                        <img src="{{ asset('frontend/images/prado-diagram.png')}}" />
                    </div>
                    <div class="details-list">
                        <div class="item">
                            <span class="ute-atribute">Engine</span>
                            <span class="value">2.8L, 4 cylinder Turbo Diesel</span>
                        </div>
                        <div class="item">
                            <span class="ute-atribute">No of Seats</span>
                            <span class="value">7</span>
                        </div>
                        <div class="item">
                            <span class="ute-atribute">Transmission</span>
                            <span class="value">6 SP AUTOMATIC</span>
                        </div>
                        <div class="item">
                            <span class="ute-atribute">Fuel Use</span>
                            <span class="value">8.0 litres/100km</span>
                        </div>
                        <div class="item">
                            <span class="ute-atribute">Warranty</span>
                            <span class="value">3 Yrs, 100,000 km</span>
                        </div>
                        <div class="item">
                            <span class="ute-atribute">Max Torque</span>
                            <span class="value">450 Nm @1,600 rpm</span>
                        </div>
                        <div class="item">
                            <span class="ute-atribute">Max Power</span>
                            <span class="value">130 kW @3,400 rpm</span>
                        </div>
                        <div class="item">
                            <span class="ute-atribute">Towing Capacity</span>
                            <span class="value">2,500 kg</span>
                        </div>
                    </div>
                    <div class="specifications-chart">
                        <p>Compare all models using our specifications chart</p>
                        <a href="javascript: void(0);" class="view-button open-spec-chart">VIEW</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="got-questions">
        <div class="site-content">
            <h2>Got Questions?</h2>
            <div class="main-content">
                <div class="qa-section">

                    <div class="accordion-question">
                        <p>
                            <a class="question">
                                1. How do I organise a test drive?
                                <span class="open">+</span>
                            </a>
                        </p>
                    </div>
                    <div class="accordion-answer">
                        <p>
                            <a class="answer">
                                Call and speak to one of our friendly customer service representatives.
                                They’ll put you in contact with your nearest dealership and organise a ute for you to drive.
                                <span class="close">-</span>
                            </a>
                        </p>
                    </div>
                    <div class="accordion-question">
                        <p>
                            <a  class="question">
                                2. Are different options and colours available?
                                <span class="open">+</span>
                            </a>
                        </p>
                    </div>
                    <div class="accordion-answer">
                        <p>
                            <a class="answer">
                                Absolutely. The dealer will help you tailor your ute to suit your preferences.
                                <span class="close">-</span>
                            </a>
                        </p>
                    </div>
                    <div class="accordion-question">
                        <p>
                            <a class="question">
                                3. How can I arrange insurance and finance?
                                <span class="open">+</span>
                            </a>
                        </p>
                    </div>
                    <div class="accordion-answer">
                        <p>
                            <a class="answer">
                                When you make an enquiry we can go over your insurance options to ensure you’ll have the coverage
                                that’s just right for your circumstances. Financing is arranged directly with the dealer.
                                <span class="close">-</span>
                            </a>
                        </p>
                    </div>

                </div>
                <div class="question-icon">
                    <img src="{{ asset('frontend/images/question-icon.png')}}" />
                </div>
            </div>
        </div>
    </div>

    <div class="research">
        <div class="site-content">
            <h2>Our independent research</h2>
            <div class="text-box">
                At our hands on Research Centre we pull apart and crash cars, as well as analyse a variety of data.
                Take a look at some of our key insights on the Toyota Prado.
            </div>
            <div class="details-box">
                <div class="site-content">
                    <div class="ute-image">
                        <img src="{{ asset('frontend/images/crash_vehicle_square.jpg')}}" />
                    </div>
                    <div class="details-info">
                        <span class="line"><strong>5</strong> - ANCAP Crash safety rating (out of 5)</span>
                        <span class="line"><strong>8.0</strong> - Fuel consumption (L/100)</span>
                        <span class="line"><strong>4.5</strong> - Green rating (out of 10)</span>
                        <span class="line"><strong>$2,274</strong> - Est. annual fuel cost (18,350km @ $1.30/L)</span>
                        <span class="line"><strong>5</strong> - Car security rating (out of 5)</span>
                    </div>
                </div>
            </div>
            <div class="specifications-chart">
                <p>Compare all models using our specifications chart</p>
                <a href="javascript: void(0)" class="view-button open-spec-chart">VIEW</a>
            </div>
        </div>
    </div>

</div>

@include('layout.frontend.partials.request-call-banner')

@stop