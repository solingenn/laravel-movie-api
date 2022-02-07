<?php declare(strict_types=1);
   
namespace App\Http\Controllers\API;
   
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Validator;
use App\Models\Movie;
use App\Http\Resources\Movie as MovieResource;
use App\Providers\MovieApiProvider\ApiResponseServiceProvider as ApiResponseService;
   
class MovieController extends Controller
{
    private ApiResponseService $apiResponseService;

    public function __construct()
    {
        $this->apiResponseService = new ApiResponseService;
    }

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $movies = Movie::all();

        if (sizeof($movies) === 0) {
            return $this->apiResponseService->responseError('No movies to show.');
        }
        return $this->apiResponseService->responseSuccess(MovieResource::collection($movies), 'Movies fetched.');

    }
    
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $input = $request->all();
        $movieInputTitle = $input['title'];
        $movieQuery = Movie::where('title', $movieInputTitle)->get();

        if (sizeof($movieQuery) > 0) {
            return $this->apiResponseService->responseError('Movie title already exists.', [], 409);
        }

        $validator = Validator::make($input, [
            'title' => 'required',
            'release_year' => 'required',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->apiResponseService->responseError($validator->errors());
        }

        $movie = Movie::create($input);
        return $this->apiResponseService->responseSuccess(new MovieResource($movie), 'Movie created.', 201);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $movie = Movie::find($id);

        if (is_null($movie)) {
            return $this->apiResponseService->responseError('Movie does not exist.');
        }
        return $this->apiResponseService->responseSuccess(new MovieResource($movie), 'Movie fetched.');
    }
    
    /**
     * @param Request $request
     * @param Movie $movie
     * @return JsonResponse
     */
    public function update(Request $request, Movie $movie): JsonResponse
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'title' => 'required',
            'release_year' => 'required',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->apiResponseService->responseError($validator->errors());       
        }

        $movie->title = $input['title'];
        $movie->release_year = $input['release_year'];
        $movie->description = $input['description'];
        $movie->save();
        
        return $this->apiResponseService->responseSuccess(new MovieResource($movie), 'Movie updated.', 201);
    }
    
    /**
     * @param Movie $movie
     * @return JsonResponse
     */
    public function destroy(Movie $movie): JsonResponse
    {
        $movie->delete();
        return $this->apiResponseService->responseSuccess([], "Movie $movie->title deleted.", 202);
    }
}