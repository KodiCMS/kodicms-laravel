<div class="panel dashboard-widget panel-info panel-body-colorful panel-dark" data-id="{{ $widget->getId() }}">
	<button type="button" class="close remove_widget">{!! UI::icon('times') !!}</button>
	<button type="button" class="settings">{!! UI::icon('cog') !!}</button>

	<div class="panel-body text-lg handle">
		<i class="fa fa-calendar fa-2x"></i>&nbsp;&nbsp;<span class="time-container"></span>
	</div>
</div>

@section('scripts')
<script type="text/javascript">
$(function(){
	var update_dashboard_calendar = function () {
		$('.time-container').html(moment(new Date()).format('{{ $format }}'));
		setTimeout(function () { update_dashboard_calendar(); }, 60000);
	};
	update_dashboard_calendar();
});
</script>
@stop