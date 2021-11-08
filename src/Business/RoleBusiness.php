<?php

namespace Ze\IAMOauth\Business;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Encore\Admin\Auth\Database\Role;
use Encore\Admin\Auth\Database\Menu;

class RoleBusiness
{
    // 同步指定角色可见的菜单
    public static function syncRoleMenus(Role $role)
    {
        $myMenuIds = [];

        $allMenus = Menu::all()->keyBy('id');

        foreach ($allMenus as $menu) {

            if (! $menu['uri']) {
                continue;
            }

            // 是否有权限访问此菜单
            if (self::canSeeMenu($role, $menu)) {
                $myMenuIds[] = $menu['id'];
            }
        }

        return $role->menus()->sync(self::getParentMenuIds($allMenus, $myMenuIds));
    }

    // 是否有权限访问此菜单
    protected static function canSeeMenu(Role $role, Menu $menu)
    {
        $request = Request::create(admin_base_path($menu['uri']), 'GET');

        // 是否有权限访问此菜单 URL
        return $role->permissions()->get()->first(function ($permission) use ($request) {
            return $permission->shouldPassThrough($request);
        });
    }

    protected static function getParentMenuIds(Collection $allMenus, array $myMenuIds)
    {
        if (! $myMenuIds) {
            return [];
        }

        $parentMenuIds = [];

        foreach ($myMenuIds as $menuId) {
            // 查找父菜单
            if ($allMenus[$menuId]['parent_id'] > 0) {
                $parentMenuIds[] = $allMenus[$menuId]['parent_id'];
            }
        }

        return array_merge(
            $myMenuIds,
            self::getParentMenuIds($allMenus, array_unique($parentMenuIds))
        );
    }
}
