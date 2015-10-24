@if(Auth::check())
<div class="menu-content top animated fadeIn" id="main-menu-profile">
	<div>
		<div class="text-bg">
			<span class="text-semibold">{{ $currentUser->username }}</span>
		</div>

		{!! $currentUser->getAvatar(50) !!}
		<div class="btn-group">
			{!! HTML::linkRoute('backend.user.current_profile', '', [], [
				'data-icon' => 'user',
				'class' => 'btn btn-xs btn-primary btn-outline dark'
			]) !!}
			{!! HTML::linkRoute('backend.user.edit', '', [Auth::user()], [
				'data-icon' => 'cog',
				'class' => 'btn btn-xs btn-warning btn-outline dark'
			]) !!}

			<a href="{{ route('backend.auth.logout') }}"
			   data-icon="power-off"
			   class="btn btn-xs btn-danger btn-outline dark"></a>
		</div>
	</div>
</div>
@endif