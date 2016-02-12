var QueryBuilderFieldType = {
    extend: function (key, data) {
        if (!data) var data = {};

        QueryBuilderFieldType[key] = $.extend({}, this._decorator, data);
        return QueryBuilderFieldType[key];
    },
    _decorator: {
        field: null,
        input: null,
        isValidate: true,
        attributes: undefined,
        construct: function (field, attributes) {
            this.field = field;
            this.attributes = attributes;
            return this;
        },
        _getInput: function (name) {
            this.input = $('<input type="text" />');
            this.input
                .addClass('form-control')
                .attr('name', name);

            if (this.field.filter.placeholder) {
                this.input.attr('placeholder', this.field.filter.placeholder);
            }

            if(_.isObject(this.attributes)) {
                this.input.attr(this.attributes);
            }

            return this.input;
        },
        getInput: function (name) {
            return this._getInput(name);
        },
        getValue: function () {
            return this.input ? this.input.val() : null;
        },
        setValues: function(values) {
            this.values = values;
        },
        getValues: function() {
            return this.values || this.field.filter.values;
        },
        setValue: function (value) {
            this.input ? this.input.val(value) : null;
            this.afterSetValue(value);
        },
        afterSetValue: function() {},
        validate: function (value) {
            if (!this.isValidate) {
                return true;
            }

            if (!this.field.filter.required) {
                return true;
            }

            var filter = this.field.filter,
                validation = filter.validation || {};

            if (value === undefined || value.length === 0) {
                return ['string_empty'];
            }
            if (validation.min !== undefined) {
                if (value.length < parseInt(validation.min)) {
                    return ['string_exceed_min_length', validation.min];
                }
            }
            if (validation.max !== undefined) {
                if (value.length > parseInt(validation.max)) {
                    return ['string_exceed_max_length', validation.max];
                }
            }
            if (validation.format) {
                if (typeof validation.format === 'string') {
                    validation.format = new RegExp(validation.format);
                }
                if (!validation.format.test(value)) {
                    return ['string_invalid_format', validation.format];
                }
            }

            return true;
        }
    }
};

QueryBuilderFieldType.extend('default');