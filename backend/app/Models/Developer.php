<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Developer extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['difficulty'];
    protected $guarded = ['id'];

    public function tasks()
    {
        return $this->belongsToMany(Task::class, 'developer_tasks');
    }
}
