<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Validator;
use App\Models\MovieDetail;
use App\Models\Movie;
use App\Models\Person;
use App\Models\Role;
use App\Http\Resources\MovieDetail as MovieDetailResource;
use App\Http\Resources\Movie as MovieResource;
use App\Http\Resources\Person as PersonResource;
use App\Http\Resources\Role as RoleResource;
use App\Providers\MovieApiProvider\ApiResponseServiceProvider as ApiResponseService;
use App\Providers\MovieApiProvider\MovieDetailServiceProvider as MovieDetailService;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class MovieDetailController extends Controller
{
    private ApiResponseService $apiResponseService;
    private MovieDetailService $movieDetailService;

    public function __construct()
    {
        $this->apiResponseService = new ApiResponseService;
        $this->movieDetailService = new MovieDetailService;
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        $movie = Movie::find($id);

        if (is_null($movie)) {
            return $this->apiResponseService->responseError('Movie details are not available.');
        }

        $movieTitle = $movie->title;
        $persons = $movie->persons()->get();

        $movieDetails = [
            'movie-title' => $movieTitle,
            'cast'        => []
        ];

        foreach ($persons as $index => $person) {
            $roleId = $person->pivot->role_id;
            $roleName = Role::find($roleId)->role_name;

            $movieDetails['cast'][$index]['person-name'] =  "$person->first_name $person->last_name";
            $movieDetails['cast'][$index]['role'] = $roleName;
            $movieDetails['cast'][$index]['character-name'] = $person->pivot->character_name;
        }

        return $this->apiResponseService->responseSuccess($movieDetails, 'Movie details fetched.');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $input = $request->all();
        $movieTitle = trim($input['movie_title']);
        $personName = trim($input['person_name']);
        $roleName = trim($input['role']);
        $characterName = (empty($input['character_name']) && $roleName !== 'actor') ? 'Himself' : trim($input['character_name']);

        $validator = Validator::make($input, [
            'movie_title' => 'required',
            'person_name' => 'required',
            'role' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->apiResponseService->responseError($validator->errors());
        }

        $personNameCollection = explode(' ', $personName);
        $personFirstName = $personNameCollection[0];
        $personLastName = $personNameCollection[1];

        $movie = Movie::where('title', $movieTitle)->first();
        $person = Person::where('first_name', $personFirstName)->where('last_name', $personLastName)->first();
        $role = Role::where('role_name', $roleName)->first();

        //check if each relation exists in Movie|Person|Role tables
        $relationExist = $this->movieDetailService->relationExist($movie, $movieTitle, $person, $personName, $role, $roleName);
        if (!is_null($relationExist)) {
            return $relationExist;
        }
        
        $movieId = $movie->id;
        $personId = $person->id;
        $roleId = $role->id;

        // check if similar entry exists, even with empty character name
        $movieDetailQuery = MovieDetail::where('movie_id', $movieId)->where('person_id', $personId)->where('role_id', $roleId)->get()->toArray();
        if (!empty($movieDetailQuery)) {
            $characterNameCheckResponse = $this->movieDetailService->checkCharacterName($movieDetailQuery, $characterName);
            if (!is_null($characterNameCheckResponse)) {
                return $characterNameCheckResponse;
            }
        }
        
        $movie->persons()->attach($personId, [
            'role_id' => $roleId,
            'character_name' => $characterName
        ]);

        return $this->apiResponseService->responseSuccess([], 'Movie detail created.', 201);
    }

    /**
     * @param Request $request
     * @param MovieDetail $movieDetail
     * @return JsonResponse
     */
    public function update(Request $request, MovieDetail $movieDetail): JsonResponse
    {
        $input = $request->all();
        $personName = trim($input['person_name']);
        $movieTitle = trim($input['movie_title']);
        $roleName = trim($input['role']);
        $characterName = trim($input['character_name']);

        $validator = Validator::make($input, [
            'movie_title' => 'required',
            'person_name' => 'required',
            'role' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->apiResponseService->responseError($validator->errors());
        }

        $personNameCollection = explode(' ', $personName);
        $personFirstName = $personNameCollection[0];
        $personLastName = $personNameCollection[1];

        $movie = Movie::where('title', $movieTitle)->first();
        $person = Person::where('first_name', $personFirstName)->where('last_name', $personLastName)->first();
        $role = Role::where('role_name', $roleName)->first();

        //check if each relation exists in Movie|Person|Role tables
        $relationExist = $this->movieDetailService->relationExist($movie, $movieTitle, $person, $personName, $role, $roleName);
        if (!is_null($relationExist)) {
            return $relationExist;
        }
        
        $movieId = $movie->id;
        $personId = $person->id;
        $roleId = $role->id;

        // check if similar entry exists, even with empty character name
        $movieDetailQuery = MovieDetail::where('movie_id', $movieId)->where('person_id', $personId)->where('role_id', $roleId)->get()->toArray();
        if (!empty($movieDetailQuery)) {
            $characterNameCheckResponse = $this->movieDetailService->checkCharacterName($movieDetailQuery, $characterName);
            if (!is_null($characterNameCheckResponse)) {
                return $characterNameCheckResponse;
            }
        }

        $movieDetail->movie_id = $movieId;
        $movieDetail->person_id = $personId;
        $movieDetail->role_id = $roleId;
        $movieDetail->character_name = $characterName;
        $movieDetail->save();

        return $this->apiResponseService->responseSuccess(new MovieDetailResource($movieDetail), 'Movie detail updated.', 201);
    }

    /**
     * @param MovieDetail $movieDetail
     * @return JsonResponse
     */
    public function destroy(MovieDetail $movieDetail)
    {
        $movieDetail->delete();
        
        return $this->apiResponseService->responseSuccess([], "Movie detail deleted.", 202);
    }
}
