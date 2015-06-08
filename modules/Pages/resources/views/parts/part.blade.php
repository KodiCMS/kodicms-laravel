<?php use KodiCMS\Pages\Model\PagePart; ?>

<script id="part-body" type="text/template">
	<div class="part panel panel-darken no-margin-b" id="part<%=name %>">
		<div class="panel-heading padding-xs-vr form-inline">
			<div class="panel-heading-sortable-handler">
				{!! UI::icon('ellipsis-v fa-lg') !!}
			</div>
			<span class="part-name panel-title"><%=name %></span>
			<input type="text" class="edit-name form-control input-sm" value="<%=name %>" />
			<% if ((is_protected == {{ PagePart::PART_PROTECTED }} && is_developer == 1) || is_protected == {{ PagePart::PART_NOT_PROTECTED }}) { %>
			<div class="panel-heading-controls">
				<div class="btn-group">
					@event('view.page.part.controls')
					{!! Form::button(UI::icon('edit'), ['class' => 'part-rename btn btn-inverse btn-xs margin-r5']) !!}
					@if (acl_check('page.parts'))
					{!! Form::button(UI::icon('cog'), ['class' => 'part-options-button btn btn-default btn-xs margin-r10']) !!}
					@endif
					<% if ( is_expanded == 0 ) { %>
					{!! Form::button(UI::icon('chevron-down'), ['class' => 'part-minimize-button btn btn-inverse btn-xs']) !!}
					<% } else { %>
					{!! Form::button(UI::icon('chevron-up'), ['class' => 'part-minimize-button btn btn-inverse btn-xs']) !!}
					<% } %>
				</div>
			</div>
			<% } %>
		</div>

		<% if ((is_protected == {{ PagePart::PART_PROTECTED }} && is_developer == 1) || is_protected == {{ PagePart::PART_NOT_PROTECTED }}) { %>
		<div class="part-options" style="display: none;">
			<div class="panel-body padding-sm form-inline">
				@if (acl_check('page.parts'))
				<div class="row">
					<div class="col-md-4 item-filter-cont">
						<label>
							@lang('pages::part.label.editor')&nbsp;&nbsp;&nbsp;
							<select class="item-filter" name="part_filter">
								@foreach (WYSIWYG::usedHtmlSelect() as $editor => $name)
								<option value="{{ $editor }}" <% if (wysiwyg == "{{ $editor }}") { print('selected="selected"')} %> >{{ $name }}</option>
								@endforeach
							</select>
						</label>
					</div>
					<div class="col-md-8 text-right">
						@event('view.page.part.options')

						<% if ( is_developer == 1 ) { %>
						<label class="checkbox-inline">
							<input type="checkbox" name="is_protected" class="px is_protected" <% if (is_protected == {{ PagePart::PART_PROTECTED }} ) { print('checked="checked"')} %>> @lang('pages::part.label.protected')
						</label>
						<label class="checkbox-inline">
							<input type="checkbox" name="is_indexable" class="px is_indexable" <% if (is_indexable == 1) { print('checked="checked"')} %>> @lang('pages::part.label.indexation')
						</label>
						<% } %>

						{!! Form::button(trans('pages::part.button.remove', ['name' => '<%= name %>']), [
						'class' => 'btn item-remove btn-xs btn-danger', 'data-icon' => 'trash-o'
						] ) !!}
					</div>
				</div>
				@else
				@lang('pages::part.label.editor')&nbsp;&nbsp;&nbsp;
				<select class="item-filter" name="part_filter">
				@foreach (WYSIWYG::usedHtmlSelect() as $editor => $name)
					<option value="{{ $editor }}" <% if (wysiwyg == "{{ $editor }}") { print('selected="selected"')} %>>{{ $name }}</option>
				@endforeach
			</select>
			@endif
		</div>
		<hr class="no-margin" />
	</div>
	<% } %>

	<% if (is_protected == {{ PagePart::PART_PROTECTED }} && is_developer == 0 ) { %>
	<div class="panel-body">
		<p class="text-warning">
			@lang('pages::part.label.protected', ['name' => '<%= name %>'])
		</p>
		</div>
		<% } else { %>
		<div class="part-textarea" <% if ( is_expanded == 0 ) { %>style="display:none;"<% } %>>
			<textarea class="form-control" rows="8" id="pageEditPartContent-<%= name %>" name="part_content[<%= id %>]"><%= content.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;") %></textarea>
		</div>
		<% } %>
	</div>
</script>