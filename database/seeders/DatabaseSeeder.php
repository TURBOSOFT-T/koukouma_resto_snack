<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\{User, config, Marque, Service, Category};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */

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

        foreach ($this->permissions as $permission) {
            Permission::create(['name' => $permission]);
        }





        // Créer un administrateur directement après la création de la table
        $user = new User();
        $user->nom = ' Admin';
        $user->prenom = 'Admin';
        $user->email = 'admin@gmail.com';
        $user->role = "admin";
        $user->adresse = '123 rue de la paix';
        $user->phone = '0612345678';
        $user->code_postal = '75000';
        $user->password = Hash::make('123456789');
        $user->save();

        $user1 = new User();
        $user1->nom = ' Koukouma';
        $user1->prenom = 'Koukouma';
        $user1->email = 'koukoumamarket@gmail.com';
        $user1->role = "admin";
        $user1->adresse = 'Douala  123 rue de la paix';
        $user1->phone = ' 683 31 00 79';
        $user1->code_postal = '75000';
        $user1->password = Hash::make('123456789');
        $user1->save();

        //creer un profil developpers
        $dev = new User();
        $dev->nom = "Client";
        $dev->prenom = 'Client';
        $dev->email = 'client@gmail.com';
        $dev->role = "client";
        $dev->adresse = '123 rue du code';
        $dev->phone = '0612345678';
        $dev->code_postal = '75000';
        $dev->password = Hash::make('123456789');
        $dev->save();


        $permissions = Permission::pluck('id', 'id')->all();

        $role = Role::create(['name' => 'admin']);
        $role->syncPermissions($permissions);
        $user->assignRole([$role->id]);
        $user1->assignRole([$role->id]);

        $role2 = Role::create(['name' => 'developper']);
        $dev->assignRole([$role2->id]);
        $role2->syncPermissions($permissions);


        $role = Role::create(['name' => 'personnel']);


        $cat = new config();
        $cat->frais = '15';
        $cat->description = 'Bienvenue à KOUKOUMA terrasse 
Nous vous offrons les services à emporter et à consommer sur place. KOUKOUMA c\’est aussi votre espace de divertissement ';
        $cat->telephone = '683 31 00 79';
        $cat->email = 'koukoumamarket@gmail.com';
        $cat->addresse = 'Douala Avenue Mohamed Melki 1005 El Omrane';

        $cat->save();
    }
}
