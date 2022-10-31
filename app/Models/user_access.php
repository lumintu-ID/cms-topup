<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class user_access extends Model
{
    use HasFactory;

    protected $keyType = "string";
    protected $primaryKey = "access_id";

    protected $guarded = [];

    public function navigation()
    {
        return $this->belongsTo(navigation::class, 'nav_id');
    }
}
