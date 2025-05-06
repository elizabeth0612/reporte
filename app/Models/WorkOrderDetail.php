<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkOrderDetail extends Model
{
    use HasFactory;
    protected $fillable = ['work_order_id','nro_trabajo','descripcion','materiales','herramientas','observaciones'];
    public function images()
    {
        return $this->hasMany(WorkOrderDetailImage::class);
    }
}
