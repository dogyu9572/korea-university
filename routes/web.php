<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SubController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\NoticeController;
use App\Http\Controllers\EducationCertificationController;
use App\Http\Controllers\SeminarTrainingController;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\Backoffice\PopupController;

// =============================================================================
// 기본 라우트 파일
// =============================================================================

// 메인 페이지
Route::get('/', [HomeController::class, 'index'])->name('home');

// 교육 · 자격증 (안내 퍼블: SubController, 신청 관련: EducationCertificationController)
Route::prefix('education_certification')->name('education_certification.')->group(function () {
    Route::get('/education', [SubController::class, 'education'])->name('education');
    Route::get('/certification', [SubController::class, 'certification'])->name('certification');
    Route::get('/application_ec', [EducationCertificationController::class, 'application_ec'])->name('application_ec');
    Route::get('/application_ec_view/{id}', [EducationCertificationController::class, 'application_ec_view'])->name('application_ec_view');
    Route::get('/application_ec_view_type2/{id}', [EducationCertificationController::class, 'application_ec_view_type2'])->name('application_ec_view_type2');
    Route::get('/application_ec_view_online/{id}', [EducationCertificationController::class, 'application_ec_view_online'])->name('application_ec_view_online');

    Route::middleware('member')->group(function () {
        Route::get('/application_ec_apply', [EducationCertificationController::class, 'application_ec_apply'])->name('application_ec_apply');
        Route::post('/application_ec_apply', [EducationCertificationController::class, 'storeEducationApplication'])->name('application_ec_apply.store');
        Route::get('/application_ec_apply_end', [EducationCertificationController::class, 'application_ec_apply_end'])->name('application_ec_apply_end');

        Route::get('/application_ec_receipt', [EducationCertificationController::class, 'application_ec_receipt'])->name('application_ec_receipt');
        Route::post('/application_ec_receipt', [EducationCertificationController::class, 'storeCertificationApplication'])->name('application_ec_receipt.store');
        Route::get('/application_ec_receipt_end', [EducationCertificationController::class, 'application_ec_receipt_end'])->name('application_ec_receipt_end');

        Route::get('/application_ec_e_learning', [EducationCertificationController::class, 'application_ec_e_learning'])->name('application_ec_e_learning');
        Route::post('/application_ec_e_learning', [EducationCertificationController::class, 'storeOnlineEducationApplication'])->name('application_ec_e_learning.store');
    });
});


// 세미나 · 해외연수 (안내 퍼블: SubController, 신청 관련: SeminarTrainingController)
Route::prefix('seminars_training')->name('seminars_training.')->group(function () {
    Route::get('/seminar', [SubController::class, 'seminar'])->name('seminar');
    Route::get('/overseas_training', [SubController::class, 'overseas_training'])->name('overseas_training');
    Route::get('/application_st', [SeminarTrainingController::class, 'application_st'])->name('application_st');
    Route::get('/application_st_view/{id}', [SeminarTrainingController::class, 'application_st_view'])->name('application_st_view')->whereNumber('id');

    Route::middleware('member')->group(function () {
        Route::get('/application_st_apply', [SeminarTrainingController::class, 'application_st_apply'])->name('application_st_apply');
        Route::post('/application_st_apply', [SeminarTrainingController::class, 'storeSeminarTrainingApplication'])->name('application_st_apply.store');
        Route::get('/application_st_apply_end', [SeminarTrainingController::class, 'application_st_apply_end'])->name('application_st_apply_end');
        Route::get('/roommate_check', [SeminarTrainingController::class, 'checkRoommateMember'])->name('roommate_check');
    });
});

// 알림마당 (게시판 연동) - NoticeController
Route::prefix('notice')->name('notice.')->group(function () {
    Route::get('/notice', [NoticeController::class, 'notice'])->name('notice');
    Route::get('/notice_view/{id}', [NoticeController::class, 'noticeView'])->name('notice_view')->whereNumber('id');
    Route::get('/faq', [NoticeController::class, 'faq'])->name('faq');
    Route::get('/data_room', [NoticeController::class, 'dataRoom'])->name('data_room');
    Route::get('/data_room_view/{id}', [NoticeController::class, 'dataRoomView'])->name('data_room_view')->whereNumber('id');
    Route::get('/past_events', [NoticeController::class, 'pastEvents'])->name('past_events');
    Route::get('/past_events_view/{id}', [NoticeController::class, 'pastEventsView'])->name('past_events_view')->whereNumber('id');
    Route::get('/recruitment', [NoticeController::class, 'recruitment'])->name('recruitment');
    Route::get('/recruitment_view/{id}', [NoticeController::class, 'recruitmentView'])->name('recruitment_view')->whereNumber('id');
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

// 마이페이지 - MypageController (회원 로그인 필요)
Route::prefix('mypage')->name('mypage.')->middleware('member')->group(function () {
    Route::get('/application_status', [MypageController::class, 'application_status'])->name('application_status');
    Route::post('/application_status/cancel', [MypageController::class, 'application_status_cancel'])->name('application_status.cancel');
    Route::get('/application_status_view/{id}', [MypageController::class, 'application_status_view'])->name('application_status_view');
    Route::get('/application_status_view2/{id}', [MypageController::class, 'application_status_view2'])->name('application_status_view2');
    Route::get('/print/receipt/{id}', [MypageController::class, 'printReceipt'])->name('print.receipt')->whereNumber('id');
    Route::get('/print/certificate_completion/{id}', [MypageController::class, 'printCertificateCompletion'])->name('print.certificate_completion')->whereNumber('id');
    Route::get('/print/certificate_finish/{id}', [MypageController::class, 'printCertificateFinish'])->name('print.certificate_finish')->whereNumber('id');
    Route::get('/print/admission_ticket/{id}', [MypageController::class, 'printAdmissionTicket'])->name('print.admission_ticket')->whereNumber('id');
    Route::get('/print/certificate_qualification/{id}', [MypageController::class, 'printCertificateQualification'])->name('print.certificate_qualification')->whereNumber('id');
    Route::get('/print/qualification_certificate/{id}', [MypageController::class, 'printQualificationCertificate'])->name('print.qualification_certificate')->whereNumber('id');
    Route::get('/my_qualification', [MypageController::class, 'my_qualification'])->name('my_qualification');
    Route::get('/my_qualification_view/{id}', [MypageController::class, 'my_qualification_view'])->name('my_qualification_view')->whereNumber('id');
    Route::get('/my_inquiries', [MypageController::class, 'my_inquiries'])->name('my_inquiries');
    Route::post('/my_inquiries', [MypageController::class, 'my_inquiries_store'])->name('my_inquiries.store');
    Route::get('/my_inquiries_view/{id}', [MypageController::class, 'my_inquiries_view'])->name('my_inquiries_view');
    Route::get('/my_inquiries_write', [MypageController::class, 'my_inquiries_write'])->name('my_inquiries_write');
    Route::get('/edit_member_information', [MypageController::class, 'edit_member_information'])->name('edit_member_information');
    Route::put('/edit_member_information', [MypageController::class, 'update_member_information'])->name('edit_member_information.update');
    Route::post('/secession', [MypageController::class, 'secession'])->name('secession');
});

// 회원 (로그인/가입/ID·PW 찾기) - MemberController
Route::prefix('member')->name('member.')->group(function () {
    Route::get('/login', [MemberController::class, 'login'])->name('login');
    Route::post('/login', [MemberController::class, 'postLogin'])->name('login.post');
    Route::get('/login/naver', [MemberController::class, 'redirectToNaver'])->name('login.naver');
    Route::get('/login/naver/callback', [MemberController::class, 'handleNaverCallback'])->name('login.naver.callback');
    Route::get('/login/kakao', [MemberController::class, 'redirectToKakao'])->name('login.kakao');
    Route::get('/login/kakao/callback', [MemberController::class, 'handleKakaoCallback'])->name('login.kakao.callback');
    Route::post('/logout', [MemberController::class, 'logout'])->name('logout');
    Route::get('/join', [MemberController::class, 'join'])->name('join');
    Route::post('/join', [MemberController::class, 'store'])->name('join.store');
    Route::post('/check-email', [MemberController::class, 'checkEmail'])->name('check_email');
    Route::post('/check-phone', [MemberController::class, 'checkPhone'])->name('check_phone');
    Route::get('/schools', [MemberController::class, 'schools'])->name('schools');
    Route::get('/join_easy', [MemberController::class, 'join_easy'])->name('join_easy');
    Route::get('/join_end', [MemberController::class, 'join_end'])->name('join_end');
    Route::get('/find_id', [MemberController::class, 'find_id'])->name('find_id');
    Route::post('/find_id', [MemberController::class, 'postFindId'])->name('find_id.post');
    Route::get('/find_id_end', [MemberController::class, 'find_id_end'])->name('find_id_end');
    Route::get('/find_pw', [MemberController::class, 'find_pw'])->name('find_pw');
    Route::post('/find_pw', [MemberController::class, 'postFindPw'])->name('find_pw.post');
    Route::get('/find_pw_reset', [MemberController::class, 'find_pw_reset'])->name('find_pw_reset');
    Route::post('/find_pw_reset', [MemberController::class, 'postFindPwReset'])->name('find_pw_reset.post');
    Route::get('/find_pw_end', [MemberController::class, 'find_pw_end'])->name('find_pw_end');
    Route::get('/pop_search_school', [MemberController::class, 'pop_search_school'])->name('pop_search_school');
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