<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkOrderSupervisor extends Model
{
    use HasFactory;
    
    protected $fillable = ['work_order_id','supervisor_id'];
    public function supervisor()
    {
        return $this->belongsTo(Supervisor::class);
    }
}
