<?php

namespace App\Models;

use DB;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'lead';
    protected $primaryKey = 'lead_id';

    /*
     * Get Customer associated with the lead.
     * */
    public function customer(){
        return $this->hasOne('App\Models\Client','id','client_id');
    }

    /*
     * Get Promotion associated with the lead.
     * */
    public function promotion(){
        return $this->hasOne('App\Models\Promotion','promotion_id','promotion_id');
    }

    /*
     * Get Product associated with the lead.
     * foreign key : promotion_id
     * */
    public function products(){
        return $this->hasMany('App\Models\Product','promotion_id','promotion_id');
    }

}
