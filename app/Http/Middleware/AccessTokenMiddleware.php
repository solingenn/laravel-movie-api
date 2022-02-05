<?php declare(strict_types=1);

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Closure;

class AccessTokenMiddleware
{
   /**
    * Handle an incoming request.
    *
    * @param  Request  $request
    * @param  \Closure  $next

    * @return mixed
    */
   public function handle(Request $request, Closure $next): mixed
   {
       var_dump('test');
       die;
       
       $request->headers->add(['Authorization' => "Bearer {$request->access_token}"]);

       return $next($request);
   }
}