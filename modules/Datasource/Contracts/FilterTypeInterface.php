<?php

namespace KodiCMS\Datasource\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Support\Arrayable;

interface FilterTypeInterface extends Arrayable
{
	/**
	 * @param FilterRuleInterface $rule
	 * @param Builder                  $query
	 *
	 * @return bool
	 */
	public function setRule(FilterRuleInterface $rule, Builder $query);

	/**
	 * @return FilterRuleInterface
	 */
	public function getRule();

	/**
	 * @return string
	 */
	public function getId();

	/**
	 * @return string
	 */
	public function getLabel();

	/**
	 * @return string
	 */
	public function getType();

	/**
	 * @return string
	 */
	public function getInput();

	/**
	 * @return array|null
	 */
	public function getOperators();

	/**
	 * @return array|null
	 */
	public function getQueryField();

	/**
	 * @return array|null
	 */
	public function getSelectField();

	/**
	 * @param string $value
	 *
	 * @return mixed
	 */
	public function parseValue($value);
}
