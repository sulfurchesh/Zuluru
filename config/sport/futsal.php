<?php
$field = 'pitch';

$config['sport'] = array(
	'field' => $field,
	'field_cap' => Inflector::humanize($field),
	'fields' => Inflector::pluralize($field),
	'fields_cap' => Inflector::humanize(Inflector::pluralize($field)),

	'roster_requirements' => array(
		'womens'=> 8,
		'mens'	=> 8,
		'co-ed'	=> 8,
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

	'rating_questions' => false,
);

$config['sport']['ratio'] = make_human_options(array_keys($config['sport']['roster_requirements']));

?>
