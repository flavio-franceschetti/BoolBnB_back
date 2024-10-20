<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApartmentImage extends Model
{
    use HasFactory;

    protected $fillable = ['apartment_id', 'img_path', 'img_name'];

    public function apartment()
    {
        return $this->belongsTo(Apartment::class);
    }
}
