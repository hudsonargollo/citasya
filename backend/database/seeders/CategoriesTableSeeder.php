<?php
/*
 * File name: CategoriesTableSeeder.php
 * Project: CitasYa
 */
namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run(): void
    {
        DB::table('categories')->truncate();

        $categories = [
            [
                'id' => 1,
                'name' => json_encode(['es' => 'Barbería y Peluquería', 'en' => 'Barbershop & Hair']),
                'color' => '#ff9f43',
                'description' => json_encode(['es' => 'Cortes, peinados, barba y estilismo profesional.', 'en' => 'Haircuts, styling, beard grooming, and hair care.']),
                'order' => 1,
                'featured' => 1,
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => json_encode(['es' => 'Uñas y Manicura', 'en' => 'Nails & Manicure']),
                'color' => '#0abde3',
                'description' => json_encode(['es' => 'Manicura, pedicura, uñas acrílicas y diseño.', 'en' => 'Manicure, pedicure, nail art, and extensions.']),
                'order' => 2,
                'featured' => 1,
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'name' => json_encode(['es' => 'Cuidado Facial y Piel', 'en' => 'Facial & Skincare']),
                'color' => '#ee5253',
                'description' => json_encode(['es' => 'Limpiezas faciales, tratamientos e hidratación.', 'en' => 'Facials, skin treatments, and rejuvenation.']),
                'order' => 3,
                'featured' => 1,
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'name' => json_encode(['es' => 'Cejas y Pestañas', 'en' => 'Brows & Lashes']),
                'color' => '#10ac84',
                'description' => json_encode(['es' => 'Microblading, lifting, laminado y perfilado.', 'en' => 'Microblading, lash lifts, and brow shaping.']),
                'order' => 4,
                'featured' => 1,
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'name' => json_encode(['es' => 'Spa y Masajes', 'en' => 'Spa & Massage']),
                'color' => '#5f27cd',
                'description' => json_encode(['es' => 'Masajes relajantes, descontracturantes y circuitos de spa.', 'en' => 'Relaxing massages, therapeutic bodywork, and spa packages.']),
                'order' => 5,
                'featured' => 1,
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 6,
                'name' => json_encode(['es' => 'Maquillaje Profesional', 'en' => 'Professional Makeup']),
                'color' => '#ff6b6b',
                'description' => json_encode(['es' => 'Maquillaje para eventos, novias y sesiones fotográficas.', 'en' => 'Event, bridal, and photography makeup.']),
                'order' => 6,
                'featured' => 1,
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 7,
                'name' => json_encode(['es' => 'Estética y Medicina Estética', 'en' => 'Aesthetics & Medical Spa']),
                'color' => '#48dbfb',
                'description' => json_encode(['es' => 'Tratamientos corporales, aparatología y estética avanzada.', 'en' => 'Body sculpting, advanced aesthetic treatments.']),
                'order' => 7,
                'featured' => 1,
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 8,
                'name' => json_encode(['es' => 'Salud y Consultas Médicas', 'en' => 'Health & Clinic Consultations']),
                'color' => '#1dd1a1',
                'description' => json_encode(['es' => 'Citas médicas, nutrición, fisioterapia y odontología.', 'en' => 'Medical appointments, nutrition, physio, and dental.']),
                'order' => 8,
                'featured' => 1,
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 9,
                'name' => json_encode(['es' => 'Tatuajes y Piercing', 'en' => 'Tattoo & Piercing']),
                'color' => '#341f97',
                'description' => json_encode(['es' => 'Tatuajes artísticos y colocación de piercings.', 'en' => 'Custom tattoos, touch-ups, and piercings.']),
                'order' => 9,
                'featured' => 1,
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 10,
                'name' => json_encode(['es' => 'Bienestar y Terapias Holísticas', 'en' => 'Wellness & Holistic Care']),
                'color' => '#f368e0',
                'description' => json_encode(['es' => 'Reiki, acupuntura, yoga y terapias integrales.', 'en' => 'Acupuncture, mindfulness, and holistic wellness.']),
                'order' => 10,
                'featured' => 1,
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 11,
                'name' => json_encode(['es' => 'Fitness y Entrenamiento', 'en' => 'Fitness & Personal Training']),
                'color' => '#ff9f1a',
                'description' => json_encode(['es' => 'Entrenadores personales, clases privadas y pilates.', 'en' => 'Personal coaching, private sessions, and pilates.']),
                'order' => 11,
                'featured' => 1,
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 12,
                'name' => json_encode(['es' => 'Cuidado de Mascotas', 'en' => 'Pet Grooming & Vet']),
                'color' => '#2e86de',
                'description' => json_encode(['es' => 'Peluquería canina, baño y consultas veterinarias.', 'en' => 'Pet grooming, spa, and veterinary appointments.']),
                'order' => 12,
                'featured' => 1,
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('categories')->insert($categories);
    }
}
