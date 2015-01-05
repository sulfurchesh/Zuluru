<?php
if ($team['track_attendance']) {
	$long = Configure::read("attendance.$status");
	$low = Inflector::slug(low($long));

	if (isset($dedicated) && $dedicated) {
		$low .= '_dedicated';
	} else {
		$dedicated = 0;
	}

	$title = sprintf (__('Current attendance: %s', true), __($long, true));
	if (!empty($comment)) {
		if ($dedicated) {
			$low .= '_comment';
		}
		$title .= " ($comment)";
	}

	$short = $this->ZuluruHtml->icon("attendance_{$low}_24.png", array(
			'title' => $title,
			'alt' => Configure::read("attendance_alt.$status"),
	));

	if (!isset($future_only)) {
		$future_only = false;
	}
	if (!isset($event_time)) {
		$event_time = '00:00:00';
	}

	$recent = ($event_date >= date('Y-m-d', time() - 14 * 24 * 60 * 60));
	$future = (strtotime("$event_date $event_time") + Configure::read('timezone.adjust') * 60 >= time() ? 1 : 0);
	$is_me = (!isset($person_id) || $person_id == $my_id);
	$is_relative = (!$is_me && in_array($person_id, $this->UserCache->read('RelativeIDs')));
	if (($future || (!$future_only && $recent)) && ($is_me || $is_relative || $is_captain)) {
		$url = array('controller' => 'team_events', 'action' => 'attendance_change', 'event' => $event_id);

		if (!$is_me) {
			$url['person'] = $person_id;
		}

		$options = Game::_attendanceOptions($team['id'], $role, $status, !$future, in_array($team['id'], $this->UserCache->read('OwnedTeamIDs')));
		$option_strings = array();
		foreach ($options as $key => $value) {
			$option_strings[] = "$key: '$value'";
		}
		$option_string = '{' . implode(', ', $option_strings) . '}';
		$url_string = Router::url($url);
		$comment = addslashes(htmlentities($comment));
		echo $this->Html->link($short, $url, array(
			'escape' => false,
			'class' => "attendance_status_$status",
			'onClick' => "return attendance_status('$url_string', $option_string, jQuery(this), $dedicated, $future, '$comment');",
		));
	} else if (!$future_only) {
		echo $this->Html->tag('span', $short, array('class' => "attendance_status_$status"));
	}
}

?>
