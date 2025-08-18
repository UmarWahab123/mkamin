@extends('discount_cards.cards_layout')

@section('styles')
    <style>
        .card-container {
            /* width: 80vmin;
                height: 114vmin;
                max-width: 400px;
                max-height: 570px; */
                width: 21cm;
                height: 29.7cm;
            position: relative;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            transform-style: preserve-3d;
            transition: transform 0.1s ease-out;
        }

        .card-background {
            width: 100%;
            height: 100%;
            background-image: url('{{ asset("storage/" . $backgroundImage) }}');
            background-size: cover;
            background-position: center;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .card-content {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            box-sizing: border-box;
            color: #6a1c1f;
            text-align: center;
            transform-style: preserve-3d;
        }

        .card-content * {
            transform: translateZ(30px);
        }

        .logo {
            width: 60px;
            margin-top: 60px;
        }

        .special-offer {
            margin-bottom: 20px;
            font-size: 3rem;
            font-family: 'Brush Script MT', cursive;
            color: #6a1c1f;
            background: rgba(206, 173, 181, 0.6);
            padding: 5px 40px;
            border-radius: 15px;
        }

        .arabic-text {
            font-size: 1.5rem;
            margin-bottom: 40px;
        }

        .card-form {
            width: 100%;
            text-align: left;
            margin-top: auto;
            margin-bottom: 20px;
        }

        .form-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            position: relative;
        }

        /* .form-row::after {
                        content: "";
                        position: absolute;
                        bottom: -5px;
                        left: 27%;
                        width: 50%;
                        height: 1px;
                        background-color: #6a1c1f;
                        opacity: 0.6;
                    } */
        .label-en {
            flex: 1;
            text-align: left;
            font-size: 1.2rem;
            letter-spacing: 3px;
            text-align: justify;
        }

        .label-ar {
            flex: 1;
            text-align: right;
            font-size: 1.2rem;
        }

        .label-value {
            text-align: center;
            font-size: 1.2rem;
            text-align: justify;
        }

        .discount-code {
            /* font-weight: bold; */
            font-size: 1.5rem;
        }

        .discount-detail {
            margin: 10px;
        }
    </style>
    <!-- Print styles -->
    <style media="print">
        @page {
            size: A5 portrait;
            margin: 0mm;
        }

        body {
            background-color: white;
            margin: 0;
            padding: 0;
            height: 100%;
        }

        .card-container {
            box-shadow: none;
            page-break-inside: avoid;
            transform: none !important;
        }

        .card-background {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            color-adjust: exact !important;
        }

        .card-content * {
            transform: none !important;
        }
    </style>
@endsection

@section('content')
    <div class="card-container" id="tilt-card">
        <div class="card-background"></div>
        <div class="card-content">
            {{-- <div class="logo">
        <svg width="60" height="60" viewBox="0 0 60 60" fill="none">
            <path d="M30 10C35 15 45 10 50 20C45 25 30 35 30 35C30 35 15 25 10 20C15 10 25 15 30 10Z" fill="#6a1c1f"/>
        </svg>
    </div> --}}
            {{-- <div class="special-offer">Special Offer</div>
    <div class="arabic-text">لعملائنا المميزين</div> --}}
            <div class="card-form">
                <div class="discount-detail">
                    <div class="form-row">
                        <div class="label-en">Name :</div>
                        <div class="label-value">{{ $customer->name }}</div>
                        <div class="label-ar">: الاســــــم</div>
                    </div>
                    <div class="form-row">
                        <div class="label-en">Start Date :</div>
                        <div iv class="label-value">{{ $discount->formattedDates['start_date'] }}</div>
                        <div class="label-ar">: تاريخ البدء</div>
                    </div>
                    <div class="form-row">
                        <div class="label-en">End Date :</div>
                        <div iv class="label-value">{{ $discount->formattedDates['end_date'] }}</div>
                        <div class="label-ar">: تاريخ الانتهاء</div>
                    </div>
                    <div class="form-row">
                        <div class="label-en">Discount value:</div>
                        <div class="label-value">{!! $discount->type == 'percentage' ? '%' : '<span class="icon-saudi_riyal"></span>' !!} {{ $discount->amount }} OFF</div>
                        <div class="label-ar">: قيمة الخصم</div>
                    </div>
                    <div class="form-row">
                        <div class="label-en">Discount code:</div>
                        <div class="label-value discount-code">{{ $discount->code }}</div>
                        <div class="label-ar">: كود الخصم</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const card = document.getElementById('tilt-card');

            // Values for the tilt effect
            const maxTilt = 15; // Maximum tilt rotation in degrees

            card.addEventListener('mousemove', handleMouseMove);
            card.addEventListener('mouseleave', resetTilt);
            card.addEventListener('mouseenter', setTransition);

            function handleMouseMove(event) {
                const rect = card.getBoundingClientRect();

                // Calculate mouse position relative to card center (in percentage from -0.5 to 0.5)
                const xPos = (event.clientX - rect.left) / rect.width - 0.5;
                const yPos = (event.clientY - rect.top) / rect.height - 0.5;

                // Calculate tilt angles based on mouse position
                const tiltX = -yPos * maxTilt; // Invert Y for natural tilting
                const tiltY = xPos * maxTilt;

                // Apply the tilt effect
                card.style.transform = `rotateX(${tiltX}deg) rotateY(${tiltY}deg)`;

                // Add slight scale effect on hover
                card.style.transform += ' scale(1.03)';
            }

            function resetTilt() {
                card.style.transform = 'rotateX(0deg) rotateY(0deg)';
                card.style.transition = 'transform 0.5s ease-out';
            }

            function setTransition() {
                card.style.transition = 'transform 0.1s ease-out';
            }
        });
    </script>
@endsection