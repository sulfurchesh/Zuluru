Dear <?php echo $person['Person']['first_name']; ?>,

You seem to have created a duplicate <?php
echo Configure::read('organization.short_name'); ?> account. You already have an account with the username <?php
echo $existing['Person']['user_name']; ?> created using the email address <?php
echo $existing['Person']['email']; ?>.

Your second account has been deleted. If you cannot remember your password for the existing account, please use the 'Forgot your password?' feature at <?php
echo Router::url (array('controller' => 'users', 'action' => 'reset_password'), true); ?> and a new password will be emailed to you.

If the above email address is no longer correct, please reply to this message and request an address change.

Thanks,
<?php echo Configure::read('email.admin_name'); ?>

<?php echo Configure::read('organization.short_name'); ?> web team
