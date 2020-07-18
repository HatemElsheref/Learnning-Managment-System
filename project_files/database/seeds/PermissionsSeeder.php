<?php

use Illuminate\Database\Seeder;
use App\Permission;
class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $permissions=InitPermissions(config('permissions.Models'),(config('permissions.Maps')));
        foreach ($permissions as $alias=>$name){
                   $permission=Permission::create([
                       'name'=>$name,
                       'alias'=>$alias
                   ]);
            auth('webadmin')->user()->permissions()->attach($permission->id);
        }

    }
}
