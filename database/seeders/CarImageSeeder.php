<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Seeder;
use App\Models\CarImage;

class CarImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $images = [
            // BMW E46 323i
            ['car_id' => 1, 'image_path' => 'cars/1/1.jpg'],
            ['car_id' => 1, 'image_path' => 'cars/1/2.jpg'],
            ['car_id' => 1, 'image_path' => 'cars/1/3.jpg'],
            ['car_id' => 1, 'image_path' => 'cars/1/4.jpg'],
            ['car_id' => 1, 'image_path' => 'cars/1/5.jpg'],
            ['car_id' => 1, 'image_path' => 'cars/1/6.jpg'],
            
            // Lexus IS200
            ['car_id' => 2, 'image_path' => 'cars/2/1.jpg'],
            ['car_id' => 2, 'image_path' => 'cars/2/2.jpg'],
            ['car_id' => 2, 'image_path' => 'cars/2/3.jpg'],
            ['car_id' => 2, 'image_path' => 'cars/2/4.jpg'],
            ['car_id' => 2, 'image_path' => 'cars/2/5.jpg'],
            
            // Mercedes E220
            ['car_id' => 3, 'image_path' => 'cars/3/1.jpg'],
            ['car_id' => 3, 'image_path' => 'cars/3/2.jpg'],
            
            // VW Passat B5
            ['car_id' => 4, 'image_path' => 'cars/4/1.jpg'],
            ['car_id' => 4, 'image_path' => 'cars/4/2.jpg'],
            
            // Audi A4 B6
            ['car_id' => 5, 'image_path' => 'cars/5/1.jpg'],
            ['car_id' => 5, 'image_path' => 'cars/5/2.jpg'],

            // Toyota Camry
            ['car_id' => 6, 'image_path' => 'cars/6/1.jpg'],
            ['car_id' => 6, 'image_path' => 'cars/6/2.jpg'],

            // Honda Accord
            ['car_id' => 7, 'image_path' => 'cars/7/1.jpg'],
            ['car_id' => 7, 'image_path' => 'cars/7/2.jpg'],

            // Volvo S60
            ['car_id' => 8, 'image_path' => 'cars/8/1.jpg'],
            ['car_id' => 8, 'image_path' => 'cars/8/2.jpg'],
        ];

        foreach ($images as $img) {
            CarImage::create($img);
        }
    }
}
