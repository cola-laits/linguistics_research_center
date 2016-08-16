<?php

/**
 * Lex Language Sub Family model config
*/

return array(

		'title' => 'Language Sub Family',
		'single' => 'Language Sub Family',
		'model' => 'LexLanguageSubFamily',

		'columns' => array(
				'name' => array(
						'title' => 'Name',
				),
				'language_family' => array(
						'title' => 'Family',
						'relationship'=> 'language_family',
						'select' => '(:table).name'
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
				'language_family' => array(
						'title' => 'Family',
						'type' => 'relationship',
						'name_field' => 'family',
						'options_sort_field' => 'name'
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
				'name' => 'required',
				'order' => 'required',
				'family_id' => 'required',

		),
		
		'messages' => array(
			'family_id.required' => 'The family field is required.'
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