<?php

declare(strict_types=1);

namespace MPouch;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\item\Item;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use onebone\economyapi\EconomyAPI;

class Main extends PluginBase implements Listener{

	public function onEnable() : void{
		$this->getLogger()->info("MoneyPouch by Shelly enabled");
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}

	/**
	 * @param CommandSender $sender
	 * @param Command       $command
	 * @param string        $label
	 * @param array         $args
	 * @return bool
	 */
	public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool{
		if(strtolower($command->getName()) === "bandage"){
			if(count($args) < 2){
				$sender->sendMessage(TextFormat::GRAY . "(" . TextFormat::BLUE . "!" . TextFormat::GRAY . ")" . TextFormat::GRAY . " /bandage <player> <tier>");
				return false;
			}
			if(!$sender->hasPermission("moneypouch.command.give")){
				$sender->sendMessage(TextFormat::GRAY . "(" . TextFormat::BLUE . "!" . TextFormat::GRAY . ")" . TextFormat::GRAY . " You dont have permission");
				return false;
			}
			if($sender->hasPermission("moneypouch.command.give") || $sender->isOp()){
				$player = $sender->getServer()->getPlayer($args[0]);
				switch($args[1]){
					case "tier1":
						$t1 = Item::get(339, 101, 1);
						$t1->setCustomName("§r§l§cBANDAGE §r§7(Right-Click)");
						$t1->setLore([
						        "§r§7Activating this item grants your health",
						        "§r§7to be restored back to full",
						        "§r§l§7§r",
						        "§r§l§cNote: §r§7once activated this cannot be undone!"
						]);
						$player->sendMessage(TextFormat::GRAY . "(" . TextFormat::BLUE . "!" . TextFormat::GRAY . ")" . TextFormat::GRAY . " Bandage given!");
						$player->getInventory()->addItem($t1);
						break;
					case "tier2":
						$t2 = Item::get(339, 102, 1);
						$t2->setCustomName("§r§l§cHEALTH BOOST §r§7(Right-Click)");
						$t2->setLore([
							"§r§7Activating this item grants your health",
							"§r§7to be boosted by 1.5x",
							"§r§7§6§l§r",
							"§r§l§cNote: §r§7once activated this cannot be undone!"
						]);
						$player->sendMessage(TextFormat::GRAY . "(" . TextFormat::BLUE . "!" . TextFormat::GRAY . ")" . TextFormat::GRAY . " Bandage given!");
						$player->getInventory()->addItem($t2);
						break;
				}
			}
		}
		return true;
	}

	/**
	 * @param PlayerInteractEvent $event
	 * @return void
	 */
	public function onInteract(PlayerInteractEvent $event) : void{
		$player = $event->getPlayer();
		if($event->getItem()->getId() === 339){
			switch($event->getItem()->getDamage()){
				case 101:
					$player-setHealth(20);
					$player->sendMessage("§3(§b!§3) §7You have been healed!");
					$player->getInventory()->removeItem(Item::get(379, 101, 1));
					return;
				case 102:
					$player->setHealth(30);
					$player->sendMessage("§3(§b!§3) §7You have been boosted!");
					$player->getInventory()->removeItem(Item::get(379, 102, 1));
					return;
			}
		}
	}

	/**
	 * @param BlockPlaceEvent $event
	 * @return void
	 */
	public function onPlace(BlockPlaceEvent $event) : void{
		if($event->getItem()->getId() === 339){
			if($event->getItem()->getDamage() === 101 && $event->getItem()->getDamage() === 102) $event->setCancelled();
		}
	}
}
