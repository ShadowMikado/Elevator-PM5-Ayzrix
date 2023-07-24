<?php

namespace ShadowMikado\Elevator\Events;

use pocketmine\block\Block;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJumpEvent;
use pocketmine\event\player\PlayerToggleSneakEvent;
use pocketmine\math\Vector3;
use pocketmine\world\World;
use ShadowMikado\Elevator\Main;

class ElevatorEvent implements Listener
{

    public function onJump(PlayerJumpEvent $e)
    {
        $player = $e->getPlayer();
        $world = $player->getWorld();

        $block = Main::$config->get("block");

        if ($world->getBlock($player->getPosition()->subtract(0, 1, 0))->getTypeId() !== $block) return false;

        $x = (int)floor($player->getPosition()->getX());
        $y = (int)floor($player->getPosition()->getY());
        $z = (int)floor($player->getPosition()->getZ());
        $maxHeight = $world->getMaxY();
        $found = false;
        $y++;

        for (; $y <= $maxHeight; $y++) {
            if ($found = ($this->isElevatorBlock($world, $x, $y, $z) !== null)) {
                break;
            }
        }

        if ($found) {
            if ($player->getPosition()->distance(new Vector3($x + 0.5, $y + 1, $z + 0.5)) <= (int)Main::$config->get("distance")) {
                $player->teleport(new Vector3($x + 0.5, $y + 1, $z + 0.5));
            } else {
                $player->sendMessage(Main::$config->get("messages")["distance_too_hight"]);
            }
        } else {
            $player->sendMessage(Main::$config->get("messages")["no_elevator_found"]);
        }
        return true;
    }

    public function isElevatorBlock(World $world, int $x, int $y, int $z): ?Block
    {
        $ElevatorBlock = $world->getBlockAt($x, $y, $z);
        $block = Main::$config->get("block");
        if ($ElevatorBlock->getTypeId() !== $block) {
            return null;
        }
        return $ElevatorBlock;
    }

    public function onSneak(PlayerToggleSneakEvent $e)
    {
        $player = $e->getPlayer();
        $world = $player->getWorld();

        $block = Main::$config->get("block");
        if (!$e->isSneaking()) return false;
        if ($world->getBlock($player->getPosition()->subtract(0, 1, 0))->getTypeId() !== $block) return false;
        $x = (int)floor($player->getPosition()->getX());
        $y = (int)floor($player->getPosition()->getY()) - 2;
        $z = (int)floor($player->getPosition()->getZ());
        $found = false;
        $y--;

        for (; $y >= 0; $y--) {
            if ($found = ($this->isElevatorBlock($world, $x, $y, $z) !== null)) {
                break;
            }
        }

        if ($found) {
            if ($player->getPosition()->distance(new Vector3($x + 0.5, $y + 1, $z + 0.5)) <= (int)Main::$config->get("distance")) {
                $player->teleport(new Vector3($x + 0.5, $y + 1, $z + 0.5));
            } else {
                $player->sendMessage(Main::$config->get("messages")["distance_too_hight"]);
            }
        } else {
            $player->sendMessage(Main::$config->get("messages")["no_elevator_found"]);
        }
        return true;
    }
}
