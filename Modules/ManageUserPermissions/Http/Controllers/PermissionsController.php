<?php

namespace Modules\ManageUserPermissions\Http\Controllers;

use Modules\DevelDashboard\Traits\Crud;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\DevelCore\Http\Controllers\Controller;

class PermissionsController extends Controller
{
    use Crud;

    public function __construct()
    {
        $this->setMeta('title', 'Dashboard');
        $this->setMeta('title', config('manageuserpermissions.display_name'));

        // CRUD setup
        $this->setModel('Modules\DevelCore\Entities\Auth\Permission');
        $this->setDatatable([
            'key' => [
                'name' => 'Key',
                'sortable' => true,
            ],
            'name' => [
                'name' => 'Name',
                'sortable' => true,
            ],
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return view('manageuserpermissions::dashboard.index', [
            'fields' => $this->datatable(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('manageuserpermissions::dashboard.create');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('manageuserpermissions::dashboard.edit');
    }
}
