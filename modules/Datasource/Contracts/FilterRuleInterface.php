<?php

namespace KodiCMS\Datasource\Contracts;

use KodiCMS\Datasource\Filter\Operator;

interface FilterRuleInterface
{
	/**
	 * @return string
	 */
	public function getId();

	/**
	 * @return FilterFieldInterface
	 */
	public function getField();

	/**
	 * @param FilterFieldInterface $field
	 */
	public function setField($field);

	/**
	 * @return string
	 */
	public function getType();

	/**
	 * @return string
	 */
	public function getInput();

	/**
	 * @return Operator
	 */
	public function getOperator();

	/**
	 * @return mixed
	 */
	public function getValue();

	/**
	 * @return string
	 */
	public function getCondition();
}
