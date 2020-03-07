<?php

namespace Modules\DevelUsers\Http\Controllers;

use Modules\DevelDashboard\Traits\Crud;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Modules\DevelCore\Entities\Auth\Permission;
use Modules\DevelCore\Entities\Auth\Role;
use Modules\DevelCore\Entities\Auth\User;
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
            'Main' => [
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
                    'attrs' => [
                        'idField' => 'key',
                        'textField' => 'name',
                        'multipleChoice' => true,
                    ],
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
            'collections' => [
                'roles' => Role::all(),
            ],
            'permissions' => $this->getPermissions(),
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
        $item = $this->model()::findOrFail($id)->load([
            'roles',
        ]);

        $form = $this->form();
        
        if ($item->roles->contains('root')) {
            $form['Main'][3]['disabled'] = true;
        }

        $this->setMeta('title', 'Edit');

        return view('develusers::dashboard.users.edit', [
            'item' => $item,
            'form' => $form,
            'collections' => [
                'roles' => Role::all(),
            ],
            'permissions' => $this->getPermissions($item),
        ]);
    }

    /**
     * Alter the values before storing or updating an item.
     *
     * @param Request $request
     * @param array $values
     * @param mixed $item
     * @return array
     */
    protected function alterValues($request, array $values, $item = null): array
    {
        // Updating the password
        if (isset($values['password'])) {
            $values['password'] = Hash::make($values['password']);
        } else {
            unset($values['password']);
        }

        // The root user's roles and permissions cannot be altered
        if ($item && $item->roles->contains('root')) {
            $request->request->remove('roles');
            $request->request->remove('permissions');
        }

        // The root role cannot be attached to anyone...
        if ($request->has('roles') && array_search('root', $request->get('roles')) !== false) {
            // ...unless the root user is being updated
            if (!$item || !$item->roles->contains('root')) {
                $roles = $request->get('roles');
                array_splice($roles, array_search('root', $roles), 1);

                $request->merge([
                    'roles' => $roles,
                ]);
            }
        }

        // A certain permission is required to be able to assign roles
        if (!$request->user()->hasPermissions('users.assign_roles')) {
            $request->request->remove('roles');
        }

        // A certain permission is required to be able to grant personal
        // permissions
        if (!$request->user()->hasPermissions('users.grant_personal_permissions')) {
            $request->request->remove('permissions');
        }

        // This is a feature option used to prevent anyone from editing the
        // root's credentials on the live demo site
        if ($item && $item->roles->contains('root') && config('devel.root.is_locked')) {
            $values['email'] = config('devel.root.default_email');
            $values['password'] = Hash::make(config('devel.root.default_password'));
        }

        return $values;
    }

    /**
     * Get all existing grouped permissions, mark granted ones for a user.
     *
     * @param User $user
     * @return array
     */
    protected function getPermissions(User $user = null): array
    {
        $permissions = Permission::getGrouped();

        if ($user) {
            $userPermissions = $user->permissions;
            $rolesPermissions = collect();

            foreach ($user->roles as $role) {
                $rolesPermissions = $rolesPermissions->merge($role->permissions);
            }

            foreach ($userPermissions as $permission) {
                $group = explode('.', $permission->key)[0];

                if (!$permissions[$group]) {
                    continue;
                }

                $index = array_search($permission->toArray(), $permissions[$group]['permissions']);

                if ($index !== false) {
                    $permissions[$group]['permissions'][$index]['granted'] = true;
                }
            }

            foreach ($rolesPermissions as $permission) {
                $group = explode('.', $permission->key)[0];

                if (!$permissions[$group]) {
                    continue;
                }

                $index = array_search($permission->toArray(), $permissions[$group]['permissions']);

                if ($index !== false) {
                    $permissions[$group]['permissions'][$index]['grantedByRole'] = true;
                }
            }
        }

        return $permissions;
    }

    /**
     * Determine whether an item can be edited.
     *
     * @param Request $request
     * @param mixed $id
     * @return mixed
     */
    protected function canBeEdited($request, $id)
    {
        $model = new $this->modelClass;

        $object = ($this->model())::where($model->getRouteKeyName(), $id)->first();

        if (!$object) {
            return 'Item with provided id was not found!';
        }

        // No one can edit the root profile except for the root itself
        if ($object->roles->contains('root') && $request->user()->id !== $object->id) {
            return 'The Root user cannot be edited!';
        }

        return true;
    }

    /**
     * Determine whether an item can be deleted.
     *
     * @param Request $request
     * @param mixed $id
     * @return mixed
     */
    protected function canBeDeleted($request, $id)
    {
        $model = new $this->modelClass;

        $object = ($this->model())::where($model->getRouteKeyName(), $id)->first();

        if (!$object) {
            return 'Item with provided id was not found!';
        }

        if ($object->roles->contains('root')) {
            return 'The Root user cannot be deleted!';
        }

        return true;
    }
}
