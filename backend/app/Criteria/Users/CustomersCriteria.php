<?php
/*
 * File name: CustomersCriteria.php
 * Last modified: 2024.04.18 at 17:21:42
 * Author: ClubeMkt - https://clubemkt.online
 * Copyright (c) 2024
 */

namespace App\Criteria\Users;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class CustomerCriteria.
 *
 * @package namespace App\Criteria\Users;
 */
class CustomersCriteria implements CriteriaInterface
{
    /**
     * Apply criteria in query repository
     *
     * @param string              $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository): mixed
    {
        return $model->whereHas("roles", function($q){ $q->where("name", "customer"); });
    }
}
