<?php namespace Plugins\butschster\DSTags\Fields;

use Assets;
use DatasourceManager;
use Illuminate\Database\Schema\Blueprint;
use KodiCMS\CMS\Http\Controllers\System\TemplateController;
use KodiCMS\Datasource\Contracts\DocumentInterface;
use KodiCMS\Datasource\Fields\Relation\ManyToMany;

class Tags extends ManyToMany
{
	/**
	 * @var bool
	 */
	protected $hasDatabaseColumn = true;

	/**
	 * @return array
	 */
	public function getSectionList()
	{
		return DatasourceManager::getSectionsFormHTML(['tags']);
	}

	/**
	 * @param Blueprint $table
	 *
	 * @return \Illuminate\Support\Fluent
	 */
	public function setDatabaseFieldType(Blueprint $table)
	{
		return $table->text($this->getDBKey())->default('');
	}


	/**
	 * @param DocumentInterface $document
	 * @param TemplateController $controller
	 */
	public function onControllerLoad(DocumentInterface $document, TemplateController $controller)
	{
		Assets::package(['jquery-tagsinput', 'jquery-ui']);
		parent::onControllerLoad($document, $controller);
	}



	/**
	 * @param DocumentInterface $document
	 * @return array
	 */
	protected function fetchDocumentTemplateValues(DocumentInterface $document)
	{
		return [
			'value' => $document->getFormValue($this->getDBKey()),
			'document' => $document,
			'section' => $document->getSection()
		];
	}
}