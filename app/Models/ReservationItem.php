<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Customer;
use App\Models\Reservation;
use App\Models\ProductAndService;
use App\Models\Staff;

class ReservationItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'reservation_id',
        'customer_id',
        'product_and_service_id',
        'name',
        'price',
        'duration',
        'quantity',
        'total',
        'vat_amount',
        'other_taxes_amount',
        'appointment_date',
        'start_time',
        'end_time',
        'staff_id',
        'staff_detail',
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'staff_detail' => 'array',
    ];

    // Relationships
    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function productAndService()
    {
        return $this->belongsTo(ProductAndService::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    // Helper methods to make displaying information easier
    public function getTimeRangeAttribute()
    {
        if (!$this->start_time || !$this->end_time) {
            return null;
        }

        return $this->start_time . ' - ' . $this->end_time;
    }
}
