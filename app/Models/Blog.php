<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'blog_category_id',
        'type',
        'title',
        'image',
        'description',
        'solid_description',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('d-m-Y H:i:s');
    }

    public function userData()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    
    public function blogCategoryData()
    {
        return $this->belongsTo(BlogCategory::class,'blog_category_id');
    }
}
