<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageCommission extends Model
{
    protected $table = "package_commission";
    public function package()
    {
        return $this->belongsTo('App\Models\Package');
    }
}
