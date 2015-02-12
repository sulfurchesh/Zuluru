<?php
$field = 'diamond';

$config['sport'] = array(
	'field' => $field,
	'field_cap' => Inflector::humanize($field),
	'fields' => Inflector::pluralize($field),
	'fields_cap' => Inflector::humanize(Inflector::pluralize($field)),

	'start' => array(
		'stat_sheet' => null,
		'stat_sheet_direction' => false,
		'live_score' => null,
		'box_score' => null,
		'twitter' => '%s batting',
	),

	'roster_requirements' => array(
		'womens'=> 12,
		'mens'	=> 12,
		'co-ed'	=> 12,
	),

	'positions' => array(
		'unspecified' => 'Unspecified',
		'pitcher' => 'Pitcher',
		'catcher' => 'Catcher',
		'firstbase' => 'First Base',
		'secondbase' => 'Second Base',
		'shortstop' => 'Shortstop',
		'thirdbase' => 'Third Base',
		'rightfielder' => 'Right Fielder',
		'centerfielder' => 'Center Fielder',
		'leftfielder' => 'Left Fielder',
		'utilityinfielder' => 'Utility Infielder',
		'utilityoutfielder' => 'Utility Outfielder',
		'designatedhitter' => 'Designated Hitter',
	),

	'score_options' => array(
		'Run' => 1,
	),

	'other_options' => array(
		'Pitcher' => 'Pitching change',
		'Fielder' => 'Fielding change',
		'Batter' => 'Pinch hitter',
		'Runner' => 'Pinch runner',
	),

	'rating_questions' => false,
	'game_length' => 9,
);

if (file_exists(CONFIGS . 'sport/baseball_custom.php')) {
	include(CONFIGS . 'sport/baseball_custom.php');
}

$config['sport']['ratio'] = make_human_options(array_keys($config['sport']['roster_requirements']));

?>
