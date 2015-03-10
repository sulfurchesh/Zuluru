<?php
if ($is_admin || $is_coordinator) {
	echo $this->element('help/topics', array(
			'section' => 'divisions',
			'topics' => array(
				'edit' => array(
					'image' => 'edit_32.png',
				),
				'add_teams' => array(
					'image' => 'team_add_32.png',
				),
				'roster_add' => array(
					'image' => 'roster_add_32.png',
				),
				'emails' => array(
					'image' => 'email_32.png',
				),
				'fields' => array(
					'image' => 'field_report_32.png',
					'title' => sprintf(__('%s Distribution Report', true), __(Configure::read('ui.field_cap'), true)),
				),
				'slots' => sprintf(__('%s Availability Report', true), __(Configure::read('ui.field_cap'), true)),
				'spirit' => array(
					'image' => 'spirit_32.png',
					'title' => 'Spirit Report',
				),
				'approve_scores' => array(
					'image' => 'score_approve_32.png',
				),
			),
	));
}
?>
