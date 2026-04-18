@props([
    "id"=>null, 
    "container" => 'col-md-6', 
    'name', 
    'format' => 'bootstrap3', 
    'data' => [], 
    'class' => '', 
    'sync_with' =>null, 
    'sync_attr' => null, 
    'sync_group_attr' => 'id', 
    'sync_no_trigger_change' => false, 
    'group_by' => null, 
    'group_by_field' => 'nombre', 
    'multiple' => false, 
    'style' => null, 
    'no_search' => false, 
    'autocomplete' => null, 
    'leyend' => null, 
    'errorbag' => null, 
    'label' => null, 
    'with_feedback' => null, 
    'icono' => null, 
    'icono_texto' => '', 
    'allow_clear' => false, 
    'placeholder' => null, 
    'opciones' => [], 
    'selected' => null, 
    'form_group' => 'form-group', 
    'leyend' => null,
    'field_value' => 'id',
    'field_name' => 'nombre',
    'opciones_data' => [],
    'tags' => false
])
<div class="{{ $container }} {{ $form_group }} {{ has_error($errorbag ? $errors->$errorbag : $errors,$name) }}">
@if ($label)
    <label class="form-label">{{ $label }}</label>
@endif
@if ($icono)
<div class="input-group">
    <span class="{{ $format == 'bootstrap3' ? 'input-group-addon' : 'input-group-text' }}"><i class="{{ $icono }}">{{ $icono_texto }}</i></span>
@endif
    <select 
        @if ($id) id="{{ $id }}" @endif 
        name="{{ $name }}" 
        class="form-control select2 {{ $class }} {{ $multiple ? 'select2-multiple':'' }}" 
        data-placeholder="{{ $placeholder }}" 
        @foreach ($data as $key=>$value) {!! "data-".$key.'="'.$value.'"' !!} @endforeach 
        {{ $allow_clear ? 'data-allow-clear="true"':'' }} 
        @if($sync_with) data-sync-with="{{ $sync_with }}" @endif
        @if($sync_no_trigger_change) data-sync-no-trigger-change="{{ $sync_no_trigger_change }}" @endif 
        {{ $multiple? ' multiple':'' }} 
        {!! $style ? 'style="'.$style.'"' : '' !!} 
        {!! $no_search ? 'data-minimum-results-for-search="-1"' : '' !!}
        {!! $tags ? 'data-tags="true"' : '' !!}
        {{ $attributes->merge() }}
    >
    @if (! $multiple)    
        <option></option>
    @endif
    @if (! $group_by)
        @foreach($opciones as $opcion)
            <x-form.select.option :opcion="$opcion" :sync_attr="$sync_attr" :field_value="$field_value" :field_name="$field_name" :multiple="$multiple" :selected="$selected" :opciones_data="$opciones_data" />
        @endforeach
    @else
        @foreach($opciones as $group)
            <optgroup {!! $sync_group_attr ? 'data-sync-id="'.$group->$sync_group_attr.'"' : '' !!} label="{{ $group->$group_by_field }}">
            @foreach($group->$group_by as $opcion)
                <x-form.select.option :opcion="$opcion" :sync_attr="$sync_attr" :field_value="$field_value" :field_name="$field_name" :multiple="$multiple" :selected="$selected" :opciones_data="$opciones_data" />
            @endforeach
            </optgroup>
        @endforeach
    @endif
    </select>
@if ($icono)
</div>
@endif
    @if ($leyend)
        <div class="leyend"><span class="fa fa-info-circle"></span> {{ $leyend }}</div>
    @endif
    @if ($with_feedback && has_error($errorbag ? $errors->$errorbag : $errors,$name, false))
        <div class="invalid-feedback">{{ ($errorbag ? $errors->$errorbag : $errors)->first($name) }}</div>
    @endif
</div>