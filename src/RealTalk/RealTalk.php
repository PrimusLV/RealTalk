<?php
namespace RealTalk;

use RealTalk\handler\EventListener;

use RealTalk\command\Shout;
use RealTalk\command\Whisper;
use RealTalk\command\Toggle;

use pocketmine\plugin\PluginBase;

class RealTalk extends PluginBase {

  /** @var bool */
  protected $enabled = true;
  /** @var int */
  protected $radius = 12;
  /** @var int */
  protected $whisperRadius = 4;
  /** @var int */
  protected $shoutRadius = 26;
  
  public function __construct(){
    # Set values for class properties
  }
  
  public function onLoad(){
    # Create classes
  }
  
  public function onEnable(){
    @mkdir($this->getDataFolder());
    $this->saveDefaultConfig();
    $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
  }
  public function onDisable(){
    $this->getConfig()->save();
  }
  
  public function getTalkRadius(){
    return $this->radius;
  }
  
  public function getWhisperRadius(){
    return $this->whiperRadius;
  }
  
  public function getShoutRadius(){
    return $this->shoutRadius();
  }
  
  /**
  * Easy function to see does message will recieve target
  *
  * @return bool
  *
  * @param Position $posA
  * @param Position $posB
  * @param int $radius
  */
  public function withinRadius(Position $posA, Position $posB, $radius){
    if($posA->getLevel()->getName() !== $posB->getLevel()->getName()) return false;
    
    if($posA->distance($posB) <= $radius) return true;
    return false;
  }
}
