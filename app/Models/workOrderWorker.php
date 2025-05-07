<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkOrderWorker extends Model
{
    use HasFactory;
    protected $fillable = ['work_order_id','worker_id'];
    public function worker()
    {
        return $this->belongsTo(Worker::class);
    }
}
