<script>
$(function() {
	function formatState(state) {
		if (!state.id) return state.text; // optgroup
		return $("<span><i class='fa fa-" + state.id + " fa-fw fa-lg'/>" + state.text+"</span>");
	}
	$("#icons").select2({
		templateResult: formatState,
		templateSelection: formatState
	});
});
</script>

{!! Form::select('icon', array_unique(config('icons', [])), null, ['class' => 'form-control', 'id' => 'icons']) !!}