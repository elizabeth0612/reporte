<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkOrder extends Model
{
    use HasFactory;
    //protected $table = 'work_orders';

    protected $fillable = ['order_work','status','empresa','image_path','descripcion'];

    
}
