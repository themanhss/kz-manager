<script type="text/javascript">
    var calculator_data = <?php echo $calculatorData; ?>;
</script>

<div class="row hide-row">
    <span class="label">Body style</span>
    <div class="options" data-name="body_style">
        <span class="option active" data-value="Dual">Dual</span>
        <span class="option" data-value="Single">Single Cab</span>
    </div>
</div>
<div class="row hide-row">
    <span class="label">Body type</span>
    <div class="options" data-name="body_type">
        <span class="option" data-value="P/UP">Pick Up</span>
        <span class="option active" data-value="C/CHAS">Cab Chassis</span>
    </div>
</div>
<div class="row">
    <span class="label">Transmission Type</span>
    <div class="options" data-name="transmission_type">
        <span class="option active" data-value="AUTOMATIC">Automatic</span>
        <span class="option" data-value="MANUAL">Manual</span>
    </div>
</div>
<div class="row">
    <span class="label">Drive Train</span>
    <div class="options" data-name="drive_train">
        <span class="option" data-value="4x2">4x2</span>
        <span class="option active" data-value="4x4">4x4</span>
    </div>
</div>
<div class="row hide-row hide-tow-bar tow-bar-<?php echo $productSlug; ?>">
    <span class="label">Tow Bar</span>
    <div class="options">
        <span class="option active" data-value="Yes">Yes</span>
        <span class="option" data-value="No">No</span>
    </div>
</div>
