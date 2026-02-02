<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MemberAuth
{
    /**
     * 회원(마이페이지) 인증 미들웨어
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('member')->check()) {
            return redirect()->guest(route('member.login'));
        }

        return $next($request);
    }
}
