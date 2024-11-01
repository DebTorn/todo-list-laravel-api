<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'items';

    protected $fillable = [
        'title',
        'description',
        'list_id',
        'completed',
        'completed_at',
        'background_color',
        'background_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public $timestamps = true;

    public function list()
    {
        return $this->belongsTo(TodoList::class);
    }
}
