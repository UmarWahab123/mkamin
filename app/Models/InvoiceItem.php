<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoiceItem extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'invoice_id',
        'invoice_number',
        'product_and_service_id',
        'product_service_id',
        'name_en',
        'name_ar',
        'unique_id',
        'image',
        'service_location',
        'price',
        'unit_price',
        'duration',
        'quantity',
        'total',
        'total_price',
        'subtotal',
        'price_after_discount',
        'other_discount_value',
        'other_discount_type',
        'other_discount_amount',
        'appointment_date',
        'start_time',
        'customer_id',
        'end_time',
        'staff_id',
        'staff_detail',
        'location_type',
        'vat_amount',
        'other_taxes_amount',
        'tax_amount',
        'discount_amount',
    ];
    protected $appends = ['name']; // Ensures these attributes are available in JSON responses

    /**
     * Get the invoice that owns this item.
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Get the product or service associated with this item.
     */
    public function productAndService()
    {
        return $this->belongsTo(ProductAndService::class);
    }

    /**
     * Get the staff member associated with this item.
     */
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

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // Accessor for 'name'
    public function getNameAttribute()
    {
        $locale = app()->getLocale();
        return $this->attributes["name_{$locale}"] ?? $this->attributes['name_en']; // Fallback to English
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
