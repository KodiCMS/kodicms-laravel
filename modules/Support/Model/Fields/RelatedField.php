<?php namespace KodiCMS\Support\Model\Fields;

use Form;
use Illuminate\Database\Eloquent\Relations\Relation;

class RelatedField extends SelectField
{
	/**
	 * @var string
	 */
	protected $keyField = 'id';

	/**
	 * @var string
	 */
	protected $valueField = 'name';

	/**
	 * @var Relation
	 */
	protected $relatedModel;

	/**
	 * @return Relation
	 */
	public function getRelatedModel()
	{
		if (!isset($this->relatedModel))
		{
			$this->relatedModel = $this->model->{$this->getModelKey()}();
		}

		return $this->relatedModel;
	}

	/**
	 * @param string $key
	 * @param mixed $value
	 * @return mixed
	 */
	public function getOptions($key, $value)
	{
		$return = parent::getOptions($key, $value);

		if (empty($return) and !is_null($value))
		{
			$return = $this->getRelatedModel()->lists($this->valueField, $this->keyField);
		}

		return $return;
	}
}