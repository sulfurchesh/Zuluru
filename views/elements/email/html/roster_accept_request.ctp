<p>Dear <?php echo $person['first_name']; ?>,</p>
<p><?php echo $captain; ?> has accepted your request to join the roster of the <?php
echo Configure::read('organization.name'); ?> team <?php
$url = Router::url(array('controller' => 'teams', 'action' => 'view', 'team' => $team['id']), true);
echo $this->Html->link($team['name'], $url);
?> as a <?php
echo Configure::read("options.roster_role.$role"); ?>.</p>
<?php echo $this->element('email/html/footer'); ?>
