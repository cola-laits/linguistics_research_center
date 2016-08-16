<?php

/**
 * Lex Semantic Field model config
*/

return array(

		'title' => 'Semantic Field',
		'single' => 'Semantic Field',
		'model' => 'LexSemanticField',

		'columns' => array(
				'number' => array(
						'title' => 'Number',
				),
				'text' => array(
						'title' => 'Text',
				),
				'abbr' => array(
						'title' => 'Abbr',
				),
				'semantic_category' => array(
						'title' => 'Semantic Category',
						'relationship'=> 'semantic_category',
						'select' => 'CONCAT((:table).number," ",(:table).text)'
				),
				'updated_by' => array(
						'title' => 'Updated By',
				),
				'updated_at' => array(
						'title' => 'Updated Time',
				),
		),
		
		'filters' => array(
				'number' => array(
						'title' => 'Number',
				),
				'text' => array(
						'title' => 'Text',
				),				
				'abbr' => array(
						'title' => 'Abbr',
				),
				'updated_by' => array(
						'title' => 'Updated By',
				),
				'updated_at' => array(
						'title' => 'Updated Time',
				),
		),
		
		'sort' => array(
				'field' => 'number',
				'direction' => 'asc',
		),

		'edit_fields' => array(
				'number' => array(
						'title' => 'Number',
						'type' => 'text',
				),
				'text' => array(
						'title' => 'Text',
						'type' => 'text',
				),
				'abbr' => array(
						'title' => 'Abbr',
						'type' => 'text',
				),
				'semantic_category' => array(
						'title' => 'Semantic Category',
						'type' => 'relationship',
						'name_field' => 'text',
						'options_sort_field' => 'text'
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
				'number' => 'required|unique:lex_semantic_field',
				'text' => 'required|unique:lex_semantic_field',
				'abbr' => 'required|unique:lex_semantic_field',
				'semantic_category_id' => 'required',
		),
		
		'messages' => array(
				'semantic_category_id.required' => 'The semantic category field is required.'
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