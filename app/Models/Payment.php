<?php

namespace App\Models;

use App\Models\Country;
use App\Models\Category;
use App\Models\Code_payment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $keyType = "string";

    protected $primaryKey = "payment_id";

    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function code_pay()
    {
        return $this->belongsTo(Code_payment::class, 'code_payment');
    }
}
