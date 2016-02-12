function getQueryBuildField(rule) {
    type = rule.filter.type || rule.filter.input;
    if (_.has(QueryBuilderField, type)) {
        var field = Object.create(QueryBuilderField[type]);
    } else {
        var field = Object.create(QueryBuilderField['default']);
    }

    return field.construct(rule);
}

var QueryBuilderField = {
    extend: function (key, data) {
        if (!data) var data = {};

        QueryBuilderField[key] = $.extend({}, this._decorator, data);
        return QueryBuilderField[key];
    },
    _decorator: {
        rule: null,
        filter: null,
        validation: null,
        operator: null,
        multiple: false,
        value: null,
        inputs: [],
        construct: function (rule) {
            this.rule = rule;
            this.filter = this.rule.filter;
            this.validation = this.filter.validation || {};
            this.operator = this.rule.operator;

            if (rule.operator.multiple) {
                this.multiple = true;
            }

            return this;
        },
        validate: function (value) {
            var result = true;

            for (i in this.inputs) {
                var input = this.inputs[i];

                if (input instanceof jQuery) {
                    result = _.isEmpty(input.val()) ? ['string_empty'] : true;
                } else {
                    result = this.inputs[i].validate(this.inputs[i].getValue());
                }

                if (result !== true) {
                    return result;
                }
            }

            return result;
        },
        getInputs: function () {
            this.inputs = [];
            var $inputs = [];

            for (var i = 0; i < this.operator.nb_inputs; i++) {
                if (_.has(QueryBuilderFieldType, this.filter.input)) {
                    var input = Object.create(QueryBuilderFieldType[this.filter.input]);
                } else {
                    var input = Object.create(QueryBuilderFieldType['default']);
                }

                input = input.construct(this);
                this.inputs.push(input);
                $inputs.push(input.getInput(this.rule.id + i));
            }

            return $inputs;
        },
        getValue: function () {
            var values = [];

            if (_.isEmpty(this.inputs)) {
                return values;
            }

            for (i in this.inputs) {
                var input = this.inputs[i];

                if (input instanceof jQuery) {
                    values.push(input.val());
                } else {
                    values.push(input.getValue());
                }
            }

            return values;
        },
        setValue: function (value) {
            this.value = value;

            for (i in this.inputs) {
                var input = this.inputs[i];

                if (input instanceof jQuery) {
                    input.val(value[i]);
                } else {
                    input.setValue(value[i])
                }
            }
        }
    }
};

QueryBuilderField.extend('default');