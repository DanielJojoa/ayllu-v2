<?php

namespace App\Http\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Tenant;
use App\Helpers\JWTHelper;
use App\Models\Tenant\Files;
use App\Models\Tenant\UserTenant;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Tymon\JWTAuth\Facades\JWTAuth;

trait TenantControllerTrait
{
   private $request;

   public function list(Request $request)
   {

      $this->params =  $request->all();

      $user = JWTAuth::parseToken()->authenticate();
      $user =  User::find($user->pkiduser);
      if ($user->hasRole('super-admin') || $this->hasListPermission($user->roles[0])) {
         $list = $this->getList($this->params);
         if ($list['status']) {
            $this->response['data'] = $list['data'];
            $this->response['status'] = 200;
         } else {
            $this->response['message'] = $list['message'];
            $this->response['status'] = 404;
         }
      } else {
         $this->response['status'] = 404;
         $this->response['message'] = 'El usuario no tiene permisos';
      }
      return $this->response;
   }


   /**
    * Store a new User data
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
   public function new(Request $request)
   {
      $this->params = $request->all();
      $this->request = $request;
      $this->response = array();
      $this->file = $request->file('file') && $request->file('file') != null ? $request->file('file') : null;
         $user = JWTAuth::parseToken()->authenticate();
         $user =  User::find($user->pkiduser);
         if ($user->hasRole('super-admin') || $this->hasCreatePermission($user->roles[0])) {
            $json_params = json_decode($this->params['json'],true);
            if (!$this->validator($json_params)->fails()) {
               $new =  $this->create($json_params);
               if ($new['status']) {
                  $this->response['status'] = 200;
                  $this->response['new'] = $new['new'];
                  $new['message']?$this->response['message'] = $new['message']:null;
                  if (method_exists($this, 'registered')) {
                     $res = $this->registered($new, $json_params);
                     if (!$res['status']) {
                        $this->response['message'] .= $res['message'];
                     }
                  }
               } else {
                  $this->response['status'] = 501;
                  $this->response['message'] = $new['message'];
               }
            } else {
               $this->response['status'] = 501;
               $this->response['errors'] = $this->validator($json_params)->errors()->all();
               $this->response['message'] = 'El formulario tiene errores';
            }
         } else {
            $this->response['status'] = 419;
            $this->response['message'] = 'El usuario no tiene permisos';
         }

      return json_encode($this->response);
   }

   /**
    * Store a new record
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
   public function edit(Request $request)
   {

      $this->params = $request->all();
      $this->request = $request;
      $this->response = array();
      $this->file = $request->file('file') && $request->file('file') != null ? $request->file('file') : null;
         $user = JWTAuth::parseToken()->authenticate();
         $user =  User::find($user->pkiduser);
         if ($user->hasRole('super-admin') || $this->hasEditPermission($user->roles[0])) {
            $json_params = json_decode($this->params['json'],true);

            if (!$this->editValidator($json_params)->fails()) {
               $edited =  $this->update($json_params, $this->file);
               if ($edited['status']) {
                  $this->response['status'] = 200;
                  $this->response['message'] = $edited['message'];
                  if (method_exists($this, 'updated')) {
                     $res = $this->updated($edited['data'], $json_params);
                     if (!$res['status']) {
                        $this->response['message'] .= $res['message'];
                     }
                  }
               } else {
                  $this->response['status'] = 501;
                  $this->response['message'] = "Falló la edición del $this->tableName " . $edited['message'];
               }
            } else {
               $this->response['status'] = 501;
               $this->response['errors'] = $this->editValidator($json_params)->errors()->all();
               $this->response['message'] = "El formulario tiene errores";
            }
         } else {

            $this->response['status'] = 401;
            $this->response['message'] = 'El usuario no tiene permisos';
         }
      return json_encode($this->response);
   }

   /**
    * Delete a record
    * @param pkid: pkid of record to delete
    * @return status status of request, 200 if the record is deleted
    * @return message message of faild or success of the request, 
    */

   public function delete(Request $request)
   {

      $this->params = $request->all();
      $this->response = array();
         $user = JWTAuth::parseToken()->authenticate();
         $user =  User::find($user->pkiduser);
         if ($user->hasRole('super-admin') || $this->hasDeletePermission($user->roles[0])) {
            $json_params = json_decode($this->params['json']);
            try {
               $res = $this->remove($json_params);
               $this->response['status'] = $res['status'] ? 200 : 501;
               $this->response['message'] = $res['message'];
            } catch (\Throwable $th) {
               $this->response['status'] = 401;
               $this->response['message'] = "$this->tableName no se pudo eliminar, Tiene ralación con otras tablas";
            }
         } else {

            $this->response['status'] = 401;
            $this->response['message'] = 'El usuario no tiene permisos';
         }
      return json_encode($this->response);
   }

   public function detail(Request $request)
   {
      $encode_json = $request->all();
      $params = json_decode($encode_json['json'], true);
      $response = array();
      $user =  Auth::user() ? User::find(Auth::user()->id) : null;
      if ($user) {
         if ($user->hasRole('super-admin') || $user->roles[0]->hasPermissionTo('ver usuario')) {

            $response['status'] =  200;
            $response['data'] = $this->table::find($params['id']);
         } else {
            $response['status'] = 401;
            $response['message'] = 'El usuario no tiene permisos';
         }
      } else {
         $response['status'] = 401;
         $response['message'] = 'Sesión caducada, ingrese nuevamente';
      }
      return $response;
   }


   public function paginate($items, $perPage = 10, $page = null, $options = [])
   {
      $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
      $items = $items instanceof Collection ? $items : Collection::make($items);
      return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
   }

   // public function storeFile($file, $params)
   // {
   //    $fileDb = null;
   //    if ($file && $file != null) {

   //       $params['originalName'] = $file->getClientOriginalName();
   //       $params['size'] = $file->getSize();
   //       $params['encryptedName'] =  $file->hashName();
   //       $params['path'] = url('storage/' . tenant('id') . $params['file_folder']) . '/' . $params['encryptedName'];
   //       $file->move(public_path('storage/' . tenant('id') . $params['file_folder']), $params['encryptedName']);

   //       $fileDb = Files::create($params);
   //    }
   //    return $fileDb;
   // }
}
