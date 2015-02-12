<?php
$field = 'pitch';

$config['sport'] = array(
	'field' => $field,
	'field_cap' => Inflector::humanize($field),
	'fields' => Inflector::pluralize($field),
	'fields_cap' => Inflector::humanize(Inflector::pluralize($field)),

	'start' => array(
		'stat_sheet' => 'Initial kick-off',
		'stat_sheet_direction' => true,
		'live_score' => 'Team taking kick-off',
		'box_score' => '%s took the kick-off',
		'twitter' => '%s takes the kick-off',
	),

	'roster_requirements' => array(
		'womens'=> 16,
		'mens'	=> 16,
		'co-ed'	=> 16,
	),

	'positions' => array(
		'unspecified' => 'Unspecified',
		'goalkeeper' => 'Goalkeeper',
		'fullback' => 'Fullback',
		'midfielder' => 'Midfielder',
		'attacker' => 'Attacker',
		'sweeper' => 'Sweeper',
		'centerfullback' => 'Center Fullback',
		'leftfullback' => 'Left Fullback',
		'rightfullback' => 'Right Fullback',
		'leftwingback' => 'Left Wingback',
		'wingback' => 'Wingback',
		'rightwingback' => 'Right Wingback',
		'leftmidfielder' => 'Left Midfielder',
		'defensivemidfielder' => 'Defensive Midfielder',
		'attackingmidfielder' => 'Attacking Midfielder',
		'rightmidfielder' => 'Right Midfielder',
		'leftwinger' => 'Left Winger',
		'striker' => 'Striker',
		'secondstriker' => 'Second Striker',
		'centerforward' => 'Center Forward',
		'rightwinger' => 'Right Winger',
	),

	'score_options' => array(
		'Goal' => 1,
	),

	'other_options' => array(
		'Half' => 'Kick-off to start second half',
		'Substitution' => 'Substitution',
	),

	'rating_questions' => false,
	'game_length' => 90,
);

if (file_exists(CONFIGS . 'sport/soccer_custom.php')) {
	include(CONFIGS . 'sport/soccer_custom.php');
}

$config['sport']['ratio'] = make_human_options(array_keys($config['sport']['roster_requirements']));

?>
