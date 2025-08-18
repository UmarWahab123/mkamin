<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Customer;
use App\Models\BookedReservationItem;
use App\Models\Invoice;
use App\Models\PointOfSale;
use App\Models\Discount;

class BookedReservation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_id',
        'point_of_sale_id',
        'reservation_date',
        'start_time',
        'end_time',
        'subtotal',
        'vat_amount',
        'other_taxes_amount',
        'total_price',
        'discount_amount',
        'discount_code',
        'discount_id',
        'other_total_discount_amount',
        'other_total_discount_value',
        'other_total_discount_type',
        'status',
        'booked_from',
        'notes',
        'payment_method',
        'location_type',
        'address',
        'latitude',
        'longitude',
        'total_duration_minutes',
        'total_paid_cash',
        'total_paid_online',
        'total_amount_paid',
        'customer_detail',
    ];

    // Relationships
    public function items()
    {
        return $this->hasMany(BookedReservationItem::class);
    }

    /**
     * Get the invoice associated with this reservation.
     */
    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function pointOfSale()
    {
        return $this->belongsTo(PointOfSale::class);
    }

    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }

    // Computed properties
    public function getTotalPaidAttribute()
    {
        return $this->total_paid_cash + $this->total_paid_online;
    }

    public function getChangeGivenAttribute()
    {
        return max(0, $this->getTotalPaidAttribute() - $this->total_price);
    }
}
