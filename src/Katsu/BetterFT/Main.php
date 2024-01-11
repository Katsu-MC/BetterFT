<?php

declare(strict_types=1);

namespace Katsu\BetterFT;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

use Katsu\BetterFT\API\FloatingTextAPI;
use Katsu\BetterFT\Command\FloatingTextCommand;

class Main extends PluginBase implements Listener {

    public function onEnable(): void {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);

        $ftFolderPath = $this->getDataFolder() . 'FT';
        if (!is_dir($ftFolderPath)) {
            @mkdir($ftFolderPath);
        }

        $config = new Config($ftFolderPath . DIRECTORY_SEPARATOR . 'floating_text.json', Config::JSON);
        FloatingTextAPI::loadFromFile($config->getPath(), $ftFolderPath);

        $this->getServer()->getCommandMap()->register('ft', new FloatingTextCommand($this, $ftFolderPath));
    }

    public function onDisable(): void {
        $ftFolderPath = $this->getDataFolder() . 'FT';
        $config = new Config($ftFolderPath . DIRECTORY_SEPARATOR . 'floating_text.json', Config::JSON);
        FloatingTextAPI::saveToFile($ftFolderPath);
    }

    public function onPlayerJoin(PlayerJoinEvent $event): void {
        $player = $event->getPlayer();
        $ftFolderPath = $this->getDataFolder() . 'FT';
        FloatingTextAPI::loadFromFile($ftFolderPath . DIRECTORY_SEPARATOR . 'floating_text.json', $ftFolderPath);
    }

    public function onPlayerQuit(PlayerQuitEvent $event): void {
        $player = $event->getPlayer();
        $ftFolderPath = $this->getDataFolder() . 'FT';
        FloatingTextAPI::saveToFile($ftFolderPath);
    }
}