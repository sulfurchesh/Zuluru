<?php
$upload_dir = Configure::read('folders.uploads');
if (file_exists ($upload_dir . DS . $upload['filename'])) {
	echo $this->Html->image(
			Router::url (array('controller' => 'people', 'action' => 'photo', 'person' => $upload['person_id'])),
			array('class' => 'thumbnail', 'title' => $person['full_name'])
	);
}
?>
