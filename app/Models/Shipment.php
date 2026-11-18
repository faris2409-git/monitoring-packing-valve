<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use HasFactory;

    protected $fillable = ['shipment_code', 'driver_name', 'vehicle_no', 'shipment_date', 'created_by', 'notes'];

    public function items()
    {
        return $this->hasMany(ShipmentItem::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}