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
    $this->registerCommands();
    $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
  }
  public function onDisable(){
    $this->getConfig()->save();
  }
  
  private funcion registerCommands(){
    $commandMap = $this->getServer()->getCommandMap();
    $commandMap->register("RealTalk", new Shout($this, "shout", "Yell out your message farther", "/shout <...message>", ['yell', 'shout', 'scream', 's']));
    $commandMap->register("RealTalk", new Whisper($this, "whisper", "Whisper your message for close distance", "/whisper <...message>", ["sizzle", "murmurm", "fizzle"]));
    $commandMap->register("RealTalk", new Toggle($this, "toggle", "Enable/Disable RealTalk all features", "/realtalk", ["rt", "rtt", "rton", "rtoff"]));
  }
  
  public function getTalkRadius(){
    return $this->radius;
  }
  
  public function getWhisperRadius(){
    return $this->whisperRadius;
  }
  
  public function getShoutRadius(){
    return $this->shoutRadius;
  }
  
  public function getRadiusForPlayer(IPlayer $player){
    if(!isset($this->type[spl_object_hash($player)])){
      return $this->getTalkRadius();
    } else {
      switch($this->getType($player)){
        case self::OMNISCIENT:
          return false; // Will see all messages
          break;
        default:
        case self::TALK:
          return $this->getTalkRadius();
          break;
        case self::WHISPER:
          return $this->getWhisperRadius();
          break;
        case self::YELL:
          return $this->getShoutRadius();
          break;
      }
    }
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
