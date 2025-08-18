@php use Illuminate\Support\Str; @endphp
<div class="bg--01 bg--scroll ct-12 content-section"
    @if(!empty($content['image']))
        style="background-image: url('{{ Str::startsWith($content['image'], ['/','http']) ? asset($content['image']) : asset('storage/'.$content['image']) }}'); background-size:cover; background-position:center;"
    @endif>
</div>
