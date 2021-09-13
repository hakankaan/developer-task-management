<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeveloperTask extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['developer_id', 'task_id'];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
    public function developer()
    {
        return $this->belongsTo(Developr::class);
    }
}
