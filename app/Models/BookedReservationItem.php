<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Customer;
use App\Models\BookedReservation;
use App\Models\ProductAndService;
use App\Models\Staff;

class BookedReservationItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'booked_reservation_id',
        'customer_id',
        'product_and_service_id',
        'name_en',
        'name_ar',
        'unique_id',
        'image',
        'service_location',
        'price',
        'duration',
        'quantity',
        'total',
        'subtotal',
        'price_after_discount',
        'other_discount_value',
        'other_discount_type',
        'other_discount_amount',
        'vat_amount',
        'other_taxes_amount',
        'appointment_date',
        'start_time',
        'end_time',
        'staff_id',
        'staff_detail',
        'location_type',
    ];

    protected $appends = ['name']; // Ensures these attributes are available in JSON responses

    // Relationships
    public function bookedReservation()
    {
        return $this->belongsTo(BookedReservation::class);
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

    // Accessor for 'name'
    public function getNameAttribute()
    {
        $locale = app()->getLocale();
        return $this->attributes["name_{$locale}"] ?? $this->attributes['name_en']; // Fallback to English
    }
}
