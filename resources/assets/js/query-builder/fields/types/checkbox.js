QueryBuilderFieldType.extend('checkbox', {
    values: false,
    getInput: function (name) {
        var that = this,
            $cont = $('<span class="checkbox checkbox-success" />'),
            type = 'checkbox';

        if (!this.field.multiple) {
            type = 'radio'
        }

        Utils.iterateOptions(this.getValues(), function (key, val) {
            var $input = $('<input type="' + type + '" id="' + name + '" name="' + name + '" value="' + key + '" class="styled checkboxToggle-apply" />');
            $cont.append($input);
            $('<label for="' + name + '"/>').insertAfter($input).text(val)
        });
        this.input = $cont;

        return this.input;
    },
    getValue: function () {
        tmp = [];
        this.input.find('input:checked').each(function () {
            tmp.push($(this).val());
        });
        return tmp;
    },
    setValue: function (value) {
        var that = this;
        value.forEach(function (value) {
            that.input.find('[value="' + value + '"]').prop('checked', true).trigger('change');
        });
    }
});