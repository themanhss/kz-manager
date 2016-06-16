
<div class="request-call-popup" id="popup-chat">
    <span class="close-button">X</span>
    <h3 class="h3-popup">Speak With a Mechanic</h3>
    <div class="content">
        <p>Like you, our mechanics are pretty busy.<br/>However, we'll get them to contact you as soon as possible.<br/>Please let us know a time and date when you're free to speak and they'll call you back.<br/> Our business hours are Mon-Fri 8:30am to 6:00pm.</p>

        <form method="post" class="contact-form" action="<?php echo route('frontend_contact_callback_post'); ?>">
            <div class="formfield">
                <input type="text" name="name" class="inp" placeholder="Name" />
            </div>
            <div class="formfield">
                <input type="text" name="phone_number" class="inp" placeholder="Phone Number"  />
            </div>
            <div class="formfield">
                <input type="text" name="time" class="inp" placeholder="Time" />
            </div>
            <div class="formfield">
                <input type="date" name="date" class="inp" placeholder="Date" />
            </div>
            <div class="submit">
                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>" />
                <input type="submit" value="Request Call Back" />
            </div>
        </form>
    </div>
</div>
