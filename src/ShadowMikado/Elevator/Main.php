<?php

namespace ShadowMikado\Elevator;

use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;
use ShadowMikado\Elevator\Events\ElevatorEvent;

class Main extends PluginBase implements Listener
{
    use SingletonTrait;

    public static Config $config;

    public function onLoad(): void
    {
        self::setInstance($this);
        $this->saveDefaultConfig();
        self::$config = $this->getConfig();
    }

    public function onEnable(): void
    {

        $this->getLogger()->info("Enabled ! with block: " . self::$config->get("block"));



        $this->getServer()->getPluginManager()->registerEvents(new ElevatorEvent, $this);
    }

    public function onDisable(): void
    {
    }
}
