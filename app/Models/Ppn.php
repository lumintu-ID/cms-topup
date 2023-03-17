<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ppn extends Model
{
    use HasFactory;

    protected $table = "ppns";
    protected $keyType = "string";

    protected $primaryKey = "id_ppn";

    protected $guarded = [];
}
