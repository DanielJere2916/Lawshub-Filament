<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory()->create();

       $uregistrar=User::factory()->create([
            'name' => 'uregistrar',
            'email' => 'Uregistrar@gmail.com',
        ]);
       $registrar=User::factory()->create([
            'name' => 'Registrar',
            'email' => 'registrarunilia@gmail.com',
        ]);
       $applicant=User::factory()->create([
            'name' => 'khuzwa',
            'email' => 'khuzwa@gmail.com',
        ]);
       $dean=User::factory()->create([
            'name' => 'dean',
            'email' => 'dean@gmail.com',
        ]);
       $hod=User::factory()->create([
            'name' => 'hod',
            'email' => 'hod@gmail.com',
        ]);
        
$role = Role::create(['name' => 'Registrar']);
$registrar->assignRole($role);
$role = Role::create(['name' => 'Applicant']);
$applicant->assignRole($role);
$role = Role::create(['name' => 'Dean']);
$dean->assignRole($role);
$role = Role::create(['name' => 'Hod']);
$hod->assignRole($role);
$role = Role::create(['name' => 'Uregistrar']);
$uregistrar->assignRole($role);
    }
}
