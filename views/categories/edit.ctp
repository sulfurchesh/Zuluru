<?php
$this->Html->addCrumb (__('Categories', true));
if (isset ($add)) {
	$this->Html->addCrumb (__('Create', true));
} else {
	$this->Html->addCrumb ($this->Form->value('Category.name'));
	$this->Html->addCrumb (__('Edit', true));
}
?>

<div class="categories form">
<?php echo $this->Form->create('Category', array('url' => Router::normalize($this->here))); ?>
	<fieldset>
		<legend><?php printf(__(isset($add) ? 'Create %s' : 'Edit %s', true), __('Category', true)); ?></legend>
	<?php
		if (!isset ($add)) {
			echo $this->Form->input('id');
		}
		echo $this->Form->input('name', array(
				'size' => 100,
		));
		if (isset ($add)) {
			echo $this->ZuluruForm->input('affiliate_id', array(
				'options' => $affiliates,
				'hide_single' => true,
				'empty' => '---',
			));
		}
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $this->Html->link(sprintf(__('List %s', true), __('Categories', true)), array('action' => 'index'));?></li>
<?php if (!isset ($add)): ?>
		<li><?php echo $this->ZuluruHtml->iconLink('delete_32.png',
				array('action' => 'delete', 'category' => $this->Form->value('Category.id')),
				array('alt' => __('Delete', true), 'title' => __('Delete Category', true)),
				array('confirm' => sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('Category.id')))); ?></li>
		<li><?php echo $this->ZuluruHtml->iconLink('add_32.png',
					array('action' => 'add'),
					array('alt' => __('New', true), 'title' => __('New', true))); ?></li>
<?php endif; ?>
	</ul>
</div>
