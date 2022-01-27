<?php

namespace tkm\kuhlsize;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\Config;

class Main extends PluginBase{
    public function onEnable(): void
    {
        $this->saveResource("config.yml");
        $cfg = new Config($this->getDataFolder() . "config.yml", 2);
    }
    public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args): bool
    {
        switch ($cmd->getName()) {
            case "size":
                $cfg = new Config($this->getDataFolder() . "config.yml", 2);
                if (!$sender instanceof Player) {
                    $sender->sendMessage($cfg->get("noplayer"));
                    return false;
                }
                if(!$sender->hasPermission("size.use")){
                    $sender->sendMessage($cfg->get("noperms"));
                    return false;
                }
                if(!isset($args[0])){
                    $sender->sendMessage($cfg->get("sizeusage"));
                    return false;
                }
                if(isset($args[0])){
                    $size = $args[0];
                    if(!is_numeric($size)){
                        $sender->sendMessage($cfg->get("sizenonumber"));
                        return false;
                    }
                    if($size > $cfg->get("maxsize") || $size <= $cfg->get("minsize")){
                        $sender->sendMessage($cfg->get("sizeistobig"));
                        return false;
                    }
                    $sender->setScale($size);
                    $msg = str_replace("{size}", $size, $cfg->get("sizesucces"));
                    $sender->sendMessage($msg);
                }
                break;
        }
        return true;
    }
}