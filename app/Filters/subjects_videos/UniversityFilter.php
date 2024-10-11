<?php

namespace App\Filters\subjects_videos;

use App\Filters\FilterRequest;
use Closure;
class UniversityFilter extends FilterRequest
{
    public function handle($request, Closure $next){
        if(request()->has('university_id')){
            return $next($request)->whereHas('subject.category',fn($e)=>
                $e->where('university_id','=',request('university_id'))
            );
        }
        return $next($request);
    }
}