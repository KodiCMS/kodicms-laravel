<?php

Admin::model('App\Page')->title('Pages')->display(function ()
{
	$display = AdminDisplay::tree();
	$display->value('title');
	return $display;
})->createAndEdit(function ()
{
	$form = AdminForm::form();
	$form->items([
		FormItem::text('title', 'Title'),
		FormItem::ckeditor('text', 'Text'),
	]);
	return $form;
});