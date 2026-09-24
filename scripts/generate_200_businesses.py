import json
import random

# Categories matching CitasYa 12 core verticals
categories = {
    1: {"name_es": "Barbería y Peluquería", "name_en": "Barbershop & Hair", "services": [
        ("Corte Degradé Clásico", 60, "00:40"),
        ("Perfilado de Barba con Toalla Caliente", 45, "00:30"),
        ("Corte Ejecutivo Tijera", 80, "00:45"),
        ("Diseño Hair Tattoo & Freestyle", 90, "00:50"),
        ("Tratamiento Anticaída Capilar", 130, "00:40"),
        ("Afeitado Tradicional Navaja", 50, "00:30"),
        ("Alisado Queratina Masculino", 140, "01:00"),
        ("Color & Camuflaje de Canas", 95, "00:45")
    ]},
    2: {"name_es": "Uñas y Manicura", "name_en": "Nails & Manicure", "services": [
        ("Manicura Rusa con Esmaltado Gel", 130, "01:15"),
        ("Uñas Esculpidas Acrílicas", 220, "01:45"),
        ("Kapping Fortalecedor Gel", 110, "01:00"),
        ("Pedicura Spa Jelly Relax", 150, "01:10"),
        ("Soft Gel Nails con Diseño", 170, "01:30"),
        ("Dipping Powder Manicura", 140, "01:00"),
        ("Retiro Seguro + Nutrición", 60, "00:35")
    ]},
    3: {"name_es": "Cuidado Facial y Piel", "name_en": "Facial & Skincare", "services": [
        ("Limpieza Profunda con Hidrafacial", 280, "01:15"),
        ("Peeling Químico Celular Renovador", 340, "00:50"),
        ("Dermapen con Ácido Hialurónico", 320, "01:00"),
        ("Protocolo Anti-Acné y Fototerapia", 250, "01:00"),
        ("Facial Glass Skin & Glow", 290, "01:15"),
        ("Masaje Kobido Lifting Japonés", 210, "00:50"),
        ("Microdermoabrasión con Puntas Diamante", 190, "00:45")
    ]},
    4: {"name_es": "Cejas y Pestañas", "name_en": "Brows & Lashes", "services": [
        ("Lash Lifting + Tinte Queratina", 160, "01:00"),
        ("Laminado de Cejas HD + Perfilado", 140, "00:50"),
        ("Extensiones Volumen Ruso", 280, "02:00"),
        ("Pestañas Híbridas Efecto Natural", 220, "01:30"),
        ("Diseño de Cejas con Henna Orgánica", 90, "00:40"),
        ("Microblading Pelo a Pelo", 550, "02:00"),
        ("Depilación con Hilo Hindú", 50, "00:25")
    ]},
    5: {"name_es": "Spa y Masajes", "name_en": "Spa & Massage", "services": [
        ("Masaje con Piedras Volcánicas Calientes", 280, "01:20"),
        ("Masaje Descontracturante Profundo", 230, "01:00"),
        ("Circuito Spa Relax para Parejas", 560, "02:00"),
        ("Masaje Tailandés Tradicional", 260, "01:15"),
        ("Drenaje Linfático Postoperatorio", 200, "01:00"),
        ("Bambuterapia Descontracturante", 240, "01:10"),
        ("Exfoliación Corporal con Sales de Uyuni", 220, "00:50")
    ]},
    6: {"name_es": "Maquillaje Profesional", "name_en": "Professional Makeup", "services": [
        ("Maquillaje Social de Noche con Pestañas", 250, "01:15"),
        ("Maquillaje Novia VIP con Prueba", 750, "02:30"),
        ("Maquillaje Piel Blindada Waterproof", 280, "01:20"),
        ("Peinado de Gala / Ondas al Agua", 180, "01:00"),
        ("Combo Graduación (MakeUp + Peinado)", 420, "02:00"),
        ("Maquillaje Editorial para Fotografía", 300, "01:15")
    ]},
    7: {"name_es": "Estética y Medicina Estética", "name_en": "Aesthetics & Medical Spa", "services": [
        ("Aplicación Toxina Botulínica (3 Zonas)", 1200, "00:45"),
        ("Relleno de Labios Ácido Hialurónico", 1400, "00:50"),
        ("Rinomodelación No Quirúrgica", 1600, "00:45"),
        ("Depilación Láser Soprano Ice (Sesión)", 550, "01:15"),
        ("Bioestimulador de Colágeno Radiesse", 2100, "01:00"),
        ("Criolipólisis Médica Reductora", 700, "01:15"),
        ("Hilos Tensores de Tracción", 1800, "01:15")
    ]},
    8: {"name_es": "Salud y Consultas Médicas", "name_en": "Health & Clinic Consultations", "services": [
        ("Consulta Médica Especializada", 250, "00:40"),
        ("Diseño Digital de Sonrisa Carillas E-Max", 1800, "01:30"),
        ("Blanqueamiento Dental Láser Led", 450, "01:00"),
        ("Limpieza Dental Profilaxis con Ultrasonido", 180, "00:45"),
        ("Consulta Pediátrica Niño Sano", 200, "00:40"),
        ("Sesión de Fisioterapia & Kinesiología", 150, "00:50"),
        ("Ecografía General de Diagnóstico", 280, "00:30"),
        ("Alineadores Invisibles Ortodoncia", 3500, "01:00")
    ]},
    9: {"name_es": "Tatuajes y Piercing", "name_en": "Tattoo & Piercing", "services": [
        ("Tatuaje Minimalista / Fine Line", 250, "01:00"),
        ("Tatuaje Mediano Personalizado", 550, "02:30"),
        ("Sesión Tatuaje Realismo / Manga (4h)", 1200, "04:00"),
        ("Piercing Titania Grado Implante", 150, "00:30"),
        ("Perforación Nostril / Hélix", 120, "00:25"),
        ("Cover Up & Restauración Tatuajes", 700, "03:00")
    ]},
    10: {"name_es": "Bienestar y Terapias Holísticas", "name_en": "Wellness & Holistic Care", "services": [
        ("Terapia de Cuencos Tibetanos & Sonido", 180, "01:00"),
        ("Sesión de Acupuntura Tradicional China", 220, "01:00"),
        ("Alineación de Chakras & Reiki", 160, "00:50"),
        ("Sesión Privada Yoga Terapéutico", 150, "01:00"),
        ("Lectura Carta Astral & Coaching Holístico", 250, "01:15"),
        ("Biomagnetismo Médico Holístico", 200, "01:00")
    ]},
    11: {"name_es": "Fitness y Entrenamiento", "name_en": "Fitness & Personal Training", "services": [
        ("Sesión Personalizada con Entrenador", 120, "01:00"),
        ("Clase Reformer Pilates Studio", 140, "00:55"),
        ("Evaluación Cineantropométrica InBody", 160, "00:35"),
        ("Entrenamiento Funcional HIIT", 90, "00:50"),
        ("Plan Nutricional Deportivo Mensual", 350, "00:45"),
        ("Sesión de Boxeo & Acondicionamiento", 110, "01:00")
    ]},
    12: {"name_es": "Cuidado de Mascotas", "name_en": "Pet Grooming & Vet", "services": [
        ("Baño Medicado & Corte Spa Canino", 130, "01:15"),
        ("Corte de Raza & Desenredado Pro", 160, "01:30"),
        ("Consulta Veterinaria Integral", 140, "00:40"),
        ("Limpieza Dental Canina No Invasiva", 220, "00:50"),
        ("Desparasitación & Vacunación Anual", 180, "00:30"),
        ("Spa Felino Especializado", 150, "01:00")
    ]}
}

zones = [
    {"name": "Equipetrol", "lat": -17.7715, "lng": -63.1945, "street": "Av. San Martín"},
    {"name": "Sirari", "lat": -17.7698, "lng": -63.1910, "street": "Calle Los Claveles"},
    {"name": "Las Palmas", "lat": -17.8025, "lng": -63.2035, "street": "Av. Las Palmas"},
    {"name": "Urbarí", "lat": -17.7945, "lng": -63.1970, "street": "Av. Piraí"},
    {"name": "Centro Histórico", "lat": -17.7830, "lng": -63.1815, "street": "Calle 24 de Septiembre"},
    {"name": "Av. Banzer Km 3-5", "lat": -17.7510, "lng": -63.1765, "street": "Av. Cristo Redentor"},
    {"name": "Ventura Mall & 4to Anillo", "lat": -17.7580, "lng": -63.1965, "street": "4to Anillo esq. San Martín"},
    {"name": "Los Cusis", "lat": -17.7650, "lng": -63.1725, "street": "Av. Los Cusis"},
    {"name": "Equipetrol Norte / DUO", "lat": -17.7665, "lng": -63.1970, "street": "Torre DUO Empresarial"},
    {"name": "Av. Busch 2do y 3er Anillo", "lat": -17.7780, "lng": -63.1950, "street": "Av. Busch"},
    {"name": "Barrio Hamacas", "lat": -17.7620, "lng": -63.1790, "street": "Calle Beni esq. 3er Anillo"},
    {"name": "Barrio Petrolero", "lat": -17.7890, "lng": -63.1920, "street": "Av. Grigotá"},
    {"name": "Urubó Village", "lat": -17.7625, "lng": -63.2180, "street": "Av. Principal Urubó"},
    {"name": "Villa Fraterna / Santos Dumont", "lat": -17.8110, "lng": -63.1890, "street": "Av. Santos Dumont"},
    {"name": "Av. Monseñor Rivero", "lat": -17.7745, "lng": -63.1830, "street": "Boulevard Monseñor Rivero"}
]

# Business naming templates by category for realistic Santa Cruz local directory
business_names = {
    1: [
        "Barbería República SCZ", "The Barbers Club Equipetrol", "Capital Fade Studio", "Barba Brava Barbershop",
        "Legendary Cuts Santa Cruz", "Imperio Urbano Barber", "Don Vito Peluquería Masculina", "Master Cut Sirari",
        "Vanguardia Barber Lounge", "Golden Scissor Urbarí", "Distrito 7 Barbershop", "El Templo del Barbero",
        "Legacy Men Grooming", "Prime Cuts Ventura", "Urban Kings Barbería", "El Barbero de Sevilla SCZ",
        "Roots Barber Shop", "Top Notch Barber Studio", "Santacruzano Barber House", "Signature Fade & Shave"
    ],
    2: [
        "Studio Nails Las Palmas", "Velvet Manicure & Pedicure", "Nail Lounge Beauty Plaza", "Acrílicas & Co. Equipetrol",
        "Boutique Uñas & Esmalte", "Chic & Shine Nail Bar", "Dulce Manicura SCZ", "Oh My Nails Sirari",
        "Pure Glam Nail Spa", "La Reinita Nails Studio", "Diamond Nails Santa Cruz", "Luxe French & Acrylics",
        "Nail Queen Urbarí", "Color & Arte Manicura", "Pretty Hands & Feet", "Nails Paradise Banzer",
        "Manos Bonitas Studio", "Glossy Nails Equipetrol Norte", "Studio 54 Nail Bar", "Lirio Blanco Nails & Spa"
    ],
    3: [
        "Centro de Estética Facial Dermolife", "HydraSkin Clinic Equipetrol", "Clínica de la Piel Dr. Suárez",
        "Facial Glow Up Sirari", "Dermaclinic Las Palmas", "Renacer Facial Spa Urbarí", "Skin Care Studio SCZ",
        "BioDermis Centro Facial", "Piel de Seda Estética", "Centro Cosmiátrico Avanzado SCZ", "Clínica Antiaging Bolivia",
        "Pureza Facial Studio", "DermaGlow Ventura", "Estética Facial Los Cusis", "Harmonía Facial Clinic",
        "Secretos de Belleza Facial", "Komorebi Skin Sanctuary", "NutriDermis Santa Cruz", "Vital Skin Studio", "Dra. Valeria Rocha Estética"
    ],
    4: [
        "Brows & Lashes Boutique SCZ", "Mirada Impacto Equipetrol", "Lash House Santa Cruz", "La Cejería Sirari",
        "Microblading Art Urbarí", "Divinas Miradas Las Palmas", "The Lash Lounge Ventura", "Cejas Perfectas Hamacas",
        "Volumen & Diseño Pestañas", "Lash Studio 360", "Miradas de Reina", "Brows Master Studio",
        "Lash Couture Equipetrol", "Studio Mirada Encantadora", "Blink & Brow Bar", "Perfección en Cejas SCZ"
    ],
    5: [
        "Santuario Zen Spa Urubó", "Aromas del Oriente Day Spa", "Mandala Spa Equipetrol", "Spa Termal Los Mangales",
        "Aguas del Edén Spa", "Pura Vida Masajes & Relajación", "Kallpa Masajes Terapéuticos", "Tierra Santa Spa Sirari",
        "Lotus Day Spa Las Palmas", "Alivio Muscular & Descontracturante", "Bambú Relax Center", "Nube Spa Experience",
        "Oasis Urbano Masajes", "Equilibrio & Relax SCZ", "Pachamama Spa & Terapia", "Serenidad Spa Hamacas",
        "Harmonie Spa & Sauna", "Vitalis Spa Center"
    ],
    6: [
        "Makeup Studio Carla Vaca", "Atelier de Maquillaje SCZ", "Glamour & Brush Equipetrol", "Makeup Bar Las Palmas",
        "Studio Belleza Nupcial", "Pro Makeup Artist Rossy", "Esencia MakeUp & Hair", "Colorimetría & Glam Studio",
        "Beauty Room Santa Cruz", "Maquillaje de Pasarela SCZ", "Studio Iluminación Pro", "Glow Artists Collective",
        "Novias Radiantes SCZ", "Vogue MakeUp Urbarí", "Studio MakeUp Andrea Paz", "Divas Glam MakeUp"
    ],
    7: [
        "Clínica Estética Bellanova", "MedEsthetic Santa Cruz", "Centro Láser & Belleza Equipetrol",
        "Harmonización & Antiaging Sirari", "Dr. Fernando Morales Medicina Estética", "Clínica Rejuvenece SCZ",
        "BioSculpt Urbarí", "Dra. Andrea Justiniano Estética", "Skin & Body Clinic Las Palmas", "Estética Médica Los Cusis",
        "LaserDerm Ventura", "Centro de Modelado Corporal", "Silueta Perfecta Estética", "LuxeMed Clínica",
        "Dermomedic Equipetrol Norte", "Clínica Vivir Mejor Estética", "ProAge Aesthetic Center", "Vitality Medical Spa"
    ],
    8: [
        "Clínica Dental OdontoSalud Equipetrol", "Centro Médico Especializado Sirari", "Odontología Integral Las Palmas",
        "Smile Clinic Santa Cruz", "Centro Pediátrico El Chuchío", "CardioSalud Diagnóstico", "Clínica Odontológica Urbarí",
        "Fisiomedic Rehabilitación Física", "GinecoSalud Integral", "Centro de Ojos Santa Cruz", "OdontoKids Pediátrico",
        "Clínica Dental del Oriente", "Salud Familiar Hamacas", "Centro Traumatológico del Sur", "Dental Design Ventura",
        "Consultorios Médicos San Martín", "Clínica Dental White Smile", "Centro Integral de Salud Los Cusis",
        "Dermavita Consultorios", "EcoSalud Diagnóstico por Imágenes"
    ],
    9: [
        "Ink Master Tattoo Studio Equipetrol", "Santa Cruz Tattoo Art", "Puro Arte Tatuajes & Piercing",
        "Black Diamond Tattoo Urbarí", "Tinta Cruceña Studio", "Viper Ink Las Palmas", "La Hermandad Tattoo",
        "Sacred Art Tattoo Sirari", "Titanium Piercing & Tattoo", "Dark Ink Collective SCZ",
        "Urban Legend Tattoos", "El Gato Negro Tatuajes"
    ],
    10: [
        "Centro Holístico Prana SCZ", "Shanti Yoga & Meditación Urubó", "Armonía & Sonido Equipetrol",
        "Centro de Acupuntura & MTC Sirari", "Casa del Sol Terapias Alternativas", "Reiki & Sanación Cuántica",
        "Espacio Consciente Las Palmas", "Bioenergetica & Salud Holística", "Ananda Bienestar Integral",
        "Luz & Guía Terapias", "Templo de Paz Santa Cruz", "Equilibrio Vital Hamacas",
        "Centro Ayurvédico del Oriente", "Sinergia Holística SCZ"
    ],
    11: [
        "FitLife Performance Studio", "CrossFit Camba Urbarí", "Pilates Core Studio Equipetrol",
        "PowerZone Training Las Palmas", "Fight Club SCZ Boxing", "Olympus Gym & Studio Banzer",
        "Iron Body Fitness Center", "Reformer Pilates Boutique Sirari", "Functional Box Ventura",
        "Titanium Fitness Hamacas", "Evolution Training SCZ", "Boutique Fitness Urubó",
        "Studio 100 Pilates & Yoga", "Alpha Athletics Equipetrol"
    ],
    12: [
        "Pet Spa & Boutique Equipetrol", "Clínica Veterinaria San Francisco", "Grooming & Pet Salon Sirari",
        "Hospital Veterinario Cruz Verde", "Mascotas Felices Urbarí", "Pet Paradise Banzer",
        "Spa de Mascotas Las Palmas", "Vet & Groom Hamacas", "Peludos VIP Santa Cruz",
        "Veterinaria del Oriente 24 Horas", "Canis & Felis Spa", "Clínica Veterinaria El Cristo"
    ]
}

new_businesses = []

phone_prefixes = ["770", "773", "780", "785", "708", "709", "716", "750", "760", "766"]

count = 0
for cat_id, names in business_names.items():
    cat_info = categories[cat_id]
    for name in names:
        count += 1
        zone = random.choice(zones)
        lat_offset = round(random.uniform(-0.005, 0.005), 5)
        lng_offset = round(random.uniform(-0.005, 0.005), 5)
        
        phone_num = f"+591 {random.choice(phone_prefixes)}{random.randint(10000, 99999)}"
        street_no = random.randint(100, 2800)
        
        # Pick 2-4 services from the category
        chosen_services = random.sample(cat_info["services"], k=random.randint(2, min(4, len(cat_info["services"]))))
        
        desc_es = f"Atención especializada y servicios de {cat_info['name_es'].lower()} en {zone['name']}. Reserva tu cita hoy con confirmación inmediata."
        desc_en = f"Specialized {cat_info['name_en'].lower()} services in {zone['name']}, Santa Cruz. Book your appointment today with instant confirmation."
        
        new_businesses.append({
            "category_id": cat_id,
            "level_id": random.choice([2, 3, 4]),
            "name_es": name,
            "name_en": name,
            "desc_es": desc_es,
            "desc_en": desc_en,
            "phone": phone_num,
            "zone": zone["name"],
            "address": f"{zone['street']} #{street_no}, {zone['name']}",
            "lat": round(zone["lat"] + lat_offset, 5),
            "lng": round(zone["lng"] + lng_offset, 5),
            "services": chosen_services
        })

print(f"Generated {len(new_businesses)} high quality Santa Cruz businesses across 12 categories!")

with open('/root/ClubeMkt/CitasYa/backend/database/seeders/businesses_200.json', 'w', encoding='utf-8') as f:
    json.dump(new_businesses, f, ensure_ascii=False, indent=2)

with open('/root/ClubeMkt/CitasYa/scripts/businesses_200.json', 'w', encoding='utf-8') as f:
    json.dump(new_businesses, f, ensure_ascii=False, indent=2)
