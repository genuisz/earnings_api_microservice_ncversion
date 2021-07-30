<?php
namespace App\Traits;

use App\Models\Permission;
use App\Models\Role;

trait HasPermissionsTrait {
  use \Staudenmeir\EloquentHasManyDeep\HasRelationships;  // Add HasManyDeep relationship
   public function givePermissionsTo(... $permissions) {

    $permissions = $this->getAllPermissions($permissions);
    if($permissions === null) {
      return $this;
    }
    $this->permissions()->saveMany($permissions);
    return $this;
  }

  public function withdrawPermissionsTo( ... $permissions ) {

    $permissions = $this->getAllPermissions($permissions);
    $this->permissions()->detach($permissions);
    return $this;

  }

  public function refreshPermissions( ... $permissions ) {

    $this->permissions()->detach();
    return $this->givePermissionsTo($permissions);
  }

  public function hasPermissionTo($permission) {

    return $this->hasPermissionThroughRole($permission) || $this->hasPermission($permission);
  }

  public function hasPermissionThroughRole($permission) {

    foreach ($permission->roles as $role){
      if($this->roles->contains($role)) {
        return true;
      }
    }
    return false;
  }

  public function hasRole( ... $roles ) {
    //d(method_exists(app(get_class($this)),'roles'));
    foreach ($roles as $role) {
      if ($this->roles->contains('slug', $role)) {
        return true;
      }
    }
    return false;
  }

  public function roles() {

    //return $this->belongsToMany(Role::class,'backend_user_role');
    if(method_exists(app(get_class($this)),'role')){
      return $this->role();
    }

  }
  public function permissions() {

    // return $this->belongsToMany(Permission::class,'users_permissions');
    //return $this->hasManyDeep('App\Models\Permission',['backend_user_role','role_permission'],['backend_user_id','role_id','id'],['id','role_id','permission_id']);
    if(method_exists(app(get_class($this)),'permission')){
      return $this->permission();
    }
  }
  protected function hasPermission($permission) {

    return (bool) $this->permissions->where('slug', $permission->slug)->count();
  }

  protected function getAllPermissions(array $permissions) {

    return Permission::whereIn('slug',$permissions)->get();
    
  }

}