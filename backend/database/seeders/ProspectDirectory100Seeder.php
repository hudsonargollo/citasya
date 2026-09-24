<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Salon;
use App\Models\Address;
use App\Models\EService;
use App\Models\Category;
use App\Models\User;

class ProspectDirectory100Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonPath = database_path('seeders/businesses_100.json');
        if (!file_exists($jsonPath)) {
            $jsonPath = base_path('database/seeders/businesses_100.json');
        }

        $items = json_decode(file_get_contents($jsonPath), true);
        $admin = User::first() ?? User::where('email', 'hudsonargollo@gmail.com')->first();
        $adminId = $admin ? $admin->id : 1;

        $createdSalons = 0;
        $createdServices = 0;

        foreach ($items as $item) {
            // 1. Address
            $address = Address::create([
                'description' => $item['zone'] . ', Santa Cruz',
                'address' => $item['address'],
                'latitude' => (string)$item['lat'],
                'longitude' => (string)$item['lng'],
                'default' => false,
                'user_id' => $adminId,
            ]);

            // 2. Salon
            $salon = new Salon();
            $salon->setTranslation('name', 'es', $item['name_es']);
            $salon->setTranslation('name', 'en', $item['name_en']);
            $salon->setTranslation('description', 'es', $item['desc_es']);
            $salon->setTranslation('description', 'en', $item['desc_en']);
            $salon->salon_level_id = $item['level_id'];
            $salon->address_id = $address->id;
            $salon->phone_number = $item['phone'];
            $salon->mobile_number = $item['phone'];
            $salon->availability_range = 15.0;
            $salon->available = true;
            $salon->featured = ($item['level_id'] >= 3);
            $salon->accepted = true;
            $salon->curation_status = 'approved';
            $salon->save();

            // Link Admin
            if ($admin) {
                $salon->users()->syncWithoutDetaching([$adminId]);
            }

            // 3. EServices
            foreach ($item['services'] as $svc) {
                $eService = new EService();
                $eService->setTranslation('name', 'es', $svc[0]);
                $eService->setTranslation('name', 'en', $svc[0]);
                $eService->setTranslation('description', 'es', "Servicio profesional en {$item['name_es']}. Reserva tu cita con confirmación inmediata.");
                $eService->setTranslation('description', 'en', "Professional service at {$item['name_en']}. Book your appointment with instant confirmation.");
                $eService->price = (float)$svc[1];
                $eService->discount_price = (float)$svc[1] * 0.9;
                $eService->duration = $svc[2];
                $eService->featured = true;
                $eService->enable_booking = true;
                $eService->available = true;
                $eService->salon_id = $salon->id;
                $eService->save();

                // Attach category
                $eService->categories()->sync([$item['category_id']]);
                $createdServices++;
            }

            $createdSalons++;
        }

        echo "Successfully seeded {$createdSalons} prospect businesses and {$createdServices} bookable services into CitasYa!\n";
    }
}
