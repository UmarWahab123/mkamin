<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'invoice_number',
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
        'location_type',
        'total_duration_minutes',
        'status',
        'booked_from',
        'notes',
        'total_paid_cash',
        'total_paid_online',
        'customer_id',
        'customer_detail',
        'booked_reservation_id',
        'payment_method',
        'total_amount_paid',
    ];

    // Relationships
    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
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

    public function bookedReservation()
    {
        return $this->belongsTo(BookedReservation::class);
    }

    // Computed properties
    public function getTotalPaidAttribute()
    {
        return (float)$this->total_paid_cash + (float)$this->total_paid_online;
    }

    public function getChangeGivenAttribute()
    {
        $totalPaid = $this->getTotalPaidAttribute();
        return max(0, $totalPaid - (float)$this->total_price);
    }
}
