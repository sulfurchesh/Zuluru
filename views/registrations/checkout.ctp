<?php
$this->Html->addCrumb (__('Registration', true));
$this->Html->addCrumb (__('Checkout', true));
?>

<div class="registrations form">
<h2><?php __('Registration Checkout');?></h2>

<?php
$order_id_format = Configure::read('registration.order_id_format');

if (!empty($registrations)):
	echo $this->Html->para(null, __('These are your current unpaid registrations. To remove one, click the "Unregister" button; note that this will delete all of your preferences and you may lose the spot that is currently tentatively reserved for you.', true));
	echo $this->Html->para(null, __('<span class="highlight-message">Payment completes your registration and confirms your booking/purchase.</span>', true));
	echo $this->Html->para(null, sprintf (__('You may also %s and register for something else before paying.', true),
		$this->Html->link(__('view the event list', true), array('controller' => 'events', 'action' => 'wizard'))
	));
	$test_payments = Configure::read('payment.test_payments');
	if (Configure::read('registration.online_payments') && ($test_payments <= 1 || ($is_admin && Configure::read('payment.test_payments') == 2))) {
		echo $this->Html->para(null, __('If you want to pay online with ' . Configure::read('payment.options') . ', click the "Pay" button below.', true));
	}
	echo $this->element('payments/offline');
?>

<table class="list">
	<tr>
		<th><?php __('Order ID'); ?></th>
		<th><?php __('Event'); ?></th>
		<th><?php __('Cost'); ?></th>
		<th><?php __('Actions'); ?></th>
	</tr>
<?php
	$total = $i = 0;
	foreach ($registrations as $registration):
	list ($cost, $tax1, $tax2) = Registration::paymentAmounts($registration);
		$total += $cost + $tax1 + $tax2;

		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
?>
	<tr<?php echo $class;?>>
		<td><?php printf ($order_id_format, $registration['Registration']['id']); ?></td>
		<td><?php
		echo $this->Html->link (Registration::longDescription($registration), array('controller' => 'events', 'action' => 'view', 'event' => $registration['Event']['id']));
		?></td>
		<td><?php echo $this->Number->currency ($cost + $tax1 + $tax2); ?></td>
		<td class="actions"><?php
		if (in_array($registration['Registration']['payment'], Configure::read('registration_none_paid'))) {
			echo $this->Html->link (__('Unregister', true),
					array('action' => 'unregister', 'registration' => $registration['Registration']['id']),
					array(),
					__('Are you sure you want to unregister from this event? This will delete all of your preferences and you may lose the spot that is currently tentatively reserved for you.', true));
		}
		?></td>
	</tr>
<?php endforeach; ?>
	<tr>
		<th></th>
		<th><?php __('Total'); ?>:</th>
		<th><?php echo $this->Number->currency ($total); ?></th>
		<th class="actions"><?php
		if (Configure::read('registration.online_payments') && ($test_payments <= 1 || ($is_admin && Configure::read('payment.test_payments') == 2))) {
			echo $this->element('payments/forms/' . Configure::read('payment.payment_implementation'));
		}
		?></th>
	</tr>
</table>
<?php endif; ?>

<?php
if (!empty($other)):
	echo $this->Html->para('error-message', 'You have registered for the following events, but cannot pay right now:');
?>
<table class="list">
	<tr>
		<th><?php __('Order ID'); ?></th>
		<th><?php __('Event'); ?></th>
		<th><?php __('Cost'); ?></th>
		<th><?php __('Reason'); ?></th>
		<th><?php __('Actions'); ?></th>
	</tr>
<?php
	$i = 0;
	foreach ($other as $registration):
		list ($cost, $tax1, $tax2) = Registration::paymentAmounts($registration);
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
?>
	<tr<?php echo $class;?>>
		<td><?php printf ($order_id_format, $registration['Registration']['id']); ?></td>
		<td><?php echo $this->Html->link ($registration['Event']['name'], array('controller' => 'events', 'action' => 'view', 'event' => $registration['Event']['id'])); ?></td>
		<td><?php echo $this->Number->currency ($cost + $tax1 + $tax2); ?></td>
		<td><?php __($registration['reason']); ?></td>
		<td class="actions"><?php
			if (!in_array($registration['Registration']['payment'], Configure::read('registration_some_paid'))) {
				echo $this->Html->link (__('Unregister', true),
						array('action' => 'unregister', 'registration' => $registration['Registration']['id']),
						array(),
						__('Are you sure you want to unregister from this event? This will delete all of your preferences and you may lose the spot that is currently tentatively reserved for you.', true));
			}
		?></td>
	</tr>
	<?php endforeach; ?>
</table>
<?php endif; ?>

<?php
echo $this->element('payments/refund');
if (Configure::read('registration.online_payments') && stripos(Configure::read('payment.options'), 'interac')) {
	echo $this->Html->para('small', __('&reg; Trade-mark of Interac Inc. Used under licence. <a href="http://www.interaconline.com/learn/" target="_blank">Learn more</a> about INTERAC Online.', true));
}
?>
</div>
