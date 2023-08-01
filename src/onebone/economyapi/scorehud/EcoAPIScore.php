<?php

namespace onebone\economyapi\scorehud;

use Ifera\ScoreHud\event\PlayerTagUpdateEvent;
use Ifera\ScoreHud\event\TagsResolveEvent;
use Ifera\ScoreHud\scoreboard\ScoreTag;
use onebone\economyapi\EconomyAPI;
use onebone\economyapi\event\money\MoneyChangedEvent;
use pocketmine\event\Listener;
use pocketmine\player\Player;

class EcoAPIScore implements Listener{

    /**
     * @param MoneyChangedEvent $event
     * @return void
     */
    public function onMoneyChange(MoneyChangedEvent $event): void{
		$username = $event->getUsername();
		if(is_null($username)){
			return;
		}
		$player = EconomyAPI::getInstance()->getServer()->getPlayerByPrefix($username);
		if($player instanceof Player && $player->isOnline()){
			(new PlayerTagUpdateEvent($player, new ScoreTag("ecoapiscore.money", (string)$event->getMoney())))->call();
		}
	}

    /**
     * @param TagsResolveEvent $event
     * @return void
     */
    public function onTagResolve(TagsResolveEvent $event): void{
		$tag = $event->getTag();
		$tags = explode('.', $tag->getName(), 2);
		$value = "";
		if($tags[0] !== 'ecoapiscore' || count($tags) < 2){
			return;
		}
		switch($tags[1]){
			case "money":
				$value = EconomyAPI::getInstance()->myMoney($event->getPlayer());
				break;
		}
		$tag->setValue((string) $value);
	}
}