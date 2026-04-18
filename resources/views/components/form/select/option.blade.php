@props([
    "opcion",
    "sync_attr" => null,
    "field_value" => "id",
    "field_name" => "nombre",
    "multiple" => false,
    "selected" => null,
    "opciones_data" => []
])
<option 
    {!! $sync_attr ? 'data-sync-id="'.$opcion->$sync_attr.'"' : '' !!}
    value="{{ $opcion->$field_value }}" 
    {!! selected($multiple && is_array($selected) ? in_array($opcion->$field_value, $selected) : (is_object($selected) ? $selected->$field_value == $opcion->$field_value : $selected == $opcion->$field_value)) !!}
    @foreach ($opciones_data as $key=>$value) {!! "data-".$key.'="' . (substr($value, 0, 1) != ':' ? $opcion->$value : substr($value, 1)) . '"' !!} @endforeach 
>
    {{ $opcion->$field_name }}
</option>