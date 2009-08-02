<?php

abstract class Model_VictoryCondition_Abstract
{
	/**
	 * Compares the two values depending on the 
	 * @param array $participants
	 * @return 0 for draw, 1 for val1, 2 for val2
	 */
	abstract protected function _compare($val1, $val2);
	
	/**
	 * Returns a full list of available tourney types in an array.  The key is the class name and the data is the result of getTypeName()
	 * @return array
	 */
	static public function getTypeList()
	{
		/*
		 * Searches the Type folder for all different tournament types.
		 * This is used for when a user is creating a tournament and want to select what type to use
		 * The array is set up like so:
		 * key: class name for tourney type (get_class() could be useful)
		 * value: result of object getName()
		 */
		$retarray = array();
		if ($dir = scandir(APPLICATION_PATH . '/models/VictoryCondition')) {
			foreach ($dir as $file) {
				if ($file != 'Abstract.php' && strtolower(substr($file, strrpos($file, '.') + 1)) == 'php') {
					$classname = 'Model_VictoryCondition_' . substr($file, 0, strrpos($file, '.'));
					try {
						if (class_exists($classname)) {
							$instance = new $classname;
							if ($instance instanceof Model_VictoryCondition_Abstract) {
								$retarray[$classname] = $instance->getTypeName();
							}
						}
					} catch (Exception $e) {}
				}
			}
		}
		return $retarray;
	}
	
	/**
	 * Gets the name of the type
	 * @return string
	 */
	abstract public function getTypeName();
	
	/**
	 * Checks all scores and returns a sorted list from first to last place
	 * @param Model_ParticipantList $participantlist List of participants along with scores.  Blank score is assumed to be 0
	 * @return Model_ParticipantList
	 */
	public function getStandings(Model_ParticipantList $participantlist)
	{
		$parray = array();
		$retarray = array();
		
		// Fill the array with participants
		foreach ($participantlist as $p) {
			$parray[] = $p;
		}
		
		// Sort into retarray
		while (count($parray) > 0) {
			$best = NULL;
			$bestkey = NULL;
			foreach ($parray as $key => $p) {
				if ($best === NULL) {
					$best = $p;
					$bestkey = $key;
				} elseif ($this->_compare($p->getScore(), $best->getScore()) < 2) {
					// If p is better or equal to best
					$best = $p;
					$bestkey = $key;
				}
			}
			
			// $best is now the best of the remaining, add to retarray
			$retarray[] = array('participant' => $best, 'draw' => 0, 'result' => 0);
			unset($parray[$bestkey]);
		}
		
		// Now add the result and draw columns
		$placeupto = 1;
		foreach ($retarray as $key => $row) {
			foreach ($retarray as $subkey => $subrow) {
				if ($key != $subkey) {
					if ($this->_compare($retarray[$key]['participant']->getScore(), $retarray[$subkey]['participant']->getScore()) == 0) {
						$retarray[$key]['draw']++;
						if ($retarray[$subkey]['result'] > 0) {
							$retarray[$key]['result'] = $retarray[$subkey]['result'];
						}
					}
				}
			}
			if (!$retarray[$key]['result']) {
				$retarray[$key]['result'] = $placeupto;
			}
			$placeupto++;
		}
		
		return $retarray;
	}
}
