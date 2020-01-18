<?php

namespace Modules\DevelDashboard\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

trait Crud
{
    /**
     * A model class to perform CRUD opeations on.
     *
     * @var string
     */
    protected $modelClass;

    /**
     * Form Request class.
     *
     * @var string
     */
    protected $requestClass;

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
     * Set the form Request class
     *
     * @param string $class
     * @return void
     */
    protected function setRequest(string $class): void
    {
        $this->requestClass = $class;
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
        $item = $this->storeOrUpdate($request);

        return response()->json($item, 201);
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
        $item = $this->storeOrUpdate($request);

        return response()->json($item, 200);
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

    /**
     * Store or update an item.
     *
     * @param Request $request
     * @param mixed $item
     * @return mixed
     */
    protected function storeOrUpdate($request, $item = null)
    {
        // Validation
        if ($this->requestClass) {
            app($this->requestClass);
        }

        $values = [];
        $model = $this->model();
        $model = new $model;

        foreach ($request->all() as $field => $value)
        {
            $columnType = DB::getSchemaBuilder()
                ->getColumnType($model->getTable(), $field);

            if ($columnType === 'boolean') {
                $values[$field] = isset($value);
            } else {
                $values[$field] = $value;
            }
        }

        if ($item) {
            $item->update($values);
        } else {
            $item = $this->model()::create($values);
        }

        return $item;
    }
}
