<?php

namespace NetherTechnology\SAO

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;

use pocketmine\Player;
use pocketmine\Inventory;

use pocketmine\item\Item;
use pocketmine\item\ItemBlock;
use pocketmine\block\Block;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerItemHeldEvent;
use pocketmine\event\player\PlayerIntersectEvent;

use pocketmine\event\entity\EntityDamageByEntityEvent;

use pocketmine\utils\TextFormat;
use pocketmine\utils\Config; 

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
		$pvpcfg = new Config($this->getDataFolder()."pvpworldconfig.yml", Config::YAML, array(
		    "" => 1,
			"UnPVPWorld" => []
			"UnPVPMessage" => "You cant PVP here.",
		));
		return $config;
	}
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
        
       public function onLogin(PlayerJoinEvent $e){
	 $p = $e->getPlayer();
	       $name = $p->getName();
	       $config = new Config($this->getDataFolder() . "\\players\\" . strtolower($name) . ".yml", Config::YAML);
	       $cfg = $config;
	       if ($cfg->get("registered") !== true) {
		       $cfg->set("registered", false);
		       $cfg->set("logged", false);
		       $cfg->save();
		       $p->sendMessage("§eYou are not registered");
	       } else {
		       $cfg->set("logged, false);
		       $cfg->save();
		       $p->sendMessage(§ePlease login);
		       }
		       }
		 
		 public function chat(PlayerChatEvent $e){
          $p = $e->getPlayer();
          $name = $p->getName();
	       $config = new Config($this->getDataFolder() . "\\players\\" . strtolower($name) . ".yml", Config::YAML);
         $cfg = $config;
         if ($cfg->get("registered") !== true) {
         $cfg->set("password", $e->getMessage());
         $cfg->set("logged", true);
         $cfg->set("registered", true);
         $cfg->save();
         $p->sendMessage("§eYou have been registered enjoy you stay!");
         $e->setCancelled();
         } elseif ($cfg->get("logged") == false and $cfg->get("registered") == true) {
                  if($e->getMessage() == $cfg->get("password")); {
                   $cfg->set("logged", true);
                   $cfg->save();
                   $e->setCancelled();
                   $p->sendMessage("§aLogged");
                   
                  }else{
                  $cfg->save();
                  $e->setCancelled();
                  $p->sendMessage("§cBad password");
                  
                  }
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
							$sender->sendMessage("ERROR");
						}
					} 
					elseif($arg[0] == "checklevel") {
						if(!empty $arg[1]){
							$config = $this->getPlayerConfig($arg[1]);
							$level = $config->get("level")
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
					if($arg[0] == "economy") {
						if($arg[1] == "shop") {
							if($sender instanceof Player) {
							    $this->shop($sender, $arg[2], $arg[3]);
						    } else {
							    $sender->sendMessage(You are not a player);
						    }
					    }
						elseif($arg[1] == "pay") {
							if($sender instanceof Player) {
								if(!empty $arg[1]) {
									if(!empty $arg[2]) {
										$config = $this->getPlayerConfig($sender->getName());
										$bconfig = $this->getPlayerConfig($arg[2]);
										if($bconfig == null) {
											$sender->sendMessage("Player never connected");
										}else{
											$config->set("money", $config->get("money") - $arg[3]);
											$bconfig->set("money", $bonfig->get("money" + $arg[3]));
											$config->save();
											$bconfig->save();
											$sender->sendMessage("You have pay @arg[2] to $arg[3]");
										}
									}
								}
							}
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
										$config->set("wlover", $arg[2]);
										$bconfig->set("wlover", $sendor);
									}
								}
							    elseif($arg[1] == "accept") {
									$sendor = $sender->getName();
									$config = $this->getPlayerConfig($sendor);
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
					elseif($atg[0] == "team") {
						if(!empty $arg[1]) {
							if($arg[1] == "team") {
								$sendor = $sender->getName();
								$config = $this->getPlayerConfig($sendor);
								$ateamm = $config->get("teamm");
								$awteam = $config->get("wteam");
								$teamm = $arg[2]
								$tmconfig = $this->getPlayerConfig($teamm);
								$bteamm = $tmconfig->get("teamm");
								$bwteam = $tmconfig->get("wteam");
								if(!empty $ateamm) {
									$sender->sendMessage("You have a teammate already");
								}
								elseif(!empty $bteamm) {
									$sender->sendMessage("$arg[2] have a teammate already");
								}
								elseif(!empty $bwteam) {
									$sender->sendMessage("THere is one person asking for team to $arg[2],plz wait for next time");
								}
								elseif(!empty $awteam) {
									$sender->sendMessage("YOu are asking for team to another people");
								}else{
									$awteam->set($arg[2]);
									$bwteam->set($sendor);
									$sender->sendMessage("You have asked $arg[2] for team");
									$teamm->sendMessage("$sendor is asking you for team, plz type /SAO team accept to accept it");
								}
							}
							elseif($arg[1] == "accept") {
								$sendor = $sender->getName();
								$config = $this->getPlayerConfig($sendor);
								$ateam = $config->get("teamm");
								$awteam = $config->get("wteam");
								$teamm = $arg[2]
								$bconfig = $->Pconfig($team);
								$bteam = $bconfig->get("teamm");
								$bwteam = $bconfig("wteam");
								if(!empty $awteam) {
									if(!empty $bwteam) {
										if($awteam == $arg[2]) {
										    $ateam->set("$arg[2]");
										    $bteam->set("$sender");
										    $sender->sendMessage("You have teamed with $arg2");
										    $teamm->sendMessage("YOu have teamed with $sender");
									    }else{
										    $sender->sendMessage("$arg2 didn't ask you for teaming");
									    }
									}else{
										$sender->sendMessage("$arg2 didn't ask you for teaming");
									}
								}else{
									$sender->sendMessage("No one is asking you for team");
								}
							}
							elseif($arg[1] == "deneny") {
								$sendor = $sender->getName();
								$config = $this->getPlayerConfig($sendor);
								$awteam = $config->get("wteam");
								$teamm = $arg[2]
								$bconfig = $this->getPlayerConfig("$teamm");
								$bwteam = $bconfig->get("wteam");
								if(!empty $awteam) {
									if(!empty $bwteam) {
										if($awteam == $arg[2]) {
											if($bwteam == $sendor) {
												$awteam->set("");
												$bwteam0>set("");
												$sender->sendMessage("YOu have denyed $arg[2]'s teaming asking");
												$teamm->sendMessage("$sender have deny your asking");
											}else{
												$sender->sendMessage("$teamm didnt ask you for teaming")
											}
										}else{
											$sender->sendMessage("$teamm didt ask you for teaming");
										}
									}else{
										$sender->sendMessage("$teamm is adking you for teaming");
									}
								}else{
									$sender->sendMessage("Noone is asking you for teaming");
								}
							}
							elseif($arg[1] == "help") {
								$sender->sendMessage("=======================[SAO Team Help]=========================");
								$sender->sendMessage("/SAO team team <player = Team with player>");
								$sender->sendMessage("/SAO team deneny <player> = Deneny the inventation of player");
								$sender->sendMessage("/SAO team accept <player> = Accept the teaming of player");
							}
						}
					}
					elseif($arg[0] == "clan") {
						if(!empty $arg[1]) {
							if($arg[1] == "create"){
								if(!empty $arg[2]) {
									$sendor = $sender->getName();
									$owner = $sendor;
									$config = $this->getClanConig($arg[2]);
									$pconfig = $this->getPlayerConfig($sendor);
									$config->set("name", $arg[2]);
									$config->set("owner", $owner);
									$money = $pconfig->get("money");
									$cm = 10000;
									$pconfig->set("money", ($momey - $cm));
									$sender->sendMessage("You have successfully created a clan");
								}
							}
							elseif($arg[1] == "quit")
						}
					}
				}
		}
	}
	public function onHeal(PlayerHeldItemEvent $event) {
		$player = $this->getPlayer();
		$item = $player->getInventory()->getItemInHand()->getId();
		$maxh = $player->getMaxHealth
		if($item == 331) {
			$player->setMaxHealth{$maxh};
		}
	}
	public function shop ($player player, $id, $amount) {
		$config = $this->getPlayerConfig($player->getName());
		switch($id) {
			case 1:
			$price = 1000 * $amount;
			$item = Item::get{DIAMOND_SWORD, 0, $amount};
			break;
			case 2;
			$price = 200 * $amount;
			$item = Item::get(IRON_SWORD, 0, $amount);
			break;
			case 3;
			$price = 100 * $amount;
			$item = Item::get(GOLDEN_APPLE, 0, $amount);
			break;
			case 4;
			$price = 15000 * $amount;
			$item = Item::get(REDSTONE, 0, $amount);
			break;
			default;
			$player->sendMessage("========[SAO Trading System]=======");
			$player->sendMessage("[ID]-[Item]=[Cost]");
			$player->sendMessage("[1]-[DIAMOND_SWORD]-[1000]");
			$player->sendMessage("[2]={IRON_SWORD}-[200]");
			$player->sendMessage("[3]-[GOLDEN_APPLE]-[100]");
			$player->sendMessage("[4]-[Refill_pouder]-[15000]");
			$player->sendMessage("Enter /SAO economy shop <ID> <Amount> to buy");
			return;
		}
		$money = $config->get("money");
		if($money < $price) {
			$player->sendMessage("You dont have enough money!!!");
			return;
		}
		$config->set($money, $money - $price);
		$config->save();
		$player->sendMessage("You have bought that item");
		$player->getInventory()->additem($item);
	}
	public function getnextlevelexp($level) {
        if ($level > 0 and $level < 100) {
		    return $level * 100;
		} else {
			return 0;
		}
	}
//API[1]
	public function onGetPlayerConfig($name) {
		$config = new Config($this->getDataFolder() . "\\players\\" . strtolower($name) . ".yml", Config::YAML, array(
		    "level" => 1,
			"exp" => 0,
			"money" => 1000,
			"dmoney" => 100,
			"daily-money" => "",
			"wlover" => "",
			"lover" => "",
			"teamm" => "",
			"wteam" => "",
			"clan" => ""
		));
		return $config;
	}
//API[2]
	public function onGetClanConfig($name) {
		$config = new Config($this->getDataFolder() . "\\clans\\" . strtolower($name) . ".yml", Config::YAML, array(
		    "name" => $name,
			"money" => 0,
			"owner" => "",
			"maxp" => 50,
			"nowp" => 1
		));
	}
//API[3]
	public function AddExp($name, $exp) {
		$config = $this->getPlayerConfig($name);
		$xp = $config->get("exp");
		if ($config->get("level") <= 0 or $config->get("level") >= 100) {
			return;
		}
		if($xp + $exp >= $this->getnextlevelexp($config->get("level"))) {
			$xp = $xp + $exp - $this->getnextlevelexp($config->get("level"));
			$config->set("exp", $xp);
			$config->save();
			$this->levelup($name);
		} else {
			$config->set("exp", $xp + $exp);
			$config->save();
		}
	}
//API[4]
	public function levelup($name) {
		$config = $this->getPlayerConfig($name);
		$level = $config->get("level");
		$level = $level + 1;
		$config->set("level", $level);
		$config->set("money", $config->get("money") + 1000);
		$config->save();
		$player = $this->getServer()->getPlayerExact($name);
		if($player != null) {
			if($player->getMaxHealth() < 160) {
				$player->setMaxHealth($player->getMaxHealth() + 2);
				$player->setHealth($player->getMaxHealth());
			}
		}else{
			$player = $this->getServer()->getOfflinePlayer($name);
			if($player->getMaxHealth() < 160) {
			$player->setMaxHealth($player->getMaxHealth() + 2);	
			$player->setHealth($player->getMaxHealth());
			}
		}
	}
	public function PVP(EntityDamageByEntityEvent $event) {
		$damager = $event->getDamager();
		$bdamage = $event->getEntity();
		$item = $damager->getInventory()->getItemInHand();
		$id = $item->getId();
		$damage = $item->getDamage();
		$hdamage = $event->getDamage();
		if($damager instanceof Player && $bdamager instanceof Entity) {
			if ($bdamager->getHealth() - $hdamage <= 0) {
				$damager->AddExp($damager->getName(), 10)
			}
		}elseif($damager instanceof Player && $bdamager instanceof Player) {
			if(in_array($damager->getLevel()->getName(), $pvpcfg->get("UnPVPWorld")) && !$damager->isOp()) {
				$damager->sendMessage($pvpcfg->get("UnPVPMessage"));
			}
		}else{
			if($id == 351) {
				if($dmaage = 0) {
					$event->setDamage(10);
					$damager->sendMessage("You use skills");
				}
				elseif($damage == 1) {
					$event->setDamage(5);
					$damager->sendMessage("You use skills");
				}
				elseif($damage == 2) {
					$event->setDamage(5);
					$damager->sendMessage("You use skills");
				}
				elseif($damage == 3) {
					$event->setDamage(5);
					$damager->sendMessage("You use skills");
				}
				elseif($damage == 4) {
					$event->setDamage(5);
					$damager->sendMessage("You use skills");
				}
				elseif($damage == 5) {
					$event->setDamage(5);
					$damager->sendMessage("You use skills");
				}
				elseif($damage == 6) {
					$event->setDamage(5);
					$damager->sendMessage("You use skills");
				}
				elseif($damage == 7) {
					$event->setDamage(5);
					$damager->sendMessage("You use skills");
				}
				elseif($damage == 8) {
					$event->setDamage(5);
					$damager->sendMessage("You use skills");
				}
				elseif($damage == 9) {
					$event->setDamage(5);
					$damager->sendMessage("You use skills");
				}
				elseif($damage == 10) {
					$event->setDamage(5);
					$damager->sendMessage("You use skills");
				}
				elseif($damage == 11) {
					$event->setDamage(5);
					$damager->sendMessage("You use skills");
				}
				elseif($damage == 12) {
					$event->setDamage(5);
					$damager->sendMessage("You use skills");
				}
				elseif($damage == 13) {
					$event->setDamage(5);
					$damager->sendMessage("You use skills");
				}
				elseif($damage == 14) {
					$event->setDamage(5);
					$damager->sendMessage("You use skills");
				}
				elseif($damage == 15) {
					$event->setDamage(5);
					$damager->sendMessage("You use skills");
				}
			    }
			}
		}
//API[4]
        public function on ReduceMoney($name $amount) {
		$config = $this->getPlayerConfig($name);
		$money = $config->get("money");
		$config->set("money", [$money - $amount]);
	}
//API[5]
        public function onAddMoney(name $amount) {
		$config = $this->getPlayerConfig($name);
		$money = $config->get("money");
		$config->set("money", [$money + $amount]);
	}
	}
>
