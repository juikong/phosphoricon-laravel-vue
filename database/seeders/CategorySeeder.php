<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->upsert([
            [
                'id' => 1,
                'name' => 'Camera',
                'description' => null,
                'phosphor_icon_id' => 231,
                'sort' => 1,
                'status' => 1,
                'created_at' => '2024-03-05 01:46:18',
                'updated_at' => '2024-03-05 01:46:18',
            ],
            [
                'id' => 2,
                'name' => 'Mobile Phones',
                'description' => null,
                'phosphor_icon_id' => 400,
                'sort' => 1,
                'status' => 1,
                'created_at' => '2024-03-05 01:46:18',
                'updated_at' => '2024-03-05 01:46:18',
            ],
            [
                'id' => 3,
                'name' => 'Computers',
                'description' => null,
                'phosphor_icon_id' => 396,
                'sort' => 1,
                'status' => 1,
                'created_at' => '2024-03-05 01:46:18',
                'updated_at' => '2024-03-05 01:46:18',
            ],
            [
                'id' => 4,
                'name' => 'Headphones',
                'description' => null,
                'phosphor_icon_id' => 642,
                'sort' => 1,
                'status' => 1,
                'created_at' => '2024-03-05 01:46:18',
                'updated_at' => '2024-03-05 01:46:18',
            ],
            [
                'id' => 5,
                'name' => 'Home Audio',
                'description' => null,
                'phosphor_icon_id' => 1026,
                'sort' => 1,
                'status' => 1,
                'created_at' => '2024-03-05 01:46:18',
                'updated_at' => '2024-03-05 01:46:18',
            ],
            [
                'id' => 6,
                'name' => 'Television & Video',
                'description' => null,
                'phosphor_icon_id' => 1093,
                'sort' => 1,
                'status' => 1,
                'created_at' => '2024-03-05 01:46:18',
                'updated_at' => '2024-03-05 01:46:18',
            ],
            [
                'id' => 7,
                'name' => 'Video Game Consoles',
                'description' => null,
                'phosphor_icon_id' => 569,
                'sort' => 1,
                'status' => 1,
                'created_at' => '2024-03-05 01:46:18',
                'updated_at' => '2024-03-05 01:46:18',
            ],
        ], ['id'], [
            'name',
            'description',
            'phosphor_icon_id',
            'sort',
            'status',
            'created_at',
            'updated_at',
        ]);
    }
}
