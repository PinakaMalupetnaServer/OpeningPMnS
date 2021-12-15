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

    /**
     * @inheritDoc
     */
    public function onRun(int $currentTick)
    {
        $player = $this->main->getServer()->getPlayerExact($this->playerName);
        foreach($this->main->getServer()->getOnlinePlayers() as $players) {
            $pk = new PlaySoundPacket;
            $pk->soundName = "medley.music";
            $pk->x = (int)$players->x;
            $pk->y = (int)$players->y;
            $pk->z = (int)$players->z;
            $pk->volume = 1;
            $pk->pitch = 1;
            $players->dataPacket($pk);
        }
        $this->timer--;
        if ($this->timer <= 131) {
            $player->sendMessage("Fireworks Starting.");
            $task = new FireworkTask();
            $this->main->tasks[$player->getId()] = $task;
            $this->main->getScheduler()->scheduleDelayedRepeatingTask($task, 40,20);
        } elseif ($this->timer <= 1) {
            $player->sendMessage("Fireworks Stopping.");
            $task = $this->main->tasks[$player->getId()];
            unset($this->main->tasks[$player->getId()]);
            $task->main->getHandler()->cancel();
        }
    }
}