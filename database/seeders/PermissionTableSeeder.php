<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            [
              'group_name' => 'user',
              'permissions' => [
                'user-list',
                'user-create',
                'user-edit',
                'user-delete',
              ]
            ],
            [
              'group_name' => 'role',
              'permissions' => [
                'role-list',
                 'role-create',
                 'role-edit',
                 'role-delete',
              ]
            ],
            [
              'group_name' => 'bank',
              'permissions' => [
                'bank-list',
                'bank-create',
                'bank-edit',
                'bank-delete',
              ]
            ],
            [
              'group_name' => 'mobile-wallet',
              'permissions' => [
                'mobile-wallet-list',
                'mobile-wallet-create',
                'mobile-wallet-edit',
                'mobile-wallet-delete',
              ]
            ],
            [
              'group_name' => 'transaction-category',
              'permissions' => [
                'transaction-category-list',
                'transaction-category-create',
                'transaction-category-edit',
                'transaction-category-delete',
              ]
            ],
            [
              'group_name' => 'active-session',
              'permissions' => [
                'active-session-list',
                'active-session-create',
                'active-session-edit',
                'active-session-delete',
              ]
            ],
            [
              'group_name' => 'category-of-blog',
              'permissions' => [
                'category-of-blog-list',
                'category-of-blog-create',
                'category-of-blog-edit',
                'category-of-blog-delete',
              ]
            ],
            [
              'group_name' => 'blog',
              'permissions' => [
                'blog-list',
                'blog-create',
                'blog-edit',
                'blog-delete',
              ]
            ],
            
            [
              'group_name' => 'category-of-documentation',
              'permissions' => [
                'category-of-documentation-list',
                'category-of-documentation-create',
                'category-of-documentation-edit',
                'category-of-documentation-delete',
              ]
            ],
            [
              'group_name' => 'documentation',
              'permissions' => [
                'documentation-list',
                'documentation-create',
                'documentation-edit',
                'documentation-delete',
              ]
            ],
            [
              'group_name' => 'contact',
              'permissions' => [
                'contact-list',
                'contact-create',
                'contact-edit',
                'contact-delete',
              ]
            ],
            [
              'group_name' => 'profession',
              'permissions' => [
                'profession-list',
                'profession-create',
                'profession-edit',
                'profession-delete',
              ]
            ],
            [
              'group_name' => 'push-notification',
              'permissions' => [
                'push-notification-list',
                'push-notification-create',
                'push-notification-edit',
                'push-notification-delete',
              ]
            ],
        ];
   
        // Create Permissions
        for ($i = 0; $i < count($permissions); $i++){
            $permissionGroup = $permissions[$i]['group_name'];
            for ($j = 0; $j < count($permissions[$i]['permissions']); $j++) {
                // Create Permission
                $permission = Permission::create(['name' => $permissions[$i]['permissions'][$j], 'group_name' => $permissionGroup]);
            }
        }
    }
}
