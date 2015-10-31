$(function () {
    $('.tags-input').tagsInput({
        autocomplete_url: '/api.datasource.tags.',
        autocomplete: {
            selectFirst: true,
            autoFill: true,
            source: function (tag, response) {
                var sectionId = this.element.closest('.tagsinput').prev().data('related-id');

                var request = Api.get('/api.datasource.tags', {section_id: sectionId, tag: tag.term}, null, false);

                var tags = [];
                if (request.responseJSON.content) {
                    for (i in request.responseJSON.content) {
                        var tag = request.responseJSON.content[i];
                        tags.push(tag.name);
                    }
                }

                response(tags);
            }
        },
        width: '100%'
    });
});