@props(["url" => "imagen", "class" => "banner", "banner"])
<div class="{{ $class }}">
    @if ($banner->linkContador())<a href="{{ $banner->linkContador() }}" target="_blank">@endif<img src="{{ $url == 'responsive' ? $banner->urlImagenResponsive() : $banner->url($url) }}">@if ($banner->linkContador())</a>@endif
</div>