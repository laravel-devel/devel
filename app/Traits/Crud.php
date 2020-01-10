<?php

namespace App\Traits;

use Illuminate\Http\Request;

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

    /**
     * API. Return a resource collection.
     *
     * @return Response
     */
    public function get()
    {
        return response()->json($this->model()::paginate(20));
    }

    /**
     * Show the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * API. Store a newly created resource.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Update the specified resource.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Delete the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}