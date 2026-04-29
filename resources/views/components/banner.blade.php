@props(["class" => "banner", "banner"])
<div class="{{ $class }}">
    @if ($banner->linkContador())<a href="{{ $banner->linkContador() }}" target="_blank">@endif
        <img src="{{ $banner->url("imagen") }}" alt="" class="banner-img-desktop" />
        <img src="{{ $banner->url("imagen_responsive") }}" alt="" class="banner-img-celular" />
    @if ($banner->linkContador())</a>@endif
</div>