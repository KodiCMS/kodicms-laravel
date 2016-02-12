QueryBuilderFieldType.extend('number', {
    getInput: function (name) {
        return this._getInput(name).attr('type', 'number');
    }
});

QueryBuilderFieldType.extend('integer', QueryBuilderFieldType['number']);