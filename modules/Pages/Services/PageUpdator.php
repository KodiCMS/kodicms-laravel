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
		$validator = Validator::make($data, [
			'title' => 'required|max:255',
			'slug' => 'max:100',
			'status' => 'required|numeric'
		]);

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
			'continue'
		]));

		return $page;
	}
}
