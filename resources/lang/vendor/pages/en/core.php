<?php

return [
	'title' => [
		'pages' => [
			'create' 	=> 'New page',
			'list' 		=> 'Page',
			'edit' 		=> 'Edit page :title',
		],
		'layouts' => [
			'list' => 'Templates'
		]
	],
	'status' => [
		'none' 			=> 'Not specified',
		'hidden' 		=> 'Hidden',
		'draft' 		=> 'Draft',
		'published' => 'Published',
		'pended' 		=> 'Expectation',
	],
	'button' => [
		'add' 			=> 'Add',
		'reorder' 	=> 'Sort',
		'view_front'=> 'View',
		'search' 		=> 'Search',
	],
	'tab' => [
		'page' => [
			'content' => 'Content',
			'meta' 		=> 'Meta-information',
			'options' => 'Settings',
			'routes' 	=> 'Routes',
		]
	],
	'label' => [
		'page' => [
			'created_by' 			=> 'Created :anchor :date',
			'updated_by' 			=> 'Updated :anchor :date',
			'layout_not_set' 	=> 'Template Unknown',
			'current_layout' 	=> 'Current template: name',
			'redirect' 				=> 'Redirect: :url',
			'behavior' 				=> 'Behavior: :behavior',
		]
	],
	'field' => [
		'title' 						=> 'Title',
		'slug' 							=> 'Slug',
		'name' 							=> 'Name',
		'page' 							=> 'Page',
		'date' 							=> 'Date',
		'status' 						=> 'Status',
		'actions' 					=> 'Actions',
		'search' 						=> 'Search',
		'breadcrumb' 				=> 'Breadcrumb',
		'meta_title' 				=> 'Meta-header',
		'meta_keywords'			=> 'Keywords',
		'meta_description' 	=> 'Description',
		'robots' 						=> 'Indexing by search engines',
		'is_redirect' 			=> 'Redirect',
		'redirect_url' 			=> 'URL destination',
		'parent_id' 				=> 'Parent page',
		'layout_file' 			=> 'Template',
		'behavior' 					=> 'Behavior',
		'published_at'			=> 'Published at',
		'created_by_id' 		=> 'Created by',
		'updated_by_id' 		=> 'Updated by',
	],
	'messages' => [
		'not_found' 					=> 'Page not found',
		'layout_not_set' 			=> 'For the current page template Unknown',
		'updated' 						=> 'Page Settings saved',
		'created' 						=> 'Page created',
		'behavior_no_routes' 	=> 'This type of behavior has no pages domestic routes',
	]
];