<?php
$this->Html->addCrumb (__('Division', true));
$this->Html->addCrumb ($division['Division']['full_league_name']);
$this->Html->addCrumb (__('Add Games', true));
$this->Html->addCrumb (__('Select Exclusions', true));
?>

<div class="schedules add">
<p><?php __('The "exclude teams" option is set for this division.'); ?> <?php echo $this->ZuluruHtml->help(array('action' => 'divisions', 'edit', 'exclude_teams')); ?></p>
<p><?php __('Please select the teams you wish to EXCLUDE from scheduling. You must ensure that you leave an even number of teams.'); ?></p>
<?php
echo $this->Form->create ('Game', array('url' => Router::normalize($this->here)));
echo $this->Form->hidden('step', array('value' => 'exclude'));

foreach ($division['Team'] as $team) {
	echo $this->Form->input("ExcludeTeams.{$team['id']}", array(
			'label' => $team['name'],
			'type' => 'checkbox',
			'hiddenField' => false,
	));
}

echo $this->Form->end(__('Next step', true));

$is_tournament = $division['Division']['schedule_type'] == 'tournament';
if (!$is_tournament) {
	echo $this->Html->para(null, sprintf(__('Alternately, you can %s.', true),
			$this->Html->link(__('create a playoff schedule', true), array('division' => $division['Division']['id'], 'playoff' => true))) .
		$this->ZuluruHtml->help(array('action' => 'schedules', 'playoffs'))
	);
}
?>

</div>