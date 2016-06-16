<?php

namespace App\Models;

use DB;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */

    protected $primaryKey = 'product_id';

    protected $table = 'product';

    public function getProductsData( $format = 'array', $promotionId = 0 )
    {
        $data = array();

        $data[0] = array(
            'holden' => array(
                'name' => 'Holden Colorado',
                'options' => array(
                    array( 'price' => '36000', 'body_type' => 'C/CHAS', 'body_style' => 'Dual', 'transmission_type' => '6 SP AUTOMATIC', 'drive_train' => '4x2' ),
                    array( 'price' => '39500', 'body_type' => 'C/CHAS', 'body_style' => 'Dual', 'transmission_type' => '6 SP AUTOMATIC', 'drive_train' => '4x4' ),
                    array( 'price' => '35500', 'body_type' => 'C/CHAS', 'body_style' => 'Dual', 'transmission_type' => '6 SP MANUAL', 'drive_train' => '4x2' ),
                    array( 'price' => '38990', 'body_type' => 'C/CHAS', 'body_style' => 'Dual', 'transmission_type' => '6 SP MANUAL', 'drive_train' => '4x4' ),
                    array( 'price' => '31000', 'body_type' => 'C/CHAS', 'body_style' => 'Single', 'transmission_type' => '6 SP AUTOMATIC', 'drive_train' => '4x2' ),
                    array( 'price' => '36400', 'body_type' => 'C/CHAS', 'body_style' => 'Single', 'transmission_type' => '6 SP AUTOMATIC', 'drive_train' => '4x4' ),
                    array( 'price' => '30500', 'body_type' => 'C/CHAS', 'body_style' => 'Single', 'transmission_type' => '6 SP MANUAL', 'drive_train' => '4x2' ),
                    array( 'price' => '35900', 'body_type' => 'C/CHAS', 'body_style' => 'Single', 'transmission_type' => '6 SP MANUAL', 'drive_train' => '4x4' ),
                    array( 'price' => '35500', 'body_type' => 'P/UP', 'body_style' => 'Dual', 'transmission_type' => '6 SP AUTOMATIC', 'drive_train' => '4x2' ),
                    array( 'price' => '36990', 'body_type' => 'P/UP', 'body_style' => 'Dual', 'transmission_type' => '6 SP AUTOMATIC', 'drive_train' => '4x4' ),
                    array( 'price' => '35000', 'body_type' => 'P/UP', 'body_style' => 'Dual', 'transmission_type' => '6 SP MANUAL', 'drive_train' => '4x2' ),
                    array( 'price' => '36990', 'body_type' => 'P/UP', 'body_style' => 'Dual', 'transmission_type' => '6 SP MANUAL', 'drive_train' => '4x4' )
                )
            ),
            'toyota' => array(
                'name' => 'Toyota Hilux',
                'options' => array(






                    array( 'price' => '45900', 'body_type' => 'C/CHAS', 'body_style' => 'Dual', 'transmission_type' => '6 SP AUTOMATIC', 'drive_train' => '4x4' ),
                    array( 'price' => '44000', 'body_type' => 'C/CHAS', 'body_style' => 'Dual', 'transmission_type' => '6 SP MANUAL', 'drive_train' => '4x4' ),
                    array( 'price' => '40235', 'body_type' => 'C/CHAS', 'body_style' => 'Single', 'transmission_type' => '5 SP MANUAL', 'drive_train' => '4x2' ),
                    array( 'price' => '46709', 'body_type' => 'C/CHAS', 'body_style' => 'Single', 'transmission_type' => '6 SP AUTOMATIC', 'drive_train' => '4x4' ),
                    array( 'price' => '48086', 'body_type' => 'C/CHAS', 'body_style' => 'Single', 'transmission_type' => '6 SP MANUAL', 'drive_train' => '4x4' ),
                    array( 'price' => '41000', 'body_type' => 'P/UP', 'body_style' => 'Dual', 'transmission_type' => '6 SP AUTOMATIC', 'drive_train' => '4x2' ),
                    array( 'price' => '39891', 'body_type' => 'P/UP', 'body_style' => 'Dual', 'transmission_type' => '6 SP AUTOMATIC', 'drive_train' => '4x4' ),
                    array( 'price' => '39100', 'body_type' => 'P/UP', 'body_style' => 'Dual', 'transmission_type' => '5 SP MANUAL', 'drive_train' => '4x2' ),
                    array( 'price' => '44933', 'body_type' => 'P/UP', 'body_style' => 'Dual', 'transmission_type' => '5 SP MANUAL', 'drive_train' => '4x4' ),
                    array( 'price' => '44000', 'body_type' => 'C/CHAS', 'body_style' => 'Dual', 'transmission_type' => '6 SP MANUAL', 'drive_train' => '4x4' )
                )
            ),
            'ford' => array(
                'name' => 'Ford Ranger',
                'options' => array(
                    array( 'price' => '37590', 'body_type' => 'C/CHAS', 'body_style' => 'Dual', 'transmission_type' => '6 SP AUTOMATIC', 'drive_train' => '4x2' ),
                    array( 'price' => '47990', 'body_type' => 'C/CHAS', 'body_style' => 'Dual', 'transmission_type' => '6 SP AUTOMATIC', 'drive_train' => '4x4' ),
                    array( 'price' => '36390', 'body_type' => 'C/CHAS', 'body_style' => 'Dual', 'transmission_type' => '6 SP MANUAL', 'drive_train' => '4x2' ),
                    array( 'price' => '45790', 'body_type' => 'C/CHAS', 'body_style' => 'Dual', 'transmission_type' => '6 SP MANUAL', 'drive_train' => '4x4' ),
                    array( 'price' => '33090', 'body_type' => 'C/CHAS', 'body_style' => 'Single', 'transmission_type' => '6 SP AUTOMATIC', 'drive_train' => '4x2' ),
                    array( 'price' => '43490', 'body_type' => 'C/CHAS', 'body_style' => 'Single', 'transmission_type' => '6 SP AUTOMATIC', 'drive_train' => '4x4' ),
                    array( 'price' => '27390', 'body_type' => 'C/CHAS', 'body_style' => 'Single', 'transmission_type' => '6 SP MANUAL', 'drive_train' => '4x2' ),
                    array( 'price' => '41290', 'body_type' => 'C/CHAS', 'body_style' => 'Single', 'transmission_type' => '6 SP MANUAL', 'drive_train' => '4x4' ),
                    array( 'price' => '38590', 'body_type' => 'P/UP', 'body_style' => 'Dual', 'transmission_type' => '6 SP AUTOMATIC', 'drive_train' => '4x2' ),
                    array( 'price' => '48990', 'body_type' => 'P/UP', 'body_style' => 'Dual', 'transmission_type' => '6 SP AUTOMATIC', 'drive_train' => '4x4' ),
                    array( 'price' => '46790', 'body_type' => 'P/UP', 'body_style' => 'Dual', 'transmission_type' => '6 SP MANUAL', 'drive_train' => '4x4' ),
                    array( 'price' => '28390', 'body_type' => 'P/UP', 'body_style' => 'Single', 'transmission_type' => '6 SP MANUAL', 'drive_train' => '4x2' ),
                    array( 'price' => '36390', 'body_type' => 'P/UP', 'body_style' => 'Dual', 'transmission_type' => '6 SP MANUAL', 'drive_train' => '4x2' )
                )
            ),
            'mitsubishi' => array(
                'name' => 'Mitsubishi Triton',
                'options' => array(
                    array( 'price' => '38740', 'body_type' => 'C/CHAS', 'body_style' => 'Dual', 'transmission_type' => '6 SP AUTOMATIC', 'drive_train' => '4x4' ),
                    array( 'price' => '36240', 'body_type' => 'C/CHAS', 'body_style' => 'Dual', 'transmission_type' => '6 SP MANUAL', 'drive_train' => '4x4' ),
                    array( 'price' => '26990', 'body_type' => 'C/CHAS', 'body_style' => 'Single', 'transmission_type' => '6 SP AUTOMATIC', 'drive_train' => '4x2' ),
                    array( 'price' => '24990', 'body_type' => 'C/CHAS', 'body_style' => 'Single', 'transmission_type' => '6 SP MANUAL', 'drive_train' => '4x2' ),
                    array( 'price' => '32490', 'body_type' => 'C/CHAS', 'body_style' => 'Single', 'transmission_type' => '6 SP MANUAL', 'drive_train' => '4x4' ),
                    array( 'price' => '35990', 'body_type' => 'P/UP', 'body_style' => 'Dual', 'transmission_type' => '6 SP AUTOMATIC', 'drive_train' => '4x2' ),
                    array( 'price' => '39490', 'body_type' => 'P/UP', 'body_style' => 'Dual', 'transmission_type' => '6 SP AUTOMATIC', 'drive_train' => '4x4' ),
                    array( 'price' => '33490', 'body_type' => 'P/UP', 'body_style' => 'Dual', 'transmission_type' => '6 SP MANUAL', 'drive_train' => '4x2' ),
                    array( 'price' => '36990', 'body_type' => 'P/UP', 'body_style' => 'Dual', 'transmission_type' => '6 SP MANUAL', 'drive_train' => '4x4' )
                )
            )
        );

        $data[1] = array(
            'holden-commodore' => array(
                'name' => 'Holden Commodore',
                'options' => array(
                    array( 'price' => '39490', 'body_style' => '4D SEDAN', 'transmission_type' => '6 SP AUTOMATIC', 'drive_train' => 'REAR WHEEL DRIVE', 'cylinders' => 'V6', 'fuel_type' => 'UNLEADED PETROL' ),
                    array( 'price' => '41490', 'body_style' => '4D WAGON', 'transmission_type' => '6 SP AUTOMATIC', 'drive_train' => 'REAR WHEEL DRIVE', 'cylinders' => 'V6', 'fuel_type' => 'UNLEADED PETROL' )
                )
            ),
            'jeep-cherokee' => array(
                'name' => 'Jeep Grand Cherokee',
                'options' => array(
                    array( 'price' => '69000', 'body_style' => '4D WAGON', 'transmission_type' => '8 SP AUTOMATIC', 'drive_train' => '4x4', 'cylinders' => 'V6', 'fuel_type' => 'DIESEL' ),
                    array( 'price' => '62000', 'body_style' => '4D WAGON', 'transmission_type' => '8 SP AUTOMATIC', 'drive_train' => '4x4', 'cylinders' => 'V6', 'fuel_type' => 'PREMIUM UNLEADED PETROL' ),
                    array( 'price' => '64000', 'body_style' => '4D WAGON', 'transmission_type' => '8 SP AUTOMATIC', 'drive_train' => '4x4', 'cylinders' => 'V8', 'fuel_type' => 'PREMIUM UNLEADED PETROL' )
                )
            ),
            'toyota-kluger' => array(
                'name' => 'Toyota Kluger',
                'options' => array(
                    array( 'price' => '51190', 'body_style' => '4D WAGON', 'transmission_type' => '6 SP AUTOMATIC', 'drive_train' => 'FRONT WHEEL DRIVE', 'cylinders' => 'V6', 'fuel_type' => 'UNLEADED PETROL' )
                )
            ),
            'toyota-land-cruiser' => array(
                'name' => 'Toyota Land Cruiser',
                'options' => array(
                    array( 'price' => '82000', 'body_style' => '4D WAGON', 'transmission_type' => '6 SP AUTOMATIC', 'drive_train' => '4x4', 'cylinders' => 'V8', 'fuel_type' => 'UNLEADED PETROL' ),
                    array( 'price' => '87000', 'body_style' => '4D WAGON', 'transmission_type' => '6 SP AUTOMATIC', 'drive_train' => '4x4', 'cylinders' => 'V8', 'fuel_type' => 'DIESEL' )
                )
            ),
            'toyota-prado' => array(
                'name' => 'Toyota Prado',
                'options' => array(
                    array( 'price' => '61990', 'body_style' => '4D WAGON', 'transmission_type' => '6 SP AUTOMATIC', 'drive_train' => 'FOUR WHEEL DRIVE', 'cylinders' => 'DIESEL TURBO 4', 'fuel_type' => 'DIESEL'),
                    array( 'price' => '60990', 'body_style' => '4D WAGON', 'transmission_type' => '6 SP AUTOMATIC', 'drive_train' => 'FOUR WHEEL DRIVE', 'cylinders' => 'V6', 'fuel_type' => 'UNLEADED PETROL' )
                ),
                'force' => array(
                    array( 'cylinders' => 'DIESEL TURBO 4', 'fuel_type' => 'DIESEL' ),
                    array( 'cylinders' => 'V6', 'fuel_type' => 'UNLEADED PETROL' )
                )
            )
        );

        $data = $data[$promotionId];

        return $format == 'json' ? json_encode($data) : $data;
    }

    public function getProductData($product)
    {
        $data = array_merge( $this->getProductsData( 'array', 0 ), $this->getProductsData( 'array', 1) );

        return isset( $data[$product] ) ? $data[$product] : array();
    }

    public function getProductVariant($product, $variant)
    {
        $data = array_merge( $this->getProductsData( 'array', 0 ), $this->getProductsData( 'array', 1) );

        return isset( $data[$product]['options'][$variant] ) ? $data[$product]['options'][$variant] : array();
    }
}
