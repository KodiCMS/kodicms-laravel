/*jshint multistr:true */

QueryBuilder.templates.group = '\
<dl id="{{= it.group_id }}" class="rules-group-container"> \
  <dt class="rules-group-header"> \
    <div class="btn-group pull-right group-actions"> \
      <button type="button" class="btn btn-xs btn-success" data-add="rule"> \
        <i class="{{= it.icons.add_rule }}"></i> \
        <span class="hidden-xs hidden-sm">{{= it.lang.add_rule }}</span> \
      </button> \
      {{? it.settings.allow_groups===-1 || it.settings.allow_groups>=it.level }} \
        <button type="button" class="btn btn-xs btn-success" data-add="group"> \
          <i class="{{= it.icons.add_group }}"></i> \
          <span class="hidden-xs hidden-sm">{{= it.lang.add_group }}</span> \
        </button> \
      {{?}} \
      {{? it.level>1 }} \
        <button type="button" class="btn btn-xs btn-danger" data-delete="group"> \
          <i class="{{= it.icons.remove_group }}"></i> \
          <span class="hidden-xs hidden-sm">{{= it.lang.delete_group }}</span> \
        </button> \
      {{?}} \
    </div> \
    <div class="btn-group group-conditions"> \
      {{~ it.conditions: condition }} \
        <label class="btn btn-sm btn-primary"> \
          <input type="radio" name="{{= it.group_id }}_cond" value="{{= condition }}"> {{= it.lang.conditions[condition] || condition }} \
        </label> \
      {{~}} \
    </div> \
  </dt> \
  <dd class=rules-group-body> \
    <ul class=rules-list></ul> \
  </dd> \
</dl>';

QueryBuilder.templates.rule = '\
<li id="{{= it.rule_id }}" class="rule-container"> \
  <div class="rule-header"> \
    <div class="btn-group pull-right rule-actions"> \
      <button type="button" class="btn btn-xs btn-danger" data-delete="rule"> \
        <i class="{{= it.icons.remove_rule }}"></i> \
        <span class="hidden-xs hidden-sm">{{= it.lang.delete_rule }}</span> \
      </button> \
    </div> \
  </div> \
  <div class="rule-filter-container"></div> \
  <div class="rule-operator-container"></div> \
  <div class="rule-value-container"></div> \
</li>';

QueryBuilder.templates.filterSelect = '\
{{ var optgroup = null; }} \
<select class="form-control" name="{{= it.rule.id }}_filter"> \
  {{? it.settings.display_empty_filter }} \
    <option value="-1">{{= it.settings.select_placeholder }}</option> \
  {{?}} \
  {{~ it.filters: filter }} \
    {{? optgroup !== filter.optgroup }} \
      {{? optgroup !== null }}</optgroup>{{?}} \
      {{? (optgroup = filter.optgroup) !== null}} \
        <optgroup label="{{= it.translate(it.settings.optgroups[optgroup]) }}"> \
      {{?}} \
    {{?}} \
    <option value="{{= filter.id }}">{{= it.translate(filter.label) }}</option> \
  {{~}} \
  {{? optgroup !== null }}</optgroup>{{?}} \
</select>';

QueryBuilder.templates.operatorSelect = '\
<select class="form-control" name="{{= it.rule.id }}_operator"> \
  {{~ it.operators: operator }} \
    <option value="{{= operator.type }}">{{= it.lang.operators[operator.type] || operator.type }}</option> \
  {{~}} \
</select>';

QueryBuilder.templates.operatorInput = '<span> \
<input type="hidden" name="{{= it.rule.id }}_operator" value="{{= it.operator.type }}" /> \
<span class="label label-info">{{= it.lang.operators[it.operator.type] || it.operator.type }}</span> \
</span>';

/**
 * Returns group HTML
 * @param group_id {string}
 * @param level {int}
 * @return {string}
 */
QueryBuilder.prototype.getGroupTemplate = function(group_id, level) {
    var h = this.templates.group({
        builder: this,
        group_id: group_id,
        level: level,
        conditions: this.settings.conditions,
        icons: this.icons,
        lang: this.lang,
        settings: this.settings
    });

    return this.change('getGroupTemplate', h, level);
};

/**
 * Returns rule HTML
 * @param rule_id {string}
 * @return {string}
 */
QueryBuilder.prototype.getRuleTemplate = function(rule_id) {
    var h = this.templates.rule({
        builder: this,
        rule_id: rule_id,
        icons: this.icons,
        lang: this.lang,
        settings: this.settings
    });

    return this.change('getRuleTemplate', h);
};

/**
 * Returns rule filter <select> HTML
 * @param rule {Rule}
 * @param filters {array}
 * @return {string}
 */
QueryBuilder.prototype.getRuleFilterSelect = function(rule, filters) { 
    var h = this.templates.filterSelect({
        builder: this,
        rule: rule,
        filters: filters,
        icons: this.icons,
        lang: this.lang,
        settings: this.settings,
        translate: this.translateLabel
    });

    return this.change('getRuleFilterSelect', h, rule);
};

/**
 * Returns rule operator <select> HTML
 * @param rule {Rule}
 * @param operators {object}
 * @return {string}
 */
QueryBuilder.prototype.getRuleOperatorSelect = function(rule, operators) {
    if(!operators) var operators = [];

    var params = {
        builder: this,
        rule: rule,
        operators: operators,
        icons: this.icons,
        lang: this.lang,
        settings: this.settings
    };

    if(_.size(operators) > 1) {
        var h = this.templates.operatorSelect(params);
    } else if(_.size(operators) == 1) {
        var h = this.templates.operatorInput($.extend({}, params, {
            operator: operators[0]
        }));
    } else {
        var h = this.templates.operatorInput($.extend({}, params, {
            operator: QueryBuilder.DEFAULTS.operators[0]
        }));
    }

    return this.change('getRuleOperatorSelect', h, rule);
};

/**
 * Return the rule value HTML
 * @param rule {Rule}
 * @param filter {object}
 * @param value_id {int}
 * @return {string}
 */
QueryBuilder.prototype.getRuleInput = function(rule, value_id) {
    var filter = rule.filter,
        validation = rule.filter.validation || {},
        name = rule.id +'_value_'+ value_id,
        c = filter.vertical ? ' class=block' : '',
        h = '';

    if(rule.operator.multiple) {
        filter.multiple = true;
    }

    if (typeof filter.input === 'function') {
        h = filter.input.call(this, rule, name);
    } else {
        switch (filter.input) {
            case 'radio': case 'checkbox':
                Utils.iterateOptions(filter.values, function(key, val) {
                    h+= '<label class="radio-inline"><input type="'+ filter.input + '" name="'+ name +'" value="'+ key +'"> '+ val +'</label> ';
                });
                break;

            case 'select':
                h+= '<select class="form-control" name="'+ name +'"'+ (filter.multiple ? ' multiple' : '') +'>';
                if (filter.placeholder) {
                    h+= '<option value="' + filter.placeholder_value + '" disabled selected>' + filter.placeholder + '</option>';
                }
                Utils.iterateOptions(filter.values, function(key, val) {
                    h+= '<option value="'+ key +'">'+ val +'</option> ';
                });
                h+= '</select>';
                break;

            case 'textarea':
                h+= '<textarea class="form-control" name="'+ name +'"';
                if (filter.size) h+= ' cols="'+ filter.size +'"';
                if (filter.rows) h+= ' rows="'+ filter.rows +'"';
                if (validation.min !== undefined) h+= ' minlength="'+ validation.min +'"';
                if (validation.max !== undefined) h+= ' maxlength="'+ validation.max +'"';
                if (filter.placeholder) h+= ' placeholder="'+ filter.placeholder +'"';
                h+= '></textarea>';
                break;

            default:
                switch (QueryBuilder.types[filter.type]) {
                    case 'number':
                        h+= '<input class="form-control" type="number" name="'+ name +'"';
                        if (validation.step !== undefined) h+= ' step="'+ validation.step +'"';
                        if (validation.min !== undefined) h+= ' min="'+ validation.min +'"';
                        if (validation.max !== undefined) h+= ' max="'+ validation.max +'"';
                        if (filter.placeholder) h+= ' placeholder="'+ filter.placeholder +'"';
                        if (filter.size) h+= ' size="'+ filter.size +'"';
                        h+= '>';
                        break;

                    case 'datetime':
                        h = $('<input type="text" name="'+name+'" class="form-control" />');
                        break;
                    default:
                        h+= '<input class="form-control" type="text" name="'+ name +'"';
                        if (filter.placeholder) h+= ' placeholder="'+ filter.placeholder +'"';
                        if (filter.type === 'string' && validation.min !== undefined) h+= ' minlength="'+ validation.min +'"';
                        if (filter.type === 'string' && validation.max !== undefined) h+= ' maxlength="'+ validation.max +'"';
                        if (filter.size) h+= ' size="'+ filter.size +'"';
                        h+= '>';
                }
        }
    }

    return this.change('getRuleInput', h, rule, name);
};