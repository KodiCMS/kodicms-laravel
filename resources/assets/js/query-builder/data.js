/*jshint loopfunc:true */

/**
 * Check if a value is correct for a filter
 * @param rule {Rule}
 * @param value {string|string[]|undefined}
 * @return {array|true}
 */
QueryBuilder.prototype.validateValue = function (rule, value) {
    var validation = rule.filter.validation || {},
        result = true;

    if (validation.callback) {
        result = validation.callback.call(this, value, rule);
    }
    else {
        result = this.validateValueInternal(rule, value);
    }

    return this.change('validateValue', result, value, rule);
};

/**
 * Default validation function
 * @throws ConfigError
 * @param rule {Rule}
 * @param value {string|string[]|undefined}
 * @return {array|true}
 */
QueryBuilder.prototype.validateValueInternal = function (rule, value) {
    return rule.field.validate(value);
};

/**
 * Returns an incremented group ID
 * @return {string}
 */
QueryBuilder.prototype.nextGroupId = function () {
    return this.status.id + '_group_' + (this.status.group_id++);
};

/**
 * Returns an incremented rule ID
 * @return {string}
 */
QueryBuilder.prototype.nextRuleId = function () {
    return this.status.id + '_rule_' + (this.status.rule_id++);
};

/**
 * Returns the operators for a filter
 * @param filter {string|object} (filter id name or filter object)
 * @return {object[]}
 */
QueryBuilder.prototype.getOperators = function (filter) {
    if (typeof filter === 'string') {
        filter = this.getFilterById(filter);
    }

    var result = [],
        type = QueryBuilder.types[filter.type] || QueryBuilder.types[filter.input];

    for (var i = 0, l = this.operators.length; i < l; i++) {
        // filter operators check
        if (filter.operators) {
            if (filter.operators.indexOf(this.operators[i].type) == -1) {
                continue;
            }
        }
        // type check
        else if (this.operators[i].apply_to.indexOf(type) == -1) {
            continue;
        }

        result.push(this.operators[i]);
    }

    if (_.isEmpty(result)) {
        result.push(this.operators[0]);
    }

    // keep sort order defined for the filter
    if (filter.operators) {
        result.sort(function (a, b) {
            return filter.operators.indexOf(a.type) - filter.operators.indexOf(b.type);
        });
    }

    return this.change('getOperators', result, filter);
};

/**
 * Returns a particular filter by its id
 * @throws UndefinedFilterError
 * @param filterId {string}
 * @return {object|null}
 */
QueryBuilder.prototype.getFilterById = function (id) {
    if (id == '-1') {
        return null;
    }

    for (var i = 0, l = this.filters.length; i < l; i++) {
        if (this.filters[i].id == id) {
            return this.filters[i];
        }
    }

    Utils.error('UndefinedFilter', 'Undefined filter "{0}"', id);
};

/**
 * Return a particular operator by its type
 * @throws UndefinedOperatorError
 * @param type {string}
 * @return {object|null}
 */
QueryBuilder.prototype.getOperatorByType = function (type) {
    if (type == '-1') {
        return null;
    }

    for (var i = 0, l = this.operators.length; i < l; i++) {
        if (this.operators[i].type == type) {
            return this.operators[i];
        }
    }

    Utils.error('UndefinedOperator', 'Undefined operator "{0}"', type);
};

/**
 * Returns rule value
 * @param rule {Rule}
 * @return {mixed}
 */
QueryBuilder.prototype.getRuleValue = function (rule) {
    value = rule.field.getValue();
    return this.change('getRuleValue', value, rule);
};

/**
 * Sets the value of a rule.
 * @param rule {Rule}
 * @param value {mixed}
 */
QueryBuilder.prototype.setRuleValue = function (rule, value) {
    var filter = rule.filter,
        operator = rule.operator;

    if (filter.valueSetter) {
        filter.valueSetter.call(this, rule, value);
    }

    rule.field.setValue(value);
};

/**
 * Clean rule flags.
 * @param rule {object}
 * @return {object}
 */
QueryBuilder.prototype.parseRuleFlags = function (rule) {
    var flags = $.extend({}, this.settings.default_rule_flags);

    if (rule.readonly) {
        $.extend(flags, {
            filter_readonly: true,
            operator_readonly: true,
            value_readonly: true,
            no_delete: true
        });
    }

    if (rule.flags) {
        $.extend(flags, rule.flags);
    }

    return this.change('parseRuleFlags', flags, rule);
};

/**
 * Clean group flags.
 * @param group {object}
 * @return {object}
 */
QueryBuilder.prototype.parseGroupFlags = function (group) {
    var flags = $.extend({}, this.settings.default_group_flags);

    if (group.readonly) {
        $.extend(flags, {
            condition_readonly: true,
            no_delete: true
        });
    }

    if (group.flags) {
        $.extend(flags, group.flags);
    }

    return this.change('parseGroupFlags', flags, group);
};

/**
 * Translate a label
 * @param label {string|object}
 * @return string
 */
QueryBuilder.prototype.translateLabel = function (label) {
    return typeof label == 'string' ? label : label[this.settings.lang_code] || label['en'];
};