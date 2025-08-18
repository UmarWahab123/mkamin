@extends('layouts.app')

@section('title', __('My Bookings - Salon'))

@section('content')
<div class="container py-6">
    <div class="row mt-8 justify-content-center">
        <div class="col-md-12">
            <h2 class="text-center mb-4">{{ __('My Bookings') }}</h2>

            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            @if ($bookings->isEmpty())
                <div class="card shadow-sm">
                    <div class="card-body text-center py-5">
                        <p class="mb-0">{{ __('You have no bookings yet.') }}</p>
                        <a href="{{ route('salon-services') }}" class="btn btn-primary mt-3">{{ __('Book a Service') }}</a>
                    </div>
                </div>
            @else
                <div class="card shadow-sm">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>{{ __('Service') }}</th>
                                        <th>{{ __('Location') }}</th>
                                        <th>{{ __('Date') }}</th>
                                        <th>{{ __('Total') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($bookings as $booking)
                                        <tr>
                                            <td>
                                                @if ($booking->items->isNotEmpty())
                                                    {{ $booking->items->first()->name }}
                                                    @if($booking->items->count() > 1)
                                                        <span class="badge bg-info">+{{ $booking->items->count() - 1 }}</span>
                                                    @endif
                                                @else
                                                    {{ __('N/A') }}
                                                @endif
                                            </td>
                                            <td>{{ ucfirst($booking->location_type) }}</td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($booking->reservation_date)->format('M d, Y') }} <br>
                                                {{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('h:i A') }}
                                            </td>
                                            <td>{{ number_format($booking->total_price, 2) }}</td>
                                            <td>
                                                <span class="badge {{
                                                    $booking->status == 'completed' ? 'bg-success' :
                                                    ($booking->status == 'cancelled' ? 'bg-danger' :
                                                    ($booking->status == 'confirmed' ? 'bg-info' :
                                                    ($booking->status == 'pending' ? 'bg-warning' : 'bg-secondary')))
                                                }}">
                                                    {{ ucfirst($booking->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <a href="{{ route('booking.confirmation', ['id' => $booking->id]) }}" class="badge bg-info text-decoration-none">
                                                        <i class="fas fa-eye"></i> {{ __('View') }}
                                                    </a>
                                                    {{-- @if ($booking->invoice && !in_array($booking->status, ['pending', 'cancelled']))
                                                        <a href="{{ route('invoices.print', $booking->invoice->id) }}" class="badge bg-secondary text-decoration-none" target="_blank">
                                                            <i class="fas fa-print"></i> {{ __('Print') }}
                                                        </a>
                                                    @endif --}}
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- <div class="d-flex justify-content-center mt-4">
                    {{ $bookings->links() }}
                </div> --}}
            @endif
        </div>
    </div>
</div>
    <!-- Cart Float Component -->
    @include('components.cart-float')
@endsection
