<?php

namespace Devel\Models;

class Settings extends Model
{
    public $table = 'devel_settings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'group',
        'key',
        'value',
        'name',
    ];

    /**
     * Map to convert setting value type to Devel Dashboard form field type
     * 
     * @var array
     */
    const TYPE_TO_FIELD_TYPE = [
        'string' => 'text',
        'integer' => 'number',
        'float' => 'text',
        'text' => 'textarea',
        'boolean' => 'switch',
    ];

    /**
     * Map to cast setting value to an appropriate type
     * 
     * @var array
     */
    const TYPE_CASTS = [
        'integer' => 'int',
        'float' => 'float',
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

        // Update the static collection
        static::$settings[$fullKey] = $setting;
    }

    /**
     * Get/read a setting value
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function read(string $key, $default = null)
    {
        if (!static::$settings) {
            static::fetchAllSettings();
        }

        if (!isset(static::$settings[$key]) || is_null(static::$settings[$key]->value)) {
            return $default;
        }

        // Convert the string value from the DB to a appropriate type
        $value = static::$settings[$key]->value;
        $type = static::$settings[$key]->type;

        // Boolean is a special case
        if ($type === 'boolean') {
            return strtolower($value) === 'true';
        }

        $cast = static::TYPE_CASTS[$type] ?? 'string';

        settype($value, $cast);

        return $value;
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

    /**
     * Re-fetched the static settings collection
     *
     * @return void
     */
    public static function refetch(): void
    {
        static::fetchAllSettings();
    }

    public function getFieldTypeAttribute()
    {
        return static::TYPE_TO_FIELD_TYPE[$this->type] ?? 'text';
    }
}
