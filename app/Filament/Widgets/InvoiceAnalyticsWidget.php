<?php

namespace App\Filament\Widgets;

use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Invoice;
use App\Models\PointOfSale;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class InvoiceAnalyticsWidget extends BaseWidget
{
    use HasWidgetShield;

    protected static ?string $pollingInterval = '15s';

    protected function getStats(): array
    {
        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();

        // Get query builder and apply point of sale filter if needed
        $query = $this->getInvoiceQuery();

        // Today's Revenue
        $todayRevenue = $query->clone()
            ->whereDate('created_at', $today)
            ->sum('total_price');

        // Today's Invoices Count
        $todayInvoices = $query->clone()
            ->whereDate('created_at', $today)
            ->count();

        // Online vs Cash Payments
        $payments = $query->clone()
            ->whereDate('created_at', $today)
            ->selectRaw('SUM(total_paid_online) as online_total, SUM(total_paid_cash) as cash_total')
            ->first();

        $onlinePayments = $payments->online_total ?? 0;
        $cashPayments = $payments->cash_total ?? 0;

        // Monthly Revenue
        $monthlyRevenue = $query->clone()
            ->whereBetween('created_at', [$startOfMonth, now()])
            ->sum('total_price');

        // VAT Collected
        $vatCollected = $query->clone()
            ->whereDate('created_at', $today)
            ->sum('vat_amount');

        // Total Discounts
        $totalDiscounts = $query->clone()
            ->whereDate('created_at', $today)
            ->sum('discount_amount');

        return [
            Stat::make('Today\'s Revenue', number_format($todayRevenue, 2) . ' SAR')
                ->description('Total revenue for today')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success'),

            Stat::make('Today\'s Invoices', $todayInvoices)
                ->description('Number of invoices today')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('info'),

            Stat::make('Payment Methods', "Online: " . number_format($onlinePayments, 2) . " SAR | Cash: " . number_format($cashPayments, 2) . " SAR")
                ->description('Today\'s payment distribution')
                ->descriptionIcon('heroicon-m-credit-card')
                ->color('warning'),

            Stat::make('Monthly Revenue', number_format($monthlyRevenue, 2) . ' SAR')
                ->description('Revenue this month')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('success'),

            Stat::make('VAT Collected', number_format($vatCollected, 2) . ' SAR')
                ->description('VAT collected today')
                ->descriptionIcon('heroicon-m-receipt-percent')
                ->color('info'),

            Stat::make('Total Discounts', number_format($totalDiscounts, 2) . ' SAR')
                ->description('Discounts given today')
                ->descriptionIcon('heroicon-m-gift')
                ->color('danger'),
        ];
    }

    /**
     * Get base invoice query with appropriate filters
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function getInvoiceQuery()
    {
        $user = Auth::user();
        $query = Invoice::query();

        // Check if user is a point of sale user
        if ($user && $user->isPointOfSale()) {
                $query->where('point_of_sale_id', $user->pointOfSale->id);
        }

        return $query;
    }
}
