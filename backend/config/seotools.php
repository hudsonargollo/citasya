<?php
/**
 * @see https://github.com/artesaos/seotools
 */

return [
    'inertia' => env('SEO_TOOLS_INERTIA', false),
    'meta' => [
        /*
         * The default configurations to be used by the meta generator.
         */
        'defaults'       => [
            'title'        => "CitasYa - Reserva de Citas y Agendas Online en Bolivia", // set false to total remove
            'titleBefore'  => false, // Put defaults.title before page title, like 'CitasYa - Dashboard'
            'description'  => 'Encuentra y reserva citas en salones de belleza, clínicas médicas, veterinarias y spas en Bolivia en menos de 30 segundos, sin llamadas.', // set false to total remove
            'separator'    => ' - ',
            'keywords'     => ['reserva de citas santa cruz', 'agenda online bolivia', 'peluquerias bolivia', 'consultorio dental santa cruz', 'citasya'],
            'canonical'    => 'current', // Set to null or 'full' to use Url::full(), set to 'current' to use Url::current(), set false to total remove
            'robots'       => 'all', // Set to 'all', 'none' or any combination of index/noindex and follow/nofollow
        ],
        /*
         * Webmaster tags are always added.
         */
        'webmaster_tags' => [
            'google'    => null,
            'bing'      => null,
            'alexa'     => null,
            'pinterest' => null,
            'yandex'    => null,
            'norton'    => null,
        ],

        'add_notranslate_class' => false,
    ],
    'opengraph' => [
        /*
         * The default configurations to be used by the opengraph generator.
         */
        'defaults' => [
            'title'       => "CitasYa - Reserva de Citas y Agendas Online en Bolivia", // set false to total remove
            'description' => 'Encuentra y reserva citas en salones de belleza, clínicas médicas, veterinarias y spas en Bolivia en menos de 30 segundos, sin llamadas.', // set false to total remove
            'url'         => null, // Set null for using Url::current(), set false to total remove
            'type'        => 'website',
            'site_name'   => 'CitasYa',
            'images'      => ['https://citasya.clubemkt.online/favicon.svg'],
        ],
    ],
    'twitter' => [
        /*
         * The default values to be used by the twitter cards generator.
         */
        'defaults' => [
            'card'        => 'summary_large_image',
            'site'        => '@CitasYa',
        ],
    ],
    'json-ld' => [
        /*
         * The default configurations to be used by the json-ld generator.
         */
        'defaults' => [
            'title'       => "CitasYa - Reserva de Citas y Agendas Online en Bolivia", // set false to total remove
            'description' => 'Encuentra y reserva citas en salones de belleza, clínicas médicas, veterinarias y spas en Bolivia en menos de 30 segundos, sin llamadas.', // set false to total remove
            'url'         => 'current', // Set to null or 'full' to use Url::full(), set to 'current' to use Url::current(), set false to total remove
            'type'        => 'WebSite',
            'images'      => ['https://citasya.clubemkt.online/favicon.svg'],
        ],
    ],
];
