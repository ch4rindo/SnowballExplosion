<?php

declare(strict_types=1);

namespace charindo\command;

use pocketmine\permission\DefaultPermissions;
use pocketmine\command\CommandSender;
use pocketmine\command\defaults\VanillaCommand;

use charindo\Main;

class SnowballCommand extends VanillaCommand{

    private Main $owner;

    public function __construct(Main $owner, string $command = "sn"){
        $description = "スノーボールの設定を行います";
        parent::__construct($command, $description, $description, [$command]);

        $this->owner = $owner;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args){
        if($sender->hasPermission(DefaultPermissions::ROOT_OPERATOR)){
            if(!isset($args[0])){
                $sender->sendMessage("§l§eサブコマンドを入力してください");
                return true;
            }elseif($args[0] === "on"){
                $this->owner->config->set("enable-snowball-explosion", true);
                $this->owner->config->save();
                $sender->sendMessage("§l§a設定が完了しました");
            }elseif($args[0] === "off"){
                $this->owner->config->set("enable-snowball-explosion", false);
                $this->owner->config->save();
                $sender->sendMessage("§l§a設定が完了しました");
            }elseif($args[0] === "force"){
                if(!isset($args[1])){
                    $sender->sendMessage("§l§e爆発の大きさを指定してください");
                }else{
                    $this->owner->config->set("force", (int) $args[1]);
                    $this->owner->config->save();
                    $sender->sendMessage("§l§a爆発の大きさを設定しました");
                }
            }else $sender->sendMessage("§l§aそのようなサブコマンドは存在しません");
        }else{
            $sender->sendMessage("§cこのコマンドを実行する権限がありません");
        }
    }
}