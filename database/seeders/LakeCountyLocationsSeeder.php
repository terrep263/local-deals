<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LakeCountyLocationsSeeder extends Seeder
{
    public function run()
    {
        $locations = [
            [
                'name' => 'Leesburg',
                'slug' => 'leesburg',
                'county' => 'Lake County',
                'state' => 'FL',
                'zip_codes' => json_encode(['34748', '34749', '34788', '34789']),
                'latitude' => 28.8108,
                'longitude' => -81.8779,
                'population' => 27823,
                'description' => 'Historic downtown, waterfront living, and vibrant community events.',
                'is_featured' => 1,
                'status' => 1,
                'sort_order' => 1,
            ],
            [
                'name' => 'Clermont',
                'slug' => 'clermont',
                'county' => 'Lake County',
                'state' => 'FL',
                'zip_codes' => json_encode(['34711', '34712', '34713', '34714', '34715']),
                'latitude' => 28.5494,
                'longitude' => -81.7729,
                'population' => 43021,
                'description' => 'Known as the "Gem of the Hills" with rolling terrain and outdoor activities.',
                'is_featured' => 1,
                'status' => 1,
                'sort_order' => 2,
            ],
            [
                'name' => 'Mount Dora',
                'slug' => 'mount-dora',
                'county' => 'Lake County',
                'state' => 'FL',
                'zip_codes' => json_encode(['32756', '32757']),
                'latitude' => 28.8025,
                'longitude' => -81.6445,
                'population' => 14559,
                'description' => 'Charming downtown with antique shops, festivals, and lakefront views.',
                'is_featured' => 1,
                'status' => 1,
                'sort_order' => 3,
            ],
            [
                'name' => 'Tavares',
                'slug' => 'tavares',
                'county' => 'Lake County',
                'state' => 'FL',
                'zip_codes' => json_encode(['32778']),
                'latitude' => 28.8042,
                'longitude' => -81.7256,
                'population' => 17900,
                'description' => 'America\'s Seaplane City - Lake County seat with waterfront dining.',
                'is_featured' => 1,
                'status' => 1,
                'sort_order' => 4,
            ],
            [
                'name' => 'Eustis',
                'slug' => 'eustis',
                'county' => 'Lake County',
                'state' => 'FL',
                'zip_codes' => json_encode(['32726', '32727']),
                'latitude' => 28.8528,
                'longitude' => -81.6853,
                'population' => 21377,
                'description' => 'Historic downtown with GeorgeFest and vibrant arts scene.',
                'is_featured' => 0,
                'status' => 1,
                'sort_order' => 5,
            ],
            [
                'name' => 'Groveland',
                'slug' => 'groveland',
                'county' => 'Lake County',
                'state' => 'FL',
                'zip_codes' => json_encode(['34736', '34737']),
                'latitude' => 28.5581,
                'longitude' => -81.8512,
                'population' => 17846,
                'description' => 'Fast-growing community near major attractions.',
                'is_featured' => 0,
                'status' => 1,
                'sort_order' => 6,
            ],
            [
                'name' => 'Minneola',
                'slug' => 'minneola',
                'county' => 'Lake County',
                'state' => 'FL',
                'zip_codes' => json_encode(['34715']),
                'latitude' => 28.5744,
                'longitude' => -81.7462,
                'population' => 12254,
                'description' => 'Family-friendly community with excellent schools.',
                'is_featured' => 0,
                'status' => 1,
                'sort_order' => 7,
            ],
            [
                'name' => 'Lady Lake',
                'slug' => 'lady-lake',
                'county' => 'Lake County',
                'state' => 'FL',
                'zip_codes' => json_encode(['32159', '32162']),
                'latitude' => 28.9175,
                'longitude' => -81.9231,
                'population' => 16158,
                'description' => 'Gateway to The Villages with active lifestyle options.',
                'is_featured' => 0,
                'status' => 1,
                'sort_order' => 8,
            ],
            [
                'name' => 'Fruitland Park',
                'slug' => 'fruitland-park',
                'county' => 'Lake County',
                'state' => 'FL',
                'zip_codes' => json_encode(['34731']),
                'latitude' => 28.8617,
                'longitude' => -81.9064,
                'population' => 5467,
                'description' => 'Small-town charm with convenient location.',
                'is_featured' => 0,
                'status' => 1,
                'sort_order' => 9,
            ],
            [
                'name' => 'Umatilla',
                'slug' => 'umatilla',
                'county' => 'Lake County',
                'state' => 'FL',
                'zip_codes' => json_encode(['32784']),
                'latitude' => 28.9292,
                'longitude' => -81.6656,
                'population' => 4056,
                'description' => 'Gateway to the Ocala National Forest.',
                'is_featured' => 0,
                'status' => 1,
                'sort_order' => 10,
            ],
            [
                'name' => 'Mascotte',
                'slug' => 'mascotte',
                'county' => 'Lake County',
                'state' => 'FL',
                'zip_codes' => json_encode(['34753']),
                'latitude' => 28.5781,
                'longitude' => -81.8867,
                'population' => 6534,
                'description' => 'Growing community with rural character.',
                'is_featured' => 0,
                'status' => 1,
                'sort_order' => 11,
            ],
            [
                'name' => 'Howey-in-the-Hills',
                'slug' => 'howey-in-the-hills',
                'county' => 'Lake County',
                'state' => 'FL',
                'zip_codes' => json_encode(['34737']),
                'latitude' => 28.7178,
                'longitude' => -81.7731,
                'population' => 1866,
                'description' => 'Home to Mission Inn Resort and rolling hills.',
                'is_featured' => 0,
                'status' => 1,
                'sort_order' => 12,
            ],
            [
                'name' => 'Montverde',
                'slug' => 'montverde',
                'county' => 'Lake County',
                'state' => 'FL',
                'zip_codes' => json_encode(['34756']),
                'latitude' => 28.6003,
                'longitude' => -81.6837,
                'population' => 1963,
                'description' => 'Home to Montverde Academy and lakeside living.',
                'is_featured' => 0,
                'status' => 1,
                'sort_order' => 13,
            ],
            [
                'name' => 'Astatula',
                'slug' => 'astatula',
                'county' => 'Lake County',
                'state' => 'FL',
                'zip_codes' => json_encode(['34705']),
                'latitude' => 28.7128,
                'longitude' => -81.7331,
                'population' => 2178,
                'description' => 'Quiet lakeside community.',
                'is_featured' => 0,
                'status' => 1,
                'sort_order' => 14,
            ],
            [
                'name' => 'The Villages (Lake County)',
                'slug' => 'the-villages-lake',
                'county' => 'Lake County',
                'state' => 'FL',
                'zip_codes' => json_encode(['32162', '32163']),
                'latitude' => 28.9005,
                'longitude' => -81.9831,
                'population' => 79000,
                'description' => 'Active adult community spanning three counties.',
                'is_featured' => 1,
                'status' => 1,
                'sort_order' => 15,
            ],
        ];

        foreach ($locations as $location) {
            $location['created_at'] = now();
            $location['updated_at'] = now();

            $exists = DB::table('cities')->where('slug', $location['slug'])->first();

            if ($exists) {
                DB::table('cities')->where('slug', $location['slug'])->update($location);
                $this->command->info("Updated: {$location['name']}");
            } else {
                DB::table('cities')->insert($location);
                $this->command->info("Created: {$location['name']}");
            }
        }

        $this->command->info('Lake County locations seeded successfully!');
    }
}

