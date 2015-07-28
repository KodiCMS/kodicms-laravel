$(function () {
	$('select[data-related-section]').each(function () {
		var $self = $(this);
		var $ds_id = $self.data('related-section');

		$self.select2({
			minimumInputLength: 1,
			placeholder: __("Type first 1 chars to find documents"),
			ajax: {
				url: '/api.datasource.document.find',
				dataType: 'json',
				delay: 250,
				method: 'get',
				data: function (params) {
					return {
						q: params.term, // search term
						section_id: $ds_id,
						document_id: $self.val()
					};
				},
				processResults: function (data, page) {
					// parse the results into the format expected by Select2.
					// since we are using custom formatting functions we do not need to
					// alter the remote JSON data

					return {
						results: data.content
					};
				}
			}
		});
	});
});