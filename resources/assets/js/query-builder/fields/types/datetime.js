QueryBuilderFieldType.extend('date', {
    getInput: function (name) {
        return this._getInput(name).addClass('datepicker').attr('type', 'date');
    }
});

QueryBuilderFieldType.extend('datetime', {
    getInput: function (name) {
        return this._getInput(name).addClass('datetimepicker').attr('type', 'datetime');
    }
});

QueryBuilderFieldType.extend('time', {
    getInput: function (name) {
        return this._getInput(name).addClass('timepicker').attr('type', 'time');
    }
});