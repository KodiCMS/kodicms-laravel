<footer>
	<div class="panel no-margin-b">
		<div class="panel-body">
			<div class="row">
				<div class="col-md-8 text-muted">
					&copy; 2012 - {{ date('Y')  }} {!! HTML::link(CMS::WEBSITE, CMS::NAME) !!} v{{ CMS::VERSION }}
						   &nbsp;&nbsp;&HorizontalLine;&nbsp;&nbsp;
					{!! trans('cms::core.footer.poweredBy', [
						'framework' => HTML::link('http://laravel.com/', 'Laravel'),
						'version' => App::version()
					]) !!}
						   &nbsp;&nbsp;&HorizontalLine;&nbsp;&nbsp;
					{!! trans('cms::core.footer.adminTeheme', [
						   'name' => HTML::link('https://wrapbootstrap.com/theme/pixeladmin-premium-admin-theme-WB07403R9', 'PixelAdmin')
					]) !!}
				</div>
			</div>
		</div>
	</div>
</footer>