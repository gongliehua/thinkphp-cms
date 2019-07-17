<?php

namespace app\http\middleware;

class Auth
{
    public function handle($request, \Closure $next)
    {
    	if ($request->path() == 'admin/index/index') {
    		return redirect('admin/Index/test');
    	}
    	return $next($request);
    }
}
