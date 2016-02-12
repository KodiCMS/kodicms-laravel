/**
 * Allowed types and their internal representation
 */
QueryBuilder.types = {
    'string':           'string',
    'integer':          'number',
    'number':           'number',
    'double':           'number',
    'date':             'datetime',
    'time':             'datetime',
    'datetime':         'datetime',
    'boolean':          'boolean',
    'client_purchases': 'client_purchases'
};

/**
 * Allowed inputs
 */
QueryBuilder.inputs = [
    'text',
    'textarea',
    'radio',
    'checkbox',
    'select',
    'date',
    'datetime',
    'time',
    'number'
];

/**
 * Runtime modifiable options with `setOptions` method
 */
QueryBuilder.modifiable_options = [
    'display_errors',
    'allow_groups',
    'allow_empty',
    'default_condition',
    'default_filter'
];

/**
 * CSS selectors for common components
 */
var Selectors = QueryBuilder.selectors = {
    group_container:      '.rules-group-container',
    rule_container:       '.rule-container',
    filter_container:     '.rule-filter-container',
    operator_container:   '.rule-operator-container',
    value_container:      '.rule-value-container',
    error_container:      '.error-container',
    condition_container:  '.rules-group-header .group-conditions',

    rule_header:          '.rule-header',
    group_header:         '.rules-group-header',
    group_actions:        '.group-actions',
    rule_actions:         '.rule-actions',

    rules_list:           '.rules-group-body>.rules-list',

    group_condition:      '.rules-group-header [name$=_cond]',
    rule_filter:          '.rule-filter-container [name$=_filter]',
    rule_operator:        '.rule-operator-container [name$=_operator]',
    rule_value:           '.rule-value-container [name*=_value_]',

    add_rule:             '[data-add=rule]',
    delete_rule:          '[data-delete=rule]',
    add_group:            '[data-add=group]',
    delete_group:         '[data-delete=group]'
};

/**
 * Template strings (see `template.js`)
 */
QueryBuilder.templates = {};

/**
 * Localized strings (see `i18n/`)
 */
QueryBuilder.regional = {
    en: {
        "__locale": "English (en)",
        "__author": "Damien \"Mistic\" Sorel, http://www.strangeplanet.fr",
        "add_rule": "Add rule",
        "add_group": "Add group",
        "delete_rule": "Delete",
        "delete_group": "Delete",
        "conditions": {
            "AND": "AND",
            "OR": "OR"
        },
        "operators": {
            "equal": "equal",
            "not_equal": "not equal",
            "in": "in",
            "not_in": "not in",
            "less": "less",
            "less_or_equal": "less or equal",
            "greater": "greater",
            "greater_or_equal": "greater or equal",
            "between": "between",
            "not_between": "not between",
            "begins_with": "begins with",
            "not_begins_with": "doesn't begin with",
            "contains": "contains",
            "not_contains": "doesn't contain",
            "ends_with": "ends with",
            "not_ends_with": "doesn't end with",
            "is_empty": "is empty",
            "is_not_empty": "is not empty",
            "is_null": "is null",
            "is_not_null": "is not null"
        },
        "errors": {
            "no_filter": "No filter selected",
            "empty_group": "The group is empty",
            "radio_empty": "No value selected",
            "checkbox_empty": "No value selected",
            "select_empty": "No value selected",
            "string_empty": "Empty value",
            "string_exceed_min_length": "Must contain at least {0} characters",
            "string_exceed_max_length": "Must not contain more than {0} characters",
            "string_invalid_format": "Invalid format ({0})",
            "number_nan": "Not a number",
            "number_not_integer": "Not an integer",
            "number_not_double": "Not a real number",
            "number_exceed_min": "Must be greater than {0}",
            "number_exceed_max": "Must be lower than {0}",
            "number_wrong_step": "Must be a multiple of {0}",
            "datetime_empty": "Empty value",
            "datetime_invalid": "Invalid date format ({0})",
            "datetime_exceed_min": "Must be after {0}",
            "datetime_exceed_max": "Must be before {0}",
            "boolean_not_valid": "Not a boolean",
            "operator_not_multiple": "Operator {0} cannot accept multiple values"
        },
        "datetimes": {
            "today": 'Today'
        },
        "invert": "Invert"
    },
    ru: {
        "__locale": "Russian (ru)",
        "add_rule": "Добавить",
        "add_group": "Добавить группу",
        "delete_rule": "Удалить",
        "delete_group": "Удалить",
        "conditions": {
            "AND": "И",
            "OR": "ИЛИ"
        },
        "operators": {
            "equal": "равно",
            "not_equal": "не равно",
            "in": "из указанных",
            "not_in": "не из указанных",
            "less": "меньше",
            "less_or_equal": "меньше или равно",
            "greater": "больше",
            "greater_or_equal": "больше или равно",
            "between": "между",
            "not_between": "не между",
            "begins_with": "начинается с",
            "not_begins_with": "не начинается с",
            "contains": "содержит",
            "not_contains": "не содержит",
            "ends_with": "оканчивается на",
            "not_ends_with": "не оканчивается на",
            "is_empty": "пустая строка",
            "is_not_empty": "не пустая строка",
            "is_null": "пусто",
            "is_not_null": "не пусто"
        },
        "errors": {
            "no_filter": "Фильтр не выбран",
            "empty_group": "Группа пуста",
            "radio_empty": "Не выбранно значение",
            "checkbox_empty": "Не выбранно значение",
            "select_empty": "Не выбранно значение",
            "string_empty": "Не заполненно",
            "string_exceed_min_length": "Должен содержать больше {0} символов",
            "string_exceed_max_length": "Должен содержать меньше {0} символов",
            "string_invalid_format": "Неверный формат ({0})",
            "number_nan": "Не число",
            "number_not_integer": "Не число",
            "number_not_double": "Не число",
            "number_exceed_min": "Должно быть больше {0}",
            "number_exceed_max": "Должно быть меньше, чем {0}",
            "number_wrong_step": "Должно быть кратно {0}",
            "datetime_empty": "Не заполненно",
            "datetime_invalid": "Неверный формат даты ({0})",
            "datetime_exceed_min": "Должно быть, после {0}",
            "datetime_exceed_max": "Должно быть, до {0}",
            "boolean_not_valid": "Не логическое",
            "operator_not_multiple": "Оператор {0} не поддерживает много значений"
        },
        "datetimes": {
            "today": 'Сегодня'
        }
    }
};

/**
 * Default configuration
 */
QueryBuilder.DEFAULTS = {
    filters: [],
    plugins: [],

    display_errors: true,
    allow_groups: -1,
    allow_empty: false,
    conditions: ['AND', 'OR'],
    default_condition: 'AND',
    inputs_separator: ' , ',
    select_placeholder: '------',
    display_empty_filter: true,
    default_filter: null,
    optgroups: {},

    default_rule_flags: {
        filter_readonly: false,
        operator_readonly: false,
        value_readonly: false,
        no_delete: false
    },

    default_group_flags: {
        condition_readonly: false,
        no_delete: false
    },

    templates: {
        group: null,
        rule: null,
        filterSelect: null,
        operatorSelect: null,
        operatorInput: null
    },

    lang_code: 'ru',
    lang: {},

    operators: [
        {type: 'equal',            nb_inputs: 1, multiple: false, apply_to: ['string', 'number', 'datetime', 'boolean', 'client_purchases']},
        {type: 'not_equal',        nb_inputs: 1, multiple: false, apply_to: ['string', 'number', 'datetime', 'boolean', 'client_purchases']},
        {type: 'in',               nb_inputs: 1, multiple: true,  apply_to: ['select']},
        {type: 'not_in',           nb_inputs: 1, multiple: true,  apply_to: ['select']},
        {type: 'less',             nb_inputs: 1, multiple: false, apply_to: ['number', 'datetime', 'client_purchases']},
        {type: 'less_or_equal',    nb_inputs: 1, multiple: false, apply_to: ['number', 'datetime', 'client_purchases']},
        {type: 'greater',          nb_inputs: 1, multiple: false, apply_to: ['number', 'datetime', 'client_purchases']},
        {type: 'greater_or_equal', nb_inputs: 1, multiple: false, apply_to: ['number', 'datetime', 'client_purchases']},
        {type: 'between',          nb_inputs: 2, multiple: false, apply_to: ['number', 'datetime', 'client_purchases']},
        {type: 'not_between',      nb_inputs: 2, multiple: false, apply_to: ['number', 'datetime', 'client_purchases']},
        {type: 'begins_with',      nb_inputs: 1, multiple: false, apply_to: ['string']},
        {type: 'not_begins_with',  nb_inputs: 1, multiple: false, apply_to: ['string']},
        {type: 'contains',         nb_inputs: 1, multiple: false, apply_to: ['string']},
        {type: 'not_contains',     nb_inputs: 1, multiple: false, apply_to: ['string']},
        {type: 'ends_with',        nb_inputs: 1, multiple: false, apply_to: ['string']},
        {type: 'not_ends_with',    nb_inputs: 1, multiple: false, apply_to: ['string']},
        {type: 'is_empty',         nb_inputs: 0, multiple: false, apply_to: ['string']},
        {type: 'is_not_empty',     nb_inputs: 0, multiple: false, apply_to: ['string']},
        {type: 'is_null',          nb_inputs: 0, multiple: false, apply_to: ['boolean']},
        {type: 'is_not_null',      nb_inputs: 0, multiple: false, apply_to: ['boolean']}
    ],

    icons: {
        add_group:    'fa fa-folder-open-o',
        add_rule:     'fa fa-plus',
        remove_group: 'fa fa-close',
        remove_rule:  'fa fa-close',
        error:        'fa fa-exclamation-triangle'
    }
};