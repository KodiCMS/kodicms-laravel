<?php namespace Plugins\News\Services;

use Validator;
use Plugins\News\Model\News;
use Plugins\News\Model\NewsContent;
use KodiCMS\CMS\Contracts\ModelUpdator;

class NewsUpdator implements ModelUpdator
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
			'title' => 'required',
			'slug' => "required|max:100|unique:news,slug,{$id}",
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
		$news = News::findOrFail($id);

		$news->update(array_only($data, [
			'title', 'slug'
		]));

		$news->content->update($data['content']);

		return $news;
	}
}
