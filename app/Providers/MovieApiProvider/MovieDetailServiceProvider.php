<?php declare(strict_types=1);

namespace App\Providers\MovieApiProvider;

use Illuminate\Http\JsonResponse;
use App\Providers\MovieApiProvider\ApiResponseServiceProvider as ApiResponseService;
use App\Models\Movie;
use App\Models\Person;
use App\Models\Role;
use App\Models\MovieDetail;

class MovieDetailServiceProvider
{
    private ApiResponseService $apiResponseService;

    public function __construct()
    {
        $this->apiResponseService = new ApiResponseService;
    }

    /**
     * @param null|Movie $movie
     * @param string $movieTitle
     * @param null|Person $person
     * @param string $personName
     * @param null|Role $role
     * @param string $roleName
     * 
     * @return null|JsonResponse
     */
    public function relationExist(null|Movie $movie, $movieTitle, null|Person $person, $personName, null|Role $role, $roleName): null|JsonResponse
    {
        if (is_null($movie)) {
            return $this->apiResponseService->responseError("Movie $movieTitle is not in database. Check your spelling or insert this movie into database.");
        }

        if (is_null($person)) {
            return $this->apiResponseService->responseError("Person $personName is not in database. Check your spelling or insert this person into database.");
        }

        if (is_null($role)) {
            return $this->apiResponseService->responseError("Role $roleName is not in database. Check your spelling or insert this role into database.");
        }

        return null;
    }

    /**
     * @param array $movieDetailQuery
     * @param string $characterName
     * 
     * @return null|JsonResponse
     */
    public function checkCharacterName(array $movieDetailQuery, string $characterName): null|JsonResponse
    {
        foreach ($movieDetailQuery as $movieDetail) {
            if (!empty($movieDetail['character_name']) && $movieDetail['character_name'] === $characterName) {
                return $this->apiResponseService->responseError("Entry already exists.", [], 409);
            }
            
            if (empty($movieDetail['character_name']) && empty($characterName)) {
                return $this->apiResponseService->responseError("Entry already exits with empty character name. Update this entry with character name or create entry with new name.", [], 409);
            }
        }
        return null;
    }
}