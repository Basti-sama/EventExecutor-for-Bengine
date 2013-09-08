<?php

class Bengine_Game_Cronjob_EventExecutor extends Recipe_CronjobAbstract
{
	/**
     * Executes all events that are queued, but max 1000 events
     *
     * @return Bengine_Game_Cronjob_EventExecutor
     */
    protected function _execute()
    {
    	$i = 0;
    	$raceCondition = Str::substring(SID, 0, 8).Str::substring(md5(microtime(true)), 0, 8);
		do
		{
			$collection = Application::getModel("game/event")->getCollection();
			$collection->addRaceConditionFilter($raceCondition);
			$collection->executeAll();
			Core::getQuery()->delete("events", "prev_rc = ?", null, null, array($raceCondition));
			$i++;
		} while ($i<50 && $collection->count() > 0);
		
        
        return $this;
    }
}

?>
