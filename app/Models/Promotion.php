<?php

namespace App\Models;

use DB;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\SoftDeletes;

class Promotion extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $primaryKey = 'promotion_id';

    protected $table = 'promotion';

    /*
     * Get Product associated with the Promotion.
     * foreign key : promotion_id
     *
     * */
    public function products()
    {
        return $this->hasMany('App\Models\Product', 'promotion_id', 'promotion_id');
    }

    /*
     * Get Lead associated with the Promotion.
     * foreign key : promotion_id
     *
     * */
    public function leads()
    {
        return $this->hasMany('App\Models\Lead', 'promotion_id', 'promotion_id');
    }

}
