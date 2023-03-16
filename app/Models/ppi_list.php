<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ppi_list extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function pricepoint()
    {
        return $this->belongsTo(PricePoint::class, 'price_point_id');
    }
}
