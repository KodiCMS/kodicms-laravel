@if(Auth::check())
<div class="menu-content top animated fadeIn" id="main-menu-profile">
	<div>
		<div class="text-bg">
			<span class="text-semibold">{{ $currentUser->username }}</span>
		</div>

		{!! $currentUser->gravatar(50) !!}
		<div class="btn-group">
			{!! HTML::linkRoute('backend.message.list', '', [], [
				'data-icon' => 'envelope',
				'class' => 'btn btn-xs btn-success btn-outline dark'
			]) !!}
			{!! HTML::linkRoute('backend.user.current_profile', '', [], [
				'data-icon' => 'user',
				'class' => 'btn btn-xs btn-primary btn-outline dark'
			]) !!}
			{!! HTML::linkRoute('backend.user.edit', '', [Auth::user()], [
				'data-icon' => 'cog',
				'class' => 'btn btn-xs btn-warning btn-outline dark'
			]) !!}

			<a href="{{ route('auth.logout') }}"
			   data-icon="power-off"
			   class="btn btn-xs btn-danger btn-outline dark"></a>
		</div>
	</div>
</div>
@endif