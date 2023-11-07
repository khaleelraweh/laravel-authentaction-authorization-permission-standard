<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EntrustSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Dictionary : 
     *              01- Roles 
     *              02- Users
     *              03- AttachRoles To  Users
     *              04- Create random customer and  AttachRole to customerRole
     * 
     * 
     * @return void
     */
    public function run()
    {

        //create fake information  using faker factory lab 
        $faker = Factory::create();


        //------------- 01- Roles ------------//
        //adminRole
        $adminRole = new Role();
        $adminRole->name         = 'admin';
        $adminRole->display_name = 'User Administrator'; // optional
        $adminRole->description  = 'User is allowed to manage and edit other users'; // optional
        $adminRole->allowed_route= 'admin';
        $adminRole->save();

        //supervisorRole
        $supervisorRole = Role::create([
            'name'=>'supervisor',
            'display_name'=>'User Supervisor',
            'description'=>'Supervisor is allowed to manage and edit other users',
            'allowed_route'=>'admin',
        ]);


        //customerRole
        $customerRole = new Role();
        $customerRole->name         = 'customer';
        $customerRole->display_name = 'Project Customer'; // optional
        $customerRole->description  = 'Customer is the customer of a given project'; // optional
        $customerRole->allowed_route= null;
        $customerRole->save();


        //------------- 02- Users  ------------//
        // Create Admin
        $admin = new User();
        $admin->first_name = 'Admin';
        $admin->last_name = 'System';
        $admin->username = 'admin';
        $admin->email = 'admin@gmail.com';
        $admin->email_verified_at = now();
        $admin->mobile = '00967772036131';
        $admin->password = bcrypt('123123123');
        $admin->user_image = 'avator.svg';
        $admin->status = 1;
        $admin->remember_token = Str::random(10);
        $admin->save();

        // Create supervisor
        $supervisor = User::create([
            'first_name'=>'Supervisor',
            'last_name'=>'System',
            'username'=>'supervisor',
            'email'=>'supervisor@gmail.com',
            'email_verified_at'=>now(),
            'mobile'=>'00967772036132',
            'password'=>bcrypt('123123123'),
            'user_image'=>'avator.svg',
            'status'=>1,
            'remember_token'=>Str::random(10),
        ]);

        // Create customer
        $customer = User::create([
            'first_name'=>'Khaleel',
            'last_name'=>'Raweh',
            'username'=>'khaleel',
            'email'=>'khaleelvisa@gmail.com',
            'email_verified_at'=>now(),
            'mobile'=>'00967772036133',
            'password'=>bcrypt('123123123'),
            'user_image'=>'avator.svg',
            'status'=>1,
            'remember_token'=>Str::random(10),
        ]);

        //------------- 03- AttachRoles To  Users  ------------//
        $admin->attachRole($adminRole);
        $supervisor->attachRole($supervisorRole);
        $customer->attachRole($customerRole);


        //------------- 04-  Create random customer and  AttachRole to customerRole  ------------//
        for ($i = 1; $i <= 20; $i++){
            //Create random customer
            $random_customer = User::create([
                'first_name'=>$faker->firstName,
                'last_name'=>$faker->lastName,
                'username'=>$faker->unique()->userName,
                'email'=>$faker->unique()->email,
                'email_verified_at'=>now(),
                'mobile'=>'0096777'.$faker->numberBetween(1000000,9999999),
                'password'=>bcrypt('123123123'),
                'user_image'=>'avator.svg',
                'status'=>1,
                'remember_token'=>Str::random(10),
            ]);

            //Add customerRole to RandomCusomer
            $random_customer->attachRole($customerRole);

        }//end for


        //------------- 05- Permission  ------------//
        //manage main dashboard page
        $manageMain = Permission::create(['name'=>'main', 'display_name'=>'الرئيسية', 'route'=>'index', 'module'=>'index', 'as'=>'index', 'icon'=>'ri-dashboard-line', 'parent'=>'0', 'parent_original'=>'0', 'sidebar_link'=>'1', 'appear'=>'1', 'ordering'=>'1']);
        $manageMain->parent_show = $manageMain->id;
        $manageMain->save();


        //Procuct Categories
        $manageProductCategories = Permission::create(['name' => 'manage_product_categories', 'display_name' => 'الاقسام' , 'route' => 'product_categories' , 'module' => 'product_categories' , 'as' => 'product_categories.index' , 'icon' => 'fas fa-file-archive' , 'parent' => '0' , 'parent_original' => '0' , 'sidebar_link' => '1' , 'appear' => '1' , 'ordering' => '5',] );
        $manageProductCategories->parent_show = $manageProductCategories->id; $manageProductCategories->save();

        $showProductCategories    =  Permission::create(['name' => 'show_product_categories'    ,  'display_name' => 'عرض الاقسام'      , 'route' => 'product_categories' , 'module' => 'product_categories' , 'as' => 'product_categories.index'    , 'icon' => 'fas fa-file-archive' , 'parent' => $manageProductCategories->id , 'parent_original' => $manageProductCategories->id ,'parent_show' => $manageProductCategories->id , 'sidebar_link' => '1' , 'appear' => '1'] );
        $createProductCategories  =  Permission::create(['name' => 'create_product_categories'  , 'display_name'  => 'إنشاء قسم' , 'route' => 'product_categories' , 'module' => 'product_categories' , 'as' => 'product_categories.create'   , 'icon' => null                  , 'parent' => $manageProductCategories->id , 'parent_original' => $manageProductCategories->id ,'parent_show' => $manageProductCategories->id , 'sidebar_link' => '0' , 'appear' => '0'] );
        $displayProductCategories =  Permission::create(['name' => 'display_product_categories' , 'display_name'  => 'عرض قسم'   , 'route' => 'product_categories' , 'module' => 'product_categories' , 'as' => 'product_categories.show'     , 'icon' => null                  , 'parent' => $manageProductCategories->id , 'parent_original' => $manageProductCategories->id ,'parent_show' => $manageProductCategories->id , 'sidebar_link' => '0' , 'appear' => '0'] );
        $updateProductCategories  =  Permission::create(['name' => 'update_product_categories'  , 'display_name'  => 'تعديل قسم' , 'route' => 'product_categories' , 'module' => 'product_categories' , 'as' => 'product_categories.edit'     , 'icon' => null                  , 'parent' => $manageProductCategories->id , 'parent_original' => $manageProductCategories->id ,'parent_show' => $manageProductCategories->id , 'sidebar_link' => '0' , 'appear' => '0'] );
        $deleteProductCategories  =  Permission::create(['name' => 'delete_product_categories'  , 'display_name'  => 'حذف قسم' , 'route' => 'product_categories' , 'module' => 'product_categories' , 'as' => 'product_categories.destroy'  , 'icon' => null                  , 'parent' => $manageProductCategories->id , 'parent_original' => $manageProductCategories->id ,'parent_show' => $manageProductCategories->id , 'sidebar_link' => '0' , 'appear' => '0'] );


         //Products 
         $manageProducts = Permission::create(['name' => 'manage_products', 'display_name' => 'المنتجات' , 'route' => 'products' , 'module' => 'products' , 'as' => 'products.index' , 'icon' => 'fas fa-file-archive' , 'parent' => '0' , 'parent_original' => '0' , 'sidebar_link' => '1' , 'appear' => '1' , 'ordering' => '15',] );
         $manageProducts->parent_show = $manageProducts->id; $manageProducts->save();
         $showProducts    =  Permission::create(['name' => 'show_products'    ,  'display_name' => 'عرض المنتجات'       , 'route' => 'products' , 'module' => 'products' , 'as' => 'products.index'    , 'icon' => 'fas fa-file-archive'  , 'parent' => $manageProducts->id , 'parent_original' => $manageProducts->id ,'parent_show' => $manageProducts->id , 'sidebar_link' => '1' , 'appear' => '1'] );
         $createProducts  =  Permission::create(['name' => 'create_products'  , 'display_name'  => 'إضافة منتج جديد' , 'route' => 'products' , 'module' => 'products' , 'as' => 'products.create'   , 'icon' => null           , 'parent' => $manageProducts->id , 'parent_original' => $manageProducts->id ,'parent_show' => $manageProducts->id , 'sidebar_link' => '1' , 'appear' => '0'] );
         $displayProducts =  Permission::create(['name' => 'display_products' , 'display_name'  => 'عرض منتج'   , 'route' => 'products' , 'module' => 'products' , 'as' => 'products.show'     , 'icon' => null           , 'parent' => $manageProducts->id , 'parent_original' => $manageProducts->id ,'parent_show' => $manageProducts->id , 'sidebar_link' => '0' , 'appear' => '0'] );
         $updateProducts  =  Permission::create(['name' => 'update_products'  , 'display_name'  => 'تحديث منتج' , 'route' => 'products' , 'module' => 'products' , 'as' => 'products.edit'     , 'icon' => null           , 'parent' => $manageProducts->id , 'parent_original' => $manageProducts->id ,'parent_show' => $manageProducts->id , 'sidebar_link' => '0' , 'appear' => '0'] );
         $deleteProducts  =  Permission::create(['name' => 'delete_products'  , 'display_name'  => 'حذف منتج' , 'route' => 'products' , 'module' => 'products' , 'as' => 'products.destroy'  , 'icon' => null           , 'parent' => $manageProducts->id , 'parent_original' => $manageProducts->id ,'parent_show' => $manageProducts->id , 'sidebar_link' => '0' , 'appear' => '0'] );
 


        
    }
}
