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
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public static function set(string $key, $value = null): void
    {
        $parts = explode('.', $key);
        $group = $parts[0];

        array_shift($parts);
        $key = implode('.', $parts);

        $data = [
            'group' => $group,
            'key' => $key,
        ];

        $setting = static::where($data)->first();

        if (!$setting) {
            $setting = static::make($data);
        }

        $setting->value = $value;
        $setting->save();
    }
}
