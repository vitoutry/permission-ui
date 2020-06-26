<?php 
namespace App\Repositories;

use Vitoutry\Repository\Contracts\RepositoryInterface;
use Vitoutry\Repository\Eloquent\Repository;
use App\Entities\Result;

class ResultRepository extends Repository {
 
    public function model() {
        return 'App\Entities\Result';
    }


    
}