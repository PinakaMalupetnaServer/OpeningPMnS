<?php

namespace princepines\OpeningPMnS;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;


class main extends PluginBase implements Listener
{

    public function onEnable()
    {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public $tasks = [];
    public $timer = 136;

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
    {
        switch ($command->getName()) {
            case "launch":
                if ($sender instanceof Player) {
                    $this->getScheduler()->scheduleRepeatingTask(new LaunchTask($this, $sender->getName()),20);
                }
                break;
            case "intro":
                if ($sender instanceof Player) {
                    $sender->sendMessage("Introduction Launching");
                    foreach ($this->getServer()->getOnlinePlayers() as $players) {
                        $pk = new PlaySoundPacket;
                        $pk->soundName = "introduction";
                        $pk->x = (int)$players->x;
                        $pk->y = (int)$players->y;
                        $pk->z = (int)$players->z;
                        $pk->volume = 1;
                        $pk->pitch = 1;
                        $players->dataPacket($pk);
                    }
                }
                break;
        }
        return 0;
    }
}