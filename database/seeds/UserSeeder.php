<?php

use App\Department;
use Illuminate\Database\Seeder;
use App\User;
use App\Company;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        try{
            $viewPermission = Permission::create(['name' => 'view']);
            $addPermission = Permission::create(['name' => 'add']);
            $deletePermission = Permission::create(['name' => 'delete']);
            $updatePermission = Permission::create(['name' => 'update']);
        }catch (Throwable $e) {
            
            report($e);

        }
           
        $role_superAdmin = new Role();
        $role_superAdmin->name = 'Super Admin';
        $role_superAdmin->guard_name = 'api';
        $role_superAdmin->save();

        $role_admin = new Role();
        $role_admin->name = 'Admin';
        $role_admin->guard_name = 'api';
        $role_admin->save();
        
        
        $role_rh = new Role();
        $role_rh->name = 'RH';
        $role_rh->guard_name = 'api';
        $role_rh->save();


        $role_user = new Role();
        $role_user->name = 'User';
        $role_user->guard_name = 'api';
        $role_user->save();

        $role_admin->givePermissionTo(['add', 'view', 'update', 'delete']);
        $role_rh->givePermissionTo(['add', 'view', 'update', 'delete']);
        $role_user->givePermissionTo('view');
        

        $role_user = Role::where('name','User')->first();
        $role_rh = Role::where('name','RH')->first();
        $role_admin = Role::where('name','Admin')->first();
        
        
        $company = new Company();
        $company->name = 'Satoripop';
        $company->save();



        $department_Mobile = new Department();
        $department_Mobile->name = 'Mobile';
        $department_Mobile->company_id = $company->id;
        $department_Mobile->save();

        $department_UXdesign = new Department();
        $department_UXdesign->name = 'UX design';
        $department_UXdesign->company_id = $company->id;
        $department_UXdesign->save();
    

        $department_web = new Department();
        $department_web->name = 'web';
        $department_web->company_id = $company->id;
        $department_web->save();

        $department_AI = new Department();
        $department_AI->name = 'AI';
        $department_AI->company_id = $company->id;
        $department_AI->save();


        try{
            $user = new User();
            $user->firstName = 'Victor';
            $user->lastName = 'Chikabala';
            $user->email = 'Employee@example.com';
            $user->password = bcrypt('employee');
            $user->gender = '1';
            $user->experienceLevel = 'junior';
            $user->department_id = $department_Mobile->id;
            $user->phone = '54123456';
            $user->save();
            $user->assignRole($role_user);

            $admin = new User();
            $admin->firstName = 'Alex';
            $admin->lastName = 'Trax';
            $admin->email = 'admin@example.com';
            $admin->password = bcrypt('admin');
            $admin->gender = '1';
            $admin->experienceLevel = 'expert';
            $admin->department_id = $department_AI->id;
            $admin->phone = '55123456';
            $admin->save();
            $admin->assignRole($role_admin);

            $rh = new User();
            $rh->firstName = 'Andy';
            $rh->lastName = 'Meandych';
            $rh->email = 'Rh@example.com';
            $rh->password = bcrypt('rh');
            $rh ->gender = '0';
            $rh->experienceLevel = 'expert';
            $rh->department_id = $department_UXdesign->id;
            $rh->phone = '52123456';
            $rh->save(); 
            $rh->assignRole($role_rh);
        }catch( Throwable $e ){
             report($e);
        }

    }
}
