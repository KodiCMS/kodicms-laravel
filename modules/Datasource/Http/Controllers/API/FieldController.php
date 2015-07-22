<?php namespace KodiCMS\Datasource\Http\Controllers\API;

use KodiCMS\Datasource\Repository\FieldRepository;
use KodiCMS\API\Http\Controllers\System\Controller;

class FieldController extends Controller
{
	/**
	 * @param FieldRepository $repository
	 */
	public function deleteField(FieldRepository $repository)
	{
		$ids = (array) $this->getRequiredParameter('fields');

		$repository->deleteByIds($ids);

		$this->setContent($ids);
	}

	public function postPosition(FieldRepository $repository)
	{
		$fieldId = $this->getRequiredParameter('field_id');
		$position = (int) $this->getParameter('position');

		$field = $repository->updatePosition($fieldId, $position);
		$this->setContent($field->getPosition());
	}
}