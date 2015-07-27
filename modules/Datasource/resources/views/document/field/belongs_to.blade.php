<div class="form-group">
	<label class="control-label col-md-2 col-sm-3" for="{{ $key }}">
		{{ $name }}
	</label>

	<div class="col-md-10 col-sm-9">
		@if($relatedDocument)
		{!! HTML::link($relatedDocument->getEditLink(), $relatedDocument->getTitle(), [
			'class' => 'btn btn-link popup'
		]) !!}
		@else

		@endif
	</div>
</div>