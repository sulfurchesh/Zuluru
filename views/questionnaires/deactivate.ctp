<?php
if ($success) {
	$content = $this->Js->link(__('Activate', true),
			array('action' => 'activate', 'questionnaire' => $questionnaire, 'id' => $id),
			array('update' => "#temp_update")
	);
	echo $this->Html->scriptBlock ("jQuery('#$id').html('$content')");
} else {
	$alert = printf(__('Failed to deactivate %s \'%s\'.', true), __('questionnaire', true), addslashes($name));
	echo $this->Html->scriptBlock ("alert('$alert')");
}

// Output the event handler code for the links
echo $this->Js->writeBuffer();
?>
