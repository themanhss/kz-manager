<?php

namespace App\Models;

use DB;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\SoftDeletes;

class Campaign extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'campaign';
    protected $primaryKey = 'campaign_id';

    /*
    * Get Promotion associated with the campaign.
    * */
    public function promotion(){
        return $this->hasOne('App\Models\Promotion','promotion_id','promotion_id');
    }
    
    public function communications() {
        return $this->hasMany('App\Models\Communication','campaign_id','campaign_id');
    }

}
