<?php

namespace Database\Seeders;

use App\Models\{User, config, Marque, Service, Category};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    private $permissions = [
        'dashboard',
        'clients_view',
        'clients_delete',

        'category_view',
        'category_add',
        'category_edit',
        'category_delete',

        'marque_view',
        'marque_add',
        'marque_edit',
        'marque_delete',

        'sponsor_view',
        'sposor_add',
        'sposor_edit',
        'sponsor_delete',

        'service_view',
        'service_add',
        'service_edit',
        'service_delete',

        'product_view',
        'product_add',
        'product_edit',
        'product_delete',

        'coupon_view',
        'coupon_add',
        'coupon_edit',
        'coupon_delete',

        'testimonial_view',
        'testimonial_add',
        'testimonial_edit',
        'testimonial_delete',

        'table_view',
        'table_add',
        'table_edit',
        'table_delete',

        'price_view',

        'order_view',
        'order_add',
        'order_edit',
        'order_delete',

        'live_order_view',
        'live_order_add',
        'live_order_edit',
        'live_order_delete',

        'setting_view',
        'message_view',
        'gestion_stock'
    ];

    public function run(): void
    {
        // 🔹 Créer les permissions
        foreach ($this->permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $permissions = Permission::pluck('id', 'id')->all();

        // 🔹 Créer le rôle "admin" et lui assigner toutes les permissions
        $roleAdmin = Role::firstOrCreate(['name' => 'admin']);
        $roleAdmin->syncPermissions($permissions);

        // 🔹 Créer le premier administrateur
        $admin1 = User::create([
            'nom' => 'Admin',
            'prenom' => 'Principal',
            'email' => 'admin1@gmail.com',
            'role' => 'admin',
            'adresse' => '123 rue de la paix',
            'phone' => '0612345678',
            'code_postal' => '75000',
            'password' => Hash::make('123456789'),
        ]);
        $admin1->assignRole([$roleAdmin->id]);

        // 🔹 Créer le deuxième administrateur
        $admin2 = User::create([
            'nom' => 'Admin',
            'prenom' => 'Secondaire',
            'email' => 'admin2@gmail.com',
            'role' => 'admin',
            'adresse' => '456 avenue de la liberté',
            'phone' => '0698765432',
            'code_postal' => '69000',
            'password' => Hash::make('123456789'),
        ]);
        $admin2->assignRole([$roleAdmin->id]);

        // 🔹 Créer un utilisateur client par défaut
        $client = User::create([
            'nom' => 'Client',
            'prenom' => 'Démo',
            'email' => 'client@gmail.com',
            'role' => 'client',
            'adresse' => '123 rue du code',
            'phone' => '0612345678',
            'code_postal' => '75000',
            'password' => Hash::make('123456789'),
        ]);

        $roleClient = Role::firstOrCreate(['name' => 'client']);
        $client->assignRole([$roleClient->id]);

        // 🔹 Créer le rôle "personnel" (vide pour le moment)
        Role::firstOrCreate(['name' => 'personnel']);

        // 🔹 Créer la configuration par défaut
        $cat = new config();
        $cat->frais = '15';
        $cat->description = 'Bienvenue à KOUKOUMA terrasse 
Nous vous offrons les services à emporter et à consommer sur place. KOUKOUMA c’est aussi votre espace de divertissement.';
        $cat->telephone = '683 31 00 79';
        $cat->email = 'koukoumamarket@gmail.com';
        $cat->addresse = 'Douala Avenue Mohamed Melki 1005 El Omrane';
        $cat->save();
    }
}
