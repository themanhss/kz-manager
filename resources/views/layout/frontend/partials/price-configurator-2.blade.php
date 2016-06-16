<script type="text/javascript">
    var calculator_data = <?php echo $calculatorData; ?>;
</script>

<?php $calculatorDataArr = json_decode($calculatorData, true); ?>

<?php $productDataArr = $calculatorDataArr[$productSlug]['options']; ?>
<?php $calculatorsKeys = array(); ?>

<?php foreach($productDataArr as $productVariant): ?>
    <?php foreach( $productVariant as $propertyKey => $propertyValue ): ?>
        <?php if ( !in_array( $propertyKey, $calculatorsKeys ) && $propertyKey != 'price' ): ?>
            <?php $calculatorsKeys[] = $propertyKey; ?>
        <?php endif; ?>
    <?php endforeach; ?>
<?php endforeach; ?>

<?php foreach( $calculatorsKeys as $key ): ?>
    <?php $keyValues = array( ); ?>

    <?php foreach($productDataArr as $productVariant): ?>
        <?php $keyValues[] = $productVariant[$key]; ?>
    <?php endforeach; ?>

    <?php $keyValues = array_unique( $keyValues ); ?>

    <div class="row <?php echo count($keyValues) == 1 ? 'full-width' : ''; ?>">
        <span class="label"><?php echo strtoupper(str_replace('_', ' ',$key)); ?></span>
        <div class="options" data-name="<?php echo $key; ?>">
            <?php foreach( $keyValues as $key => $value ): ?>
                <span class="option <?php echo count($keyValues) == 1 ? 'is-single' : ''; ?> <?php echo $key == 0 ? 'active' : ''; ?>" data-value="<?php echo $value; ?>"><?php echo $value; ?></span>
            <?php endforeach; ?>
        </div>
    </div>
<?php endforeach; ?>
