<?php

namespace App\Providers;

use App\Models\AdminMenu;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use SocialiteProviders\Manager\SocialiteWasCalled;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // HTTPS 강제 (.env의 APP_URL이 https://로 시작하는 경우)
        $applicationUrl = config('app.url');
        if (is_string($applicationUrl) && str_starts_with($applicationUrl, 'https://')) {
            URL::forceScheme('https');
        }

        // 백오피스 경로에서 현재 메뉴 정보를 뷰에 공유
        if (Request::is('backoffice*')) {
            View::composer('*', function ($view) {
                $currentPath = Request::path();
                $currentMenu = AdminMenu::getCurrentMenu($currentPath);

                // 현재 메뉴가 있으면 타이틀 생성, 없으면 기본 타이틀 사용
                $menuTitle = $currentMenu ? $currentMenu->name : '백오피스';
                $title = "백오피스 - {$menuTitle}";

                $view->with('menuTitle', $menuTitle);
                $view->with('title', $title);
                
                // 사이드바 데이터 추가 (로그인한 관리자 권한에 맞는 메뉴만 표시)
                $view->with('siteTitle', \App\Models\Setting::getValue('site_title', '관리자'));
                $view->with('mainMenus', auth()->check()
                    ? \App\Models\AdminMenu::getMainMenusForUser(auth()->user())
                    : collect());
            });
        }

        // 쿼리 로깅 활성화 (디버깅용)
        if (config('app.debug')) {
            DB::listen(function ($query) {
                Log::info(
                    'SQL 쿼리 실행',
                    [
                        'sql' => $query->sql,
                        'bindings' => $query->bindings,
                        'time' => $query->time
                    ]
                );
            });
        }

        // Socialite 네이버/카카오 프로바이더 등록
        Event::listen(function (SocialiteWasCalled $event) {
            $event->extendSocialite('naver', \SocialiteProviders\Naver\Provider::class);
            $event->extendSocialite('kakao', \SocialiteProviders\Kakao\KakaoProvider::class);
        });
    }
}
