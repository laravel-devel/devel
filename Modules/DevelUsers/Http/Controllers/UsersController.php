<?php

namespace Modules\DevelUsers\Http\Controllers;

use Modules\DevelDashboard\Traits\Crud;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Modules\DevelCore\Entities\Auth\Role;
use Modules\DevelCore\Http\Controllers\Controller;

class UsersController extends Controller
{
    use Crud;

    public function __construct()
    {
        $this->setMeta('title', 'Dashboard');
        $this->setMeta('title', config('develusers.display_name'));

        // CRUD setup
        $this->setModel('Modules\DevelCore\Entities\Auth\User');
        $this->setRequest('Modules\DevelUsers\Http\Requests\UserRequest');

        $this->setDatatable([
            'name' => [
                'name' => 'Name',
                'sortable' => true,
            ],
            'email' => [
                'name' => 'Email',
                'sortable' => true,
            ],
        ], [
            'delete' => ['dashboard.develusers.users.destroy', ':id'],
            'create' => ['dashboard.develusers.users.create'],
            'edit' => ['dashboard.develusers.users.edit', ':id'],
        ]);
        
        $this->setForm([
            [
                'type' => 'text',
                'name' => 'name',
                'label' => 'Name',
            ],
            [
                'type' => 'text',
                'name' => 'email',
                'label' => 'Email',
            ],
            [
                'type' => 'password',
                'name' => 'password',
                'label' => 'Password',
            ],
            [
                'type' => 'multiselect',
                'name' => 'roles',
                'label' => 'Roles',
                // TODO: I should be able to CRUD-generate this
                'attrs' => [
                    'idField' => 'key',
                    'textField' => 'name',
                    'multipleChoice' => true,
                ],
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
        return view('develusers::dashboard.users.index', [
            'fields' => $this->datatable(),
            'actions' => $this->actions(),
            'permissions' => $this->permissions(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $this->setMeta('title', 'Add');

        return view('develusers::dashboard.users.create', [
            'form' => $this->form(),
            // TODO: I should be able to CRUD-generate this, not sure about the
            // actual array of values though. But I should be able to get the
            // related model name, so should be no problem. Use full models
            // names so that I wouldn't have to import (USE) anything.
            'collections' => [
                'roles' => Role::all(),
            ],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param mixed $id
     * @return Response
     */
    public function edit($id)
    {
        // TODO: (?) Add "load()" generation to the CRUD controller generator
        $item = $this->model()::findOrFail($id)->load([
            'roles',
        ]);

        $this->setMeta('title', 'Edit');

        return view('develusers::dashboard.users.edit', [
            'item' => $item,
            'form' => $this->form(),
            // TODO: I should be able to CRUD-generate this, not sure about the
            // actual array of values though. But I should be able to get the
            // related model name, so should be no problem. Use full models
            // names so that I wouldn't have to import (USE) anything.
            'collections' => [
                'roles' => Role::all(),
            ],
        ]);
    }

    /**
     * Alter the values before storing or updating an item.
     *
     * @param Request $request
     * @param array $item
     * @return array
     */
    protected function alterValues($request, array $values): array
    {
        if (isset($values['password'])) {
            $values['password'] = Hash::make($values['password']);
        } else {
            unset($values['password']);
        }

        return $values;
    }
}
