<?php

declare(strict_types=1);

namespace charindo;

use charindo\command\SnowballCommand;
use pocketmine\entity\EntitySizeInfo;
use pocketmine\entity\projectile\Snowball;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;

use pocketmine\utils\Config;
use pocketmine\world\Explosion;
use pocketmine\world\Position;

use pocketmine\event\entity\ProjectileHitBlockEvent;

class Main extends PluginBase implements Listener {

    public Config $config;

    public function onEnable(): void {
        $this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML, array(
            "enable-snowball-explosion" => false,
            "force" => 4,
        ));

        $this->getServer()->getCommandMap()->register("SnowballExplosion", new SnowballCommand($this, "sn"));

        $this->getLogger()->info("雪玉投げたと思ったらなんか爆発しちゃった～プラグインを読み込みました");
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onHit(ProjectileHitBlockEvent $event) {
        if ($this->config->get("enable-snowball-explosion")) {
            $entity = $event->getEntity();
            if ($entity instanceof Snowball) {
                $location = $entity->getLocation();
                $size = new EntitySizeInfo(0.98, 0.98);
                $explosion = new Explosion(Position::fromObject($location->add(0, $size->getHeight() / 2, 0), $entity->getWorld()), $this->config->get("force"), $entity);
                $explosion->explodeA();
                $explosion->explodeB();
            }
        }
    }
}