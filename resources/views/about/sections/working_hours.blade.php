@php use Illuminate\Support\Str; @endphp
<section class="py-8 ct-table content-section division">
    <div class="container">
        <div class="row d-flex align-items-center">

            <div class="col-lg-6 order-last order-lg-2">
                <div class="txt-table left-column wow fadeInRight">
                    <table class="table">
                        <tbody>
                            @foreach ($content['working_hours'] ?? [] as $index => $hours)
                                <tr @if ($index == count($content['working_hours'] ?? []) - 1) class="last-tr" @endif>
                                    <td style="color: {{ $content['dayNameColor'] ?? '#000' }}">
                                        {{ __($hours['day']) }}</td>
                                    <td> - </td>
                                    <td class="text-end" style="color: {{ $content['timeColor'] ?? '#000' }}">
                                        {{ __($hours['time']) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-lg-6 order-first order-lg-2">
                <div class="right-column wow fadeInLeft">
                    <span class="section-id" style="color: {{ $content['smallTitleColor'] ?? '#333' }}">
                        {{ __($content['smallTitle'] ?? 'Working Hours') }}
                    </span>

                    <h2 class="h2-md" style="color: {{ $content['titleColor'] ?? '#000' }}">
                        {{ __($content['title'] ?? 'Visit Us Today') }}
                    </h2>

                    <p class="mb-0" style="color: {{ $content['descriptionColor'] ?? '#666' }}">
                        {{ __($content['description'] ?? '') }}
                    </p>

                    @if(!empty($content['show_qr']) && !empty($content['qr_html']))
                        <div class="row justify-content-center mt-2">
                            <div class="col-auto">
                                <div class="qr-code-container text-center">
                                    <div class="card p-3 shadow-sm">
                                        {!! $content['qr_html'] !!}
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
