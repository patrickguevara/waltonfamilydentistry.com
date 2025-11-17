<?php

namespace Database\Seeders;

use App\Models\OfficeInfo;
use App\Models\Service;
use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Database\Seeder;

class InitialDataSeeder extends Seeder
{
    public function run(): void
    {
        // Create super admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@waltonfamilydentistry.com',
            'password' => bcrypt('password'), // Change this in production
            'role' => 'super_admin',
            'is_active' => true,
        ]);

        // Create office info
        OfficeInfo::create([
            'practice_name' => 'Walton Family Dentistry',
            'phone' => '(512) 953-8362',
            'email' => 'info@waltonfamilydentistry.com',
            'address_line1' => '4100 East 51st Street',
            'city' => 'Austin',
            'state' => 'TX',
            'zip' => '78723',
            'monday_hours' => '8:00 AM - 5:00 PM',
            'tuesday_hours' => '8:00 AM - 5:00 PM',
            'wednesday_hours' => '8:00 AM - 5:00 PM',
            'thursday_hours' => '8:00 AM - 5:00 PM',
            'friday_hours' => '8:00 AM - 2:00 PM',
            'saturday_hours' => 'Closed',
            'sunday_hours' => 'Closed',
            'emergency_message' => 'For dental emergencies outside of office hours, please call our emergency line at (512) 953-8362.',
        ]);

        // Create sample services
        $services = [
            [
                'title' => 'Cleanings & Exams',
                'description' => 'Regular dental cleanings and comprehensive oral examinations to maintain your dental health and catch potential issues early.',
                'order' => 1,
            ],
            [
                'title' => 'Pediatric Dentistry',
                'description' => 'Specialized dental care for children in a comfortable, friendly environment. We help establish good oral health habits from an early age.',
                'order' => 2,
            ],
            [
                'title' => 'Fillings & Restorations',
                'description' => 'Tooth-colored fillings and restorations to repair cavities and damaged teeth while maintaining a natural appearance.',
                'order' => 3,
            ],
            [
                'title' => 'Cosmetic Dentistry',
                'description' => 'Teeth whitening, veneers, and other cosmetic procedures to enhance your smile and boost your confidence.',
                'order' => 4,
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }

        // Create sample team member
        TeamMember::create([
            'name' => 'Dr. Sample Dentist',
            'role' => 'Dentist',
            'credentials' => 'DDS',
            'bio' => 'Dr. Dentist has been providing quality dental care to the Austin community for over 15 years. Committed to patient comfort and using the latest dental technologies.',
            'order' => 1,
            'is_active' => true,
        ]);
    }
}
