<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EngineValve extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'name', 'type', 'spec'];

    public function packagingTasks()
    {
        return $this->hasMany(PackagingTask::class);
    }
}