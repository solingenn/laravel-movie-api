<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Validator;
use App\Models\Role;
use App\Http\Resources\Role as RoleResource;
use App\Providers\MovieApiProvider\ApiResponseServiceProvider as ApiResponseService;

class RoleController extends Controller
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
        $roles = Role::all();

        if (sizeof($roles) === 0) {
            return $this->apiResponseService->responseError('No roles to show.');
        }
        return $this->apiResponseService->responseSuccess(RoleResource::collection($roles), 'Roles fetched.');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $input = $request->all();
        $roleName = $input['role_name'];
        $roleQuery = Role::where('role_name', $roleName)->get();

        if (sizeof($roleQuery) > 0) {
            return $this->apiResponseService->responseError('Role already exists.', [], 409);
        }

        $validator = Validator::make($input, [
            'role_name' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->apiResponseService->responseError($validator->errors());
        }

        $role = Role::create($input);
        return $this->apiResponseService->responseSuccess(new RoleResource($role), 'Role created.', 201);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $role = Role::find($id);

        if (is_null($role)) {
            return $this->apiResponseService->responseError('Role does not exist.');
        }
        return $this->apiResponseService->responseSuccess(new RoleResource($role), 'Role fetched.');
    }

    /**
     * @param Request $request
     * @param Role $role
     * @return JsonResponse
     */
    public function update(Request $request, Role $role): JsonResponse
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'role_name' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->apiResponseService->responseError($validator->errors());       
        }

        $role->role_name = $input['role_name'];
        $role->save();
        
        return $this->apiResponseService->responseSuccess(new RoleResource($role), 'Role updated.', 201);
    }
    
    /**
     * @param Role $role
     * @return JsonResponse
     */
    public function destroy(Role $role): JsonResponse
    {
        $role->delete();
        return $this->apiResponseService->responseSuccess([], "Role $role->role_name deleted.", 202);
    }
}
