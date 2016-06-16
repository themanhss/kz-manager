@extends("layout.frontend.master")

@section("content")
    <div class="about-intro">
        <div class="site-content">
            <h2>About us</h2>
            <div class="text-box">
                Being an NRMA Insurance customer means you get to enjoy the value of belonging
                to the greater IAG family, which includes Swann Insurance, CGU and SGIO.
            </div>
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

    <div class="offer-description">
        <div class="site-content">
            <h2>About the offer</h2>

            <div class="description-row">
                <h5>We’ve been getting to know you (well, people just like you)</h5>
                <div class="details">
                    We recently got home from chatting with tradies and business owners along
                    the east coast about what they value in their transport. Not only were we told
                    what attributes were key to a ute that gets the job done, we were asked whether
                    there was a way to purchase a new vehicle that’s quicker and more convenient.
                </div>
            </div>

            <div class="description-row">
                <h5>…and have unlocked the value of the IAG family of businesses</h5>
                <div class="details">
                    Because you’re a valued NRMA Insurance customer, we’ve taken what we’ve learnt
                    and are passing on the buying power of the IAG family of businesses to offer
                    you a range of great priced utes from our Swann Insurance network of dealers.
                </div>
            </div>

            <div class="description-row">
                <h5>…to offer you Australia’s best utes</h5>
                <div class="details">
                    These four rugged, high performing and popular utes are perfect for any tradie
                    or business owner. We’ve also put them through their paces at the cutting edge
                    IAG Research Centre so we can show you exactly what they’re capable of.
                </div>
            </div>

            <div class="description-row">
                <h5>…so you can get into your new ute without barely putting down a tool.</h5>
                <div class="details">
                    We know you’re after a ute that has all the functionality, grunt and creature
                    comforts you need to tackle the working week and the weekend. To get you on the
                    road in your new ride faster, we’ve taken care of the negotiation legwork for you
                    and landed a terrific price across all four utes. All you need to do is select
                    the vehicle you want and get in contact with one of our mechanics or customer
                    service representatives. From there they’ll organise for you to visit the dealership,
                    check out the vehicle and finalise the purchase of your brand new ute.
                </div>
            </div>
        </div>
    </div>
    <div class="about-iag">
        <div class="site-content">
            <div class="content">
                <div class="left column">
                    <h5>Who is IAG?</h5>
                    <div class="text-box">
                        IAG is Australia’s largest insurance provider. It’s responsible for some of Australia’s
                        most well-known and trusted insurance brands, including NRMA Insurance, Swann Insurance,
                        CGU, SGIO and SGIC. An employer of 15,000 people, IAG also manages prominent insurance
                        brands across New Zealand, Thailand, Vietnam and Indonesia.
                    </div>
                    <div class="ute-icon"></div>
                </div>
                <div class="right column">
                    <h5>IAG Research Centre</h5>
                    <div class="text-box">
                        The state-of-the-art IAG Research Centre conducts physical testing and analysis on popular
                        cars and motorcycles, with a focus on safety and security. The Centre is proud to share its
                        findings with consumers to help inform their buying decision. IAG is the only insurance
                        company in Australia to operate its own research centre. It is also the only insurance
                        based member of the Australasian New Car Assessment Program (ANCAP).
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('layout.frontend.partials.call-us-banner')
@stop