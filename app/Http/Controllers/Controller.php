<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function __construct()
    {
        $this->perPage = (int) request()->get('per_page') ?? 15;
        $this->page = (int) (request()->get('page') ?: 1);
        $this->returnType = request()->get('return_type') ?? 'pagination';
        
    }

    /**
     * @param Builder $query_or_collection
     * @return mixed
     */
    public function handleCollectionOrPaginationReturn($query_or_collection, $isCollection = false)
    {
        if ($isCollection) {
            $collection = collect($query_or_collection);

            $total = count($query_or_collection);
            $totalPages = ceil(($total = max(1, $total)) / $this->perPage);
            $this->page = max(1, min($this->page, $totalPages));
            $paginatedItems = $collection->forPage($this->page, $this->perPage)->values();
            
            return [
                'current_page' => $this->page,
                'data' => $paginatedItems,
                'per_page' => $this->perPage,
                'total' => $total,
                'total_pages' => $totalPages,
                'has_more' => $this->page < $totalPages,
            ];
        }

        if ($this->returnType === 'pagination') {
            return $query_or_collection->paginate($this->perPage);
        }
        
        return $query_or_collection->get();
    }
}
