<?php

/**
 * Lex Semantic Category model config
*/

return array(

		'title' => 'Semantic Category',
		'single' => 'Semantic Category',
		'model' => 'LexSemanticCategory',

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
						'type' => 'number',
				),
				'text' => array(
						'title' => 'Text',
						'type' => 'text',
				),
				'abbr' => array(
						'title' => 'Abbr',
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
				'number' => 'required|unique:lex_semantic_category',
				'text' => 'required|unique:lex_semantic_category',
				'abbr' => 'required|unique:lex_semantic_category',

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