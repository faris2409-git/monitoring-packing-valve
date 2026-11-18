<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackagingTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_code', 'engine_valve_id', 'created_by', 'assigned_to',
        'quantity_target', 'quantity_packed', 'target_delivery_date', 'instruction',
        'status', 'notes', 'accepted_at', 'completed_at'
    ];

    protected $casts = [
        'target_delivery_date' => 'date',
        'accepted_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function engineValve()
    {
        return $this->belongsTo(EngineValve::class);
    }

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function operator()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function shipmentItems()
    {
        return $this->hasMany(ShipmentItem::class);
    }
}