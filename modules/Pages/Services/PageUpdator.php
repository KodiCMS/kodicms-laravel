<?php namespace KodiCMS\Pages\Services;

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

		$page->update(array_except($data, [
			'continue', 'commit'
		]));

		return $page;
	}
}
