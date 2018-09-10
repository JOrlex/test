<?php

namespace bajan\Envoys;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\utils\Config;
use pocketmine\Player;
use pocketmine\block\Block;
use pocketmine\item\Item;
use pocketmine\utils\TextFormat as TF;
use pocketmine\math\Vector3;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\tile\Tile;
use PiggyCustomEnchants\Blocks\PiggyObsidian;
use PiggyCustomEnchants\Commands\CustomEnchantCommand;
use PiggyCustomEnchants\CustomEnchants\CustomEnchants;
use PiggyCustomEnchants\CustomEnchants\CustomEnchantsIds;
use PiggyCustomEnchants\Entities\PiggyFireball;
use PiggyCustomEnchants\Entities\PiggyLightning;
use PiggyCustomEnchants\Entities\PiggyWitherSkull;
use PiggyCustomEnchants\Entities\PigProjectile;
use PiggyCustomEnchants\Entities\VolleyArrow;
use PiggyCustomEnchants\Tasks\AutoAimTask;
use PiggyCustomEnchants\Tasks\CactusTask;
use PiggyCustomEnchants\Tasks\ChickenTask;
use PiggyCustomEnchants\Tasks\EffectTask;
use PiggyCustomEnchants\Tasks\ForcefieldTask;
use PiggyCustomEnchants\Tasks\JetpackTask;
use PiggyCustomEnchants\Tasks\MeditationTask;
use PiggyCustomEnchants\Tasks\ParachuteTask;
use PiggyCustomEnchants\Tasks\PoisonousGasTask;
use PiggyCustomEnchants\Tasks\ProwlTask;
use PiggyCustomEnchants\Tasks\RadarTask;
use PiggyCustomEnchants\Tasks\SizeTask;
use PiggyCustomEnchants\Tasks\SpiderTask;
use PiggyCustomEnchants\Tasks\VacuumTask;
use pocketmine\block\BlockFactory;
use pocketmine\entity\Entity;
use pocketmine\item\Armor;
use pocketmine\item\Axe;
use pocketmine\item\Durable;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Hoe;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\Pickaxe;
use pocketmine\item\Shears;
use pocketmine\item\Shovel;
use pocketmine\item\Sword;
use pocketmine\level\Position;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\ShortTag;

class Main extends PluginBase implements Listener{

	//minutes
	public $spawntime = 5;

	public function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->getServer()->getScheduler()->scheduleRepeatingTask(new EnvoyTask($this), $this->spawntime*60*20);
		@mkdir($this->getDataFolder());
		$this->saveResource("Config.yml");
		$this->saveResource("Envoys.yml");
		$this->saveResource("Items.yml");
		$this->cfg = new Config($this->getDataFolder()."Config.yml",Config::YAML);
		$this->envoys = new Config($this->getDataFolder()."Envoys.yml",Config::YAML);
		$this->items = new Config($this->getDataFolder()."Items.yml",Config::YAML);
	}

	public function runEnvoyEvent(){
		foreach($this->getServer()->getOnlinePlayers() as $players){
			$players->sendMessage(TF::AQUA."WORLD EVENT");
			$players->sendMessage(TF::GREEN."Envoys are being spawned in the warzone!");
		}
		foreach($this->envoys as $data => $level){
			$data = explode(":",$data);
			$tile = $this->getServer()->getLevelByName($level)->getTile(new Vector3(intval($data[0]),intval($data[1]),intval($data[2])));
			$i = rand(3,5);
			while($i > 0){
				$item = $this->items[array_rand($this->items)];
				$item = explode(":",$item);
				$tile->getInventory()->addItem($reward[$chances]);
				$i--;
			}
		}
	}

	public function setEnvoy(Player $sender, Item $item1, $item2, $item3, $item4, $item5, $item6, $item7, $item8, $item9){
		$this->envoys->set($sender->x.":".$sender->y.":".$sender->z, $sender->getLevel()->getName());
		$this->envoys->save();
		$items{ = $item1 = get::Item(340, 0, 1), $item2 = get::Item(340, 0, 1), $item3 = get::Item(340, 0, 1), $item4 = get::Item(340, 0, 1),
		$item5 = get::Item(340, 0, 1), $item6 = get::Item(340, 0, 1), $item7 = get::Item(0, 0, 0), $item8 = get::Item(0, 0, 0), $item9 = get::Item(0, 0, 0),
    $chances = mt_rand(0, 1)};
    $reward = [$item1, $item2, $item3 $item4, $item5, $item6, $item7, $item8, $item9];
    $player->getInventory()->addItem($reward[$chances]);
		$item = $items[array_rand($items)];
		$values = explode(":", $item);
		$level = $sender->getLevel();
		$level->setBlock($sender->getPosition()->asVector3(), Block::get(54));
		$nbt = new CompoundTag(" ", [
			new ListTag("Items", []),
			new StringTag("id", Tile::CHEST),
			new IntTag("x", $sender->x),
			new IntTag("y", $sender->y),
			new IntTag("z", $sender->z)
		]);
		$chest = Tile::createTile("Chest", $sender->getLevel(), $nbt);
		$level->addTile($chest);
		$inv = $chest->getInventory();
		$inv->addItem(Item::get($values[0], $values[1]));
		$sender->sendMessage(TF::GREEN."Envoy set!");
		return true;
	}

    public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args) : bool {
		switch($cmd){
			case "setenvoy":
				if(!$sender->hasPermission("envoy.set")) {$sender->sendMessage(TF::RED."You do not have the required permission"); return false;}
				$this->setEnvoy($sender);
				return true;
		}
	}


   public function addEnchantment(){
		 $piggyce = $this->getServer()->getPluginManager()->getPlugin("PiggyCustomEnchants");
 if ($piggyce->isEnabled()) {
		 $enchantmentIds = array_map(function ($value) {
		 return CustomEnchants::getEnchantmentByName(strtoupper(str_replace("Aerial", "Backstab", "Blind", "Blessed", "Charge", "Cripple", "Deathbringer", "Disarming",
		 "Disarmor", "Gooey", "Hallucination", "Lifesteal", "Lightning", "Poison", "Vampire", "Wither", $value[0])));
		 });
		 $enchantment = $enchantmentIds[array_rand($enchantmentIds)];
		 $enchantmentLevel = mt_rand(1, 5);
 }
 		 $piggyce = $this->getServer()->getPluginManager()->getPlugin("PiggyCustomEnchants");
 if ($piggyce->isEnabled()) {
	 	 $enchantmentIds = array_map(function ($value) {
		 return CustomEnchants::getEnchantmentByName(strtoupper(str_replace(" ", "", $value[1])));
	 	});
		 $enchantment = $enchantmentIds[array_rand($enchantmentIds)];
     $enchantmentLevel = mt_rand(1, 5);
 }
     $piggyce = $this->getServer()->getPluginManager()->getPlugin("PiggyCustomEnchants");
 if ($piggyce->isEnabled()) {
     $enchantmentIds = array_map(function ($value) {
		 return CustomEnchants::getEnchantmentByName(strtoupper(str_replace(" ", "", $value[2])));
    });
     $enchantment = $enchantmentIds[array_rand($enchantmentIds)];
     $enchantmentLevel = mt_rand(1, 5);
 }
     $piggyce = $this->getServer()->getPluginManager()->getPlugin("PiggyCustomEnchants");
 if ($piggyce->isEnabled()) {
     $enchantmentIds = array_map(function ($value) {
		 return CustomEnchants::getEnchantmentByName(strtoupper(str_replace(" ", "", $value[3])));
    });
     $enchantment = $enchantmentIds[array_rand($enchantmentIds)];
     $enchantmentLevel = mt_rand(1, 5);
 }
     $piggyce = $this->getServer()->getPluginManager()->getPlugin("PiggyCustomEnchants");
 if ($piggyce->isEnabled()) {
     $enchantmentIds = array_map(function ($value) {
		 return CustomEnchants::getEnchantmentByName(strtoupper(str_replace(" ", "", $value[4])));
    });
     $enchantment = $enchantmentIds[array_rand($enchantmentIds)];
     $enchantmentLevel = mt_rand(1, 5);
 }
     $piggyce = $this->getServer()->getPluginManager()->getPlugin("PiggyCustomEnchants");
 if ($piggyce->isEnabled()) {
     $enchantmentIds = array_map(function ($value) {
		 return CustomEnchants::getEnchantmentByName(strtoupper(str_replace(" ", "", $value[5])));
    });
     $enchantment = $enchantmentIds[array_rand($enchantmentIds)];
     $enchantmentLevel = mt_rand(1, 5);
 }
}
}
