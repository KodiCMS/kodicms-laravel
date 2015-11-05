<div class="form-group {{ $errors->has($name) ? 'has-error' : '' }}">
	<label for="{{ $name }}">{{ $label }}</label>
	<textarea class="form-control" rows="{{ $rows }}" name="{{ $name }}" @if(isset($readonly))readonly="{{ $readonly }}"@endif>{!! $value !!}</textarea>
	@include(app('sleeping_owl.template')->getTemplateViewPath('formitem.errors'))
</div>