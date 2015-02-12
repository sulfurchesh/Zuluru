<?php
$field = 'court';

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
		'womens'	=> 8,
		'mens'		=> 8,
		'co-ed'		=> 8,
	),

	'positions' => array(
		'unspecified' => 'Unspecified',
		'goalkeeper' => 'Goalkeeper',
		'defender' => 'Defender',
		'wing' => 'Wing/Wide',
		'pivot' => 'Pivot/Target',
	),

	'score_options' => array(
		'Goal' => 1,
	),

	'other_options' => array(
		'Half' => 'Kick-off to start second half',
	),

	'rating_questions' => false,
	'game_length' => 50,
);

if (file_exists(CONFIGS . 'sport/futsal_custom.php')) {
	include(CONFIGS . 'sport/futsal_custom.php');
}

$config['sport']['ratio'] = make_human_options(array_keys($config['sport']['roster_requirements']));

?>
