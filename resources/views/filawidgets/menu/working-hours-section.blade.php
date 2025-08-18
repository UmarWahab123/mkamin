<!-- WORKING HOURS
============================================= -->
@php
use App\Helpers\WidgetHelper;
@endphp
<section class="py-8 ct-table content-section division">
    <div class="container">
        <div class="row d-flex align-items-center">

            <!-- TABLE -->
            <div class="col-lg-6 order-last order-lg-2">
                <div class="txt-table left-column wow fadeInRight">
                    <table class="table">
                        <tbody>
                            @foreach ($workingHours as $index => $hours)
                                <tr @if ($index == count($workingHours) - 1) class="last-tr" @endif>
                                    <td style="color: {{ $workingHoursContent['dayNameColor'] }}">
                                        {{ __($hours['day']) }}</td>
                                    <td> - </td>
                                    <td class="text-end" style="color: {{ $workingHoursContent['timeColor'] }}">
                                        {{ __($hours['time']) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- END TABLE -->

            <!-- TEXT -->
            <div class="col-lg-6 order-first order-lg-2">
                <div class="right-column wow fadeInLeft">

                    <!-- Section ID -->
                    <span class="section-id" style="color: {{ $workingHoursContent['smallTitleColor'] }}">
                        {{ __($workingHoursContent['smallTitle']) }}
                    </span>

                    <!-- Title -->
                    <h2 class="h2-md" style="color: {{ $workingHoursContent['titleColor'] }}">
                        {{ __($workingHoursContent['title']) }}
                    </h2>

                    <!-- Text -->
                    <p class="mb-0" style="color: {{ $workingHoursContent['descriptionColor'] }}">
                        {{ __($workingHoursContent['description']) }}
                    </p>

                </div>
            </div>

        </div>
        <!-- End row -->
    </div>
    <!-- End container -->
</section>
<!-- END WORKING HOURS -->
