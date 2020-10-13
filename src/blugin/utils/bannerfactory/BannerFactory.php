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

namespace blugin\utils\bannerfactory;

use blugin\utils\bannerfactory\data\BannerData;
use blugin\utils\bannerfactory\data\PatternData;
use pocketmine\item\Banner;

class BannerFactory implements BannerConsts, DefaultPatternIds{
    /** @var PatternData[] banner name => banner data */
    protected static ?array $bannerDataMap = null;

    /** @var Banner[] hash => cached banner item */
    protected static array $cache = [];

    /**
     * @param string $bannerName
     * @param int[]  $colors color level => color number
     *
     * @return Banner
     *
     * @throws \Exception
     */
    public static function make(string $bannerName, array $colors) : Banner{
        if(!isset($colors[self::LEVEL_BASE]))
            throw new \InvalidArgumentException("colors paramter must includes COLOR_BASE");

        $hash = $bannerName . ":" . implode(":", $colors);
        if(isset(self::$cache[$hash]))
            return clone self::$cache[$hash];

        $bannerData = self::getBannerData($bannerName);
        if($bannerData  === null)
            throw new \InvalidArgumentException("$bannerName is invalid banner name");

        self::$cache[$hash] = new Banner($colors[self::LEVEL_BASE]);
        self::$cache[$hash]->setNamedTag($bannerData->nbtSerialize($colors));
        return clone self::$cache[$hash];
    }

    public static function registerBannerData(string $patternName, BannerData $bannerData) : void{
        self::registerDefaults();

        //Remove caches when overwrite pattern
        if(isset(self::$bannerDataMap[$patternName])){
            foreach(array_keys(self::$cache) as $hash){
                if(strpos($hash, "$patternName:") === 0){
                    unset(self::$cache[$hash]);
                }
            }
        }
        self::$bannerDataMap[$patternName] = $bannerData;
    }

    /** @return BannerData|null */
    public static function getBannerData(string $patternName) : ?BannerData{
        self::registerDefaults();
        return self::$bannerDataMap[$patternName] ?? null;
    }

    public static function registerDefaults() : void{
        if(self::$bannerDataMap === null){
            self::$bannerDataMap = [];
            foreach(self::DEFAULTS_DATA_MAP as $name => $json){
                self::$bannerDataMap[$name] = BannerData::jsonDeserialize($json);
            }
        }
    }
}