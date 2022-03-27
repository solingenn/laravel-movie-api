<?php declare(strict_types=1);
   
namespace App\Http\Controllers\API;
   
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Validator;
use App\Models\User;
use App\Providers\MovieApiProvider\ApiResponseServiceProvider as ApiResponseService;
   
class AuthController extends Controller
{
    private ApiResponseService $apiResponseService;

    public function __construct()
    {
        $this->apiResponseService = new ApiResponseService;
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
        ]);
   
        if ($validator->fails()) {
            return $this->apiResponseService->responseError('Error validation', ['error' => $validator->errors()]);
        }
   
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('MyAuthApp')->plainTextToken;
        $success['name'] =  $user->name;
   
        return $this->apiResponseService->responseSuccess($success, 'User created successfully.');
    }

    public function login(Request $request)
    {
        if (Auth::attempt([
                'email' => $request->email, 
                'password' => $request->password
            ])) { 
            $authUser = Auth::user(); 
            $success['token'] =  $authUser->createToken('MyAuthApp')->plainTextToken; 
            $success['name'] =  $authUser->name;

            return $this->apiResponseService->responseSuccess($success, 'User signed in.');
        } else { 
            return $this->apiResponseService->responseError('Unauthorised.', ['error' => 'Unauthorised'], 401);
        } 
    }
}