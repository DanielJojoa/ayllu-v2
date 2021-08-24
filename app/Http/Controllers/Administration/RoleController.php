<?php

namespace App\Http\Controllers\Administration;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Http\Traits\TenantControllerTrait;
use Illuminate\Http\Request;
use App\Models\User;
use Hamcrest\Type\IsObject;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    use TenantControllerTrait;
    private $params;
    private $tableName = 'Rol';
    private $table = Role::class;

    /**
     * Validate if user has list permission
     * @param Role : Role to check permission
     * @return boolean: return true if has pasrmission, return false if has not permission
     */
    protected function hasListPermission($role)
    {
        return $role->hasPermissionTo('Listar usuarios');
    }

    /**
     * Validate if user has create permission
     * @param Role : Role to check permission
     * @return boolean: return true if has pasrmission, return false if has not permission
     */
    protected function hasCreatePermission($role)
    {
        return $role->hasPermissionTo('Crear usuarios');
    }
    /**
     * Validate if user has edit permission
     * @param Role : Role to check permission
     * @return boolean: return true if has pasrmission, return false if has not permission
     */
    protected function hasEditPermission($role)
    {
        return $role->hasPermissionTo('Editar usuarios');
    }
    /**
     * Validate if user has delete permission
     * @param Role : Role to check permission
     * @return boolean: return true if has pasrmission, return false if has not permission
     */
    protected function hasDeletePermission($role)
    {
        return $role->hasPermissionTo('Borrar usuarios');
    }

    protected function getList($params)
    {
        $roles = Role::all();
        return array('status' => sizeof($roles) > 0, 'data' => $roles, 'message' => sizeof($roles) > 0 ? 'Listado de roles' : 'No se encontraron roles');
    }

}
