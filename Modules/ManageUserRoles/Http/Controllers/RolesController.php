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
}
