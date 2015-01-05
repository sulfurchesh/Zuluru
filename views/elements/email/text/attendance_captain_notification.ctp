Dear <?php echo $captains; ?>,

<?php echo $person['full_name']; ?> has indicated that <?php
echo ($person['gender'] == 'Male' ? 'he' : 'she'); ?> will be <?php
echo Configure::read("attendance_verb.$status");
?> the <?php echo $team['name']; ?> game<?php
if (isset($game)) {
	$url = Router::url(array('controller' => 'fields', 'action' => 'view', 'field' => $game['GameSlot']['Field']['id']), true);
	echo ' against ' . $opponent['name'] .
		" at {$game['GameSlot']['Field']['long_name']} ($url)" .
		' starting at ' . $this->ZuluruTime->time($game['GameSlot']['game_start']);
	$arg = 'game';
	$val = $game['Game']['id'];
} else {
	$arg = 'date';
	$val = $date;
}
?> on <?php
echo $this->ZuluruTime->date($date);
?>.

<?php if (!empty($comment)): ?>
<?php echo $comment; ?>


<?php endif; ?>
<?php if ($status == ATTENDANCE_AVAILABLE): ?>
If you need <?php echo $person['first_name']; ?> for this game:
<?php
echo Router::url(array('controller' => 'games', 'action' => 'attendance_change', 'team' => $team['id'], $arg => $val, 'person' => $person['id'], 'code' => $code, 'status' => ATTENDANCE_ATTENDING), true);
?>


If you know <b>for sure</b> that you don't need <?php echo $person['first_name']; ?> for this game:
<?php
echo Router::url(array('controller' => 'games', 'action' => 'attendance_change', 'team' => $team['id'], $arg => $val, 'person' => $person['id'], 'code' => $code, 'status' => ATTENDANCE_ABSENT), true);
?>


Either of these actions will generate an automatic email to <?php echo $person['first_name']; ?> indicating your selection. If you are unsure whether you will need <?php echo $person['first_name']; ?> for this game, it's best to leave <?php echo ($person['gender'] == 'Male' ? 'him' : 'her'); ?> listed as available, and take action later when you know for sure. You can always update <?php echo ($person['gender'] == 'Male' ? 'his' : 'her'); ?> status on the web site, there is no need to keep this email for that purpose.

<?php endif; ?>
You can also check up-to-the-minute details here:
<?php
echo Router::url(array('controller' => 'games', 'action' => 'attendance', 'team' => $team['id'], 'game' => $game['Game']['id']), true);
?>


You need to be logged into the website to update this.

<?php echo $this->element('email/text/footer'); ?>
