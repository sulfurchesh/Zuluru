<?php
$class = null;
if (count ($classes)) {
	$class = ' class="' . implode (' ', $classes). '"';
}
?>
<tr<?php echo $class;?>>
	<?php if ($is_admin || $is_coordinator): ?>
	<td><?php echo $team['rating']; ?></td>
	<?php endif; ?>
	<td><?php
	echo $this->element('teams/block', array('team' => $team));
	?></td>
	<?php if ($division['Division']['is_playoff']): ?>
	<td><?php echo $team['affiliate_division']; ?></td>
	<?php endif; ?>
	<?php if ($is_logged_in): ?>
	<td><?php
	$roster_required = Configure::read("roster_requirements.{$division['Division']['ratio']}");
	$count = $team['roster_count'];
	if (($is_admin || $is_coordinator) && $team['roster_count'] < $roster_required && $division['Division']['roster_deadline'] != '0000-00-00') {
		echo $this->Html->tag ('span', $count, array('class' => 'warning-message'));
	} else {
		echo $count;
	}
	?></td>
	<td><?php echo $team['average_skill']; ?></td>
	<td class="actions">
	<?php echo $this->element('teams/actions', compact('team')); ?>
	</td>
	<?php endif; ?>
</tr>
