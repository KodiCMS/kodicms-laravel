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
							{!! HTML::linkRoute('backend.settings', UI::icon('cogs fa-lg')) !!}
						</li>
						<li>
							{!! HTML::link(url('/'), UI::hidden(trans('cms::core.navigation.site')), [
							'target' => 'blank', 'data-icon' => 'globe fa-lg text-info'
							]) !!}
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
										{!! HTML::linkRoute('backend.user.logout', trans('user.action.logout'), [], [
										'data-icon' => 'power-off text-danger',
										'class' => 'btn btn-default btn-xs text-bold pull-right'
										]) !!}
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