<p><?php __('USA Ultimate developed their current College ranking system to replace the RRI system. It is not clear what weakness in RRI was being addressed with this change.'); ?></p>
<p><?php __('With the USA Ultimate College system, ratings are re-calculated on a daily basis, taking into account the strength of each team\'s schedule. For example, if your first game was a loss to a low-ranked team who later prove themselves to have been initially under-estimated, the penalty for that loss will be reduced as the season progresses.'); ?></p>
<?php if ($is_admin): ?>
<p class="warning-message"><?php __('NOTE: For ratings to be re-calculated, you MUST have a daily cron job set up as described in the README file.'); ?></p>
<p><?php
printf(__('Details are %s.', true),
	$this->Html->link(__('here', true), 'http://www.usaultimate.org/competition/college_division/college_season/college_rankings.aspx')
);
?></p>
<?php endif; ?>
