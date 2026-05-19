<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Aircraft;
use App\Models\Flight;
use App\Models\Medical;
use App\Models\Mro;
use App\Models\Organization;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Admin User
        User::factory()->create([
            'name' => 'KCAA Admin',
            'email' => 'admin@caa.com',
            'password' => bcrypt('figureitout'),
            'role' => 'admin',
            'status' => 'active',
        ]);

        // 2. Seed Aircraft Table (Kenyan Registration Prefix: 5Y-)
        Aircraft::create([
            'manufacturer' => 'Boeing',
            'model' => '787-8 Dreamliner',
            'serial_number' => 'MSN-36124',
            'year' => 2014,
            'registration_mark' => 'KZA', // 5Y-KZA
            'owner_name' => 'Kenya Airways',
            'owner_address' => 'Airport North Road, Embakasi, Nairobi, Kenya',
            'owner_email' => 'operations@kenya-airways.com',
            'owner_phone' => '+254 20 6422000',
            'status' => 'active',
            'issue_date' => '2014-04-15',
            'created_at' => now()->subMonths(5),
        ]);

        Aircraft::create([
            'manufacturer' => 'Embraer',
            'model' => 'E190-100IGW',
            'serial_number' => 'MSN-19000523',
            'year' => 2012,
            'registration_mark' => 'KYR', // 5Y-KYR
            'owner_name' => 'Kenya Airways',
            'owner_address' => 'Airport North Road, Embakasi, Nairobi, Kenya',
            'owner_email' => 'operations@kenya-airways.com',
            'owner_phone' => '+254 20 6422000',
            'status' => 'active',
            'issue_date' => '2012-09-10',
            'created_at' => now()->subMonths(4),
        ]);

        Aircraft::create([
            'manufacturer' => 'Bombardier',
            'model' => 'DHC-8-402 Dash 8',
            'serial_number' => 'MSN-4512',
            'year' => 2016,
            'registration_mark' => 'SFL', // 5Y-SFL
            'owner_name' => 'Safarilink Aviation',
            'owner_address' => 'Wilson Airport, Langata Road, Nairobi, Kenya',
            'owner_email' => 'info@flysafarilink.com',
            'owner_phone' => '+254 20 6000777',
            'status' => 'pending', // Pending renewal
            'issue_date' => '2026-05-12',
            'created_at' => now()->subMonths(1),
        ]);

        Aircraft::create([
            'manufacturer' => 'Cessna',
            'model' => '208 Caravan',
            'serial_number' => 'C208-0952',
            'year' => 2015,
            'registration_mark' => 'AKY', // 5Y-AKY
            'owner_name' => 'Airkenya Express',
            'owner_address' => 'Wilson Airport, Nairobi, Kenya',
            'owner_email' => 'res@airkenya.com',
            'owner_phone' => '+254 20 3916000',
            'status' => 'suspended',
            'issue_date' => '2025-01-20',
            'created_at' => now()->subMonths(3),
        ]);

        Aircraft::create([
            'manufacturer' => 'De Havilland',
            'model' => 'DHC-6 Twin Otter',
            'serial_number' => 'TO-845',
            'year' => 2018,
            'registration_mark' => 'RNG', // 5Y-RNG
            'owner_name' => 'Renegade Air',
            'owner_address' => 'Wilson Airport, Langata Road, Nairobi, Kenya',
            'owner_email' => 'ops@renegadeair.com',
            'owner_phone' => '+254 703 048000',
            'status' => 'active',
            'issue_date' => '2021-11-05',
            'created_at' => now()->subMonths(2),
        ]);

        Aircraft::create([
            'manufacturer' => 'Cessna',
            'model' => '172 Skyhawk',
            'serial_number' => 'C172-29381',
            'year' => 2020,
            'registration_mark' => 'KSF', // 5Y-KSF
            'owner_name' => 'Kenya School of Flying',
            'owner_address' => 'Wilson Airport, Nairobi, Kenya',
            'owner_email' => 'info@flyingschool.co.ke',
            'owner_phone' => '+254 20 6007892',
            'status' => 'pending',
            'issue_date' => null,
            'created_at' => now(),
        ]);

        // 3. Seed Flights (Personnel Licensing)
        Flight::create([
            'first_name' => 'David',
            'last_name' => 'Kipchumba',
            'dob' => '1983-04-12',
            'nationality' => 'Kenyan',
            'address' => 'Karen, Nairobi',
            'application_type' => 'Initial Issue',
            'license_category' => 'ATPL (Airline Transport Pilot License)',
            'total_hours' => 6200,
            'medical_cert' => 'Class 1',
            'status' => 'active',
            'issue_date' => '2020-05-18',
            'created_at' => now()->subMonths(5),
        ]);

        Flight::create([
            'first_name' => 'Sarah',
            'last_name' => 'Wanjiku',
            'dob' => '1991-08-25',
            'nationality' => 'Kenyan',
            'address' => 'Westlands, Nairobi',
            'application_type' => 'Renewal',
            'license_category' => 'CPL (Commercial Pilot License)',
            'total_hours' => 1450,
            'medical_cert' => 'Class 1',
            'status' => 'active',
            'issue_date' => '2023-10-14',
            'created_at' => now()->subMonths(3),
        ]);

        Flight::create([
            'first_name' => 'John',
            'last_name' => 'Mwangi',
            'dob' => '1997-03-30',
            'nationality' => 'Kenyan',
            'address' => 'Langata, Nairobi',
            'application_type' => 'Initial Issue',
            'license_category' => 'PPL (Private Pilot License)',
            'total_hours' => 180,
            'medical_cert' => 'Class 2',
            'status' => 'active',
            'issue_date' => '2025-03-05',
            'created_at' => now()->subMonths(1),
        ]);

        Flight::create([
            'first_name' => 'Grace',
            'last_name' => 'Anyango',
            'dob' => '1994-11-05',
            'nationality' => 'Kenyan',
            'address' => 'Nyali, Mombasa',
            'application_type' => 'Addition of Rating',
            'license_category' => 'ATC (Air Traffic Controller)',
            'total_hours' => null,
            'medical_cert' => 'Class 3',
            'status' => 'pending',
            'issue_date' => null,
            'created_at' => now(),
        ]);

        Flight::create([
            'first_name' => 'Patrick',
            'last_name' => 'Omondi',
            'dob' => '1976-06-20',
            'nationality' => 'Kenyan',
            'address' => 'Milimani, Kisumu',
            'application_type' => 'Renewal',
            'license_category' => 'AME (Aircraft Maintenance Engineer)',
            'total_hours' => null,
            'medical_cert' => 'Not Applicable',
            'status' => 'expired',
            'issue_date' => '2021-03-10',
            'created_at' => now()->subMonths(4),
        ]);

        // 4. Seed Medical Certificates
        Medical::create([
            'full_name' => 'James Kamau',
            'license_number' => 'PL-93821',
            'medical_class' => 'Class 1',
            'dob' => '1987-12-15',
            'status' => 'pending',
            'issue_date' => null,
            'created_at' => now(),
        ]);

        Medical::create([
            'full_name' => 'Lucy Wambui',
            'license_number' => 'PL-18472',
            'medical_class' => 'Class 1',
            'dob' => '1992-05-18',
            'status' => 'approved',
            'issue_date' => '2026-05-02',
            'created_at' => now()->subMonths(1),
        ]);

        Medical::create([
            'full_name' => 'Erick Kiprop',
            'license_number' => 'PL-47291',
            'medical_class' => 'Class 2',
            'dob' => '1995-09-22',
            'status' => 'approved',
            'issue_date' => '2026-04-18',
            'created_at' => now()->subMonths(2),
        ]);

        Medical::create([
            'full_name' => 'Hellen Mutua',
            'license_number' => 'PL-08241',
            'medical_class' => 'Class 3',
            'dob' => '1984-01-30',
            'status' => 'rejected',
            'issue_date' => null,
            'created_at' => now()->subMonths(3),
        ]);

        // 5. Seed MRO Facilities
        Mro::create([
            'certificate_no' => 'MRO/KE/001',
            'name' => 'Kenya Airways Technical',
            'address' => 'Hangar 1, JKIA, Nairobi',
            'country' => 'Kenya',
            'application_type' => 'Renewal',
            'ratings' => ['A1', 'B1', 'C'],
            'accountable_manager' => 'Peter Mwangi',
            'quality_manager' => 'Rose Mutua',
            'status' => 'approved',
            'expiry_date' => '2027-09-30',
            'created_at' => now()->subMonths(5),
        ]);

        Mro::create([
            'certificate_no' => 'MRO/KE/045',
            'name' => 'Nairobi Aviation Maintenance Services',
            'address' => 'Wilson Airport Hangar Area, Nairobi',
            'country' => 'Kenya',
            'application_type' => 'Initial Grant',
            'ratings' => ['A1', 'C'],
            'accountable_manager' => 'Francis Njoroge',
            'quality_manager' => 'Jane Koech',
            'status' => 'pending', // Pending Audit
            'expiry_date' => '2026-07-15',
            'created_at' => now(),
        ]);

        Mro::create([
            'certificate_no' => 'MRO/TZ/008',
            'name' => 'Kilimanjaro Aero Maintenance',
            'address' => 'JNIA Airport, Dar es Salaam',
            'country' => 'Tanzania',
            'application_type' => 'Renewal',
            'ratings' => ['A1'],
            'accountable_manager' => 'John Tembo',
            'quality_manager' => 'Sarah Mrema',
            'status' => 'expired',
            'expiry_date' => '2026-01-20',
            'created_at' => now()->subMonths(2),
        ]);

        // 6. Seed Organizations
        Organization::create([
            'name' => 'Kenya School of Flying',
            'type' => 'Training Institute',
            'status' => 'active',
            'issue_date' => '2012-06-18',
            'created_at' => now()->subMonths(5),
        ]);

        Organization::create([
            'name' => 'Safarilink Aviation',
            'type' => 'Service Provider',
            'status' => 'active',
            'issue_date' => '2008-04-10',
            'created_at' => now()->subMonths(4),
        ]);

        Organization::create([
            'name' => 'Kenya Airways',
            'type' => 'Service Provider',
            'status' => 'active',
            'issue_date' => '1977-01-22',
            'created_at' => now()->subMonths(3),
        ]);

        Organization::create([
            'name' => 'East African Aviation Academy',
            'type' => 'Training Institute',
            'status' => 'pending',
            'issue_date' => null,
            'created_at' => now(),
        ]);
    }
}
