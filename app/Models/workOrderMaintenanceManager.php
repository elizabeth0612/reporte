<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class workOrderMaintenanceManager extends Model
{
    use HasFactory;
    protected $fillable = ['work_order_id','maintenance_manager_id'];
    public function maintenanceManager()
    {
        return $this->belongsTo(MaintenanceManager::class);
    }
    
}
