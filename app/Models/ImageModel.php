<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImageModel extends Model
{
    use HasFactory;

    protected $table = 'uploadimage';
    protected $fillable = ['image_name', 'categories_id','price'];

    public function category()
    {
        return $this->belongsTo(CategoriesModel::class, 'categories_id');
    }


}
