$(document).ready(function(){
    WoopraTracking.Init();

    $(".open-mi").on('click', function(){
        WoopraTracking.Track('click_comparison_page');

        if ( $(window).width() < 1080 )
        {
            var win = window.open(productComparisonUrl, '_blank');
            win.focus();
        }
        else
        {
            $('#popup-overlay').show();
            $(window).scrollTop(0);
            $('#popup-box')
                .show()
                .css('top', $(window).scrollTop() + 100);

            $(window).trigger('mi-opened');
        }
    });

    $(window).on('scroll resize load', function(){
        if ( $(window).scrollTop() > 50 )
            $('body').addClass('sticky-nav');

        else
            $('body').removeClass('sticky-nav');

        if ( $(window).width() < 999 )
        {
            $('body').removeClass('sticky-nav');
        }

    });

    $(".open-spec-chart").on('click', function(){
        WoopraTracking.Track('click_comparison_page');

        if ( $(window).width() < 1180 )
        {
            var win = window.open(productComparisonUrl2, '_blank');
            win.focus();
        }
        else
        {
            $('#popup-overlay').show();
            $(window).scrollTop(0);
            $('#chart-box')
                .show()
                .css('top', $(window).scrollTop() + 20);

            $(window).trigger('mi-opened');
        }
    });

    $(".close-button").on('click', function(){
        $('#popup-overlay, #popup-box, #privacy-popup, #terms-popup, #popup-chat, #chart-box').hide();
    });

    $(document).on('keyup', function(e){
        if (e.keyCode == 13 || e.keyCode == 27)
            $('#popup-overlay, #popup-box, #privacy-popup, #terms-popup, #popup-chat').hide();
    });

    $('#popup-box').each(function(){
        var popupBox = $(this);
        var closeBtn = popupBox.find('.close-btn');

        $(window).on('scroll mi-opened', function(){
            if ( popupBox.is(":visible") && $(window).scrollTop() < popupBox.height() )
            {
                closeBtn.css('margin-top', $(window).scrollTop() + 'px');
            }
        });
    });

    $(".open-privacy").on('click', function(){
        $('#popup-overlay').show();
        $('#privacy-popup')
            .show()
            .css('top', $(window).scrollTop() + 60);
    });

    $(".open-terms").on('click', function(){
        $('#popup-overlay').show();
        $('#terms-popup')
            .show()
            .css('top', $(window).scrollTop() + 60);
    });

    $(".toggle").on('click', function(){
        $('#toggle-nav').toggleClass('is-visible');
    });

    $(".call-back-popup").on('click', function(e){
        e.preventDefault();
        $('#popup-chat').show()
        .css('top', $(window).scrollTop() + 40);
    });

    $('#popup-chat').each(function(){
        var popup = $(this);
        var form = popup.find('form');
        var button = form.find('input[type=submit]');

        form.on('submit', function(e){
            e.preventDefault();
            button.attr('disabled', 'disabled').val('Please wait...');
            form.find(".form-error").remove();

            $.ajax({
                'url': form.attr('action'),
                'type': 'POST',
                'data': form.serialize(),
                'dataType': 'json',
                'success': function(r) {
                    if (r.status == 'ok')
                    {
                        form.find('.inp').val('');
                        button.before('<div class="form-success">' + r.message + '</div>');
                    }
                    else if (r.status == 'error')
                    {
                        button.before('<div class="form-error">' + r.message + '</div>');
                    }

                    button.removeAttr('disabled').val('Request Call Back');
                },
                'error': function(r) {
                    alert('An error occured. Please try again.');
                    button.removeAttr('disabled').val('Request Call Back');
                }
            });
        });
    });

    $(".request-callback-button").on('click', function(e){
        e.preventDefault();
        $('#popup-chat').show()
        .css('top', $(window).scrollTop() + 40);

    });

    $(".accordion-question").click(function(){
        var isActive = false;
        var answer = $(this).next(".accordion-answer");

        if (answer.is(":visible"))
        {
            isActive = true;
        }

        $(".accordion-answer").hide();

        if (!isActive)
            answer.show();
    });

    $(".price-config").each(function(){
        var priceConfigurator = $(this);
        var optionsSets = priceConfigurator.find('.row > .options');
        var utePriceResults = $(".ute-price-result");
        var otherUtesItems = $(".other-utes-items");
        var isPromotionPage = utePriceResults.length > 1;
        var isProductPage = !isPromotionPage;
        var formatPrice = function(price) {
            var priceFormatted = '$';
            price = price.toString();

            for ( var i=0; i < price.length; i++ )
            {
                priceFormatted = priceFormatted.toString() + price[i].toString() + ( i == 1 ? ',' : '' );
            }

            return priceFormatted;
        };
        var calculatePrice = function() {

            var props = {};
            var vehiclesDisplayed = 0;
            optionsSets.each(function(){
                var activeOption = $(this).find(".option.active").attr('data-value');
                if ($(this).attr('data-name') != null && activeOption )
                {
                    props[ $(this).attr('data-name') ] = activeOption;
                }
            });

            otherUtesItems
                .removeAttr('style')
                .siblings('.no-results-showing').remove();

            utePriceResults.each(function(){
                var price = $(this);
                var product = $(this).attr('data-attr-product');

                if ( product )
                {
                    var productFromDb = calculator_data[ product ];
                    if ( productFromDb )
                    {
                        var options = productFromDb.options;
                        for ( var i in options )
                        {
                            var option = options[i];
                            var comparedProps = 0;
                            var matchedProps = 0;
                            for ( var j in props )
                            {
                                comparedProps++;
                                if ( typeof option[j] != 'undefined' && option[j].toLowerCase().indexOf( props[j].toLowerCase() ) != -1 )
                                {
                                    matchedProps++;
                                }
                            }

                            if ( isProductPage )
                            {
                                price.siblings('.no-results-showing').remove();
                                price
                                    .hide()
                                    .after( '<p class="no-results-showing">Vehicle not offered with this option</p>' );
                                if ( comparedProps == matchedProps )
                                {
                                    price.siblings('.no-results-showing').remove();

                                    var towBarPrice = $(".tow-bar-toyota-hilux .option.active").attr('data-value') == 'Yes' ? 858 : 0;

                                    price
                                        .html( formatPrice(option.price * 1 + towBarPrice * 1) )
                                        .show()
                                        .parent()
                                            .find('.print-summary-button')
                                            .each(function(){
                                                var href = $(this).attr('href');
                                                href = href.indexOf('?') != -1 ? ( href.substring(0, href.indexOf('?')) ) : href;

                                                $(this).attr('href', href + '?product=' + product + '&variant=' + i + '&color=' + $('#options-color .option.active').text());
                                            });

                                    break;
                                }
                            }
                            else if ( isPromotionPage )
                            {
                                price.parent().hide();
                                if ( comparedProps == matchedProps )
                                {
                                    price
                                        .html( formatPrice(option.price) )
                                        .parent()
                                            .show();

                                    vehiclesDisplayed++;

                                    break;
                                }
                            }
                        }
                    }
                }
            });

            if ( isPromotionPage && vehiclesDisplayed < 1 )
            {
                otherUtesItems
                    .hide()
                    .after('<p class="no-results-showing" style="text-align: center; padding-top: 150px;">No utes available for these options</p>');
            }
        };

        optionsSets.each(function(){
            var optionSet = $(this);

            optionSet.find('.option').each(function(){
                var opt = $(this);
                var optInside = $('<span/>', { 'class': 'option-inside', 'html': opt.html() });

                opt.empty().append(optInside);
            });
        });
        $(window).on('iag:ready load resize', function(){
            optionsSets.each(function(){
                var optionSet = $(this);
                var optionsHere = optionSet.find('.option');
                var optionsHereInsides = optionsHere.find('.option-inside');
                var maxHeight = 0;

                optionsHere.removeAttr('style');

                optionsHereInsides.each(function(){
                    maxHeight = $(this).height() > maxHeight ? $(this).height() : maxHeight;
                });

                optionsHere.css({ 'line-height': maxHeight + 'px' });
            });
        });
        $(window).trigger('iag:ready');

        calculatePrice();
        optionsSets.find(".option").on('click', function(){
            var currentOption = $(this);

            utePriceResults.each(function(){
                var product = $(this).attr('data-attr-product');
                if ( product )
                {
                    var productFromDb = calculator_data[product];
                    if (productFromDb)
                    {
                        var forced = productFromDb.force;
                        if ( forced )
                        {
                            for ( var i in forced )
                            {
                                for ( var j in forced[i] )
                                {
                                    if ( j == currentOption.parent().attr('data-name') && forced[i][j] == currentOption.attr('data-value') )
                                    {
                                        for ( var k in forced[i] )
                                        {
                                            if ( j != k )
                                            {
                                                var r = $( '.options[data-name="' + k + '"] .option[data-value="' + forced[i][k] + '"]' );
                                                r.addClass('active').siblings('.option').removeClass('active');
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            });

            currentOption.addClass('active').siblings('.option').removeClass('active');

            calculatePrice();
        });
    });
});

var WoopraTracking = {
    'Init': function() {
        var search = window.location.search;
        var emailExists = false;
        var currentEmail = '';
        if ( search[0] == '?' )
        {
            search = search.substring(1);
        }
        search = search.split('&');

        for ( var i in search )
        {
            var pieces = search[i].split('=');
            if ( typeof pieces[0] != 'undefined' && pieces[0] == 'email' && typeof pieces[1] != 'undefined' && WoopraTracking.ValidateEmail(pieces[1]) )
            {
                emailExists = true;
                currentEmail = pieces[1];
                WoopraTracking.Identify(currentEmail);
                $('.contact-form input[name=email]').val(currentEmail);
            }
        }

        if ( !emailExists )
        {
            var tryEmail = WoopraTracking.ReadEmailCookie();

            if ( tryEmail != '' && WoopraTracking.ValidateEmail( tryEmail ) )
            {
                emailExists = true;
                currentEmail = tryEmail;
                WoopraTracking.Identify(currentEmail);
                $('.contact-form input[name=email]').val(currentEmail);
            }
        }

        if ( !emailExists )
        {
            $('#email-popup')
                .show()
                .each(function(){
                    var popup = $(this);
                    var field = popup.find("input[type=text]");

                    popup.find("input[type=submit]")
                        .unbind('click')
                        .on('click', function(e){
                            e.preventDefault();
                            field.siblings('.form-error').remove();
                            if ( WoopraTracking.ValidateEmail( field.val() ) )
                            {
                                popup.hide();
                                WoopraTracking.Identify(field.val());
                                $('.contact-form input[name=email]').val(field.val());
                            }
                            else
                            {
                                field.after('<div class="form-error">Please enter a valid email address</div>');
                            }
                    });
                });
        }

        $('a[href^="tel:"]').on('click', function(){
            WoopraTracking.Track('phone_call');
        });

        if ( typeof event_to_track != 'undefined' )
        {
            WoopraTracking.Track(event_to_track);
        }
    },
    'Identify': function(email) {
        woopra.identify({
            email: email
        });
        //woopra.track();
        Visitor.actions.tracking(email);

        document.cookie = "woopra_visitor_email=" + email + "; path=/";
    },
    'Track': function(event) {
        woopra.track(event, {  }, function() {
            console.log('done');
        });
    },
    'ReadEmailCookie': function() {
        var cname = 'woopra_visitor_email';
        var name = cname + "=";
        var ca = document.cookie.split(';');
        for(var i = 0; i <ca.length; i++)
        {
            var c = ca[i];
            while (c.charAt(0)==' ')
            {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0)
            {
                return c.substring(name.length,c.length);
            }
        }

        return "";
    },
    'ValidateEmail': function(email) {
        var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

        return re.test(email);
    }
};