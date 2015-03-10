<p><?php printf(__('The options you have for what type of games to schedule depend on the schedule type of the league. Each option will indicate how many teams, %s and days will be involved.', true), __(Configure::read('ui.fields'), true)); ?></p>
<?php
echo $this->element('help/topics', array(
		'section' => 'schedules',
		'topics' => array(
			'add/schedule_type/roundrobin' => 'Round Robin',
			'add/schedule_type/ratings_ladder' => 'Ratings Ladder',
			'add/tournament' => 'Tournament',
		),
));
?>
