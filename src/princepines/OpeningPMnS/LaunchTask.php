<?php


namespace princepines\OpeningPMnS;

use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use princepines\OpeningPMnS\main;
use princepines\OpeningPMnS\FireworkTask;
use pocketmine\scheduler\Task;

class LaunchTask extends Task
{

    public function __construct(main $main, $playerName)
    {
        $this->main = $main;
        $this->playerName = $playerName;
    }

    public $timer = 136;
    public $tasks = [];

    /**
     * @inheritDoc
     */
    public function onRun(int $currentTick)
    {
        $player = $this->main->getServer()->getPlayerExact($this->playerName);
        $this->timer--;
        if ($this->timer = 131) {
            $player->sendMessage("Fireworks Starting.");
            $task = new FireworkTask();
            $this->tasks[$player->getId()] = $task;
            $this->main->getScheduler()->scheduleDelayedRepeatingTask($task, 40,20);
        } elseif ($this->timer <= 1) {
            $player->sendMessage("Fireworks Stopping.");
            $task = $this->tasks[$player->getId()];
            unset($this->tasks[$player->getId()]);
            $task->main->getHandler()->cancel();
        }
    }
}