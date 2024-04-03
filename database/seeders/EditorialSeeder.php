<?php

namespace Database\Seeders;

use App\Models\Editorial;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EditorialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Editorial::create([
            'name' => 'desconocido',
            'direction' => 'desconocida',
            'phone' => 'no tiene',
            'email' => 'desconocido'
        ]);
    }
}
