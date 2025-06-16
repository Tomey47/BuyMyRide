<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Car;

class CarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cars = [
            [
                'user_id' => 1,
                'make' => 'BMW',
                'model' => '3 Series',
                'year' => 2002,
                'body_type' => 'sedan',
                'transmission' => 'manual',
                'fuel_type' => 'petrol',
                'mileage' => 185000,
                'price' => 4500,
                'color' => 'Melna',
                'description' => 'Labi uzturēts BMW E46 323i ar pilnu servisa vēsturi. Manuālā pārnesumkārba, sporta pakete, sildāmie sēdekļi un panorāmas jumts. Nesen veikta remonta darbi: jauns ķēdes pārnesums, ūdens sūknis un piekare. Auto ir no Vācijas, importēts 2010. gadā. Visi dokumenti kārtībā, reģistrēts Latvijā.',
                'location' => 'Rīga',
                'is_active' => true,
                'is_approved' => true,
            ],
            [
                'user_id' => 1,
                'make' => 'Lexus',
                'model' => 'IS',
                'year' => 2001,
                'body_type' => 'sedan',
                'transmission' => 'manual',
                'fuel_type' => 'petrol',
                'mileage' => 165000,
                'price' => 3800,
                'color' => 'Melna',
                'description' => 'Klasiskais Lexus IS200 ar 2.0L 6-cilindru dzinēju. Manuālā pārnesumkārba, ādas salons un premium skaņas sistēma. Auto ir no Anglijas, importēts 2012. gadā. Zināms ar uzticamību un komfortablu braukšanu. Visi dokumenti kārtībā, reģistrēts Latvijā.',
                'location' => 'Jelgava',
                'is_active' => true,
                'is_approved' => true,
            ],
            [
                'user_id' => 1,
                'make' => 'Mercedes-Benz',
                'model' => 'E-Class',
                'year' => 2005,
                'body_type' => 'sedan',
                'transmission' => 'automatic',
                'fuel_type' => 'diesel',
                'mileage' => 210000,
                'price' => 5500,
                'color' => 'Zila',
                'description' => 'Mercedes E220 CDI ar izcilu degvielas patēriņu. Automātiskā pārnesumkārba, ādas salons un pilna servisa vēsture. Nesen nomainīts ķēdes pārnesums. Auto ir no Vācijas, importēts 2015. gadā. Visi dokumenti kārtībā, reģistrēts Latvijā.',
                'location' => 'Liepāja',
                'is_active' => true,
                'is_approved' => true,
            ],
            [
                'user_id' => 1,
                'make' => 'Volkswagen',
                'model' => 'Passat',
                'year' => 2003,
                'body_type' => 'sedan',
                'transmission' => 'manual',
                'fuel_type' => 'diesel',
                'mileage' => 195000,
                'price' => 3200,
                'color' => 'Metāla',
                'description' => 'VW Passat 1.9 TDI ar leģendāru uzticamību. Manuālā pārnesumkārba, sildāmie sēdekļi un tempomats. Nesen nomainīts sajūgs un ķēdes pārnesums. Auto ir no Vācijas, importēts 2013. gadā. Visi dokumenti kārtībā, reģistrēts Latvijā.',
                'location' => 'Ventspils',
                'is_active' => true,
                'is_approved' => true,
            ],
            [
                'user_id' => 1,
                'make' => 'Audi',
                'model' => 'A4',
                'year' => 2004,
                'body_type' => 'sedan',
                'transmission' => 'manual',
                'fuel_type' => 'petrol',
                'mileage' => 175000,
                'price' => 4200,
                'color' => 'Sarkana',
                'description' => 'Audi A4 1.8T ar quattro pilnpiedziņu. Manuālā pārnesumkārba, sporta piekare un premium salons. Nesen pārbūvēts turbokompresors un nomainīts ķēdes pārnesums. Auto ir no Vācijas, importēts 2014. gadā. Visi dokumenti kārtībā, reģistrēts Latvijā.',
                'location' => 'Daugavpils',
                'is_active' => true,
                'is_approved' => true,
            ],
            [
                'user_id' => 1,
                'make' => 'Toyota',
                'model' => 'Camry',
                'year' => 2006,
                'body_type' => 'sedan',
                'transmission' => 'automatic',
                'fuel_type' => 'petrol',
                'mileage' => 190000,
                'price' => 4800,
                'color' => 'Balta',
                'description' => 'Toyota Camry 2.4 ar izcilu uzticamību. Automātiskā pārnesumkārba, ādas salons un divu zonu klimatkontrole. Pilna servisa vēsture pieejama. Auto ir no Japānas, importēts 2016. gadā. Visi dokumenti kārtībā, reģistrēts Latvijā.',
                'location' => 'Rīga',
                'is_active' => true,
                'is_approved' => true,
            ],
            [
                'user_id' => 1,
                'make' => 'Honda',
                'model' => 'Accord',
                'year' => 2003,
                'body_type' => 'sedan',
                'transmission' => 'manual',
                'fuel_type' => 'petrol',
                'mileage' => 168000,
                'price' => 3500,
                'color' => 'Melna',
                'description' => 'Honda Accord 2.0 ar VTEC dzinēju. Manuālā pārnesumkārba, sporta pakete un premium skaņas sistēma. Nesen nomainīts sajūgs un ķēdes pārnesums. Auto ir no Anglijas, importēts 2013. gadā. Visi dokumenti kārtībā, reģistrēts Latvijā.',
                'location' => 'Jelgava',
                'is_active' => true,
                'is_approved' => true,
            ],
            [
                'user_id' => 1,
                'make' => 'Volvo',
                'model' => 'S60',
                'year' => 2005,
                'body_type' => 'sedan',
                'transmission' => 'automatic',
                'fuel_type' => 'petrol',
                'mileage' => 182000,
                'price' => 4200,
                'color' => 'Metāla',
                'description' => 'Volvo S60 2.4 ar premium drošības funkcijām. Automātiskā pārnesumkārba, ādas salons un sildāmie sēdekļi. Nesen nomainīts ķēdes pārnesums un atjaunināta piekare. Auto ir no Zviedrijas, importēts 2014. gadā. Visi dokumenti kārtībā, reģistrēts Latvijā.',
                'location' => 'Liepāja',
                'is_active' => true,
                'is_approved' => false,
            ],
        ];

        foreach ($cars as $car) {
            Car::create($car);
        }
    }
}
