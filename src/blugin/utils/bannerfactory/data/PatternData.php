<?php

/*
 *
 *  ____  _             _         _____
 * | __ )| |_   _  __ _(_)_ __   |_   _|__  __ _ _ __ ___
 * |  _ \| | | | |/ _` | | '_ \    | |/ _ \/ _` | '_ ` _ \
 * | |_) | | |_| | (_| | | | | |   | |  __/ (_| | | | | | |
 * |____/|_|\__,_|\__, |_|_| |_|   |_|\___|\__,_|_| |_| |_|
 *                |___/
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the MIT License.
 *
 * @author  Blugin team
 * @link    https://github.com/Blugin
 * @license https://www.gnu.org/licenses/mit MIT License
 *
 *   (\ /)
 *  ( . .) ♥
 *  c(")(")
 */

declare(strict_types=1);

namespace blugin\utils\bannerfactory\data;

use blugin\utils\bannerfactory\BannerConsts;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;

class PatternData implements \JsonSerializable, NbtSerializable, BannerConsts{
    protected string $name;

    protected int $colorLevel;

    public function __construct(string $name, int $color){
        $this->setName($name);
        $this->setColorLevel($color);
    }

    public function getName() : string{
        return $this->name;
    }

    public function setName(string $name) : void{
        $this->name = $name;
    }

    public function getColorLevel() : int{
        return $this->colorLevel;
    }

    public function setColorLevel(int $color) : void{
        $this->colorLevel = $color;
    }

    public function nbtSerialize(array $colors) : CompoundTag{
        return new CompoundTag("", [
            new StringTag(self::TAG_PATTERN_NAME, $this->getName()),
            new IntTag(self::TAG_PATTERN_COLOR, $colors[$this->getColorLevel()] ?? self::COLOR_BLACK),
        ]);
    }

    public function jsonSerialize(){
        return $this->getName() . $this->getColorLevel();
    }

    public static function jsonDeserialize($json) : self{
        if(!is_string($json) || !preg_match("/([a-z]*)([0-9]*)/", $json, $matches))
            throw new \RuntimeException("Invalid banner pattern data : $json");

        [, $name, $colorLevel] = $matches;
        return new self($name, (int) $colorLevel);
    }
}