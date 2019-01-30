<?php

/**
 * Lex Reflex model config
*/

return array(

		'title' => 'Reflex',
		'single' => 'Reflex',
		'model' => 'LexReflex',

		'columns' => array(
				'entries' => array(
						'title' => 'Entries',
						'relationship'=> 'entries',
						'select' => 'GROUP_CONCAT((:table).entry ORDER BY (:table).order SEPARATOR ", ")'
				),
				'gloss' => array(
						'title' => 'Gloss',
				),
				'language' => array(
						'title' => 'Language',
						'relationship'=> 'language',
						'select' => '(:table).name'
				),
				'lang_attribute' => array(
						'title' => 'Lang Attribute',
				),
				'class_attribute' => array(
						'title' => 'Class Attribute',
				),
				'updated_by' => array(
						'title' => 'Updated By',
				),
				'updated_at' => array(
						'title' => 'Updated Time',
				),
		),
		
		'filters' => array(
				'gloss' => array(
						'title' => 'Gloss',
				),
				'lang_attribute' => array(
						'title' => 'Lang Attribute',
				),
				'class_attribute' => array(
						'title' => 'Class Attribute',
				),
				'updated_by' => array(
						'title' => 'Updated By',
				),
				'updated_at' => array(
						'title' => 'Updated Time',
				),
		),
		
		'sort' => array(
				'field' => 'gloss',
				'direction' => 'asc',
		),

		'edit_fields' => array(
				'gloss' => array(
						'title' => 'Gloss',
						'type' => 'text',
				),
				'etymas' => array(
						'title' => 'Etymas',
						'type' => 'relationship',
						'name_field' => 'entry',
						'options_sort_field' => 'entry'
				),
				'language' => array(
						'title' => 'Language',
						'type' => 'relationship',
						'name_field' => 'stripped_name',
						'options_sort_field' => 'name'
				),
				'sources' => array(
						'title' => 'Sources',
						'type' => 'relationship',
						'name_field' => 'code',
						'sort_field' => 'order',
						'description' => 'You can drag and drop these to change their order',
				),
				'lang_attribute' => array(
						'title' => 'Lang Attribute',
						'type' => 'text',
				),
				'class_attribute' => array(
						'title' => 'Class Attribute',
						'type' => 'text',
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
				'gloss' => 'required',
				'language_id' => 'required',
				'lang_attribute' => 'required',
				'class_attribute' => 'required',

		),
		
		'messages' => array(
				'language_id.required' => 'The language field is required.'
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