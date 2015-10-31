<div class="panel-body">

    <div class="form-group">
        <label class="control-label col-md-3" for="name">Test setting</label>

        <div class="col-md-9">
            {!! Form::text('settings[test]', $plugin->test, [
            'class' => 'form-control'
            ]) !!}
        </div>
    </div>

</div>