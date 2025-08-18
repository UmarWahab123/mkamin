<!-- Home Page Wide Image Section
============================================= -->
<!-- Home Page Wide Image Section - DYNAMIC WIDGET
============================================= -->
{{-- @php
use App\Helpers\WidgetHelper;
dd(getImageUrl($section->content['image']));
dd($section->content);
@endphp --}}
<div class="bg--scroll ct-12 content-section"
style="background-image: url({{ isset($section->content['image']) && $section->content['image'] ? getImageUrl($section->content['image'], asset('assets/images/mcs-8.jpeg')) : asset('assets/images/mcs-8.jpeg') }});">
</div>
<!-- END Home Page Wide Image Section -->