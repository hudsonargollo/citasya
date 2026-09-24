<?php

use App\Models\Salon;
use App\Models\Address;

$sczTopSalons = [
    1 => [
        'name' => 'Rodolfo Paz Peluquería & Spa',
        'desc' => 'Salón de belleza de alta gama en Equipetrol. Especialistas en corte, colorimetría, peinados y tratamientos capilares.',
        'address' => 'Av. San Martín #1550, Equipetrol, Santa Cruz',
        'lat' => -17.7735,
        'lng' => -63.1972,
        'phone' => '+591 3 342-8890',
    ],
    2 => [
        'name' => 'Gino Dell\'Anno Peluqueros Equipetrol',
        'desc' => 'Estilismo profesional y vanguardia italiana en el corazón de Equipetrol.',
        'address' => 'Calle 8 Este #12, Equipetrol, Santa Cruz',
        'lat' => -17.7718,
        'lng' => -63.1950,
        'phone' => '+591 3 344-5511',
    ],
    3 => [
        'name' => 'Beautique Nail Bar & Spa Urbarí',
        'desc' => 'Especialistas en uñas acrílicas, gel esculpido, manicura spa y pedicura estética.',
        'address' => 'Av. Piraí esq. 2do Anillo, Urbarí, Santa Cruz',
        'lat' => -17.7942,
        'lng' => -63.1965,
        'phone' => '+591 3 355-1290',
    ],
    4 => [
        'name' => 'Glamour Studio Las Palmas',
        'desc' => 'Centro integral de belleza, estética facial y corporal en Las Palmas.',
        'address' => 'Av. Irala #450, Las Palmas, Santa Cruz',
        'lat' => -17.8035,
        'lng' => -63.2015,
        'phone' => '+591 3 333-8901',
    ],
    5 => [
        'name' => 'L\'Essence Spa & Wellness Urubó',
        'desc' => 'Experiencias de relajación, masajes descontracturantes, sauna y circuitos termales en Urubó.',
        'address' => 'Boulevard Las Brisas, Urubó, Santa Cruz',
        'lat' => -17.7620,
        'lng' => -63.2180,
        'phone' => '+591 3 388-7744',
    ],
    6 => [
        'name' => 'Dermoestética Santa Cruz',
        'desc' => 'Clínica de medicina estética, limpieza facial profunda, peeling y rejuvenecimiento.',
        'address' => 'Calle Manuel Ignacio Salvatierra #230, Centro, Santa Cruz',
        'lat' => -17.7845,
        'lng' => -63.1812,
        'phone' => '+591 3 336-4422',
    ],
    7 => [
        'name' => 'The Godfather Barbershop SCZ',
        'desc' => 'Cortes clásicos y modernos para caballeros, perfilado de barba con toalla caliente.',
        'address' => 'Av. San Martín esq. Calle 5, Equipetrol, Santa Cruz',
        'lat' => -17.7760,
        'lng' => -63.1975,
        'phone' => '+591 7 845-9922',
    ],
    8 => [
        'name' => 'Pestañas & Cejas Studio Sirari',
        'desc' => 'Lifting de pestañas, extensiones pelo a pelo y microblading de cejas.',
        'address' => 'Calle Los Claveles #88, Sirari, Santa Cruz',
        'lat' => -17.7705,
        'lng' => -63.1915,
        'phone' => '+591 3 345-2010',
    ],
    9 => [
        'name' => 'Clínica Dental Foianini Dental Care',
        'desc' => 'Odontología estética, blanqueamiento dental láser y ortodoncia invisible.',
        'address' => 'Av. Irala #468, Centro, Santa Cruz',
        'lat' => -17.7890,
        'lng' => -63.1865,
        'phone' => '+591 3 336-2211',
    ],
    10 => [
        'name' => 'Tattoo & Piercing Ventura',
        'desc' => 'Estudio profesional de tatuajes personalizados y piercings higiénicos certificados.',
        'address' => 'Ventura Mall 2do Nivel, Santa Cruz',
        'lat' => -17.7535,
        'lng' => -63.1980,
        'phone' => '+591 7 500-1122',
    ],
];

foreach ($sczTopSalons as $id => $data) {
    $salon = Salon::find($id);
    if (!$salon) continue;

    $salon->name = ['es' => $data['name'], 'en' => $data['name']];
    $salon->description = ['es' => $data['desc'], 'en' => $data['desc']];
    $salon->phone_number = $data['phone'];
    $salon->mobile_number = $data['phone'];
    $salon->accepted = true;
    $salon->available = true;
    $salon->featured = true;

    if ($salon->address) {
        $salon->address->description = $data['address'];
        $salon->address->address = $data['address'];
        $salon->address->latitude = $data['lat'];
        $salon->address->longitude = $data['lng'];
        $salon->address->save();
    } else {
        $addr = Address::create([
            'description' => $data['address'],
            'address' => $data['address'],
            'latitude' => $data['lat'],
            'longitude' => $data['lng'],
            'default' => true,
            'user_id' => 1,
        ]);
        $salon->address_id = $addr->id;
    }
    $salon->save();
    echo "Updated Salon {$id} -> {$data['name']} ({$data['address']})\n";
}

// Ensure default Santa Cruz user location for demo/guest address
$defaultAddr = Address::firstOrCreate(
    ['description' => 'Santa Cruz de la Sierra - Centro'],
    [
        'address' => 'Plaza 24 de Septiembre, Santa Cruz de la Sierra, Bolivia',
        'latitude' => -17.7833,
        'longitude' => -63.1821,
        'default' => true,
        'user_id' => 1,
    ]
);

echo "Santa Cruz de la Sierra geolocation alignment complete for all businesses!\n";
