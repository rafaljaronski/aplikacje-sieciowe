<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleStatus extends Model
{
    protected $table = 'article_status';
    protected $fillable = ['name', 'display_status'];
    
    public function articles() {
        return $this->hasMany(Article::class, 'status_id');
    }
}
