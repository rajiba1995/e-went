<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            ['parent_name' => 'master_management', 'name' => 'banners', 'route' => 'admin.banner.index'],
            ['parent_name' => 'master_management', 'name' => 'why_ewent', 'route' => 'admin.why-ewent'],
            ['parent_name' => 'master_management', 'name' => 'faq', 'route' => 'admin.faq.index'],
            ['parent_name' => 'master_management', 'name' => 'policy_details', 'route' => 'admin.policy-details'],

            ['parent_name' => 'employee_management', 'name' => 'employee_list', 'route' => 'admin.employee.list'],
            ['parent_name' => 'employee_management', 'name' => 'employee_create', 'route' => 'admin.employee.create'],
            ['parent_name' => 'employee_management', 'name' => 'employee_update', 'route' => 'admin.employee.update'],
            ['parent_name' => 'employee_management', 'name' => 'designations', 'route' => 'admin.designation.index'],
            ['parent_name' => 'employee_management', 'name' => 'designation_permissions', 'route' => 'admin.designation.permission'],


            ['parent_name' => 'rider_management', 'name' => 'rider_verification_list', 'route' => 'admin.customer.verification.list'],
            ['parent_name' => 'rider_management', 'name' => 'rider_engagement_list', 'route' => 'admin.customer.engagement.list'],
            ['parent_name' => 'rider_management', 'name' => 'rider_details', 'route' => 'admin.customer.details'],

            ['parent_name' => 'model_management', 'name' => 'model_list', 'route' => 'admin.product.index'],
            ['parent_name' => 'model_management', 'name' => 'model_create', 'route' => 'admin.product.add'],
            ['parent_name' => 'model_management', 'name' => 'model_create', 'route' => 'admin.product.update'],
            ['parent_name' => 'model_management', 'name' => 'subscription_create', 'route' => 'admin.model.subscriptions'],

            ['parent_name' => 'vehicle_management', 'name' => 'vehicle_list', 'route' => 'admin.vehicle.list'],
            ['parent_name' => 'vehicle_management', 'name' => 'vehicle_create', 'route' => 'admin.vehicle.create'],
            ['parent_name' => 'vehicle_management', 'name' => 'vehicle_update', 'route' => 'admin.vehicle.update'],
            ['parent_name' => 'vehicle_management', 'name' => 'vehicle_details', 'route' => 'admin.vehicle.detail'],

            ['parent_name' => 'payment_management', 'name' => 'payment_summary', 'route' => 'admin.payment.summary'],
            ['parent_name' => 'payment_management', 'name' => 'vehicle_payment_summary', 'route' => 'admin.payment.vehicle.summary'],
            ['parent_name' => 'payment_management', 'name' => 'user_payment_summary', 'route' => 'admin.payment.user_history'],
            ['parent_name' => 'payment_management', 'name' => 'user_payment_history', 'route' => 'admin.payment.user_payment_history'],
        ];
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name']],
                [
                    'parent_name' => $permission['parent_name'],
                    'route' => $permission['route']
                ]
            );
        }
    }
}
