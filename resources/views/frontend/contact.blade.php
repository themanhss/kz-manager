@extends("layout.frontend.master")

@section("content")
    <script type="text/javascript">
        var event_to_track = 'contact_page_visited';
    </script>
    <div class="contact-page">
        <div class="site-content">
            <h2>Make An Enquiry</h2>
            <div class="intro">
                You can speak directly to one of our customer service representatives or experienced mechanics.
            </div>
            <div class="contact-methods">
                <p>Call our Customer Service Representative<a href="tel:1300 768 347">1300 768 347</a><br />(Open 9am - 5pm, Monday to Friday)</p>
                <p>Talk with a Mechanic<a href="" class="call-back-popup"> - request call back</a></p>
                <p>Fill out the form below to make an email enquiry</p>
                <div class="speech-bubble"></div>
            </div>
            <form class="contact-form" method="post" novalidate="novalidate">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <label>What is your first name?</label>
                <?php if ($first_name_error): ?>
                    <div class="form-error">Please enter your first name</div>
                <?php endif; ?>
                <input type="text" name="first_name" value="<?php echo $first_name; ?>" />

                <label>What is your last name?</label>
                <?php if ($last_name_error): ?>
                    <div class="form-error">Please enter your last name</div>
                <?php endif; ?>
                <input type="text" name="last_name" value="<?php echo $last_name; ?>" />

                <label>Please enter your email address:</label>
                <?php if ($email_error): ?>
                    <div class="form-error">Please enter a valid email address</div>
                <?php endif; ?>
                <input type="email" name="email" value="<?php echo $email; ?>" />

                <label>What is your contact number?</label>
                <input type="text" name="phone" value="<?php echo $phone; ?>" />

                <label>Which vehicle are you interested in?</label>
                <?php if ($vehicles_error): ?>
                    <div class="form-error">Please select at least a vehicle model</div>
                <?php endif; ?>

                <div class="chosen-utes">
                    <?php foreach( $promotionData['products'] as $productKey => $product ): ?>
                        <div class="chosen-ute">
                            <label>
                                <!-- <img src="{{ asset('frontend/images/' . $product['slug'] . '-small.jpg')}}" class="ute-img"/> -->
                                <input type="checkbox" name="vehicles[]" value="<?php echo $productKey; ?>" <?php if ( in_array( $productKey, $vehicles ) ): ?> checked="checked" <?php endif; ?> />
                                <span class="ute-name"><?php echo $product['name']; ?></span>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>

                <label>How can we help you?</label>
                <?php if ($message_error): ?>
                    <div class="form-error">Please enter your message</div>
                <?php endif; ?>
                <textarea name="message"><?php echo $message; ?></textarea>

                <?php if ( $form_error ): ?>
                    <div class="form-error">There was an error. Please try by phone.</div>
                <?php endif; ?>

                <?php if ( $form_success ): ?>
                    <div class="form-success">Thanks for your message!</div>
                <?php endif; ?>

                <button type="submit" value="submit" class="make-enquiry-btn" onclick="WoopraTracking.Track('email_inquiry');">make enquiry</button>

                <div class="insurance-note">
                    By choosing either of these options, you agree to receive an insurance
                    offer relating to your purchase.
                </div>
            </form>
        </div>
    </div>

@stop
