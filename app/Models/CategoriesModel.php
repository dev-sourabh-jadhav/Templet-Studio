<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriesModel extends Model
{
    use HasFactory;


    protected $table = 'categories';

    protected $fillable = [
        'categories_name'
    ];

    public function images()
    {
        return $this->hasMany(ImageModel::class, 'categories_id');
    }


}
