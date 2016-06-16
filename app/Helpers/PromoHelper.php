<?php

function getTempConfig()
{
    return array(
        '5e98b' => array(
            'name' => 'Promo V1',
            'slug' => 'promo-v1',
            'products' => array(
                '438ec' => array(
                    'slug' => 'mitsubishi-triton',
                    'name' => 'Mitsubishi Triton'
                ),
                '11bce' => array(
                    'slug' => 'holden-colorado',
                    'name' => 'Holden Colorado'
                ),
                '8ebdb' => array(
                    'slug' => 'ford-ranger',
                    'name' => 'Ford Ranger'
                ),
                'f2d04' => array(
                    'slug' => 'toyota-hilux',
                    'name' => 'Toyota Hilux'
                )
            )
        ),
        '0dd03' => array(
            'name' => 'Promo V2',
            'slug' => 'promo-v2',
            'products' => array(
                'bef84' => array(
                    'slug' => 'toyota-land-cruiser',
                    'name' => 'Toyota Land Cruiser'
                ),
                'd769e' => array(
                    'slug' => 'toyota-prado',
                    'name' => 'Toyota Prado'
                ),
                '5b1de' => array(
                    'slug' => 'toyota-kluger',
                    'name' => 'Toyota Kluger'
                ),
                '31ddf' => array(
                    'slug' => 'holden-commodore',
                    'name' => 'Holden Commodore'
                ),
                'a3ab6' => array(
                    'slug' => 'jeep-cherokee',
                    'name' => 'Jeep Cherokee'
                )
            )
        )
    );
}

function getProductKey($promotionId, $productId)
{
    $tempConfig = getTempConfig();
    $tempConfigKeys = array_keys( $tempConfig );

    if ( isset( $tempConfigKeys[$promotionId] ) )
    {
        $promotionData = $tempConfig[ $tempConfigKeys[$promotionId] ];
        $promotionDataKeys = array_keys( $promotionData['products'] );

        if ( isset( $promotionDataKeys[$productId] ) )
        {
            return $promotionDataKeys[ $productId ];
        }
    }

    return '';
}

function getProductSlug($promotionId, $productId)
{
    $tempConfig = getTempConfig();
    $tempConfigKeys = array_keys( $tempConfig );

    if ( isset( $tempConfigKeys[$promotionId] ) )
    {
        $promotionData = $tempConfig[ $tempConfigKeys[$promotionId] ];
        $promotionDataKeys = array_keys( $promotionData['products'] );

        if ( isset( $promotionDataKeys[$productId] ) )
        {
            return $promotionData['products'][ $promotionDataKeys[ $productId ] ]['slug'];
        }
    }

    return '';
}

function getProductSlugByProductKey($key)
{
    $tempConfig = getTempConfig();

    foreach ( $tempConfig as $promotionKey => $promotionData )
    {
        foreach ( $promotionData['products'] as $productKey => $productData )
        {
            if ( $productKey == $key )
            {
                return $productData['slug'];
            }
        }
    }

    return false;
}

function getPromotionIdxByProductKey($key)
{
    $tempConfig = getTempConfig();
    $tempConfigKeys = array_keys( $tempConfig );

    foreach ( $tempConfig as $promotionKey => $promotionData )
    {
        foreach ( $promotionData['products'] as $productKey => $productData )
        {
            if ( $productKey == $key )
            {
                return array_search( $promotionKey, $tempConfigKeys );
            }
        }
    }

    return -1;
}

function getPromotionKey($idx)
{
    $tempConfig = getTempConfig();
    $tempConfigKeys = array_keys( $tempConfig );

    if ( isset( $tempConfigKeys[ $idx ] ) )
    {
        return $tempConfigKeys[ $idx ];
    }
}

function getPromotionSlug($idx)
{
    $tempConfig = getTempConfig();
    $tempConfigKeys = array_keys( $tempConfig );

    if ( isset( $tempConfigKeys[ $idx ] ) )
    {
        return $tempConfig[ $tempConfigKeys[ $idx ] ]['slug'];
    }
}

function getPromotionIdx($promotionId)
{
    $tempConfig = getTempConfig();
    $tempConfigKeys = array_keys( $tempConfig );
    $promotionIdx = array_search( $promotionId, $tempConfigKeys );

    return $promotionIdx;
}
