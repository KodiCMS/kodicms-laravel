<?php namespace Plugins\News\Services;

use KodiCMS\CMS\Contracts\ModelCreator;
use Plugins\News\Model\News;
use Plugins\News\Model\NewsContent;
use Validator;

class NewsCreator implements ModelCreator
{
	/**
	 * Get a validator for an incoming registration request.
	 *
	 * @param  array $data
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	public function validator(array $data)
	{
		$validator = Validator::make($data, [
			'title' => 'required',
			'slug' => 'required|max:100|unique:news',
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
		$news = News::create(array_only($data, [
			'title', 'slug'
		]));

		$news->content()->save(new NewsContent($data['content']));
		return $news;
	}
}
