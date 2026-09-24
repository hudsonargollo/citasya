<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\EService;
use App\Models\Salon;
use App\Models\Slide;
use Illuminate\Database\Seeder;

class AttachBrandMediaSeeder extends Seeder
{
    public function run(): void
    {
        $brandImages = [
            'brand_0.png', 'brand_1.png', 'brand_2.png', 'brand_3.png',
            'brand_4.png', 'brand_5.png', 'brand_6.png', 'brand_7.png',
            'brand_8.png', 'brand_9.png', 'brand_10.png', 'brand_11.png',
            'brand_12.png', 'brand_13.png', 'brand_14.png', 'brand_15.png', 'brand_16.png'
        ];

        $landingImages = [
            'landing_0.png', 'landing_1.png', 'landing_2.png', 'landing_3.png'
        ];

        // 1. Seed Slides
        $slides = Slide::all();
        foreach ($slides as $idx => $slide) {
            $slideNum = $idx + 1;
            $customSlidePath = public_path("images/slides/slide{$slideNum}.png");

            if (file_exists($customSlidePath)) {
                $slide->text = '';
                $slide->button = '';
                $slide->save();
                $slide->clearMediaCollection('image');
                $slide->addMedia($customSlidePath)->preservingOriginal()->toMediaCollection('image');
                continue;
            }

            $img = $landingImages[$idx % count($landingImages)];
            $path = public_path('images/brand/' . $img);
            if (file_exists($path)) {
                $slide->clearMediaCollection('image');
                $slide->addMedia($path)->preservingOriginal()->toMediaCollection('image');
            }
        }

        // 2. Seed Categories
        $categories = Category::all();
        $categoryFiles = [
            1 => '1_barberia.png',
            2 => '2_unas.png',
            3 => '3_facial.png',
            4 => '4_cejas.png',
            5 => '5_spa.png',
            6 => '6_maquillaje.png',
            7 => '7_estetica.png',
            8 => '8_salud.png',
            9 => '9_tatuajes.png',
            10 => '10_bienestar.png',
            11 => '11_fitness.png',
            12 => '12_mascotas.png',
        ];
        foreach ($categories as $idx => $category) {
            if (isset($categoryFiles[$category->id])) {
                $pngPath = public_path("images/categories/" . $categoryFiles[$category->id]);
                if (file_exists($pngPath)) {
                    $category->clearMediaCollection('image');
                    $category->addMedia($pngPath)->preservingOriginal()->toMediaCollection('image');
                    continue;
                }
            }

            $img = $brandImages[$idx % count($brandImages)];
            $path = public_path('images/brand/' . $img);
            if (file_exists($path)) {
                $category->clearMediaCollection('image');
                $category->addMedia($path)->preservingOriginal()->toMediaCollection('image');
            }
        }

        // 3. Seed Salons
        $salons = Salon::all();
        $serviceCategoryBanners = [
            1 => 'barber.png',
            2 => 'nails.png',
            3 => 'steticists.png',
            4 => 'steticists.png',
            5 => 'spa.png',
            6 => 'steticists.png',
            7 => 'steticists.png',
            8 => 'dentists.png',
            9 => 'services.png',
            10 => 'spa.png',
            11 => 'services.png',
            12 => 'services.png',
        ];

        foreach ($salons as $idx => $salon) {
            $catId = null;
            foreach ($salon->eServices as $svc) {
                $c = $svc->categories()->first();
                if ($c) {
                    $catId = $c->id;
                    break;
                }
            }
            if (!$catId) {
                $catId = ($idx % 12) + 1;
            }

            $bannerImg = isset($serviceCategoryBanners[$catId]) ? $serviceCategoryBanners[$catId] : null;
            $bannerPath = $bannerImg ? public_path('images/services/' . $bannerImg) : null;

            if ($bannerPath && file_exists($bannerPath)) {
                $salon->clearMediaCollection('image');
                $salon->addMedia($bannerPath)->preservingOriginal()->toMediaCollection('image');
                continue;
            }

            $img = $brandImages[($idx + 3) % count($brandImages)];
            $path = public_path('images/brand/' . $img);
            if (file_exists($path)) {
                $salon->clearMediaCollection('image');
                $salon->addMedia($path)->preservingOriginal()->toMediaCollection('image');
            }
        }

        // 4. Seed EServices
        $eServices = EService::all();
        foreach ($eServices as $idx => $service) {
            $img = $brandImages[($idx + 5) % count($brandImages)];
            $path = public_path('images/brand/' . $img);
            if (file_exists($path)) {
                $service->clearMediaCollection('image');
                $service->addMedia($path)->preservingOriginal()->toMediaCollection('image');
            }
        }
    }
}
