<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'invoice_item_id',
        'ticket_status_id',
        'status_updated_at',
        'point_of_sale_id',
        'ticket_detail',
    ];

    protected $casts = [
        'status_updated_at' => 'datetime',
    ];

    public function ticketStatus()
    {
        return $this->belongsTo(TicketStatus::class);
    }

    public function invoiceItem()
    {
        return $this->belongsTo(InvoiceItem::class);
    }

    public function pointOfSale()
    {
        return $this->belongsTo(PointOfSale::class);
    }
}
