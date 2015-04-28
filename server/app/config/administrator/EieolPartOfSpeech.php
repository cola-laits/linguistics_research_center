<?php

/**
 * Eieol Part of Speech model config
*/

return array(

		'title' => 'Part of Speech',
		'single' => 'Part of Speech',
		'model' => 'EieolPartOfSpeech',

		'columns' => array(
				'part_of_speech' => array(
						'title' => 'Part Of Speech',
				),
				'updated_by' => array(
						'title' => 'Updated By',
				),
				'updated_at' => array(
						'title' => 'Updated Time',
				),
		),
		
		'filters' => array(
				'part_of_speech' => array(
						'title' => 'Part Of Speech',
				),
				'updated_by' => array(
						'title' => 'Updated By',
				),
				'updated_at' => array(
						'title' => 'Updated Time',
				),
		),
		
		'sort' => array(
				'field' => 'part_of_speech',
				'direction' => 'asc',
		),

		'edit_fields' => array(
				'part_of_speech' => array(
						'title' => 'Part Of Speech',
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
				'part_of_speech' => 'required',

		),
		
		'action_permissions'=> array(
				'delete' => function($model)
				{
					return false;
				}
		),
);