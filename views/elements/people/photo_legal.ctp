<?php
$short = Configure::read('organization.short_name');
$long = Configure::read('organization.name');
$max = ini_get('upload_max_filesize');
$unit = substr($max,-1);
if ($unit == 'M' || $unit == 'K') {
	$max .= 'b';
}
?>
<p><?php __('By uploading a photo (only one photo per person allowed at the moment), you can further personalize your profile. In addition, others might use photos to determine who they mean to nominate as an all-star, for the purposes of drafting hat teams, recruiting players, etc.'); ?></p>
<p><?php printf(__('Photos must be less than %s. You will have the opportunity to crop the photo after uploading. Crop areas must currently be square. Sorry, no support for uploading from mobile phones, yet.', true), $max); ?></p>
<p><?php printf(__('This is an OPTIONAL feature of the %s (%s) web site. Your photo will be available only to other %s members who are logged in to this site and will not otherwise by publicly visible.', true), $long, $short, $short); ?></p>
<?php if (Configure::read('feature.approve_photos')): ?>
<p><strong><?php printf(__('Photos must be approved by an administrator before they will be visible by anyone, including you. To be approved, a photo must be of you and only you (e.g. no logos or shots of groups or your pet or your car) and must clearly show your face. Photos may not include nudity or depiction of any activity that is illegal or otherwise contrary to the spirit of the sport. Determination of whether a photo is suitable is within the sole discretion of the %s.', true), $short); ?></strong></p>
<?php endif; ?>
<p><strong><?php __('By uploading a photo you confirm that you are the legal copyright holder, or have obtained permission from the copyright holder to use it for this purpose.'); ?></strong></p>
<p><strong><?php printf(__('By uploading a photo you consent to allow the %s to publish this photograph as your profile picture on the %s web site, and hereby release, waive and forever discharge the %s, its employees, volunteers, officers and directors, and contractors, of and from all liability, injury, loss, death, claims, demands, damages, costs, expenses, actions and causes of action, whether in law or in equity, howsoever caused, arising from any actions related to the publishing of this photograph.', true), $short, $short, $short); ?></strong></p>
