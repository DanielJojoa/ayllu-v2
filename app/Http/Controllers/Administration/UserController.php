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

class UserController extends Controller
{
    use TenantControllerTrait;
    private $params;
    private $tableName = 'Usuario';
    private $table = User::class;

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
        $filters = isset($params['filters']) ? json_decode($params['filters']) : null;
        $users =  isset($params['page']) ? User::filter($filters)->with('country')->paginate(10) :
            User::filter($filters)->with('country')->get();
        return array('status' => sizeof($users) > 0, 'data' => $users, 'message' => sizeof($users) > 0 ? 'Listado de usuarios' : 'No se encontraron usuarios');
    }
    protected function validator($params)
    {
        $messages = [
            'email.unique' => 'El :attribute  ya existe en otra cuenta.',
            'email.required' => 'El :attribute es requerido.',
        ];
        $rules = ['email' => ['required', 'unique:users']];
        return Validator::make($params, $rules, $messages);
    }
    protected function create($params)
    {
        $imageName = '';
        if ($image = $this->request->image) {
            $imageName = time() . '.' . $image->extension();
            $image->move(public_path('storage/' . '/user_images'), $imageName);
        }
        $params['password'] = Hash::make($params['password']);
        $params['image'] = $imageName == '' ? null : $imageName;
        $user = User::create($params);
        return array(
            'new' => $user,
            'status' => is_object($user),
            "message" => is_object($user) ? "Usuario $user->name $user->lastname registrado" :
                "No se pudo registrar el usuario"
        );
    }
    protected function registered($user, $params = null)
    {
        $response = array();
        if ($user) {
            if (isset($params['role_id'])) {

                if ($role = Role::find($params['role_id'])) {
                    $user->assignRole($role->name);
                    $response['status'] = true;
                } else {
                    $response['status'] = false;
                    $response['message'] = "No se encontró el rol enviado";
                }
            }
        } else {
            $response['status'] = false;
            $response['message'] = "No se envió el usuario";
        }
    }

    protected function editValidator($params)
    {

        $messages = [
            'email.unique' => 'El :attribute  ya existe en otra cuenta.',
            'pkiduser.required' => 'El id del usuario es obligatorio',
        ];
        $rules = [
            'pkiduser' => ['required'],
            'email' => ['required', Rule::unique('users')->ignore($params['pkiduser'],'pkiduser')],
        ];
        return Validator::make($params, $rules, $messages);
    }

    protected function update($params)
    {
        $response = array();
        $user = User::find($params['pkiduser']);
        $imageName = '';
        if ($user) {
            $image =  $this->request->image;
            if ($image && $image != null) {
                $imageName = time() . '.' . $image->extension();
                $image->move(public_path('storage/' . '/user_images'), $imageName);
                if ($user->image) {
                    File::delete(public_path('storage/' . '/user_images') . '/' . $user->image);
                }
            }
            $params['image'] = $imageName == '' ? $user->image : $imageName;
            $params['password'] = isset($params['password']) && $params['password'] != '' ? Hash::make($params['password']) : $user->password;
            $user->fill($params);
            if (isset($params['role_id'])) {
                $user->roles()->sync($params['role_id']);
            }

            $user->save();
            $response['status'] = true;
            $response['message'] = "Usuario $user->name  editado";
        } else {
            $response['status'] = false;
            $response['message'] = "No se encontró al usuario con id " . $params['id'];
        }
        return $response;
    }


    protected function remove($params)
    {
        $response =  array();
        $user = User::find($params['pkiduser']);
        $response = array();
        if ($user) {
            $user->type_status_id = 1; //poner el id del estado eliminado
            $user->save();
            $response['status'] = true;
            $response['message'] = "Se eliminó el usuario $user->name";
        } else {
            $response['status'] = false;
            $response['message'] = 'No se pudo encontrar al usuario con id ' . $params['id'];
        }
        return json_encode($response);
    }
}
