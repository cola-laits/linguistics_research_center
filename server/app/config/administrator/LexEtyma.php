<?php

/**
 * Lex Etyma model config
*/

return array(

		'title' => 'Etyma',
		'single' => 'Etyma',
		'model' => 'LexEtyma',

		'columns' => array(
				'old_id' => array(
						'title' => 'Old Id',
				),
				'order' => array(
						'title' => 'Order',
				),
				'entry' => array(
						'title' => 'Entry',
				),
				'gloss' => array(
						'title' => 'Gloss',
				),
				'page_number' => array(
						'title' => 'Page Number',
				),
				'updated_by' => array(
						'title' => 'Updated By',
				),
				'updated_at' => array(
						'title' => 'Updated Time',
				),
		),
		
		'filters' => array(
				'old_id' => array(
						'title' => 'Old Id',
				),
				'order' => array(
						'title' => 'Order',
				),
				'entry' => array(
						'title' => 'Entry',
				),
				'gloss' => array(
						'title' => 'Gloss',
				),
				'page_number' => array(
						'title' => 'Page Number',
				),
				'updated_by' => array(
						'title' => 'Updated By',
				),
				'updated_at' => array(
						'title' => 'Updated Time',
				),
		),
		
		'sort' => array(
				'field' => 'old_id',
				'direction' => 'asc',
		),

		'edit_fields' => array(
				'old_id' => array(
						'title' => 'Old Id',
						'type' => 'number',
						'thousands_separator' => '',
				),
				'order' => array(
						'title' => 'Order',
						'type' => 'number',
						'thousands_separator' => '',
				),
				'entry' => array(
						'title' => 'Entry',
						'type' => 'wysiwyg',
				),
				'gloss' => array(
						'title' => 'Gloss',
						'type' => 'text',
				),
				'semantic_fields' => array(
						'title' => 'Semantic Fields',
						'type' => 'relationship',
						'name_field' => 'text',						
				),
				'cross_references' => array(
						'title' => 'Cross References',
						'type' => 'relationship',
						'name_field' => 'entry',
				),
				'page_number' => array(
						'title' => 'Page Number',
						'type' => 'text',
				),
				'reflexes' => array(
						'title' => 'Reflexes',
						'type' => 'relationship',
						'name_field' => 'reflex_lister',
						'autocomplete' => true,
						'editable' => false,
						'description' => 'If you need to add a new reflex, you have to go to the reflex page and add it to the Etyma',
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
				'old_id' => 'required|unique:lex_etyma',
				'order' => 'required|unique:lex_etyma',
				'entry' => 'required|unique:lex_etyma',
				'gloss' => 'required',
				'page_number' => 'required',

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