<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $table = 'article';
    protected $fillable = ['title', 'content', 'status_id', 'author_id', 'reviewed_by', 'reviewed_at', 'rejection_reason'];
    protected $casts = [
        'reviewed_at' => 'datetime'
    ];
    
    public function status() {
        return $this->belongsTo(ArticleStatus::class, 'status_id');
    }
    
    public function author() {
        return $this->belongsTo(User::class, 'author_id');
    }
    
    public function reviewer() {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
