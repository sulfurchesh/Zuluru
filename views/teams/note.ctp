<?php
$this->Html->addCrumb (__('Teams', true));
$this->Html->addCrumb ($this->data['Team']['name']);
$this->Html->addCrumb (__('Note', true));
if (empty($this->data['Note']['id'])) {
	$this->Html->addCrumb (__('Add', true));
} else {
	$this->Html->addCrumb (__('Edit', true));
}
?>

<div class="people form">
<?php
echo $this->Form->create('Note', array('url' => Router::normalize($this->here)));
if (!empty($this->data['Note']['id'])) {
	echo $this->Form->input('id');
}
$options = array(
		VISIBILITY_PRIVATE => __('Only I will be able to see this', true),
);
if (in_array($this->data['Team']['id'], $this->UserCache->read('OwnedTeamIDs'))) {
	$options[VISIBILITY_CAPTAINS] = __('Only I and the coaches/captains of our team', true);
}
if (in_array($this->data['Team']['id'], $this->UserCache->read('TeamIDs'))) {
	$options[VISIBILITY_TEAM] = __('Everyone on my team', true);
}
if ($is_admin) {
	$options[VISIBILITY_COORDINATOR] = __('Admins and coordinators of this division', true);
	$options[VISIBILITY_ADMIN] = __('Administrators only', true);
} else {
	if (in_array($this->data['Team']['division_id'], $this->UserCache->read('DivisionIDs'))) {
		$options[VISIBILITY_COORDINATOR] = __('Admins and coordinators of this division', true);
	}
}
echo $this->ZuluruForm->input('visibility', array(
		'options' => $options,
		'hide_single' => true,
));
echo $this->ZuluruForm->input('note', array('cols' => 70, 'class' => 'mceSimple'));
echo $this->Form->end(__('Submit', true));
?>
</div>
<?php if (Configure::read('feature.tiny_mce')) $this->TinyMce->editor('simple'); ?>
