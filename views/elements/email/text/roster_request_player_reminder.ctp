Dear <?php echo $person['first_name']; ?>,

You requested to join the roster of the <?php
echo Configure::read('organization.name'); ?> team <?php echo $team['name']; ?> as a <?php
echo Configure::read("options.roster_role.${roster['role']}"); ?>.

This request has not yet been responded to by a coach or captain, and will expire <?php echo $days; ?> days from now. An email has been sent to remind them, but you might want to get in touch directly as well.

<?php echo $this->element('email/text/footer'); ?>
