<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class navigation extends Model
{
    use HasFactory;

    protected $keyType = "string";

    protected $primaryKey = "nav_id";

    protected $guarded = [];


    function access()
    {
        return $this->hasMany(user_access::class, 'access_id');
    }

    function label()
    {
        return $this->belongsTo(label_navigation::class, 'id_label');
    }
}
