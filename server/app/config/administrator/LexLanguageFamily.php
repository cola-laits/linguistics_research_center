<?php

/**
 * Lex Language Family model config
*/

return array(

		'title' => 'Language Family',
		'single' => 'Language Family',
		'model' => 'LexLanguageFamily',

		'columns' => array(
				'name' => array(
						'title' => 'Name',
				),
				'order' => array(
						'title' => 'Order',
				),
				'updated_by' => array(
						'title' => 'Updated By',
				),
				'updated_at' => array(
						'title' => 'Updated Time',
				),
		),
		
		'filters' => array(
				'name' => array(
						'title' => 'Name',
				),
				'order' => array(
						'title' => 'Order',
				),
				'updated_by' => array(
						'title' => 'Updated By',
				),
				'updated_at' => array(
						'title' => 'Updated Time',
				),
		),
		
		'sort' => array(
				'field' => 'name',
				'direction' => 'asc',
		),

		'edit_fields' => array(
				'name' => array(
						'title' => 'Name',
						'type' => 'wysiwyg',
				),
				'order' => array(
						'title' => 'Order',
						'type' => 'number',
						'thousands_separator' => '',
				),
				'updated_by' => array(
						'title' => 'Updated By',
						'editable' => false
				),
				'updated_at' => array(
						'title' => 'Updated Time',
						'editable' => false,
				),
				'created_by' => array(
						'title' => 'Created By',
						'editable' => false
				),
				'created_at' => array(
						'title' => 'Created Time',
						'editable' => false,
				),
		),
		
		'rules' => array(
				'name' => 'required|unique:lex_language_family',
				'order' => 'required|unique:lex_language_family',

		),
		
		'action_permissions'=> array(
				'delete' => function($model){
					return false;
				},
				'view' => function($model){
					return Auth::user()->isAdmin();
				},
				'update' => function($model){
					return Auth::user()->isAdmin();
				},
				'create' => function($model){
					return Auth::user()->isAdmin();
				}
		),
);