CMS.controllers.add('system.about', function () {
	function calculateEditorHeight() {
		var conentH = CMS.calculateContentHeight();
		var h = 90;
		
		return conentH - h;
	}
	
	$('#phpinfo').height(calculateEditorHeight());
	$(window).resize(function() {
		$('#phpinfo').height(calculateEditorHeight());
	});
});

CMS.controllers.add('system.settings', function () {
	$('#ace-select').on('change', function() {
		change_ace_theme($(this).val());
	});

	CMS.filters.switchOn('highlight_content', 'ace', $('#highlight_content').data());

	function change_ace_theme(theme) {
		var editor = ace.edit('highlight_contentDiv');
		editor.setTheme("ace/theme/" + theme);
	}
});

CMS.controllers.add('system.update', function() {
	CMS.loader.show('#files');

	Api.get('/api.updates.check', {}, function (response) {
		CMS.loader.hide();
		$('#files').html(response.content);
		CMS.ui.init('icon')
	})

	$('#files').on('click', '.show-diff', function () {
		var $li = $(this).closest('.list-group-item');
		var path = $li.data('path');
		if (!path) return false;

		CMS.loader.show('#files');

		Api.get('/api.updates.diff', {path: path}, function (response) {
			$('.diff-container').remove();

			$('<li class="diff-container list-group-item no-padding" />')
				.html(response.content)
				.insertAfter($li);

			CMS.loader.hide();
			diff();
		});
	});

	function diff() {
		var b = document.getElementById('localFile');
		var a = document.getElementById('remoteFile');
		var result = document.getElementById('resultDiff');

		var diff = JsDiff.diffLines(a.textContent, b.textContent);
		var fragment = document.createDocumentFragment();

		for (var i = 0; i < diff.length; i++) {

			if (diff[i].added && diff[i + 1] && diff[i + 1].removed) {
				var swap = diff[i];
				diff[i] = diff[i + 1];
				diff[i + 1] = swap;
			}

			var node;
			if (diff[i].removed) {
				node = document.createElement('del');
				node.appendChild(document.createTextNode(diff[i].value));
			} else if (diff[i].added) {
				node = document.createElement('ins');
				node.appendChild(document.createTextNode(diff[i].value));
			} else {
				node = document.createTextNode('...\n');
			}
			fragment.appendChild(node);
		}

		result.textContent = '';
		if(fragment.childNodes.length > 1)
			result.appendChild(fragment);
		else
			$('.diff-container').remove();
	}
});