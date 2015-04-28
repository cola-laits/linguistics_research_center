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
						'type' => 'text',
				),
				'gloss' => array(
						'title' => 'Gloss',
						'type' => 'text',
				),
				'page_number' => array(
						'title' => 'Page Number',
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
				'old_id' => 'required',
				'order' => 'required',
				'entry' => 'required',
				'gloss' => 'required',
				'page_number' => 'required',

		),
		
		'action_permissions'=> array(
				'delete' => function($model)
				{
					return false;
				}
		),
);