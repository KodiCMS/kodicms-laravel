<?php namespace KodiCMS\Pages\Services;

use Carbon\Carbon;
use KodiCMS\CMS\Contracts\ModelUpdator;
use KodiCMS\Pages\Model\Page;
use Validator;

class PageUpdator implements ModelUpdator
{
	/**
	 * Get a validator for an incoming registration request.
	 *
	 * @param integer $id
	 * @param  array $data
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	public function validator($id, array $data)
	{
		$parent_id = (int) array_get($data, 'parent_id');

		$validator = Validator::make($data, [
			'title' => 'required|max:255',
			'slug' => "max:100|unique:pages,slug,{$id},id,parent_id,{$parent_id}"
		]);

		$validator->sometimes('status', 'required|numeric', function($input) use($id)
		{
			return $id > 1;
		});

		return $validator;
	}

	/**
	 * Create a new user instance after a valid registration.
	 *
	 * @param integer $id
	 * @param  array $data
	 * @return User
	 */
	public function update($id, array $data)
	{
		$page = Page::findOrFail($id);

		if (!isset($data['is_redirect']))
		{
			$data['is_redirect'] = 0;
		}

		// TODO: фильтровать входные данные через модель
		$page
			->update(array_only($data, [
				'title', 'slug', 'is_redirect', 'breadcrumb',
				'meta_title', 'meta_keywords', 'meta_description',
				'robots', 'parent_id', 'layout_file', 'behavior',
				'status', 'published_at', 'redirect_url'
			]));

		return $page;
	}
}
