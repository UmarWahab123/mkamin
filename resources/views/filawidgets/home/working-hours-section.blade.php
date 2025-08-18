<section class="py-8 ct-table content-section division">
            <div class="container">
                <div class="row d-flex align-items-center">
                    <!-- TABLE -->
                    <div class="col-lg-6 order-last order-lg-2">
                        <div class="txt-table left-column wow fadeInRight">
                            <table class="table">
                                <tbody>
                                    @foreach($section->content['workingHours'] as $index => $hours)
                                        <tr @if($index == count($section->content['workingHours']) - 1) class="last-tr" @endif>
                                            <td style="color: {{ $section->content['dayNameColor'] ?? '#333' }}">
                                                {{ __($hours['day'] ?? 'Day') }}
                                            </td>
                                            <td> - </td>
                                            <td class="text-end" style="color: {{ $section->content['timeColor'] ?? '#666' }}">
                                                {{ __($hours['time'] ?? 'Time') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- TEXT -->
                    <div class="col-lg-6 order-first order-lg-2">
                        <div class="right-column wow fadeInLeft">
                            <span class="section-id" style="color: {{ $section->content['smallTitleColor'] ?? '#e74c3c' }}">
                                {{ __($section->content['smallTitle'] ?? 'Working Hours') }}
                            </span>
                            <h2 class="h2-md" style="color: {{ $section->content['titleColor'] ?? '#2c3e50' }}">
                                {{ __($section->content['title'] ?? 'Our Schedule') }}
                            </h2>
                            <p class="mb-0" style="color: {{ $section->content['descriptionColor'] ?? '#666' }}">
                                {{ __($section->content['description'] ?? 'Contact us during our business hours for appointments and inquiries.') }}
                            </p>

                            @if(isset($qrCode))
                                <div class="row justify-content-center mt-2">
                                    <div class="col-auto">
                                        <div class="qr-code-container text-center">
                                            <div class="card p-3 shadow-sm">
                                                {!! $qrCode !!}
                                                <h6 class="mt-2">{{ __('Book Now') }}</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>