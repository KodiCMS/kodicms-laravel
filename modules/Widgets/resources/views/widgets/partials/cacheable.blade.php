<div class="panel-heading">
	<span class="panel-title" data-icon="hdd-o">@lang('widgets::core.title.cache')</span>
	<div class="panel-heading-controls">
		{!! Form::checkbox('settings[cache]', 1, $widget->isCacheEnabled(), [
			'class' => 'px form-switcher', 'data-size' => 'mini', 'id' => 'cache',
			'data-on' => trans('widgets::core.button.cache.enabled'),
			'data-off' => trans('widgets::core.button.cache.disabled'),
			'data-onstyle' => 'success', 'data-offstyle' => 'danger'
		]) !!}
	</div>
</div>
<div class="panel-body" id="cache_settings_container">
	<div id="cache_lifetime_group" class="form-group">
		<label class="control-label col-xs-3" for="cache_lifetime">@lang('widgets::core.settings.cache_lifetime')</label>
		<div class="col-xs-3">
			{!! Form::text('settings[cache_lifetime]', $widget->getCacheLifetime(), [
			'class' => 'form-control', 'id' => 'cache_lifetime'
			]) !!}
		</div>

		<div class="col-md-6">
			<span class="flags" id="cache_lifetime_labels" data-target="#cache_lifetime">
				<span class="label" data-value="<?php echo Date::MINUTE; ?>"><?php echo __('Minute'); ?></span>
				<span class="label" data-value="<?php echo Date::HOUR; ?>"><?php echo __('Hour'); ?></span>
				<span class="label" data-value="<?php echo Date::DAY; ?>"><?php echo __('Day'); ?></span>
				<span class="label" data-value="<?php echo Date::WEEK; ?>"><?php echo __('Week'); ?></span>
				<span class="label" data-value="<?php echo Date::MONTH; ?>"><?php echo __('Month'); ?></span>
				<span class="label" data-value="<?php echo Date::YEAR; ?>"><?php echo __('Year'); ?></span>
			</span>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-xs-3">@lang('widgets::core.settings.cache_tags')</label>
		<div class="col-xs-9">
			{!! Form::select('settings[cache_tags][]', $widget->getHTMLSelectCacheTags(), $widget->getCacheTags(), [
				'class' => 'tags form-control', 'multiple'
			]) !!}
		</div>
	</div>
</div>