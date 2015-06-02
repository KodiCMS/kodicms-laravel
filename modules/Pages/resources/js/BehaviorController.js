CMS.controllers.add(['page.get.edit', 'page.get.create'], function () {
	var loadBehaviorData = function(behaviorId) {
		var $cont = $('#behavor_options_container');
		if(behaviorId.length > 0) {
			var pageId = ((typeof PAGE != 'undefined')&&PAGE.id)||null
			Api.get('/api.page.behavior.settings', {pid: pageId, behavior: behaviorId}, function(resp) {
				$('#behavor_options').html(resp.content);
				$cont.addClass('well well-sm');
			});
		} else {
			$cont.removeClass('well well-sm');
			$('#behavor_options').empty();
		}
	};	

	var behaviorId = $('select[name="behavior"]').on('change', function() {
		loadBehaviorData($(this).val());
	}).find('option:selected').val();

	loadBehaviorData(behaviorId);
});