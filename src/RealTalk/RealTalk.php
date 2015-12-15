<?php
namespace RealTalk;

use RealTalk\handler\EventListener;

use RealTalk\command\Shout;
use RealTalk\command\Whisper;
use RealTalk\command\Toggle;

use pocketmine\plugin\PluginBase;

class RealTalk extends PluginBase {
  
  const OMNISCIENT = 1;
  const YELL = 2;
  const TALK = 3;
  const WHISPER = 4;

  /** @var bool */
  protected $enabled = true;
  /** @var int */
  protected $radius = 12;
  /** @var int */
  protected $whisperRadius = 4;
  /** @var int */
  protected $shoutRadius = 26;
  /** @var array */
  protected $action;
  
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
    
    foreach($this->getServer()->getOnlinePlayers() as $p){ // Handle reload
      $this->action[spl_object_hash($p)] = self::TALK;
    }
    
  }
  public function onDisable(){
    $this->getConfig()->save();
  }
  
  private funcion registerCommands(){
    $commandMap = $this->getServer()->getCommandMap();
    $commandMap->register("RealTalk", new Shout($this, "shout", "Yell out your message farther", "/shout <...message>", ['yell', 'shout', 'scream', 's']));
    $commandMap->register("RealTalk", new Whisper($this, "whisper", "Whisper your message for close distance", "/whisper <...message>", ["sizzle", "murmurm", "fizzle"]));
    $commandMap->register("RealTalk", new RealTalk($this, "realtalk", "Main RealTalk's command", "/realtalk", ["realt", "rtalk", "rt"]));
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
      switch($this->getActionType($player)){
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
  
  public function getActionType(IPlayer $player){
    if(isset($action = $this->action[spl_object_hash($player)])){
      return $action;
    } else {
      return self::TALK;
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
