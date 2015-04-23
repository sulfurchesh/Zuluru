<tr>
	<th rowspan="2"><?php __('Rank'); ?></th>
	<th rowspan="2"><?php __('Team'); ?></th>
	<?php if ($division['current_round'] != 1): ?>
	<th colspan="7"><?php __('Current Round'); ?></th>
	<?php endif; ?>
	<th colspan="8"><?php __('Season To Date'); ?></th>
	<th rowspan="2"><?php __('Streak'); ?></th>
	<?php if (League::hasSpirit($league)): ?>
	<th rowspan="2"><?php __('Spirit'); ?></th>
	<?php endif; ?>
	<?php if (League::hasCarbonFlip($league)): ?>
	<th colspan="4"><?php __('Carbon Flip'); ?></th>
	<?php endif; ?>
</tr>
<tr>
	<?php if ($division['current_round'] != 1): ?>
	<th title="<?php __('Wins'); ?>"><?php __('W'); ?></th>
	<th title="<?php __('Draws'); ?>"><?php __('D'); ?></th>
	<th title="<?php __('Losses'); ?>"><?php __('L'); ?></th>
	<th title="<?php __('Goals For'); ?>"><?php __('GF'); ?></th>
	<th title="<?php __('Goals Against'); ?>"><?php __('GA'); ?></th>
	<th title="<?php __('Goal Difference'); ?>"><?php __('GD'); ?></th>
	<th title="<?php __('Points'); ?>"><?php __('P'); ?></th>
	<?php endif; ?>
	<th title="<?php __('Played'); ?>"><?php __('Pld'); ?></th>
	<th title="<?php __('Wins'); ?>"><?php __('W'); ?></th>
	<th title="<?php __('Draws'); ?>"><?php __('D'); ?></th>
	<th title="<?php __('Losses'); ?>"><?php __('L'); ?></th>
	<th title="<?php __('Goals For'); ?>"><?php __('GF'); ?></th>
	<th title="<?php __('Goals Against'); ?>"><?php __('GA'); ?></th>
	<th title="<?php __('Goal Difference'); ?>"><?php __('GD'); ?></th>
	<th title="<?php __('Points'); ?>"><?php __('P'); ?></th>
	<?php if (League::hasCarbonFlip($league)): ?>
	<th title="<?php __('Wins'); ?>"><?php __('W'); ?></th>
	<th title="<?php __('Losses'); ?>"><?php __('L'); ?></th>
	<th title="<?php __('Ties'); ?>"><?php __('T'); ?></th>
	<th title="<?php __('Average'); ?>"><?php __('A'); ?></th>
	<?php endif; ?>
</tr>
