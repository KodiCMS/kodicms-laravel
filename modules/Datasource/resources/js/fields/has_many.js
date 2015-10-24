$(function () {
	$('select[data-related-many]').each(function () {
		var $self = $(this),
			$ds_id = $self.data('related-many'),
			placeHolderText = 'Type first 1 chars to find documents';

		if($self.data('related-name').length)
			placeHolderText += ' in section: [' + $self.data('related-name') + ']';

		$self.select2({
			minimumInputLength: 1,
			multiple: true,
			placeholder: placeHolderText,
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