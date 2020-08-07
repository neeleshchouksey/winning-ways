<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $table = "packages";

    public function packagecommission()
    {
        return $this->hasMany('App\Models\PackageCommission');
    }
}
