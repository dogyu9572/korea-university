<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SubController;
use App\Http\Controllers\Backoffice\PopupController;

// =============================================================================
// 기본 라우트 파일
// =============================================================================

// 메인 페이지
Route::get('/', [HomeController::class, 'index'])->name('home');

// 교육 · 자격증 라우트
Route::prefix('education_certification')->name('education_certification.')->group(function () {
    Route::get('/education', [SubController::class, 'education'])->name('education');
});
Route::prefix('education_certification')->name('education_certification.')->group(function () {
    Route::get('/certification', [SubController::class, 'certification'])->name('certification');
});
Route::prefix('education_certification')->name('education_certification.')->group(function () {
    Route::get('/application_ec', [SubController::class, 'application_ec'])->name('application_ec');
});
Route::prefix('education_certification')->name('education_certification.')->group(function () {
    Route::get('/application_ec_view', [SubController::class, 'application_ec_view'])->name('application_ec_view');
});
Route::prefix('education_certification')->name('education_certification.')->group(function () {
    Route::get('/application_ec_apply', [SubController::class, 'application_ec_apply'])->name('application_ec_apply');
});
Route::prefix('education_certification')->name('education_certification.')->group(function () {
    Route::get('/application_ec_apply_end', [SubController::class, 'application_ec_apply_end'])->name('application_ec_apply_end');
});
Route::prefix('education_certification')->name('education_certification.')->group(function () {
    Route::get('/application_ec_view_type2', [SubController::class, 'application_ec_view_type2'])->name('application_ec_view_type2');
});
Route::prefix('education_certification')->name('education_certification.')->group(function () {
    Route::get('/application_ec_receipt', [SubController::class, 'application_ec_receipt'])->name('application_ec_receipt');
});
Route::prefix('education_certification')->name('education_certification.')->group(function () {
    Route::get('/application_ec_receipt_end', [SubController::class, 'application_ec_receipt_end'])->name('application_ec_receipt_end');
});
Route::prefix('education_certification')->name('education_certification.')->group(function () {
    Route::get('/application_ec_e-learning', [SubController::class, 'application_ec_e_learning'])
        ->name('application_ec_e_learning');
});


//세미나 · 해외연수
Route::prefix('seminars_training')->name('seminars_training.')->group(function () {
    Route::get('/seminar', [SubController::class, 'seminar'])->name('seminar');
});
Route::prefix('seminars_training')->name('seminars_training.')->group(function () {
    Route::get('/overseas_training', [SubController::class, 'overseas_training'])->name('overseas_training');
});
Route::prefix('seminars_training')->name('seminars_training.')->group(function () {
    Route::get('/application_st', [SubController::class, 'application_st'])->name('application_st');
});
Route::prefix('seminars_training')->name('seminars_training.')->group(function () {
    Route::get('/application_st_view', [SubController::class, 'application_st_view'])->name('application_st_view');
});
Route::prefix('seminars_training')->name('seminars_training.')->group(function () {
    Route::get('/application_st_apply', [SubController::class, 'application_st_apply'])->name('application_st_apply');
});
Route::prefix('seminars_training')->name('seminars_training.')->group(function () {
    Route::get('/application_st_apply_end', [SubController::class, 'application_st_apply_end'])->name('application_st_apply_end');
});

//알림마당
Route::prefix('notice')->name('notice.')->group(function () {
    Route::get('/notice', [SubController::class, 'notice'])->name('notice');
});
Route::prefix('notice')->name('notice.')->group(function () {
    Route::get('/notice_view', [SubController::class, 'notice_view'])->name('notice_view');
});
Route::prefix('notice')->name('notice.')->group(function () {
    Route::get('/faq', [SubController::class, 'faq'])->name('faq');
});
Route::prefix('notice')->name('notice.')->group(function () {
    Route::get('/data_room', [SubController::class, 'data_room'])->name('data_room');
});
Route::prefix('notice')->name('notice.')->group(function () {
    Route::get('/data_room_view', [SubController::class, 'data_room_view'])->name('data_room_view');
});
Route::prefix('notice')->name('notice.')->group(function () {
    Route::get('/past_events', [SubController::class, 'past_events'])->name('past_events');
});
Route::prefix('notice')->name('notice.')->group(function () {
    Route::get('/past_events_view', [SubController::class, 'past_events_view'])->name('past_events_view');
});
Route::prefix('notice')->name('notice.')->group(function () {
    Route::get('/recruitment', [SubController::class, 'recruitment'])->name('recruitment');
});
Route::prefix('notice')->name('notice.')->group(function () {
    Route::get('/recruitment_view', [SubController::class, 'recruitment_view'])->name('recruitment_view');
});

//협회 소개
Route::prefix('about')->name('about.')->group(function () {
    Route::get('/establishment', [SubController::class, 'establishment'])->name('establishment');
});
Route::prefix('about')->name('about.')->group(function () {
    Route::get('/projects', [SubController::class, 'projects'])->name('projects');
});
Route::prefix('about')->name('about.')->group(function () {
    Route::get('/history', [SubController::class, 'history'])->name('history');
});
Route::prefix('about')->name('about.')->group(function () {
    Route::get('/bylaws', [SubController::class, 'bylaws'])->name('bylaws');
});
Route::prefix('about')->name('about.')->group(function () {
    Route::get('/organizational', [SubController::class, 'organizational'])->name('organizational');
});
Route::prefix('about')->name('about.')->group(function () {
    Route::get('/about_institutions', [SubController::class, 'about_institutions'])->name('about_institutions');
});

//마이페이지
Route::prefix('mypage')->name('mypage.')->group(function () {
    Route::get('/application_status', [SubController::class, 'application_status'])->name('application_status');
});
Route::prefix('mypage')->name('mypage.')->group(function () {
    Route::get('/application_status_view', [SubController::class, 'application_status_view'])->name('application_status_view');
});
Route::prefix('mypage')->name('mypage.')->group(function () {
    Route::get('/application_status_view2', [SubController::class, 'application_status_view2'])->name('application_status_view2');
});
Route::prefix('mypage')->name('mypage.')->group(function () {
    Route::get('/my_qualification', [SubController::class, 'my_qualification'])->name('my_qualification');
});
Route::prefix('mypage')->name('mypage.')->group(function () {
    Route::get('/my_qualification_view', [SubController::class, 'my_qualification_view'])->name('my_qualification_view');
});
Route::prefix('mypage')->name('mypage.')->group(function () {
    Route::get('/my_inquiries', [SubController::class, 'my_inquiries'])->name('my_inquiries');
});
Route::prefix('mypage')->name('mypage.')->group(function () {
    Route::get('/my_inquiries_view', [SubController::class, 'my_inquiries_view'])->name('my_inquiries_view');
});
Route::prefix('mypage')->name('mypage.')->group(function () {
    Route::get('/my_inquiries_write', [SubController::class, 'my_inquiries_write'])->name('my_inquiries_write');
});
Route::prefix('mypage')->name('mypage.')->group(function () {
    Route::get('/edit_member_information', [SubController::class, 'edit_member_information'])->name('edit_member_information');
});

//로그인,회원가입
Route::prefix('member')->name('member.')->group(function () {
    Route::get('/login', [SubController::class, 'login'])->name('login');
});
Route::prefix('member')->name('member.')->group(function () {
    Route::get('/join', [SubController::class, 'join'])->name('join');
});
Route::prefix('member')->name('member.')->group(function () {
    Route::get('/join_easy', [SubController::class, 'join_easy'])->name('join_easy');
});
Route::prefix('member')->name('member.')->group(function () {
    Route::get('/join_end', [SubController::class, 'join_end'])->name('join_end');
});
Route::prefix('member')->name('member.')->group(function () {
    Route::get('/find_id', [SubController::class, 'find_id'])->name('find_id');
});
Route::prefix('member')->name('member.')->group(function () {
    Route::get('/find_id_end', [SubController::class, 'find_id_end'])->name('find_id_end');
});
Route::prefix('member')->name('member.')->group(function () {
    Route::get('/find_pw', [SubController::class, 'find_pw'])->name('find_pw');
});
Route::prefix('member')->name('member.')->group(function () {
    Route::get('/find_pw_reset', [SubController::class, 'find_pw_reset'])->name('find_pw_reset');
});
Route::prefix('member')->name('member.')->group(function () {
    Route::get('/find_pw_end', [SubController::class, 'find_pw_end'])->name('find_pw_end');
});
Route::prefix('member')->name('member.')->group(function () {
    Route::get('/pop_search_school', [SubController::class, 'pop_search_school'])->name('pop_search_school');
});

//약관
Route::prefix('terms')->name('terms.')->group(function () {
    Route::get('/terms', [SubController::class, 'terms'])->name('terms');
});
Route::prefix('terms')->name('terms.')->group(function () {
    Route::get('/txt_terms', [SubController::class, 'txt_terms'])->name('txt_terms');
});
Route::prefix('terms')->name('terms.')->group(function () {
    Route::get('/privacy_policy', [SubController::class, 'privacy_policy'])->name('privacy_policy');
});
Route::prefix('terms')->name('terms.')->group(function () {
    Route::get('/txt_privacy_policy', [SubController::class, 'txt_privacy_policy'])->name('txt_privacy_policy');
});
Route::prefix('terms')->name('terms.')->group(function () {
    Route::get('/email_collection', [SubController::class, 'email_collection'])->name('email_collection');
});
Route::prefix('terms')->name('terms.')->group(function () {
    Route::get('/txt_email_collection', [SubController::class, 'txt_email_collection'])->name('txt_email_collection');
});

//인쇄
Route::prefix('print')->name('print.')->group(function () {
    Route::get('/admission_ticket', [SubController::class, 'admission_ticket'])->name('admission_ticket');
});
Route::prefix('print')->name('print.')->group(function () {
    Route::get('/receipt', [SubController::class, 'receipt'])->name('receipt');
});
Route::prefix('print')->name('print.')->group(function () {
    Route::get('/certificate', [SubController::class, 'certificate'])->name('certificate');
});
Route::prefix('print')->name('print.')->group(function () {
    Route::get('/certificate_qualification', [SubController::class, 'certificate_qualification'])->name('certificate_qualification');
});
Route::prefix('print')->name('print.')->group(function () {
    Route::get('/certificate_completion', [SubController::class, 'certificate_completion'])->name('certificate_completion');
});
Route::prefix('print')->name('print.')->group(function () {
    Route::get('/certificate_finish', [SubController::class, 'certificate_finish'])->name('certificate_finish');
});
Route::prefix('print')->name('print.')->group(function () {
    Route::get('/pop_print', [SubController::class, 'pop_print'])->name('pop_print');
});


// =============================================================================
// 분리된 라우트 파일들 포함
// =============================================================================

// 팝업 표시 (일반 팝업용)
Route::get('/popup/{popup}', [PopupController::class, 'showPopup'])->name('popup.show');

// 인증 관련 라우트
Route::prefix('auth')->name('auth.')->group(function () {
    // 로그인
    /*Route::get('/login', [LoginController::class, 'showLoginForm'])
        ->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/logout', [LoginController::class, 'logout'])
        ->name('logout');*/

    // 회원가입
    /*Route::get('/register', [RegisterController::class, 'showRegistrationForm'])
        ->name('register');
    Route::post('/register', [RegisterController::class, 'register']);*/

    // 비밀번호 재설정
    /*Route::prefix('password')->name('password.')->group(function () {
        Route::get('/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])
            ->name('request');
        Route::post('/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])
            ->name('email');
        Route::get('/reset/{token}', [ResetPasswordController::class, 'showResetForm'])
            ->name('reset');
        Route::post('/reset', [ResetPasswordController::class, 'reset'])
            ->name('update');
    });*/
});

// 백오피스 라우트 (관리자 전용)
require __DIR__.'/backoffice.php';