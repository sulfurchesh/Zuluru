<?php
if ($success) {
	$content = $this->Js->link(__('Deactivate', true),
			array('action' => 'deactivate', 'badge' => $badge, 'id' => $id),
			array('update' => "#temp_update")
	);
	echo $this->Html->scriptBlock ("jQuery('#$id').html('$content')");
} else {
	$alert = printf(__('Failed to activate %s \'%s\'.', true), __('badge', true), addslashes($name));
	echo $this->Html->scriptBlock ("alert('$alert')");
}

// Output the event handler code for the links
echo $this->Js->writeBuffer();
?>
