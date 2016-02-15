<div id="main-navbar" class="navbar" role="navigation">
	<button type="button" id="main-menu-toggle"><i class="navbar-icon fa fa-bars icon"></i>
		<span class="hide-menu-text">@lang('cms::core.navigation.hide')</span>
	</button>
	<div class="navbar-inner">
		<div class="navbar-header">
			{!! link_to(backend_url_segment(), 'KodiCMS', ['class' => 'navbar-brand']); !!}
		</div>

		<div id="main-navbar-collapse" class="collapse navbar-collapse main-navbar-collapse">
			<div>
				@event('view.navbar.left')

				<div class="right clearfix">
					<ul class="nav navbar-nav pull-right right-navbar-nav">
						@event('view.navbar.right.before')
						<li>
							<a href="{{ route('backend.settings') }}">{!! UI::icon('cogs fa-lg') !!}</a>
						</li>
						<li>
							<a href="{{ url('/') }}" target="_blank", data-icon="globe fa-lg text-info">
								{!! UI::hidden(Lang::get('cms::core.navigation.site')) !!}
							</a>
						</li>
						@event('view.navbar.right.after')
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
