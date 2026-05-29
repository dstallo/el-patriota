@props (['type'=>'checkbox', 'name' => null, 'errorbag' => null, 'container'=>'mb-3', 'fieldcontainer' => 'form-switch-container', 'icono' => null, 'id' => null, 'checked'=>false, 'leyend'=>null, 'label'=>null, 'label_class' => 'form-label',  'disabled' => false, 'with_feedback' => null, 'icono' => null, 'icono_texto' => '', 'placeholder' => '', 'format' => 'bootstrap3'])

<?php
if (! $id)
    $id = 'form-switch-'.Illuminate\Support\Str::random(5);
?>

<div class="{{ $container }} {{ has_error($errorbag ? $errors->$errorbag : $errors,$name) }}">
    <div class="{{ $fieldcontainer }}">
    @if ($label)    
        <div class="{{ $label_class }}">
            {{ $label }}
        @if ($icono)
            <i class="{{ $icono }}">{{ $icono_texto }}</i>
        @endif
        </div>
    @endif
        <label class="form-check form-switch">
            <input type="{{ $type }}" {{ $disabled ? 'disabled':'' }} @if ($name) name="{{ $name }}" @endif id="{{ $id }}" {{ $attributes(["class"=>"form-check-input"]) }} {{ $checked? 'checked':'' }}>
            <span class="slider round"></span>
        </label>

    @if ($leyend)
        <div class="leyend"><i class="ion ion-information-circled"></i> {{ $leyend }}</div>
    @endif

    </div>
@if($with_feedback && has_error($errorbag ? $errors->$errorbag : $errors,$name, false))
    <div class="invalid-feedback<?php if($label) echo(' with-label');?>">{{ ($errorbag ? $errors->$errorbag : $errors)->first($name) }}</div>
@endif
</div>