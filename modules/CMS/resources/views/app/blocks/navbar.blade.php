<div id="main-navbar" class="navbar" role="navigation">
	<button type="button" id="main-menu-toggle"><i class="navbar-icon fa fa-bars icon"></i>
		<span class="hide-menu-text">@lang('cms::core.navigation.hide')</span>
	</button>
	<div class="navbar-inner">
		<div class="navbar-header">
			{!! HTML::link($adminDir, 'KodiCMS', ['class' => 'navbar-brand']); !!}
		</div>

		<div id="main-navbar-collapse" class="collapse navbar-collapse main-navbar-collapse">
			<div>
				<div class="right clearfix">
					<ul class="nav navbar-nav pull-right right-navbar-nav">
						<li>
							<a href="{{ route('backend.settings') }}">{!! UI::icon('cogs fa-lg') !!}</a>
						</li>
						<li>
							<a href="{{ url('/') }}" target="_blank", data-icon="globe fa-lg text-info">
								{!! UI::hidden(Lang::get('cms::core.navigation.site')) !!}
							</a>
						</li>
							@if(Auth::check())
								<li class="dropdown user-menu">
									{{-- <a href="#" class="dropdown-toggle user-menu" data-toggle="dropdown">
										{!! Auth::user()->gravatar(25) !!}
										<span>{{ Auth::user()->username }}</span>
									</a>--}}
								<ul class="dropdown-menu">
									<li class="user-header">
										{{-- !! Auth::user()->gravatar(90, NULL, ['class' => 'img-circle']) !! --}}
										<p>
											{{ Auth::user()->username }}
											<small>{{ Auth::user()->email }}</small>
										</p>
									</li>
									<li class="user-body">
										<div class="col-xs-6">
											{!! HTML::linkRoute('backend.user.profile', trans('user.sections.profile'), [], ['data-icon' => 'user']) !!}
										</div>
										<div class="col-xs-6">
											{!! HTML::linkRoute('backend.user.edit', trans('user.sections.settings'), ['id' =>
											Auth::id()], ['data-icon' => 'cog']) !!}
										</div>
									</li>
									<li class="user-footer">
										<a href="{{ rooute('backend.user.logout') }}"
										   data-icon="power-off text-danger"
										   class="btn btn-default btn-xs text-bold pull-right">@lang('user.action.logout')</a>
									</li>
								</ul>
							</li>
						@endif
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>