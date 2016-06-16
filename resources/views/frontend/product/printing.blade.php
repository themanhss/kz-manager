@extends("layout.frontend.master")
@section("content")
    <script type="text/javascript">
        var event_to_track = 'print_page_visited';
    </script>
    <style type="text/css">
        #header, #footer { display: none; }
    </style>
    <style type="text/css" media="print">
        .call-button.print-btn { display: none; }
    </style>
    <div id="print-summary" class="printable-offer">
        <span class="call-button print-btn">
            <span class="a padded" onclick="window.print();">PRINT</span>
        </span>
        <div class="site-content print-content">
            <div class="nrma-logo">
                <img src="{{ asset('frontend/images/nrma-big-logo.jpg')}}" alt="NRMA Insurance" title="NRMA Insurance" />
            </div>
            <h2>YOUR OFFER SUMMARY</h2>
            <div class="selected-details">
                <div class="top">
                    <div class="ute-img">
                        <img src="{{ asset('frontend/images/' . $product . '-small.jpg')}}" />
                    </div>
                    <span class="ute-name"><?php echo $productData['name']; ?></span>
                    <span class="ute-price">$<?php echo number_format($variantData['price']); ?></span>
                </div>
                <div class="bottom">
                    <ul class="details-list">
                        <?php foreach( $variantData as $key => $value ): ?>
                            <?php if ($key != 'price'): ?>
                                <li class="option <?php echo str_replace('_', '-', $key); ?>"><?php echo str_replace('_', ' ', strtoupper($key)); ?></li>
                                <li class="selected">
                                    <?php if ( $key == 'body_type' ): ?>
                                        <?php echo $variantData['body_type'] == 'P/UP' ? 'Pick Up' : 'Cab Chassis'; ?>
                                    <?php else: ?>
                                        <?php echo $value; ?>
                                    <?php endif; ?>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>

                        <li class="option colour">COLOUR</li>
                        <li class="selected"><?php echo $color; ?></li>
                    </ul>
                </div>
            </div>
            <div class="btm-note">
                <span class="offer-ends">Offer ends 30th June 2016</span>
                <span>Call us on 1300 768 347, Monday - Friday, 9am - 5pm</span>
            </div>
        </div>
    </div>
@stop