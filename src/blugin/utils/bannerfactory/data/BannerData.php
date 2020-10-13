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
use pocketmine\nbt\tag\ListTag;

class BannerData implements \JsonSerializable, NbtSerializable, BannerConsts{
    /** @var PatternData[] */
    protected array $patterns = [];

    /** @param PatternData[] $patterns */
    public function __construct(array $patterns){
        $this->patterns = $patterns;
    }

    /** @return PatternData[] */
    public function getPatterns() : array{
        return $this->patterns;
    }

    /** @param PatternData[] $patterns */
    public function setPatterns(array $patterns) : void{
        $this->patterns = $patterns;
    }

    public function nbtSerialize(array $colors) : CompoundTag{
        $patterns = [];
        foreach($this->patterns as $pattern){
            $patterns[] = $pattern->nbtSerialize($colors);
        }
        return CompoundTag::create()->setTag(self::TAG_PATTERNS, new ListTag($patterns));
    }

    public function jsonSerialize(){
        $patterns = [];
        foreach($this->patterns as $pattern){
            $patterns[] = $pattern->jsonSerialize();
        }
        return $patterns;
    }

    public static function jsonDeserialize($json) : self{
        if(!is_array($json))
            throw new \RuntimeException("Invalid banner patterns data : $json");

        $patterns = [];
        foreach($json as $patternJson){
            $patterns[] = PatternData::jsonDeserialize($patternJson);
        }
        return new self($patterns);
    }
}