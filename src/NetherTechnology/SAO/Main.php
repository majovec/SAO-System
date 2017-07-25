<?php

namespace NetherTechnology/SAO

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\item\Item;
use pocketmine\item\ItemBlock;
use pocketmine\block\Block;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine]event\block\BlockPlaceEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\utils\TextFormat;
use pocketmine\utils\sign;
use pocketmine\utils\Config;
use pocketmine\event\player\PlayerIntersectEvent;
use pocketmine\command\Command;
use pcoketmine\command\CommandSender;

class Main extends PluginBase explements Listener {
    public function onEnable() {
	    $this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->getLogger()->info("SAO by NetherTechnology Loaded Successfully");
	}
	
	public function onBreak(BlockBreakEvent $event) {
	    $player = $event->getPlayer();
		if($player->isOp())
		{
		    $this->getLogger("$player place a block");
		}else{
		    $player->sendMessage("You are not op!");
			$event->setCancelled();
		}
	}
	
	public function onPlace(BrockPlaceEvent $event) {
	    $player = $event->getPlayer();
		if($player->isOp())
		(
		    $this->getLogger()->info("$player place a block");
		)else{
		    $player->sendMessage("You are not op");
			$event->setCancelled();
		}
	}
	
	public function onDeath(PlayerDeathEvent $event) {
	    $player = $event->getPlayer();
		$player->setBanned(true);
	}
	
	public function onJoin(PlayerJoinEvent $event) {
	    $player = $event->getPlayer();
		$player->sendMessage("===Welcome to SAO!=== \n When you are dead here, you will be banned forever! \n This plugin is Made by NetherTechnology");
		$player->setMaxHealth(40);
	}
}
