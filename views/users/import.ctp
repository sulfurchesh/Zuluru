<?php
$this->Html->addCrumb (__('Users', true));
$this->Html->addCrumb (__('Import', true));
?>

<div class="users view">
<h2><?php  echo __('Import Users', true);?></h2>

<?php if (isset($header)): ?>
<p>The following columns were recognized and will be imported: <?php echo implode(', ', $header); ?></p>
<p>The following columns were not recognized and will be skipped: <?php echo implode(', ', $skip); ?></p>
<?php endif; ?>

<?php if (!empty($succeeded)): ?>
<p><?php echo count($succeeded); ?> accounts had no problems: <a class="success_hidden" href="#">Show details</a><a class="success_details" href="#">Hide details</a></p>
<div class="success_details"><?php echo $this->Html->nestedList($succeeded); ?></div>
<?php
$this->Js->get('a.success_hidden')->event('click', 'jQuery(".success_details").show(); jQuery(".success_hidden").hide();');
$this->Js->get('a.success_details')->event('click', 'jQuery(".success_details").hide(); jQuery(".success_hidden").show();');
$this->Js->buffer('
jQuery(".success_details").hide();
');
endif;
?>

<?php if (!empty($resolved)): ?>
<p><?php echo count($resolved); ?> accounts had one or more problems which were resolved: <a class="resolved_hidden" href="#">Show details</a><a class="resolved_details" href="#">Hide details</a></p>
<div class="resolved_details"><?php echo $this->Html->nestedList($resolved); ?></div>
<?php
$this->Js->get('a.resolved_hidden')->event('click', 'jQuery(".resolved_details").show(); jQuery(".resolved_hidden").hide();');
$this->Js->get('a.resolved_details')->event('click', 'jQuery(".resolved_details").hide(); jQuery(".resolved_hidden").show();');
$this->Js->buffer('
jQuery(".resolved_details").hide();
');
endif;
?>

<?php if (!empty($failed)): ?>
<p><?php echo count($failed); ?> accounts had one more more unresolvable problems: <a class="failure_hidden" href="#">Show details</a><a class="failure_details" href="#">Hide details</a></p>
<div class="failure_details"><?php echo $this->Html->nestedList($failed); ?></div>
<?php
$this->Js->get('a.failure_hidden')->event('click', 'jQuery(".failure_details").show(); jQuery(".failure_hidden").hide();');
$this->Js->get('a.failure_details')->event('click', 'jQuery(".failure_details").hide(); jQuery(".failure_hidden").show();');
$this->Js->buffer('
jQuery(".failure_details").hide();
');
endif;
?>

<?php
echo $this->Form->create(false, array('url' => Router::normalize($this->here), 'enctype' => 'multipart/form-data'));
echo $this->Form->input('file', array('type' => 'file', 'label' => __('CSV file', true)));
echo $this->ZuluruForm->input('Affiliate.Affiliate', array(
		'options' => $affiliates,
		'hide_single' => true,
		'empty' => '---',
));

echo $this->Form->input('Person.trim_email_domain', array(
		'type' => 'checkbox',
		'after' => $this->Html->para(null, 'If checked, and a user name is created from an email address, the domain portion of the email address will be removed first. If duplicates are caused this way, they will be numbered 2, 3, etc.'),
));
echo $this->Form->input('Person.trial_run', array(
		'type' => 'checkbox',
		'after' => $this->Html->para(null, 'If checked, no users will be created; the file will be tested and a report generated.'),
));
echo $this->ZuluruForm->input('Person.status', array(
		'label' => __('Status to set for imported accounts', true),
		'options' => Configure::read('options.record_status'),
		'empty' => '---',
));
echo $this->ZuluruForm->input('Person.on_error', array(
		'options' => array(
			'skip' => 'Skip record',
			'blank' => 'Import blank field',
			'ignore' => 'Ignore errors and import data as-is',
		),
		'empty' => '---',
		'after' => $this->Html->para(null, 'Note that the email and user name fields cannot be blank, so records with errors in those fields will always be skipped. This has no effect if "trial run" is checked above.'),
));
echo $this->Form->input('Person.notify_new_users', array(
		'type' => 'checkbox',
		'after' => $this->Html->para(null, 'If checked, new users will receive an email with their user name and password. This has no effect if "trial run" is checked above.'),
));
echo $this->Form->end(__('Upload', true));
?>

<ul>
<li>File to be imported must have column names in the first row.</li>
<li>The only required column is email.</li>
<li>If there is no user_name column, or if the user_name column is blank for a user, their email address will be used as their user name.</li>
<li>If there is no password column, or if the password column is blank for a user, a random one will be generated.</li>
<li>An id column may be included, but this is discouraged unless you really know what you're doing.</li>
<li>Other optional columns are <?php
echo implode(', ', $columns);
?>.
<li>Any other columns will be ignored.</li>
<li>Column names must match these names exactly, including case-sensitivity and underscores where present.</li>
<?php if (in_array('birthdate', $columns)): ?>
<li><?php if (Configure::read('feature.birth_year_only')): ?>
Birthdate may be specified in YYYY-MM-DD or YYYY format.
<?php else: ?>
Birthdate must be specified in YYYY-MM-DD format.
<?php endif; ?></li>
<?php endif; ?>
<li>Rows starting with a # will be skipped.</li>
</ul>
</div>
