<?php

namespace Modules\DevelCore\Entities;

class Settings extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'group',
        'key',
        'value',
    ];

    /**
     * An array of all the existing setings
     *
     * @var array
     */
    protected static $settings = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id',
    ];

    /**
     * Set a setting value
     *
     * @param string $fullKey
     * @param mixed $value
     * @return void
     */
    public static function set(string $fullKey, $value = null): void
    {
        $parts = explode('-', $fullKey);
        $group = $parts[0];

        array_shift($parts);
        $key = implode('-', $parts);

        $data = [
            'group' => $group,
            'key' => $key,
        ];

        $setting = static::where($data)->first();

        if (!$setting) {
            $data['name'] = static::keyToName($fullKey);

            $setting = static::make($data);
        }

        $setting->value = $value;
        $setting->save();
    }

    /**
     * Get/read a setting value
     *
     * @param string $key
     * @param mixed $default
     * @return string
     */
    public static function read(string $key, $default = null): string
    {
        if (!static::$settings) {
            static::fetchAllSettings();
        }

        if (!isset(static::$settings[$key])) {
            return $default;
        }
        
        return static::$settings[$key]->value ?: $default;
    }

    /**
     * Get a setting object
     *
     * @param string $key
     * @return mixed
     */
    public static function getObject(string $key)
    {
        if (!static::$settings) {
            static::fetchAllSettings();
        }
        
        return static::$settings[$key] ?? null;
    }

    /**
     * Fetch all the settings, create an array with keys.
     *
     * @return void
     */
    protected static function fetchAllSettings(): void
    {
        $settings = static::all();

        static::$settings = $settings->mapWithKeys(function ($item) {
            return ["{$item->group}-{$item->key}" => $item];
        });
    }

    /**
     * Create a setting's name from its key
     *
     * @param string $key
     * @return string
     */
    public static function keyToName(string $key): string
    {
        $key = str_replace('-', ' ', $key);

        return ucwords(str_replace('_', ' ', $key));
    }
}
