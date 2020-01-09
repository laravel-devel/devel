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
     * List of model fields to include in the datatable.
     *
     * @var array
     */
    protected $datatableFields = [];

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
     * Set datatable fields to be displayed.
     *
     * @param array $fields
     * @return void
     */
    public function setDatatable(array $fields): void
    {
        $this->datatableFields = $fields;
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

    /**
     * Return the datatable fields list
     *
     * @return array
     */
    public function datatable(): array
    {
        return $this->datatableFields;
    }
}