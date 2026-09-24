<?php
/*
 * File name: CategoryAPIController.php
 * Last modified: 2024.04.10 at 12:26:06
 * Author: ClubeMkt - https://clubemkt.online
 * Copyright (c) 2024
 */

namespace App\Http\Controllers\API;


use App\Criteria\Categories\NearCriteria;
use App\Criteria\Categories\ParentCriteria;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Repositories\CategoryRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;

/**
 * Class CategoryController
 * @package App\Http\Controllers\API
 */
class CategoryAPIController extends Controller
{
    /** @var  CategoryRepository */
    private CategoryRepository $categoryRepository;

    public function __construct(CategoryRepository $categoryRepo)
    {
        parent::__construct();
        $this->categoryRepository = $categoryRepo;
    }

    /**
     * Display a listing of the Category.
     * GET|HEAD /categories
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $cacheKey = 'api_categories_' . md5($request->fullUrl());
        $data = \Illuminate\Support\Facades\Cache::remember($cacheKey, 600, function () use ($request) {
            try {
                $this->categoryRepository->pushCriteria(new RequestCriteria($request));
                $this->categoryRepository->pushCriteria(new ParentCriteria($request));
                $this->categoryRepository->pushCriteria(new NearCriteria($request));
                $this->categoryRepository->pushCriteria(new LimitOffsetCriteria($request));
                $categories = $this->categoryRepository->all();
                return $categories->toArray();
            } catch (RepositoryException $e) {
                return null;
            }
        });

        if ($data === null) {
            return $this->sendError('Error retrieving categories');
        }

        return $this->sendResponse($data, 'Categories retrieved successfully');
    }

    /**
     * Display the specified Category.
     * GET|HEAD /categories/{id}
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        /** @var Category $category */
        if (!empty($this->categoryRepository)) {
            $category = $this->categoryRepository->findWithoutFail($id);
        }

        if (empty($category)) {
            return $this->sendError('Category not found');
        }

        return $this->sendResponse($category->toArray(), 'Category retrieved successfully');
    }
}
