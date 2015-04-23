<?php
$class = null;
if (count ($classes)) {
	$class = ' class="' . implode (' ', $classes). '"';
}
?>
<tr<?php echo $class;?>>
	<td><?php echo $seed; ?></td>
	<td><?php
	echo $this->element('teams/block', array('team' => $team));
	?></td>
	<td><?php echo $team['rating']; ?></td>
	<td><?php echo (array_key_exists('Season', $team) ? $team['Season']['W'] : '-'); ?></td>
	<td><?php echo (array_key_exists('Season', $team) ? $team['Season']['L'] : '-'); ?></td>
	<td><?php echo (array_key_exists('Season', $team) ? $team['Season']['T'] : '-'); ?></td>
	<td><?php echo (array_key_exists('Season', $team) ? $team['Season']['def'] : '-'); ?></td>
	<td><?php echo (array_key_exists('Season', $team) ? $team['Season']['gf'] : '-'); ?></td>
	<td><?php echo (array_key_exists('Season', $team) ? $team['Season']['ga'] : '-'); ?></td>
	<td><?php echo (array_key_exists('Season', $team) ? $team['Season']['gf'] - $team['Season']['ga'] : '-'); ?></td>
	<td><?php
	if (array_key_exists('Season', $team) && $team['Season']['str'] > 0) {  // XXX: Config as per options
		echo $team['Season']['str'] . __($team['Season']['str_type'], true);
	} else {
		echo '-';
	}
	?></td>
	<?php if (League::hasSpirit($league)): ?>
	<td><?php
	if (!array_key_exists('Season', $team) || $team['Season']['spirit_games'] == 0) {
		$spirit = null;
	} else {
		$spirit = $team['Season']['spirit'] / $team['Season']['spirit_games'];
	}
	echo $this->element ('spirit/symbol', array(
			'spirit_obj' => $spirit_obj,
			'league' => $league,
			'is_coordinator' => $is_coordinator,
			'value' => $spirit,
	));
	?></td>
	<?php endif; ?>
	<?php if (League::hasCarbonFlip($league)): ?>
	<td><?php echo (array_key_exists('Season', $team) ? $team['Season']['CFW'] : '-'); ?></td>
	<td><?php echo (array_key_exists('Season', $team) ? $team['Season']['CFL'] : '-'); ?></td>
	<td><?php echo (array_key_exists('Season', $team) ? $team['Season']['CFT'] : '-'); ?></td>
	<td><?php echo ((array_key_exists('Season', $team) && $team['Season']['cf_games'] > 0) ? sprintf('%0.1f', $team['Season']['cf_pts'] / $team['Season']['cf_games']) : '-'); ?></td>
	<?php endif; ?>
</tr>
