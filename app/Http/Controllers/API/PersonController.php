<?php declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Validator;
use App\Models\Person;
use App\Http\Resources\Person as PersonResource;
use App\Providers\MovieApiProvider\ApiResponseServiceProvider as ApiResponseService;

class PersonController extends Controller
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
        $persons = Person::all();

        if (sizeof($persons) === 0) {
            return $this->apiResponseService->responseError('No person to show.');
        }
        return $this->apiResponseService->responseSuccess(PersonResource::collection($persons), 'Persons fetched.');
    }

    /**
     * @param Request  $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $input = $request->all();
        $personInputFirstName = $input['first_name'];
        $personInputLastName = $input['last_name'];
        $personQuery = Person::where('first_name', $personInputFirstName)->where('last_name', $personInputLastName)->get('id');

        if (sizeof($personQuery) > 0) {
            return $this->apiResponseService->responseError('Person already exists.', [], 409);
        }

        $validator = Validator::make($input, [
            'first_name' => 'required',
            'last_name'  => 'required',
            'born'       => 'required',
        ]);

        if ($validator->fails()) {
            return $this->apiResponseService->responseError($validator->errors());
        }

        $person = Person::create($input);
        return $this->apiResponseService->responseSuccess(new PersonResource($person), 'Person created.', 201);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $person = Person::find($id);

        if (is_null($person)) {
            return $this->apiResponseService->responseError('Person does not exist.');
        }
        return $this->apiResponseService->responseSuccess(new PersonResource($person), 'Person fetched.');
    }

    /**
     * @param Request $request
     * @param Person $person
     * @return JsonResponse
     */
    public function update(Request $request, Person $person): JsonResponse
    {
        $input = $request->all();

        //die(var_dump($input));

        $validator = Validator::make($input, [
            'first_name' => 'required',
            'last_name'  => 'required',
            'born'       => 'required',
        ]);

        if ($validator->fails()) {
            return $this->apiResponseService->responseError($validator->errors());       
        }

        $person->first_name = $input['first_name'];
        $person->last_name = $input['last_name'];
        $person->born = $input['born'];
        $person->save();
        
        return $this->apiResponseService->responseSuccess(new PersonResource($person), 'Person updated.', 201);
    }

    /**
     * @param Person $person
     * @return JsonResponse
     */
    public function destroy(Person $person): JsonResponse
    {
        $person->delete();
        $personName = "$person->first_name $person->last_name";

        return $this->apiResponseService->responseSuccess([], "Person $personName deleted.", 202);
    }
}
