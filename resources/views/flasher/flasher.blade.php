<script>
@if ($flash=flasher()->get())
	sweetAlert("{{ $flash['titulo'] }}", "{{ $flash['mensaje'] }}", "{{ $flash['tipo'] }}");
@endif
</script>