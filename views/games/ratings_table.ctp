<?php
$this->Html->addCrumb (__('Games', true));
$this->Html->addCrumb (__('Game', true) . ' ' . $game['Game']['id']);
$this->Html->addCrumb (__('Ratings Table', true));
?>

<div class="games view">
<h2><?php  __('View Ratings Table'); ?></h2>
<p><?php __('The number of rating points transferred depends on several factors:'); ?>
<ul>
<li><?php __('the total score'); ?></li>
<li><?php __('the difference in score'); ?></li>
<li><?php __('and the current rating of both teams'); ?></li>
</ul></p>

<p><?php __('How to read the table below:'); ?>
<ul>
<li><?php __('Find the \'home\' team\'s score along the left.'); ?></li>
<li><?php __('Find the \'away\' team\'s score along the top.'); ?></li>
<li><?php __('The points shown in the table where these two scores intersect are the number of rating points that will be transfered from the losing team to the winning team.'); ?></li>
</ul></p>

<p><?php __('A tie does not necessarily mean 0 rating points will be transfered. Unless the two team\'s rating scores are very close, one team is expected to win. If that team doesn\'t win, they will lose rating points. The opposite is also true: if a team is expected to lose, but they tie, they will gain some rating points.'); ?></p>

<p><?php __('Ties are shown from the home team\'s perspective.  So, a negative value indicates that in the event of a tie, the home team will lose rating points (and the away team will gain them).'); ?></p>
<?php
if (!isset ($rating_home)) {
	$rating_home = $game['HomeTeam']['rating'];
	$rating_away = $game['AwayTeam']['rating'];
	$type = __('current', true);
} else {
	$type = __('"what if"', true);
}
$expected_home = $ratings_obj->calculateExpectedWin($rating_home, $rating_away);
$expected_away = $ratings_obj->calculateExpectedWin($rating_away, $rating_home);
?>

<p><?php __('Home'); ?>: <strong><?php echo $game['HomeTeam']['name']; ?></strong>, <?php echo $type; ?> <?php __('rating of'); ?> <strong><?php echo $rating_home; ?></strong>, <?php printf ('(%0.1f%% %s)', $expected_home * 100, __('chance to win', true)); ?>
<br><?php __('Away'); ?>: <strong><?php echo $game['AwayTeam']['name']; ?></strong>, <?php echo $type; ?> <?php __('rating of'); ?> <strong><?php echo $rating_away; ?></strong>, <?php printf ('(%0.1f%% %s)', $expected_away * 100, __('chance to win', true)); ?>

<?php
$header = array('&nbsp;');
$rows = array();
for ($h = 0; $h <= $max_score; $h++) {
	$header[] = $h;
	$row = array($h);
	for ($a = 0; $a <= $max_score; $a++) {
		if ($h > $a) {
			// home win
			$change = $ratings_obj->calculateRatingsChange($h, $a, $expected_home);
			$row[] = array($change, array('title' => sprintf(__('%s wins %d to %d, takes %d rating points from %s', true), $game['HomeTeam']['name'], $h, $a, $change, $game['AwayTeam']['name']), 'class' => 'highlight-message'));
		} else if ($h == $a) {
			// treat as a home win
			$change = $ratings_obj->calculateRatingsChange($h, $a, $expected_home);
			$row[] = array($change, array('title' => sprintf(__('Tie %d to %d, %s takes %d rating points from %s', true), $h, $a, $game['HomeTeam']['name'], $change, $game['AwayTeam']['name']), 'class' => 'highlight-message'));
		} else {
			$change = $ratings_obj->calculateRatingsChange($h, $a, $expected_away);
			$row[] = array($change, array('title' => sprintf(__('%s wins %d to %d, takes %d rating points from %s', true), $game['AwayTeam']['name'], $a, $h, $change, $game['HomeTeam']['name'])));
		}
	}
	$rows[] = $row ;
}
?>

<table class="list">
	<thead>
<?php echo $this->Html->tableHeaders ($header); ?>
	</thead>
	<tbody>
<?php echo $this->Html->tableCells ($rows); ?>
	</tbody>
</table>

<p><?php __('What if the teams had different ratings? Check it here:'); ?></p>
<?php
echo $this->Form->create('Game', array('url' => Router::normalize($this->here)));
echo $this->Form->input('rating_home', array(
		'label' => $game['HomeTeam']['name'],
		'size' => 5,
));
echo $this->Form->input('rating_away', array(
		'label' => $game['AwayTeam']['name'],
		'size' => 5,
));
echo $this->Form->end(__('What if?', true));
?>
