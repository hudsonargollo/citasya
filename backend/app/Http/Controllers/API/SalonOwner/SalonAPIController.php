<?php

namespace App\Http\Controllers\API\SalonOwner;

use App\Criteria\Salons\SalonsOfUserCriteria;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateSalonRequest;
use App\Http\Requests\UpdateSalonRequest;
use App\Repositories\SalonRepository;
use App\Repositories\UploadRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;

/**
 * Class SalonController
 * @package App\Http\Controllers\API\SalonOwner
 */
class SalonAPIController extends Controller
{
    /** @var  SalonRepository */
    private SalonRepository $salonRepository;
    private UploadRepository $uploadRepository;

    public function __construct(SalonRepository $salonRepo, UploadRepository $uploadRepository)
    {
        $this->salonRepository = $salonRepo;
        $this->uploadRepository = $uploadRepository;
        parent::__construct();
    }

    /**
     * Display a listing of the Salon.
     * GET /api/salon_owner/salons
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $this->salonRepository->pushCriteria(new RequestCriteria($request));
            $this->salonRepository->pushCriteria(new SalonsOfUserCriteria(auth()->id()));
            $this->salonRepository->pushCriteria(new LimitOffsetCriteria($request));
            $salons = $this->salonRepository->all();
            $this->filterCollection($request, $salons);
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }

        return $this->sendResponse($salons->toArray(), 'Salons retrieved successfully');
    }

    /**
     * Display the specified Salon.
     * GET /api/salon_owner/salons/{id}
     */
    public function show(int $id, Request $request): JsonResponse
    {
        try {
            $this->salonRepository->pushCriteria(new RequestCriteria($request));
            $this->salonRepository->pushCriteria(new LimitOffsetCriteria($request));
            $salon = $this->salonRepository->findWithoutFail($id);
            if (empty($salon)) {
                return $this->sendError('Salon not found');
            }
            $this->filterModel($request, $salon);
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }

        return $this->sendResponse($salon->toArray(), 'Salon retrieved successfully');
    }

    /**
     * Store a newly created Salon.
     * POST /api/salon_owner/salons
     */
    public function store(CreateSalonRequest $request): JsonResponse
    {
        try {
            $input = $request->all();
            $input['users'] = [auth()->id()];
            $input['accepted'] = 1;
            $input['available'] = 1;

            $salon = $this->salonRepository->create($input);
            if (isset($input['image']) && $input['image'] && is_array($input['image'])) {
                foreach ($input['image'] as $fileUuid) {
                    $cacheUpload = $this->uploadRepository->getByUuid($fileUuid);
                    $mediaItem = $cacheUpload->getMedia('image')->first();
                    $mediaItem->copy($salon, 'image');
                }
            }
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
        return $this->sendResponse($salon->toArray(), __('lang.saved_successfully', ['operator' => __('lang.salon')]));
    }

    /**
     * Update the specified Salon.
     * PUT /api/salon_owner/salons/{id}
     */
    public function update(int $id, UpdateSalonRequest $request): JsonResponse
    {
        $this->salonRepository->pushCriteria(new SalonsOfUserCriteria(auth()->id()));
        $salon = $this->salonRepository->findWithoutFail($id);

        if (empty($salon)) {
            return $this->sendError('Salon not found or unauthorized');
        }

        try {
            $input = $request->all();
            $salon = $this->salonRepository->update($input, $id);
            if (isset($input['image']) && $input['image'] && is_array($input['image'])) {
                if ($salon->hasMedia('image')) {
                    $salon->getMedia('image')->each->delete();
                }
                foreach ($input['image'] as $fileUuid) {
                    $cacheUpload = $this->uploadRepository->getByUuid($fileUuid);
                    $mediaItem = $cacheUpload->getMedia('image')->first();
                    $mediaItem->copy($salon, 'image');
                }
            }
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
        return $this->sendResponse($salon->toArray(), __('lang.updated_successfully', ['operator' => __('lang.salon')]));
    }
}
