<?php namespace Plugins\butschster\DSArticles;

use KodiCMS\Datasource\Fields\Source\User;
use KodiCMS\Datasource\Fields\Primitive\HTML;
use KodiCMS\Datasource\FieldGroups\TabsGroup;
use KodiCMS\Datasource\FieldGroups\TitleGroup;
use KodiCMS\Datasource\Fields\Primitive\String;
use KodiCMS\Datasource\Sections\SectionToolbar;
use KodiCMS\Datasource\FieldGroups\SpoilerGroup;
use KodiCMS\Datasource\Fields\Primitive\Primary;
use KodiCMS\Datasource\Fields\Primitive\Boolean;
use KodiCMS\Datasource\Fields\Primitive\Textarea;
use KodiCMS\Datasource\Fields\Primitive\Timestamp;
use KodiCMS\Datasource\Sections\SectionHeadlineDatatables;

class Section extends \KodiCMS\Datasource\Model\Section
{
	/**
	 * @var string
	 */
	protected $sectionTableName = 'articles';

	/**
	 * @return string
	 */
	public function getDocumentClass()
	{
		return Document::class;
	}

	/**
	 * @return string
	 */
	public function getHeadlineClass()
	{
		return SectionHeadlineDatatables::class;
	}

	/**
	 * @return string
	 */
	public function getToolbarClass()
	{
		return SectionToolbar::class;
	}

	/**
	 * @return array
	 */
	public function getSystemFields()
	{
		return [
			new Primary([
				'key' => 'id',
				'name' => 'ID',
				'settings' => [
					'headline_parameters' => [
						'width' => 30,
						'visible' => true
					]
				]
			]),
			(new TitleGroup())->setFields([
				new String([
					'key' => 'header',
					'name' => 'Header',
					'settings' => [
						'is_required' => true,
						'headline_parameters' => [
							'visible' => true
						]
					]
				]),
				new Boolean([
					'key' => 'published',
					'name' => 'Published',
					'settings' => [
						'default_value' => true,
						'headline_parameters' => [
							'width' => 30,
							'visible' => true
						]
					]
				]),
			]),
			(new SpoilerGroup(['name' => 'Meta']))->setFields([
				new String([
					'key' => 'meta_title',
					'name' => 'Meta title'
				]),
				new String([
					'key' => 'meta_keywords',
					'name' => 'Meta keywords'
				]),
				new Textarea([
					'key' => 'meta_description',
					'name' => 'Meta description'
				]),
				new User([
					'key' => 'created_by_id',
					'name' => 'Created By',
					'settings' => [
						'current_only' => true,
						'headline_parameters' => [
							'width' => 100,
							'visible' => true
						]
					]
				]),
			]),
			(new TabsGroup())->setFields([
				new HTML([
					'key' => 'description',
					'name' => 'Description',
					'settings' => [
						'headline_parameters' => [
							'visible' => true
						]
					]
				]),
				new HTML([
					'key' => 'text',
					'name' => 'Text'
				]),
			]),
			new Timestamp([
				'key' => static::CREATED_AT,
				'name' => 'Created At',
				'settings' => [
					'headline_parameters' => [
						'width' => 200,
						'visible' => true
					]
				]
			]),
			new Timestamp([
				'key' => static::UPDATED_AT,
				'name' => 'Updated At',
				'settings' => [
					'headline_parameters' => [
						'width' => 200,
						'visible' => false
					]
				]
			])
		];
	}
}