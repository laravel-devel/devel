<?php

namespace Modules\ManageUserRoles\Http\Controllers;

use Modules\DevelDashboard\Traits\Crud;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\DevelCore\Http\Controllers\Controller;

class RolesController extends Controller
{
    use Crud;

    public function __construct()
    {
        $this->setMeta('title', 'Dashboard');
        $this->setMeta('title', config('manageuserroles.display_name'));

        // CRUD setup
        $this->setModel('Modules\DevelCore\Entities\Auth\Role');
        $this->setDatatable([
            'key' => [
                'name' => 'Key',
                'sortable' => true,
            ],
            'name' => [
                'name' => 'Name',
                'sortable' => true,
            ],
            'default' => [
                'name' => 'Default',
                'sortable' => true,
                'format' => "value ? 'yes' : ''",
            ],
        ], [
            'delete' => route('dashboard.manageuserroles.destroy', ':key'),
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return view('manageuserroles::dashboard.index', [
            'fields' => $this->datatable(),
            'actions' => $this->actions(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('manageuserroles::dashboard.create');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('manageuserroles::dashboard.edit');
    }

    /**
     * Determine whether an item can be deleted.
     *
     * @param mixed $id
     * @return mixed
     */
    protected function canBeDeleted($id)
    {
        $model = new $this->modelClass;

        $object = ($this->model())::where($model->getRouteKeyName(), $id)->first();

        if (!$object) {
            return 'Item with provided was id not found!';
        }

        if ($object->default) {
            return 'The default role cannot be deleted!';
        }

        if ($object->key === 'admin') {
            return 'The admin role cannot be deleted!';
        }

        return true;
    }
}
