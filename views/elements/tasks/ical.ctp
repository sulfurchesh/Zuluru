<?php
$modified = strtotime ($task['TaskSlot']['updated']);
$modified = date('Ymd', $modified) . 'T' . date('His', $modified) . 'Z';

// Get domain URL for signing tasks
$domain = Configure::read('urls.domain');

// encode task start and end times
$task_date = "TZID=$timezone:" . strftime('%Y%m%d', strtotime($task['TaskSlot']['task_date'])); // from date type
if ($task['TaskSlot']['task_start'] > $task['TaskSlot']['task_end']) {
	$task_end_date = "TZID=$timezone:" . strftime('%Y%m%d', strtotime($task['TaskSlot']['task_date']) + DAY);
} else {
	$task_end_date = $task_date;
}
$task_start = $task_date . 'T'
		. implode('', explode(':', $task['TaskSlot']['task_start'])); // from 'hh:mm:ss' string
$task_end = $task_end_date . 'T'
		. implode('', explode(':', $task['TaskSlot']['task_end']));  // from 'hh:mm:ss' string
$task_stamp = strftime('%a %b %d %Y', strtotime ($task['TaskSlot']['task_date'])) .
		" {$task['TaskSlot']['task_start']} to {$task['TaskSlot']['task_end']}";

// date stamp this file
$now = gmstrftime('%Y%m%dT%H%M%SZ'); // MUST be in UTC

// output task
?>
BEGIN:VEVENT
UID:<?php echo "$uid_prefix$task_slot@$domain"; ?>

DTSTAMP:<?php echo $now; ?>

CREATED:<?php echo $modified; ?>

LAST-MODIFIED:<?php echo $modified; ?>

DTSTART;<?php echo $task_start; ?>

DTEND;<?php echo $task_end; ?>

SUMMARY:<?php echo ical_encode($task['Task']['name']); ?>

DESCRIPTION:<?php echo ical_encode($task['Task']['name']); ?>, <?php __('reporting to'); ?> <?php echo $task['Task']['Person']['full_name']; ?>, <?php __('on'); ?> <?php echo $task_stamp; ?>

STATUS:CONFIRMED
TRANSP:OPAQUE
END:VEVENT
