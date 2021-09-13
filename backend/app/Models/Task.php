<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = ['difficulty', 'duration'];
    protected $guarded = ['id'];

    public function developers()
    {
        return $this->belongsToMany(Developer::class, 'developer_tasks');
    }
}
