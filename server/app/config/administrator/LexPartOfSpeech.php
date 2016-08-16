<?php

/**
 * Lex Part Of Speech model config
*/

return array(

		'title' => 'Part Of Speech',
		'single' => 'Part Of Speech',
		'model' => 'LexPartOfSpeech',

		'columns' => array(
				'code' => array(
						'title' => 'Code',
				),
				'display' => array(
						'title' => 'Display',
				),
				'updated_by' => array(
						'title' => 'Updated By',
				),
				'updated_at' => array(
						'title' => 'Updated Time',
				),
		),
		
		'filters' => array(
				'code' => array(
						'title' => 'Code',
				),
				'display' => array(
						'title' => 'Display',
				),
				'updated_by' => array(
						'title' => 'Updated By',
				),
				'updated_at' => array(
						'title' => 'Updated Time',
				),
		),
		
		'sort' => array(
				'field' => 'code',
				'direction' => 'asc',
		),

		'edit_fields' => array(
				'code' => array(
						'title' => 'Code',
						'type' => 'text',
				),
				'display' => array(
						'title' => 'Display',
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
				'code' => 'required|unique:lex_part_of_speech',
				'display' => 'required|unique:lex_part_of_speech',
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