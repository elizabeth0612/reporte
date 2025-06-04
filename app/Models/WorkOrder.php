<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkOrder extends Model
{
    use HasFactory;
    //protected $table = 'work_orders';

    protected $fillable = ['mes_work','status','empresa','image_path','descripcion','user_register'];

    public function user()
        {
            return $this->belongsTo(User::class,'user_register');
        }
        protected static function booted()
        {
            static::creating(function ($model) {
                $model->user_register = auth()->id();
            });
            static::updating(function ($model) {
                $model->user_update = auth()->id();
            });
        }

}
