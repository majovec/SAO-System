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
use pocketmine\tile\sign;
use pocketmine\utils\Config;
use pocketmine\event\player\PlayerIntersectEvent;
use pocketmine\command\Command;
use pcoketmine\command\CommandSender;

class Main extends PluginBase explements Listener {
	portected $Listener;
	
	
    public function onEnable() {
	    $this->getServer()->getPluginManager()->registerEvents($this, $this);
	    @mkdir($this->getDataFolder()); 
        @mkdir($this->getDataFolder() . "\\players"); 
		$this->getLogger()->info("SAO by NetherTechnology Loaded Successfully");
		$this->Listener = new EventListener($this);
		$this->getServer()->getScheduler()->scheduleRepeatingTask(new sendTipTask($this), 10);
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
    $id = $player->getName();
	$player->setNameTag("$id");
	}
	
	public function onCommand(CommandSender $sender, Command $command, $label, array $args) {
		switch ($command->getName()) {
			case "Exp";
			if($sender->isOp()) {
				if(!empty $arg[0]){
					if($arg[0] == "setexp"){
						if(!empty $arg[1]) {
							if(!empty $arg[2]) {
								$config = $this->getPlayerConfig($arg[1]);
								if($arg[2] > 0 and $arg[2] <= 100) {
									$config->set("level", $arg[2]);
									$config->save();
									$sender->sendMessage("Set player $arg[1] level set to $arg[2]");
									return true;
								} else {
									$sender->sendMessage("Level must be between 1 - 100");
									return true;
								}
							} else {
								$sender->sendMessage("ERROR");
							}
						} else {
							$sender->sendMessage(""ERROR);
						}
					} 
					elseif($arg[0] == "checklevel") {
						if(!empty $arg[1]){
							$config = $this->getPlayerConfig($arg[1]);
							$level = $config->get("leevl")
							$sender->sendMessage("$arg[1] level is : $level");
						} else {
							$sender->sendMessage(ERROR);
						}
					}
					elseif($arg[0] == "checkexp") {
						if(!empty $arg[1]) {
							$config = $this->getPlayerConfig($arg[1]);
							$exp = $config->get("exp")
							$sender->sendMessage("$arg[1] exp is : #exp");
						}else{
							$sender->sendMessage("ERROR");
						}
					}
					elseif($arg[0] == "help"){
						$sender->sendMessage("===============[SAO EXP SYSTEM HELP]===============");
						$sender->sendMessage("/Exp help - Check help");
						$sender->sendMessage("/Exp checklevel <player> - Check Player level");
						$sender->sendMessage("/Exp checkexp <player> - Check Player Exp");
						$sender->sendMessage("/Exp setlevel <player> <level> - Set Player Levels");
					}
				}else{
					$sender->sendMessage("ERROR");
				}
			}else{
				$sender->sendMessage("You do not have permission");
			}
			case "signup";
			    if($sender instanceof Player) {
					$config = $this->getPlayerConfig($sender->getName());
					$day = $config->get("daily-money");
					$nmoney = $config->get("money");
					$dmoney = $config->get("dmoney");
					$gmoney = $nmoney + $dmoney + 1 - 1;
					if (date("Y-m-d", time()) != $day) {
						$config->set("money", $gmoney);
						$config->save();
						$sender->sendMessage("You have signed up Successfully, \n You have Got $dmoney coins");
						return true;
					} else {
						$sender->sendMessage("You have Already signed up!!!");
					}
				} else {
					$sender->sendMessage("You are not a player!");
				}
		    case "SAO";
			    if(!empty $arg[0]) {
					if($arg[0] == "shop") {
						if($sender instanceof Player) {
							$this->glory($sender, $arg[1], $arg[2]);
						} else {
							$sender->sendMessage(You are not a player);
						}
					}
					elseif($arg[0] == "throw") {
						if($sender instanceof Player) {
							$this->rm($sender, $arg[1]);
						} else {
							$sender->sendMessage("You are now a player");
						}
					}
					elseif($arg[0] == "love") {
						if($sender instanceof Player) {
							if(!empty $arg[1]) {
								if($arg[1] == "Mary") {
									$player = $this->getServer()->getPlayer($arg[2]);
									$sendor = $sender->getName();
									$config = $this->getPlayerConfig($sendor);
									$lover = $config->get("lover");
									$bconfig = $this->getPlayerConfig($arg[2]);
									$blover = $bconfig->get("lover");
									if($player == null) {
										$sender=>sendMessage("This player is offline!");
									} elseif(empty $blover) {
									    $sender->sendMessage("This player is married!!!");										
									} else {
										$sender->sendMessage("You have ask $arg[2] for mary successfully");
										$arg[2]->sendMessage("$sendor is asking for mary you! \n Please type /SAO love accept tp accept it!");
										$bconfig->set("wlover", $sendor);
									}
								}
							    elseif($arg[1] == "accept") {
									$sendor = $sender->get();
									$config = $this->getPlayer($sendor);
									$wlover = $config->get("wlover");
									$bconfig = $this->getPlayerConfig($lover);
									if(!empty $wlover) {
										$sender->sendMessage("You have Married #wlover successfully!");
										$this->getServer->broadMessage("$wlover has alredy married $sendor!!!");
										$config->set("lover", $wlover);
										$bconfig->set("lover", $sendor);
									} else {
										$sender->sendMessage("You dont have anyone asked for mary");
									}
								}
							}
						}
					}
				}
		}
	}
}
>
