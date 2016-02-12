QueryBuilderFieldType.extend('select', {
    getInput: function (name, value) {
        var that = this;
        this.input = $('<select />');
        this.input
            .addClass('form-control')
            .attr('name', name);

        if (this.field.multiple) {
            this.input.attr('multiple', 'multiple');
        }

        Utils.iterateOptions(this.getValues(), function (key, val) {
            that.input.append($('<option value="' + key + '">' + val + '</option>'));
        });

        return this.input;
    },
    validate: function (value) {
        var filter = this.field.filter,
            operator = this.field.operator;

        if (filter.multiple) {
            if (value === undefined || value.length === 0 || (filter.placeholder && value == filter.placeholder_value)) {
                return ['select_empty'];
            }
            else if (!operator.multiple && value.length > 1) {
                return ['operator_not_multiple', operator.type];
            }
        }
        else {
            if (value === undefined || (filter.placeholder && value == filter.placeholder_value)) {
                return ['select_empty'];
            }
        }

        return true;
    }
});