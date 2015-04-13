<?php namespace KodiCMS\Pages\Services;

use KodiCMS\CMS\Contracts\ModelCreator;
use KodiCMS\Pages\Model\Page;
use Validator;

class PageCreator implements ModelCreator
{
	/**
	 * Get a validator for an incoming registration request.
	 *
	 * @param  array $data
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	public function validator(array $data)
	{
		$parent_id = (int) array_get($data, 'parent_id');
		$validator = Validator::make($data, [
			'title' => 'required|max:32',
			'slug' => "max:100|unique:pages,slug,NULL,id,parent_id,{$parent_id}",
			'status' => 'required|numeric'
		]);

		return $validator;
	}

	/**
	 * Create a new user instance after a valid registration.
	 *
	 * @param  array $data
	 * @return User
	 */
	public function create(array $data)
	{
		$page = Page::create(array_only($data, [
			'title', 'slug', 'is_redirect', 'breadcrumb',
			'meta_title', 'meta_keywords', 'meta_description',
			'robots', 'parent_id', 'layout_file', 'behavior',
			'status', 'published_at'
		]));

		return $page;
	}
}
