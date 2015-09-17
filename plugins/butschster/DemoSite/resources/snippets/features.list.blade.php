@if(!empty($header))
<div class="headline headline-md headline-border">
	<h4>{{ $header }}</h4>
</div>
@endif

<div class="row">
	@foreach($documents as $doc)
		<div class="col-md-4">
			<div class="feature">
				@if(!empty($doc['image']))
				<div class="thumbnail-img">
					{!! Html::image($doc['image'], null, ['class' => 'img-responsive']) !!}
				</div>
				@endif
				<h4>{{ $doc['header'] }}</h4>
				{!! $doc['text']['filtered'] !!}
			</div>
		</div>
	@endforeach
</div>