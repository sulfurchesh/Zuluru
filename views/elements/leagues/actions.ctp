<?php
if (!isset($is_manager)) {
	$is_manager = false;
}
if (!isset($is_coordinator)) {
	$is_coordinator = false;
}
if (!isset($format)) {
	$format = 'links';
}
if (!isset($size)) {
	$size = ($format == 'links' ? 24 : 32);
}
if (!isset($collapse)) {
	$collapse = false;
}
if (!isset($return)) {
	$return = false;
}
if (!isset($from_division_actions)) {
	$from_division_actions = false;
}

$links = array();

if ($this->params['controller'] != 'leagues' || $this->params['action'] != 'view') {
	$links[] = $this->ZuluruHtml->iconLink("view_$size.png",
		array('controller' => 'leagues', 'action' => 'view', 'league' => $league['League']['id']),
		array('alt' => __('Details', true), 'title' => __('View League Details', true)));
}
$schedule_types = array_unique(Set::extract('/Division/schedule_type', $league));
if (!empty($schedule_types) && ($schedule_types[0] != 'none' || count($schedule_types) > 1)) {
	if ($this->params['controller'] != 'leagues' || $this->params['action'] != 'schedule') {
		$links[] = $this->ZuluruHtml->iconLink("schedule_$size.png",
			array('controller' => 'leagues', 'action' => 'schedule', 'league' => $league['League']['id']),
			array('alt' => __('Schedule', true), 'title' => __('Schedule', true)));
	}
	if ($this->params['controller'] != 'leagues' || $this->params['action'] != 'standings') {
		$links[] = $this->ZuluruHtml->iconLink("standings_$size.png",
			array('controller' => 'leagues', 'action' => 'standings', 'league' => $league['League']['id']),
			array('alt' => __('Standings', true), 'title' => __('Standings', true)));
	}
}
if ($is_admin || $is_manager || ($collapse && $is_coordinator)) {
	if ($this->params['controller'] != 'leagues' || $this->params['action'] != 'edit') {
		$links[] = $this->ZuluruHtml->iconLink("edit_$size.png",
			array('controller' => 'leagues', 'action' => 'edit', 'league' => $league['League']['id'], 'return' => true),
			array('alt' => __('Edit', true), 'title' => __('Edit League', true)));
	}
	if ($this->params['controller'] != 'leagues' || $this->params['action'] != 'add') {
		$links[] = $this->ZuluruHtml->iconLink("league_clone_$size.png",
			array('controller' => 'leagues', 'action' => 'add', 'league' => $league['League']['id'], 'return' => $return),
			array('alt' => __('Clone League', true), 'title' => __('Clone League', true)));
	}
	if ($this->params['controller'] != 'divisions' || $this->params['action'] != 'add') {
		$links[] = $this->ZuluruHtml->iconLink("division_add_$size.png",
			array('controller' => 'divisions', 'action' => 'add', 'league' => $league['League']['id'], 'return' => $return),
			array('alt' => __('Add Division', true), 'title' => __('Add Division', true)));
	}
	if (!$collapse) {
		$url = array('controller' => 'leagues', 'action' => 'delete', 'league' => $league['League']['id']);
		if ($this->params['controller'] != 'leagues') {
			$url['return'] = true;
		}
		$links[] = $this->ZuluruHtml->iconLink("delete_$size.png",
			$url,
			array('alt' => __('Delete', true), 'title' => __('Delete League', true)),
			array('confirm' => sprintf(__('Are you sure you want to delete # %s?', true), $league['League']['id'])));
	}
	if ($this->params['controller'] != 'leagues' || $this->params['action'] != 'participation') {
		$links[] = $this->Html->link(__('Participation', true),
			array('controller' => 'leagues', 'action' => 'participation', 'league' => $league['League']['id']));
	}
}

if (!empty($extra)) {
	if (is_array($extra)) {
		$links = array_merge($links, $extra);
	} else {
		$links[] = $extra;
	}
}

if ($collapse && !$from_division_actions) {
	echo $this->element('divisions/actions', array_merge(
		compact('league', 'is_manager', 'format', 'size', 'collapse', 'return'),
		array(
			'from_league_actions' => true,
			'division' => $league['Division'][0],
			'league_actions' => $links,
		)
	));
} else if ($format == 'links') {
	echo implode('', $links);
} else {
	echo $this->ZuluruHtml->nestedList($links);
}
?>
