<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\BackOfficeAuth;
use App\Http\Middleware\MemberAuth;
use App\Http\Middleware\TrackVisitor;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // 이메일 저장 쿠키는 암호화 제외 (로그인 폼 기억용)
        $middleware->encryptCookies(['remembered_email']);

        // 백오피스 경로에 대해 BackOfficeAuth 미들웨어 등록
        $middleware->group('backoffice', [
            BackOfficeAuth::class,
        ]);

        // 마이페이지 경로에 대해 MemberAuth 미들웨어 등록
        $middleware->group('member', [
            MemberAuth::class,
        ]);
        
        // 방문자 추적 미들웨어를 전역에 등록
        $middleware->append(TrackVisitor::class);
        
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
