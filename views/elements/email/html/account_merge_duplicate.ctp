<p>Dear <?php echo $person['Person']['first_name']; ?>,</p>
<p>You seem to have created a duplicate <?php
echo Configure::read('organization.short_name'); ?> account. You already had an account<?php
if (!empty($person['Person']['user_name'])): ?> with the username <?php
	echo $existing['Person']['user_name']; ?> created using the email address <?php
	echo $existing['Person']['email']; ?><?php endif; ?>.</p>
<p>To preserve historical information (registrations, team records, etc.) this old account has been merged with your new information. You will be able to access this account with your newly chosen user name and password.</p>
<?php echo $this->element('email/html/footer'); ?>
