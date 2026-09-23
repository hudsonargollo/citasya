<?php namespace App\Http\Middleware;

use App\Repositories\UploadRepository;
use Closure;
use Exception;
use Illuminate\Http\Request;

class App
{

    /**
     * @var UploadRepository
     */
    protected UploadRepository $uploadRepository;

    /**
     * @param UploadRepository $uploadRepository
     */
    public function __construct(UploadRepository $uploadRepository)
    {
        $this->uploadRepository = $uploadRepository;
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        try {
            $this->uploadRepository = new UploadRepository(app());
            $appLogo = asset('images/brand/logo_2d_horizontal.webp');
            $logoSetting = setting('app_logo', '');
            if (!empty($logoSetting)) {
                if (filter_var($logoSetting, FILTER_VALIDATE_URL) || str_starts_with($logoSetting, '/') || str_starts_with($logoSetting, 'images/')) {
                    $appLogo = str_starts_with($logoSetting, 'images/') ? asset($logoSetting) : $logoSetting;
                } else {
                    $upload = $this->uploadRepository->findByField('uuid', $logoSetting)->first();
                    if ($upload && $upload->hasMedia('app_logo')) {
                        $appLogo = $upload->getFirstMediaUrl('app_logo');
                    }
                }
            }
            view()->share('app_logo', $appLogo);
        } catch (Exception $exception) {
        }

        return $next($request);
    }

}
/*
 * File name: App.php
 * Last modified: 2024.04.18 at 17:30:50
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2024
 */


