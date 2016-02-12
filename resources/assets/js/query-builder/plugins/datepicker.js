/**
 * @throws ConfigError
 */
QueryBuilder.define('datepicker', function (options) {
    if (!$.fn.datetimepicker) {
        Utils.error('MissingLibrary', 'Datetime picker is required to use "datepicker" plugin.');
    }

    this.on('afterCreateRuleInput', function (e, rule) {
        var filter = rule.filter;

        if (filter.type == 'date') {
           App.Components.init('datepicker');
        }
    });
});