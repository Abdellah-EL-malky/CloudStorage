<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Création de rôles
        $roles = [
            [
                'name' => 'admin',
                'description' => 'Administrateur avec contrôle total sur le système'
            ],
            [
                'name' => 'manager',
                'description' => 'Gestionnaire pouvant créer des espaces et inviter des utilisateurs'
            ],
            [
                'name' => 'collaborator',
                'description' => 'Collaborateur pouvant modifier les fichiers partagés'
            ],
            [
                'name' => 'reader',
                'description' => 'Lecteur pouvant uniquement consulter les fichiers partagés'
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
