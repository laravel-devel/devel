<?php

namespace App\Traits;

trait Crud
{
    /**
     * A model class to perform CRUD opeations on.
     *
     * @var string
     */
    protected $modelClass;

    /**
     * Set the model class
     *
     * @param string $class
     * @return void
     */
    public function setModel(string $class): void
    {
        $this->modelClass = $class;
    }

    /**
     * Return the model class
     *
     * @return string
     */
    public function model(): string
    {
        return $this->modelClass;
    }
}