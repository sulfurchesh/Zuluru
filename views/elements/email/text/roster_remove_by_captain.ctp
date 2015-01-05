Dear <?php echo $person['first_name']; ?>,

<?php echo $captain; ?> has removed you from the roster of the <?php
echo Configure::read('organization.name'); ?> team <?php echo $team['name']; ?>. You were previously listed as a <?php
echo Configure::read("options.roster_role.$old_role"); ?>.

This is a notification only, there is no action required on your part.

If you believe that this has happened in error, please contact <?php echo $reply; ?>.

<?php echo $this->element('email/text/footer'); ?>
