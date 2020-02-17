<?php

namespace Modules\DevelUsers\Http\Controllers;

use Modules\DevelDashboard\Traits\Crud;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
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
            ],
            [
                'type' => 'multiselect',
                'name' => 'permissions',
                'label' => 'Permissions',
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
        $item = $this->model()::findOrFail($id);

        $this->setMeta('title', 'Edit');

        return view('develusers::dashboard.users.edit', [
            'item' => $item,
            'form' => $this->form(),
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
