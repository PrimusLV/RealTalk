<?php
namespace RealTalk\handler;

use RealTalk\RealTalk;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;

class EventListener implements Listener {
  
  /** @var RealTalk */
  private $owner;
  
  public function __construct(RealTalk $plugin){
    $this->owner = $plugin;
  }
  
  public function getPlugin(){
    return $this->owner;
  }
  
  public function onChat(PlayerChatEvent $event){
    if($this->getPlugin()->enabled === false) return;
    $player = $event->getPlayer();
    $recipients = [];
    
    $radius = $this->getPlugin()->getRadiusForPlayer($player);
    
    if($radius === false) return;
    
    foreach($player->getLevel()->getPlayers() as $target){
      if($this->getPlugin()->isWithinRadius($player, $target, $radius)) $recipients[] = $target;
    }
    
    $event->setRecipients($recipients);
  }
  
}
