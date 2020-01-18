<?php

namespace Modules\DevelDashboard\Traits;

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
     * List of datatable row actions.
     *
     * @var array
     */
    protected $datatableActions = [];

    /**
     * List of form fields to include into the create/edit forms.
     *
     * @var array
     */
    protected $formFields = [];

    /**
     * Set the model class
     *
     * @param string $class
     * @return void
     */
    protected function setModel(string $class): void
    {
        $this->modelClass = $class;
    }

    /**
     * Set datatable fields to be displayed.
     *
     * @param array $fields
     * @param array $actions
     * @return void
     */
    protected function setDatatable(array $fields, array $actions = []): void
    {
        $this->datatableFields = $fields;
        $this->datatableActions = $actions;
    }

    /**
     * Set form fields to be included into the form.
     *
     * @param array $fields
     * @return void
     */
    protected function setForm(array $fields): void
    {
        $this->formFields = $fields;
    }

    /**
     * Return the model class
     *
     * @return string
     */
    protected function model(): string
    {
        return $this->modelClass;
    }

    /**
     * Return the datatable fields list
     *
     * @return array
     */
    protected function datatable(): array
    {
        return $this->datatableFields;
    }
    
    /**
     * Return the datatable fields list
     *
     * @return array
     */
    protected function actions(): array
    {
        return $this->datatableActions;
    }

    /**
     * Return the form fields list
     *
     * @return array
     */
    protected function form(): array
    {
        return $this->formFields;
    }

    /**
     * API. Return a resource collection.
     *
     * @return Response
     */
    public function get(Request $request)
    {
        $data = $this->model()::sort($request->sort)
            ->search($request->search)
            ->paginate(20);

        return response()->json($data);
    }

    /**
     * Show the specified resource.
     *
     * @param mixed $id
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
     * @param mixed $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Delete the specified resource.
     *
     * @param mixed $id
     * @return Response
     */
    public function destroy($id)
    {
        if (($can = $this->canBeDeleted($id)) !== true) {
            return response()->json([
                'message' => $can,
            ], 409);
        }
        
        $model = new $this->modelClass;

        $object = $this->model()::where($model->getRouteKeyName(), $id)->first();

        if (!$object) {
            return response()->json([
                'message' => 'Item with provided id was not found!',
            ], 404);
        }

        $object->delete();

        return response()->json([]);
    }

    /**
     * Determine whether an item can be deleted.
     *
     * @param mixed $id
     * @return mixed
     */
    protected function canBeDeleted($id)
    {
        return true;
    }
}
