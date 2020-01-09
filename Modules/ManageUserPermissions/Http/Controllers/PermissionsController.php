<?php

namespace Modules\ManageUserPermissions\Http\Controllers;

use App\Traits\Crud;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

class PermissionsController extends Controller
{
    use Crud;

    public function __construct()
    {
        $this->setMeta('title', 'Dashboard');
        $this->setMeta('title', config('manageuserpermissions.display_name'));

        // CRUD setup
        $this->setModel('App\Models\Auth\Permission');
        $this->setDatatable([
            'key' => 'Key',
            'name' => 'Name',
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
     * Return a listing of the resource.
     *
     * @return Response
     */
    public function get()
    {
        return response()->json($this->model()::paginate(20));
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
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('manageuserpermissions::dashboard.show');
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

    /**
     * Update the specified resource in storage.
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
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
