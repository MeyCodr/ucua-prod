<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;

class Standard extends Model
{
    public static function paginate_records($routeName, $limit, $records, $currentPage){
        $start = 0;
        if($currentPage == 1){
            $start = 0;
        }
        else{
            for ($i=0; $i < $currentPage; $i++) { 
                if($i > 0){
                    $start = $start + $limit;
                }
            }
        }

        $paginated_records = new Paginator(array_slice($records, $start, $limit), count($records), $limit);
        $paginated_records->withPath($routeName);

        return $paginated_records;
    }
}
