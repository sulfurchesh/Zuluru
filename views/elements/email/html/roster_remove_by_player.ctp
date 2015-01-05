<p>Dear <?php echo $captains; ?>,</p>
<p><?php echo $person['full_name']; ?> has removed themselves from the roster of the <?php
echo Configure::read('organization.name'); ?> team <?php
$url = Router::url(array('controller' => 'teams', 'action' => 'view', 'team' => $team['id']), true);
echo $this->Html->link($team['name'], $url);
?>. They were previously listed as a <?php
echo Configure::read("options.roster_role.$old_role"); ?>.</p>
<p>This is a notification only, there is no action required on your part.</p>
<p>If you believe that this has happened in error, please contact <?php echo $reply; ?>.</p>
<?php echo $this->element('email/html/footer'); ?>
