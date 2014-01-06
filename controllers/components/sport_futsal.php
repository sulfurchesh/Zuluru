<?php
/**
 * Class for Futsal sport-specific functionality.
 */

class SportFutsalComponent extends SportComponent
{
	var $sport = 'futsal';

	// In futsal, a win is worth 3 points, not 2.
	function winValue() {
		return 3;
	}
	
	function forfeitValue() {
		return 0;
	}
	
	function gaa_season($stat_type, &$stats) {
		$this->_init_rosters($stats);

		$m_id = $this->_stat_type_id('Minutes Played');
		$g_id = $this->_stat_type_id('Goals Against');
		$length = $this->game_length($stats);

		foreach ($this->rosters as $team_id => $roster) {
			foreach ($roster as $person_id => $position) {
				$minutes = $this->_value_sum($m_id, $person_id, $stats);
				if ($minutes) {
					$value = round(($this->_value_sum($g_id, $person_id, $stats) * $length) / $minutes, 2);
				} else {
					$value = 0;
				}

				if (Stat::applicable($stat_type, $position) || $value != 0) {
					$stats['Calculated'][$person_id][$stat_type['id']] = $value;
				}
			}
		}
	}
}

?>
