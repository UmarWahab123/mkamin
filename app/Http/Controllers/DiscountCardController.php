<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Discount;
use App\Models\DiscountCardTemplate;
use Illuminate\Http\Request;

class DiscountCardController extends Controller
{
    /**
     * Display the discount card for a customer.
     *
     * @param Discount $discount
     * @param Customer $customer
     * @return \Illuminate\View\View
     */
    public function show(Discount $discount, Customer $customer)
    {
        // Check if this customer has this discount
        $pivotRecord = $discount->customers()->where('customer_id', $customer->id)->first();

        if (!$pivotRecord) {
            abort(404, 'This customer does not have access to this discount');
        }

        // Get the template from the pivot record
        $templateId = $pivotRecord->pivot->discount_card_template_id;
        $backgroundImage = DiscountCardTemplate::find($templateId)->image;
        // Validate template exists
        // Pass the data to the view
        return view('discount_cards.templates.card', [
            'discount' => $discount,
            'customer' => $customer,
            'backgroundImage' => $backgroundImage,
        ]);
    }
}
