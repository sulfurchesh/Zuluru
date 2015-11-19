<?php
if (isset($cannot)) {
	$alert = sprintf(__('This %s has responses saved, and cannot be removed for historical purposes. You can deactivate it instead, so it will no longer be shown for new registrations.', true), __('question', true));
	echo $this->Html->scriptBlock ("alert('$alert')");
} else {
	if ($success) {
		echo $this->Html->scriptBlock ("jQuery('#$id').remove();");
	} else {
		$alert = sprintf(__('Failed to remove this %s.', true), __('question', true));
		echo $this->Html->scriptBlock ("alert('$alert')");
	}
}

// Output the event handler code for the links
echo $this->Js->writeBuffer();
?>
