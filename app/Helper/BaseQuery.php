<?php

namespace App\Helper;

use Illuminate\Support\Facades\Session;
use Spatie\Permission\Models\Role;

trait BaseQuery
{
    /**
     * add new record
     */
    public function add($model, $data)
    {
        return $model->create($data);
    }

    /**
     * edit or update the record if exist by id
     */
    public function create_update($model, $data, $id)
    {

        return $model->updateOrCreate($id, $data);
    }
    /**
     * check id exist in model
     */
    public function check_exist()
    {
        $construction_id = $this->session_get('construction_site_id');

        if ($construction_id == null) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * get all record
     */
    public function get_all($model)
    {
        return $model->get();
    }

    /**
     * get all record
     */
    public function get_all_users($model)
    {
        return $model->whereHas('roles', function ($q) {
            $q->where('name', '!=', 'admin');
        })->orderBy('created_at', 'DESC')->get();;
    }

    /**
     * get record by its id
     */
    public function get_by_id($model, $id)
    {
        return $model->findOrFail($id);
    }

    /**
     * get record by column
     */
    public function get_by_column($model, $column, $value)
    {
        return $model->where($column, $value)->get();
    }

    /**
     * get single record by column
     */
    public function get_by_column_single($model, $column, $value)
    {
        return $model->where($column, $value)->first();
    }

    /**
     * delete record by its id
     */
    public function delete($model, $id)
    {
        return $model->findOrFail($id)->delete();
    }

    /**
     * get all roles
     */
    public function all_roles()
    {
        return Role::pluck('name')->toArray();
    }

    /**
     * get user by role
     */
    public function user_by_role($model, $role)
    {
        $user = $model->whereHas('roles', function ($q) use ($role) {
            $q->where('name', $role);
        })->orderBy('created_at', 'DESC')->get();

        return $user;
    }

    /**
     * get user by id with role
     */
    public function user_by_id_with_role($model, $id, $role)
    {
        $user = $model->whereHas('roles', function ($q) use ($role) {
            $q->where('name', $role);
        })->find($id);

        return $user;
    }

    /**
     * get user count by id with role
     */
    public function user_count_by_id_with_role($model, $id, $role)
    {
        $user = $model->whereHas('roles', function ($q) use ($role) {
            $q->where('name', $role);
        })->count('id', $id);

        return $user;
    }

    /**
     * increament page status in parent model
     */
    public function update_page_status($model, $id, $stats)
    {
        $status = $model->find($id);
        $status->page_status = $stats;
        $status->save();
        return $status;
    }

    /**
     * store value in session
     */
    public function session_store($key, $value)
    {
        Session::put($key, $value);
    }

    /**
     * store value in session
     */
    public function session_get($key)
    {
        return Session::get($key);
    }

    /**
     * store value in session
     */
    public function session_remove($key)
    {
        return Session::remove($key);
    }
}
