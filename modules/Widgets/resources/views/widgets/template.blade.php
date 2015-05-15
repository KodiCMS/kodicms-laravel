@section('scripts')
<script type="text/javascript">
$(function() {
	$('#textarea_content').on('filter:switch:on', function(e, editor) {
		$('#content').setHeightFor('#textarea_contentDiv', {
			contentHeight: true,
			updateOnResize: true,
			offset: 30,
			minHeight: 300,
			onCalculate: function(a, h) {
				CMS.filters.exec('textarea_content', 'changeHeight', h);
			},
			onResize: function(a, h) {
				CMS.filters.exec('textarea_content', 'changeHeight', h);
			}
		});
	});

	CMS.filters.switchOn('textarea_content', DEFAULT_CODE_EDITOR, $('#textarea_content').data());
});
</script>
@stop

<textarea id="textarea_content" data-readonly="on">{{ $content }}</textarea>