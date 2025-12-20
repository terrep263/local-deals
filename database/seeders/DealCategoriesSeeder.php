<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DealCategoriesSeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'category_name' => 'Restaurants',
                'category_slug' => 'restaurants',
                'description' => 'Dining deals from local restaurants, cafes, and eateries.',
                'category_icon' => 'flaticon-cutlery',
                'color' => '#ef4444',
                'is_featured' => 1,
                'status' => 1,
                'sort_order' => 1,
            ],
            [
                'category_name' => 'Bars & Nightlife',
                'category_slug' => 'bars-nightlife',
                'description' => 'Happy hour specials, drink deals, and entertainment.',
                'category_icon' => 'flaticon-wine-glass',
                'color' => '#8b5cf6',
                'is_featured' => 0,
                'status' => 1,
                'sort_order' => 2,
            ],
            [
                'category_name' => 'Coffee & Bakeries',
                'category_slug' => 'coffee-bakeries',
                'description' => 'Coffee shops, bakeries, and sweet treats.',
                'category_icon' => 'flaticon-coffee-cup',
                'color' => '#78350f',
                'is_featured' => 0,
                'status' => 1,
                'sort_order' => 3,
            ],
            [
                'category_name' => 'Health & Fitness',
                'category_slug' => 'health-fitness',
                'description' => 'Gyms, fitness classes, personal training, and wellness.',
                'category_icon' => 'flaticon-dumbbell',
                'color' => '#10b981',
                'is_featured' => 1,
                'status' => 1,
                'sort_order' => 4,
            ],
            [
                'category_name' => 'Spa & Beauty',
                'category_slug' => 'spa-beauty',
                'description' => 'Spa treatments, massages, facials, and beauty services.',
                'category_icon' => 'flaticon-spa',
                'color' => '#ec4899',
                'is_featured' => 1,
                'status' => 1,
                'sort_order' => 5,
            ],
            [
                'category_name' => 'Hair & Nails',
                'category_slug' => 'hair-nails',
                'description' => 'Hair salons, barbershops, and nail services.',
                'category_icon' => 'flaticon-scissors',
                'color' => '#f472b6',
                'is_featured' => 0,
                'status' => 1,
                'sort_order' => 6,
            ],
            [
                'category_name' => 'Things to Do',
                'category_slug' => 'things-to-do',
                'description' => 'Activities, attractions, tours, and experiences.',
                'category_icon' => 'flaticon-ticket',
                'color' => '#f97316',
                'is_featured' => 1,
                'status' => 1,
                'sort_order' => 7,
            ],
            [
                'category_name' => 'Events & Tickets',
                'category_slug' => 'events-tickets',
                'description' => 'Concerts, shows, festivals, and event tickets.',
                'category_icon' => 'flaticon-calendar',
                'color' => '#6366f1',
                'is_featured' => 0,
                'status' => 1,
                'sort_order' => 8,
            ],
            [
                'category_name' => 'Sports & Recreation',
                'category_slug' => 'sports-recreation',
                'description' => 'Golf, water sports, outdoor activities.',
                'category_icon' => 'flaticon-football',
                'color' => '#22c55e',
                'is_featured' => 0,
                'status' => 1,
                'sort_order' => 9,
            ],
            [
                'category_name' => 'Automotive',
                'category_slug' => 'automotive',
                'description' => 'Auto repair, oil changes, detailing, car services.',
                'category_icon' => 'flaticon-car',
                'color' => '#3b82f6',
                'is_featured' => 1,
                'status' => 1,
                'sort_order' => 10,
            ],
            [
                'category_name' => 'Home Services',
                'category_slug' => 'home-services',
                'description' => 'Cleaning, lawn care, repairs, home improvement.',
                'category_icon' => 'flaticon-house',
                'color' => '#14b8a6',
                'is_featured' => 1,
                'status' => 1,
                'sort_order' => 11,
            ],
            [
                'category_name' => 'Professional Services',
                'category_slug' => 'professional-services',
                'description' => 'Legal, financial, insurance, business services.',
                'category_icon' => 'flaticon-suitcase',
                'color' => '#64748b',
                'is_featured' => 0,
                'status' => 1,
                'sort_order' => 12,
            ],
            [
                'category_name' => 'Pet Services',
                'category_slug' => 'pet-services',
                'description' => 'Grooming, boarding, vet services, pet supplies.',
                'category_icon' => 'flaticon-pet',
                'color' => '#a855f7',
                'is_featured' => 0,
                'status' => 1,
                'sort_order' => 13,
            ],
            [
                'category_name' => 'Shopping',
                'category_slug' => 'shopping',
                'description' => 'Retail stores, boutiques, merchandise deals.',
                'category_icon' => 'flaticon-shopping-bag',
                'color' => '#f43f5e',
                'is_featured' => 1,
                'status' => 1,
                'sort_order' => 14,
            ],
            [
                'category_name' => 'Electronics',
                'category_slug' => 'electronics',
                'description' => 'Computers, phones, gadgets, tech deals.',
                'category_icon' => 'flaticon-computer',
                'color' => '#0ea5e9',
                'is_featured' => 0,
                'status' => 1,
                'sort_order' => 15,
            ],
            [
                'category_name' => 'Travel & Hotels',
                'category_slug' => 'travel-hotels',
                'description' => 'Hotels, resorts, vacation rentals, travel packages.',
                'category_icon' => 'flaticon-airplane',
                'color' => '#06b6d4',
                'is_featured' => 0,
                'status' => 1,
                'sort_order' => 16,
            ],
            [
                'category_name' => 'Real Estate',
                'category_slug' => 'real-estate',
                'description' => 'Property deals, rentals, real estate services.',
                'category_icon' => 'flaticon-building',
                'color' => '#059669',
                'is_featured' => 0,
                'status' => 1,
                'sort_order' => 17,
            ],
            [
                'category_name' => 'Education & Classes',
                'category_slug' => 'education-classes',
                'description' => 'Courses, tutoring, lessons, educational services.',
                'category_icon' => 'flaticon-mortarboard',
                'color' => '#eab308',
                'is_featured' => 0,
                'status' => 1,
                'sort_order' => 18,
            ],
            [
                'category_name' => 'Medical & Dental',
                'category_slug' => 'medical-dental',
                'description' => 'Healthcare, dental, vision, medical services.',
                'category_icon' => 'flaticon-stethoscope',
                'color' => '#dc2626',
                'is_featured' => 0,
                'status' => 1,
                'sort_order' => 19,
            ],
            [
                'category_name' => 'Other',
                'category_slug' => 'other',
                'description' => 'Miscellaneous deals and services.',
                'category_icon' => 'flaticon-tag',
                'color' => '#71717a',
                'is_featured' => 0,
                'status' => 1,
                'sort_order' => 99,
            ],
        ];

        foreach ($categories as $category) {
            $exists = DB::table('categories')->where('category_slug', $category['category_slug'])->first();

            if ($exists) {
                // Preserve existing icon if already set
                if (!empty($exists->category_icon) && isset($category['category_icon'])) {
                    unset($category['category_icon']);
                }
                DB::table('categories')->where('category_slug', $category['category_slug'])->update($category);
                $this->command->info("Updated: {$category['category_name']}");
            } else {
                DB::table('categories')->insert($category);
                $this->command->info("Created: {$category['category_name']}");
            }
        }

        $this->command->info('Deal categories seeded successfully!');
    }
}

