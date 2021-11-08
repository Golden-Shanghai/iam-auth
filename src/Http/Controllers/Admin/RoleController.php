<?php


namespace Ze\IAMAuth\Http\Controllers\Admin;

use Encore\Admin\Controllers\RoleController as BaseRoleController;
use Encore\Admin\Form;
use Ze\IAMOauth\Business\RoleBusiness;

class RoleController extends BaseRoleController
{
    protected function grid()
    {
        $grid = parent::grid();

        $grid->paginate(500);
        $grid->model()->orderBy('slug');

        // 权限颗粒过长不予展示
        $grid->column('permissions')->hide();

        $grid->column('created_at')->hide();

        return $grid;
    }

    protected function detail($id)
    {
        $show = parent::detail($id);

        $show->field('permissions', trans('admin.permissions'))->unescape()->as(function ($permission) {
            return $permission->pluck('name')->implode('<br />');
        });

        return $show;
    }

    public function form()
    {
        $form = parent::form();

        $form->saved(function (Form $form) {
            // 同步指定角色可见的菜单
            RoleBusiness::syncRoleMenus($form->model());
        });

        return $form;
    }
}
