<?php namespace KodiCMS\Datasource;

class DatasourceManager {

	/**
	 * @param integer $sectionId
	 * @param integer $folderId
	 */
	public static function moveSectioToFolde($sectionId, $folderId)
	{

	}

	/**
	 * @return array
	 */
	public static function getAvailableSections()
	{
		return config('datasources', []);
	}
}