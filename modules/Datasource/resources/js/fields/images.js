$(function () {
	function formatData(data)
	{
		return $('<span><image src="/' +data.image+ '" style="height: 50px;" class="img-thumbnail"/> ' + data.text + '</span>');
	}

	$('select[data-related-images]').each(function () {
		var $self = $(this),
			$ds_id = $self.data('related-images'),
			placeHolderText = 'Type first 1 chars to find documents';

		if($self.data('related-name').length)
			placeHolderText += ' in section: [' + $self.data('related-name') + ']';

		$self.select2({
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
					return {
						results: data.content
					};
				}
			},
			templateResult: formatData,
			templateSelection: formatData
		});
	});
});