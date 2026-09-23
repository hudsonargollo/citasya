<?php

namespace App\Http\Controllers\API\SalonOwner;

use App\Criteria\EServices\EServicesOfSalonCriteria;
use App\Criteria\EServices\NearCriteria;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateEServiceRequest;
use App\Http\Requests\UpdateEServiceRequest;
use App\Repositories\EServiceRepository;
use App\Repositories\UploadRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;

/**
 * Class EServiceController
 * @package App\Http\Controllers\API\SalonOwner
 */
class EServiceAPIController extends Controller
{
    /** @var  EServiceRepository */
    private EServiceRepository $eServiceRepository;
    private UploadRepository $uploadRepository;

    public function __construct(EServiceRepository $eServiceRepo, UploadRepository $uploadRepository)
    {
        parent::__construct();
        $this->eServiceRepository = $eServiceRepo;
        $this->uploadRepository = $uploadRepository;
    }

    /**
     * Display a listing of the EService.
     * GET /api/salon_owner/e_services
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $this->eServiceRepository->pushCriteria(new RequestCriteria($request));
            $this->eServiceRepository->pushCriteria(new EServicesOfSalonCriteria(auth()->id()));
            $this->eServiceRepository->pushCriteria(new NearCriteria($request));
            $this->eServiceRepository->pushCriteria(new LimitOffsetCriteria($request));
            $eServices = $this->eServiceRepository->all();

            $this->availableSalon($request, $eServices);
            $this->limitOffset($request, $eServices);
            $this->filterCollection($request, $eServices);
            $eServices = array_values($eServices->toArray());
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
        return $this->sendResponse($eServices, 'E Services retrieved successfully');
    }

    /**
     * Display the specified EService.
     * GET /api/salon_owner/e_services/{id}
     */
    public function show(int $id, Request $request): JsonResponse
    {
        try {
            $this->eServiceRepository->pushCriteria(new RequestCriteria($request));
            $this->eServiceRepository->pushCriteria(new LimitOffsetCriteria($request));
            $eService = $this->eServiceRepository->findWithoutFail($id);
            if (empty($eService)) {
                return $this->sendError('Service not found');
            }
            $this->filterModel($request, $eService);
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
        return $this->sendResponse($eService->toArray(), 'E Service retrieved successfully');
    }

    /**
     * Store a newly created EService in storage.
     * POST /api/salon_owner/e_services
     */
    public function store(CreateEServiceRequest $request): JsonResponse
    {
        $input = $request->all();
        try {
            $eService = $this->eServiceRepository->create($input);
            if (isset($input['image']) && $input['image'] && is_array($input['image'])) {
                foreach ($input['image'] as $fileUuid) {
                    $cacheUpload = $this->uploadRepository->getByUuid($fileUuid);
                    $mediaItem = $cacheUpload->getMedia('image')->first();
                    $mediaItem->copy($eService, 'image');
                }
            }
            if (isset($input['categories'])) {
                $eService->categories()->sync($input['categories']);
            }
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }

        return $this->sendResponse($eService->toArray(), __('lang.saved_successfully', ['operator' => __('lang.e_service')]));
    }

    /**
     * Update the specified EService in storage.
     * PUT /api/salon_owner/e_services/{id}
     */
    public function update(int $id, UpdateEServiceRequest $request): JsonResponse
    {
        $eService = $this->eServiceRepository->findWithoutFail($id);
        if (empty($eService)) {
            return $this->sendError('Service not found');
        }

        $input = $request->all();
        try {
            $eService = $this->eServiceRepository->update($input, $id);
            if (isset($input['image']) && $input['image'] && is_array($input['image'])) {
                if ($eService->hasMedia('image')) {
                    $eService->getMedia('image')->each->delete();
                }
                foreach ($input['image'] as $fileUuid) {
                    $cacheUpload = $this->uploadRepository->getByUuid($fileUuid);
                    $mediaItem = $cacheUpload->getMedia('image')->first();
                    $mediaItem->copy($eService, 'image');
                }
            }
            if (isset($input['categories'])) {
                $eService->categories()->sync($input['categories']);
            }
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }

        return $this->sendResponse($eService->toArray(), __('lang.updated_successfully', ['operator' => __('lang.e_service')]));
    }

    /**
     * Remove the specified EService from storage.
     * DELETE /api/salon_owner/e_services/{id}
     */
    public function destroy(int $id): JsonResponse
    {
        $eService = $this->eServiceRepository->findWithoutFail($id);
        if (empty($eService)) {
            return $this->sendError('Service not found');
        }

        $this->eServiceRepository->delete($id);
        return $this->sendResponse($eService, __('lang.deleted_successfully', ['operator' => __('lang.e_service')]));
    }

    /**
     * @param Request $request
     * @param Collection $eServices
     */
    private function availableSalon(Request $request, Collection &$eServices): void
    {
        if ($request->has('available_salon')) {
            $eServices = $eServices->filter(function ($element) {
                return !$element->salon->closed;
            });
        }
    }
}
