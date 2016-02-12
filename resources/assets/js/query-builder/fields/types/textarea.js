QueryBuilderFieldType.extend('textarea', {
    getInput: function (name) {
        this.input = $('<textarea/>');
        this.input
            .addClass('form-control')
            .attr('name', name);

        if (this.field.filter.size) {
            this.input.attr('cols', this.field.filter.size);
        }

        if (this.field.filter.rows) {
            this.input.attr('rows', this.field.filter.rows);
        }

        if (this.field.filter.placeholder) {
            this.input.attr('placeholder', this.field.filter.placeholder);
        }

        Utils.iterateOptions(this.field.filter.values, function (key, val) {
            this.input.append($('<option />').attr('value', key).val(val))
        });

        return this.input;
    }
});