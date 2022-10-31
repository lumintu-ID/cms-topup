<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class label_navigation extends Model
{
    use HasFactory;

    protected $primaryKey = "id_label";

    protected $guarded = [];

    function navigation()
    {
        return $this->hasOne(navigation::class, 'id_label');
    }
}
