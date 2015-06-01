<?php

/**
 * Lex Reflex Entry model config
*/

return array(

		'title' => 'Reflex -> Entry',
		'single' => 'Reflex -> Entry',
		'model' => 'LexReflexEntry',

		'columns' => array(
				'reflex' => array(
						'title' => 'Reflex',
						'relationship'=> 'reflex.language',
						'select' => 'CONCAT((:table).name, " -> ", reflex_lex_reflex.gloss)'
				),
				'entry' => array(
						'title' => 'Entry',
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
				'entry' => array(
						'title' => 'Entry',
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
				'field' => 'entry',
				'direction' => 'asc',
		),

		'edit_fields' => array(
				'entry' => array(
						'title' => 'Entry',
						'type' => 'text',
				),
				'order' => array(
						'title' => 'Order',
						'type' => 'number',
						'thousands_separator' => '',
				),
				'reflex' => array(
						'title' => 'Reflex',
						'type' => 'relationship',
						'name_field' => 'reflex_lister',
						'autocomplete' => true,				
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
				'entry' => 'required',
				'order' => 'required',
				'reflex' => 'required',
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