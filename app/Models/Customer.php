<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Customer extends Model
{
    use HasFactory;

    public function transactions() { return $this->hasMany(Transaction::class); }
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'gender',
        'dob',
        'customer_type_id',
        'note',
              'delivery_time',
              'foam_box_required',
              'foam_box_price',
              'use_truck_station',
              'truck_station_address',
              'truck_receive_time',
              'truck_return_time',
              'truck_return_address',
              'truck_invoice_image',
              'truck_delivery_image',
              'truck_station_phone',
              'truck_fee',
         ];
    protected $dates = ['dob'];
    protected $casts = [
        'dob' => 'date',
    ];
    

    /**
     * Quan hệ: Customer thuộc một loại khách hàng
     */
    public function type()
    {
        return $this->belongsTo(CustomerType::class, 'customer_type_id');
    }

    /**
     * Quan hệ: Customer có nhiều địa chỉ
     */
    public function addresses()
    {
        return $this->hasMany(CustomerAddress::class);
    }
}
