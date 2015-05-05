<?php namespace MH\Http\Middleware;

use Closure;
use App;
use Illuminate\Contracts\Auth\Guard;

class IsAdminAccount {

    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if( ! $this->auth->user()->hasRole('admin'))
        {
            return App::missing();
        }
        return $next($request);
    }

}