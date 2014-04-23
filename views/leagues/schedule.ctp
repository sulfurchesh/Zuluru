<?php
$this->Html->addCrumb (__('Leagues', true));
$this->Html->addCrumb ($league['League']['full_name']);
$this->Html->addCrumb (__('Schedule', true));
?>

<?php
// Perhaps remove manager status, if we're looking at a different affiliate
if ($is_manager && !in_array($league['League']['affiliate_id'], $this->UserCache->read('ManagedAffiliateIDs'))) {
	$is_manager = false;
}
?>

<?php if (!empty($league['League']['header'])): ?>
<div class="league_header"><?php echo $league['League']['header']; ?></div>
<?php endif; ?>
<div class="leagues schedule">
<h2><?php echo __('League Schedule', true) . ': ' . $league['League']['full_name'];?></h2>
<?php
if (in_array('tournament', Set::extract('/Division/schedule_type', $league))) {
	echo $this->element('leagues/schedule/tournament/notice');
}

if (!empty ($edit_date)) {
	echo $this->Form->create ('Game', array('url' => Router::normalize($this->here)));

	// Put the slots into a more useful form for us
	$all_slots = array();
	foreach ($game_slots as $slots) {
		$all_slots = array_merge($all_slots, $slots);
	}
	usort($all_slots, array('GameSlot', 'compareTimeAndField'));
	$slots = array();
	foreach ($all_slots as $slot) {
		if ($is_tournament) {
			$slots[$slot['GameSlot']['id']] = $this->ZuluruTime->day ($slot['GameSlot']['game_date']) . ' ' . $this->ZuluruTime->time ($slot['GameSlot']['game_start']) . ' ' . $slot['Field']['long_name'];
		} else {
			$slots[$slot['GameSlot']['id']] = $this->ZuluruTime->time ($slot['GameSlot']['game_start']) . ' ' . $slot['Field']['long_name'];
		}
	}
}
?>
<?php if (!empty($league['Game'])):?>
<?php
	$future = reset(Set::extract('/Game/GameSlot[game_date>=' . date('Y-m-d') . ']/game_date', $league));
	if ($future) {
		echo $this->Html->para(null, $this->Html->link(__('Jump to upcoming games', true), "#$future"));
	}
?>
	<table class="list">
	<?php
	$schedule_types = array_unique(Set::extract('/Division/schedule_type', $league));
	$competition = (count($schedule_types) == 1 && $schedule_types[0] == 'competition');
	?>
	<tr>
		<th><?php if ($is_tournament): ?><?php __('Game'); ?><?php endif; ?></th>
		<th><?php __('Time'); ?></th>
		<th><?php __(Configure::read('sport.field_cap')); ?></th>
		<th><?php __($competition ? 'Team' : 'Home'); ?></th>
		<?php if (!$competition): ?>
		<th><?php __('Away'); ?></th>
		<?php endif; ?>
		<th><?php __('Score'); ?></th>
	</tr>
	<?php
	$dates = array_unique(Set::extract ('/Game/GameSlot/game_date', $league));
	foreach ($dates as $date) {
		if ($date == $edit_date) {
			echo $this->element('leagues/schedule/week_edit', compact ('date', 'slots', 'is_manager', 'is_tournament'));
		} else {
			echo $this->element('leagues/schedule/week_view', compact ('date', 'is_manager'));
		}
	}
	?>
	</table>
<?php endif; ?>

<?php
if (!empty ($edit_date)) {
	echo $this->Form->end();
}
?>

</div>

<div class="actions"><?php echo $this->element('leagues/actions', array(
	'league' => $league,
	'format' => 'list',
)); ?></div>
<?php if (!empty($league['League']['footer'])): ?>
<div class="league_footer"><?php echo $league['League']['footer']; ?></div>
<?php endif; ?>
