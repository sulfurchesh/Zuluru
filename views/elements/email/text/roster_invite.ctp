Dear <?php echo $person['first_name']; ?>,

<?php echo $captain; ?> has invited you to join the roster of the <?php
echo Configure::read('organization.name'); ?> team <?php echo $team['name']; ?> as a <?php
echo Configure::read("options.roster_role.$role"); ?>.

<?php echo $team['name']; ?> plays in the <?php echo $this->element('email/division'); ?>.

More details about <?php echo $team['name']; ?> may be found at
<?php echo Router::url(array('controller' => 'teams', 'action' => 'view', 'team' => $team['id']), true); ?>


We ask that you please accept or decline this invitation at your earliest convenience. The invitation will expire after a couple of weeks.

If you accept the invitation, you will be added to the team's roster and your contact information will be made available to the team coaches and captains.

Note that, before accepting the invitation, you must be a registered member of <?php echo Configure::read('organization.short_name'); ?>.

<?php if (isset($accept_warning)): ?>
The system has also generated this warning which must be resolved before you can accept this invitation:
<?php echo $accept_warning; ?>

<?php endif; ?>

Accept the invitation here:
<?php echo Router::url(array('controller' => 'teams', 'action' => 'roster_accept', 'team' => $team['id'], 'person' => $person['id'], 'code' => $code), true); ?>


If you decline the invitation you will be removed from this team's roster and your contact information will not be made available to the coaches or captains. This protocol is in accordance with the <?php
echo Configure::read('organization.short_name'); ?> Privacy Policy.

Decline the invitation here:
<?php echo Router::url(array('controller' => 'teams', 'action' => 'roster_decline', 'team' => $team['id'], 'person' => $person['id'], 'code' => $code), true); ?>


Please be advised that players are NOT considered a part of a team roster until they have accepted the invitation to join. The <?php
echo $team['name']; ?> roster must be completed <?php
$min = Configure::read("sport.roster_requirements.{$division['ratio']}");
if ($min > 0): ?>(minimum of <?php echo $min; ?> rostered players) <?php endif; ?>by the team roster deadline (<?php
$date_format = reset(Configure::read('options.date_formats'));
echo $this->ZuluruTime->date(Division::rosterDeadline($division));
?>), and all team members must have accepted the invitation.

<?php echo $this->element('email/text/footer'); ?>
