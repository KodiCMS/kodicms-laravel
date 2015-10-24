<br />
@if($page)
	<label>Document page</label>
	{!! Form::select('behavior_settings[item_page_id]', $page->getSitemap(), $settings->item_page_id, [
	'class' => 'form-control'
	]) !!}
	<script>CMS.ui.init('select2')</script>
@else
	<div class="alert alert-warning">Page not created</div>
@endif