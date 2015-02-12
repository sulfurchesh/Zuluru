<?php
$this->Html->addCrumb (__('Divisions', true));
$this->Html->addCrumb ($division['Division']['league_name']);
$this->Html->addCrumb (__('Stats', true));
?>

<div class="divisions stats">
<h2><?php echo $division['Division']['league_name'];?></h2>
</div>
<div class="actions">
	<?php echo $this->element('divisions/actions', array('division' => $division['Division'], 'league' => $division['League'], 'format' => 'list')); ?>
</div>

<div class="related">
<?php
$na = __('N/A', true);

$has_numbers = false;
$numbers = array_unique(Set::extract('/Person/TeamsPerson/number', $division));
if (Configure::read('feature.shirt_numbers') && count($numbers) > 1 && $numbers[0] !== null) {
	$has_numbers = true;
}

$headers = array(
	$this->Html->tag('th', __('Name', true)),
	$this->Html->tag('th', __('Gender', true)), // XXX: don't show in non-coed
	$this->Html->tag('th', __('Team', true)),
);
if ($has_numbers) {
	array_unshift($headers, $this->Html->tag('th', '#'));
}

// Sort the stats into groups for display
$tables = array();
foreach ($division['League']['StatType'] as $stat_type) {
	if (!array_key_exists($stat_type['positions'], $tables)) {
		$tables[$stat_type['positions']] = array(
			'headers' => $headers,
			'rows' => array(),
		);
	}

	$tables[$stat_type['positions']]['headers'][] = $this->Html->tag('th',
		$this->Html->tag('span', __($stat_type['abbr'], true), array('title' => $stat_type['name'])),
		array('class' => $stat_type['class'])
	);
	$total = array();

	foreach ($division['Person'] as $person) {
		if (!array_key_exists($person['Person']['id'], $tables[$stat_type['positions']]['rows'])) {
			$tables[$stat_type['positions']]['rows'][$person['Person']['id']] = array(
				$this->element('people/block', compact('person')),
				__($person['Person']['gender'], true),  // XXX: don't show in non-coed
				$this->element('teams/block', array('team' => $person)),
			);
			if ($has_numbers) {
				array_unshift($tables[$stat_type['positions']]['rows'][$person['Person']['id']], $person['TeamsPerson']['number']);
			}
		}
		if (array_key_exists('Calculated', $division) &&
			array_key_exists($person['Person']['id'], $division['Calculated']) &&
			array_key_exists($stat_type['id'], $division['Calculated'][$person['Person']['id']]))
		{
			$value = $division['Calculated'][$person['Person']['id']][$stat_type['id']];
			if ($stat_type['type'] == 'season_total') {
				$total[] = $division['Calculated'][$person['Person']['id']][$stat_type['id']];
			}
		} else {
			if ($stat_type['type'] == 'season_calc') {
				$value = $na;
			} else {
				$value = 0;
			}
		}
		if (!empty($stat_type['formatter_function'])) {
			$value = $sport_obj->{$stat_type['formatter_function']}($value);
		}
		$tables[$stat_type['positions']]['rows'][$person['Person']['id']][] = array($value, array('class' => $stat_type['class']));
	}

	if ($stat_type['type'] == 'season_total') {
		if (empty($stat_type['sum_function'])) {
			$total = array_sum($total);
		} else {
			$total = $sport_obj->{$stat_type['sum_function']}($total);
		}
		if (!empty($stat_type['formatter_function'])) {
			$total = $sport_obj->{$stat_type['formatter_function']}($total);
		}
	} else {
		$total = '';
	}
}

foreach ($tables as $positions => $table):
	// Maybe prune out rows that are all zeroes; don't do it for the main stats block for all positions
	if (!empty($positions)) {
		foreach ($table['rows'] as $key => $row) {
			$remove = true;

			// Skip name and gender columns
			array_shift($row);
			array_shift($row);

			while (!empty($row)) {
				$value = array_shift($row);
				if ($value[0] != 0 && $value[0] != $na) {
					$remove = false;
					break;
				}
			}
			if ($remove) {
				unset($table['rows'][$key]);
			}
		}
	}

	if (empty($table['rows'])) {
		continue;
	}
?>
	<table class="list tablesorter">
		<thead>
		<tr>
			<?php echo implode('', $table['headers']); ?>
		</tr>
		</thead>
		<tbody>
			<?php echo $this->Html->tableCells(array_values($table['rows'])); ?>
		</tbody>
	</table>
<?php endforeach; ?>

</div>

<?php
// Make the table sortable
$this->ZuluruHtml->script (array('jquery.tablesorter.min.js'), array('inline' => false));
$this->ZuluruHtml->css('jquery.tablesorter.css', null, array('inline' => false));
$this->Js->buffer ("
	jQuery('.tablesorter').tablesorter({sortInitialOrder: 'desc'});
");
?>
