<!-- Home Page Wide Image Section
============================================= -->
@php
use App\Helpers\WidgetHelper;
@endphp
<div class="bg--scroll ct-12 content-section"
style="background-image: url({{ $wideImage ? WidgetHelper::getImageUrl($wideImage) : asset('assets/images/mcs-8.jpeg') }});">
</div>
<!-- END Home Page Wide Image Section -->
