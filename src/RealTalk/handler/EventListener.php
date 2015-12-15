<?php
namespace RealTalk\handler;

use RealTalk\RealTalk;
use pocketmine\event\Listener;

class EventListener implements Listener {
  
  /** @var RealTalk */
  private $owner;
  
  public function __construct(RealTalk $plugin){
    $this->owner = $plugin;
  }
  
  public function getPlugin(){
    return $this->owner;
  }
  
}
