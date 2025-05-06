<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkOrderDetailImage extends Model
{
    use HasFactory;
    protected $fillable = [
        'work_order_detail_id', 'image_path','descripcion'
    ];
}
