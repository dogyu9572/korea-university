<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backoffice\AuthController;
use App\Http\Controllers\Backoffice\AdminMenuController;
use App\Http\Controllers\Backoffice\CategoryController;
use App\Http\Controllers\Backoffice\SettingController;
use App\Http\Controllers\Backoffice\BoardController;
use App\Http\Controllers\Backoffice\BoardTemplateController;
use App\Http\Controllers\Backoffice\BoardSkinController;
use App\Http\Controllers\Backoffice\BoardPostController;
use App\Http\Controllers\Backoffice\UserController;
use App\Http\Controllers\Backoffice\LogController;
use App\Http\Controllers\Backoffice\AdminController;
use App\Http\Controllers\Backoffice\AdminGroupController;
use App\Http\Controllers\Backoffice\BannerController;
use App\Http\Controllers\Backoffice\PopupController;
use App\Http\Controllers\Backoffice\AccessStatisticsController;
use App\Http\Controllers\Backoffice\EducationStatisticsController;
use App\Http\Controllers\Backoffice\CertificationStatisticsController;
use App\Http\Controllers\Backoffice\OrganizationalController;
use App\Http\Controllers\Backoffice\HistoryController;
use App\Http\Controllers\Backoffice\MemberController;
use App\Http\Controllers\Backoffice\SchoolController;
use App\Http\Controllers\Backoffice\InquiryController;
use App\Http\Controllers\Backoffice\EducationController;
use App\Http\Controllers\Backoffice\EducationProgramController;
use App\Http\Controllers\Backoffice\OnlineEducationController;
use App\Http\Controllers\Backoffice\CertificationController;
use App\Http\Controllers\Backoffice\SeminarTrainingController;
use App\Http\Controllers\Backoffice\LectureVideoController;
use App\Http\Controllers\Backoffice\EducationApplicationController;

// =============================================================================
// 백오피스 인증 라우트
// =============================================================================
Route::prefix('backoffice')->name('backoffice.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])
        ->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/logout', [AuthController::class, 'logout'])
        ->name('logout');
});

// =============================================================================
// 백오피스 라우트 (관리자 전용)
// =============================================================================

Route::prefix('backoffice')->middleware(['backoffice'])->group(function () {
    
    // 대시보드
    Route::get('/', [App\Http\Controllers\Backoffice\DashboardController::class, 'index'])
        ->name('backoffice.dashboard');
    
    // 대시보드 API
    Route::get('/api/statistics', [App\Http\Controllers\Backoffice\DashboardController::class, 'statistics'])
        ->name('backoffice.api.statistics');

    // -------------------------------------------------------------------------
    // 시스템 관리
    // -------------------------------------------------------------------------

    // 관리자 메뉴 관리
    Route::resource('admin-menus', AdminMenuController::class, [
        'names' => 'backoffice.admin-menus'
    ])->except(['show']);

    // 메뉴 순서 업데이트
    Route::post('admin-menus/update-order', [AdminMenuController::class, 'updateOrder'])
        ->name('backoffice.admin-menus.update-order');
    
    // 메뉴 부모 업데이트 (드래그로 메뉴 이동)
    Route::post('admin-menus/update-parent', [AdminMenuController::class, 'updateParent'])
        ->name('backoffice.admin-menus.update-parent');

    // 카테고리 관리
    // 카테고리 순서 업데이트 (resource 라우트보다 앞에 위치)
    Route::post('categories/update-order', [CategoryController::class, 'updateOrder'])
        ->name('backoffice.categories.update-order');

    // 활성 카테고리 조회 (AJAX - resource 라우트보다 앞에 위치)
    Route::get('categories/active/{group}', [CategoryController::class, 'getActiveCategories'])
        ->name('backoffice.categories.active');

    // 특정 그룹의 1차 카테고리 조회 (AJAX)
    Route::get('categories/get-by-group/{groupId}', [CategoryController::class, 'getByGroup'])
        ->name('backoffice.categories.get-by-group');

    // 카테고리 수정용 데이터 조회 (AJAX)
    Route::get('categories/{category}/edit-data', [CategoryController::class, 'getEditData'])
        ->name('backoffice.categories.edit-data');

    // 인라인 수정 (AJAX)
    Route::post('categories/{category}/update-inline', [CategoryController::class, 'updateInline'])
        ->name('backoffice.categories.update-inline');

    // 모달 등록 (AJAX)
    Route::post('categories/store-modal', [CategoryController::class, 'storeModal'])
        ->name('backoffice.categories.store-modal');

    // 모달 수정 (AJAX)
    Route::put('categories/update-modal', [CategoryController::class, 'updateModal'])
        ->name('backoffice.categories.update-modal');

    // 미리 생성될 코드 조회 (AJAX)
    Route::post('categories/generate-preview-code', [CategoryController::class, 'generatePreviewCode'])
        ->name('backoffice.categories.generate-preview-code');

    Route::resource('categories', CategoryController::class, [
        'names' => 'backoffice.categories'
    ])->except(['show']);

    // 기본설정 관리
    Route::get('setting', [SettingController::class, 'index'])
        ->name('backoffice.setting.index');
    Route::post('setting', [SettingController::class, 'update'])
        ->name('backoffice.setting.update');

    // 접속 로그 관리
    Route::get('logs/access', [LogController::class, 'access'])
        ->name('backoffice.logs.access');
    Route::get('user-access-logs', [LogController::class, 'userAccessLogs'])
        ->name('backoffice.user-access-logs');
    Route::get('admin-access-logs', [LogController::class, 'adminAccessLogs'])
        ->name('backoffice.admin-access-logs');
    
    // 통계 관리
    Route::get('access-statistics', [AccessStatisticsController::class, 'index'])
        ->name('backoffice.access-statistics');
    Route::get('access-statistics/get-statistics', [AccessStatisticsController::class, 'getStatistics'])
        ->name('backoffice.access-statistics.get-statistics');
    Route::get('education-statistics', [EducationStatisticsController::class, 'index'])
        ->name('backoffice.education-statistics');
    Route::get('education-statistics/export', [EducationStatisticsController::class, 'export'])
        ->name('backoffice.education-statistics.export');
    Route::get('certification-statistics', [CertificationStatisticsController::class, 'index'])
        ->name('backoffice.certification-statistics');
    Route::get('certification-statistics/export', [CertificationStatisticsController::class, 'export'])
        ->name('backoffice.certification-statistics.export');

    // 관리자 계정 관리
    Route::post('admins/bulk-destroy', [AdminController::class, 'bulkDestroy'])
        ->name('backoffice.admins.bulk-destroy');
    Route::post('admins/check-login-id', [AdminController::class, 'checkLoginId'])
        ->name('backoffice.admins.check-login-id');
    Route::resource('admins', AdminController::class, [
        'names' => 'backoffice.admins'
    ]);

    // 관리자 권한 그룹 관리
    Route::resource('admin-groups', AdminGroupController::class, [
        'names' => 'backoffice.admin-groups'
    ])->except(['show']);

    // 권한 그룹 권한 설정
    Route::get('admin-groups/{admin_group}/permissions', [AdminGroupController::class, 'editPermissions'])
        ->name('backoffice.admin-groups.permissions.edit');
    Route::post('admin-groups/{admin_group}/permissions', [AdminGroupController::class, 'updatePermissions'])
        ->name('backoffice.admin-groups.permissions.update');

    // -------------------------------------------------------------------------
    // 콘텐츠 관리
    // -------------------------------------------------------------------------

    // 이미지 업로드
    Route::post('upload-image', function (Request $request) {
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = $file->store('uploads/editor', 'public');

            return response()->json([
                'uploaded' => true,
                'url' => asset('storage/' . $path)
            ]);
        }

        return response()->json([
            'uploaded' => false,
            'error' => ['message' => '이미지 업로드에 실패했습니다.']
        ]);
    });

    // 정렬 순서 업데이트
    Route::post('board-posts/update-sort-order', [BoardPostController::class, 'updateSortOrder'])->name('backoffice.board-posts.update-sort-order');

    // 게시글 관리 (특정 게시판)
    Route::prefix('board-posts/{slug}')->name('backoffice.board-posts.')->group(function () {
        Route::get('/', [BoardPostController::class, 'index'])->name('index');
        Route::get('/create', [BoardPostController::class, 'create'])->name('create');
        Route::post('/', [BoardPostController::class, 'store'])->name('store');
        Route::get('/{post}', [BoardPostController::class, 'show'])->name('show');
        Route::get('/{post}/edit', [BoardPostController::class, 'edit'])->name('edit');
        Route::put('/{post}', [BoardPostController::class, 'update'])->name('update');
        Route::delete('/{post}', [BoardPostController::class, 'destroy'])->name('destroy');
        Route::post('/bulk-destroy', [BoardPostController::class, 'bulkDestroy'])->name('bulk_destroy');
    });

    // 게시판 관리
    Route::resource('boards', BoardController::class, [
        'names' => 'backoffice.boards'
    ])->except(['show']); // show는 제외 (게시글 목록과 충돌)

    // 게시판 템플릿 관리
    Route::resource('board-templates', BoardTemplateController::class, [
        'names' => 'backoffice.board-templates',
        'parameters' => ['board-templates' => 'boardTemplate']
    ]);

    // 게시판 템플릿 추가 기능
    Route::post('board-templates/{boardTemplate}/duplicate', [BoardTemplateController::class, 'duplicate'])
        ->name('backoffice.board-templates.duplicate');
    Route::get('board-templates/{boardTemplate}/data', [BoardTemplateController::class, 'getTemplateData'])
        ->name('backoffice.board-templates.data');

    // 게시판 스킨 관리
    Route::resource('board-skins', BoardSkinController::class, [
        'names' => 'backoffice.board-skins',
        'parameters' => ['board-skins' => 'boardSkin']
    ]);

    // 게시판 스킨 템플릿 편집
    Route::prefix('board-skins/{boardSkin}')->name('backoffice.board-skins.')->group(function () {
        Route::get('template', [BoardSkinController::class, 'editTemplate'])
            ->name('edit_template');
        Route::post('template', [BoardSkinController::class, 'updateTemplate'])
            ->name('update_template');
    });

    // 게시글 관리
    Route::resource('posts', BoardPostController::class, [
        'names' => 'backoffice.posts'
    ]);

    // 회원 관리
    Route::resource('users', UserController::class, [
        'names' => 'backoffice.users'
    ]);

    // 배너 관리
    Route::resource('banners', BannerController::class, [
        'names' => 'backoffice.banners'
    ]);
    Route::post('banners/update-order', [BannerController::class, 'updateOrder'])->name('backoffice.banners.update-order');

    // 팝업 관리
    Route::resource('popups', PopupController::class, [
        'names' => 'backoffice.popups'
    ]);
    Route::post('popups/update-order', [PopupController::class, 'updateOrder'])->name('backoffice.popups.update-order');

    // 조직도 관리
    Route::get('organizational', [OrganizationalController::class, 'index'])->name('backoffice.organizational.index');
    Route::post('organizational/chart', [OrganizationalController::class, 'updateChart'])->name('backoffice.organizational.update-chart');
    Route::post('organizational-members', [OrganizationalController::class, 'storeMember'])->name('backoffice.organizational.store-member');
    Route::match(['put', 'post'], 'organizational-members/{id}', [OrganizationalController::class, 'updateMember'])->name('backoffice.organizational.update-member');
    Route::delete('organizational-members/{id}', [OrganizationalController::class, 'destroyMember'])->name('backoffice.organizational.destroy-member');
    Route::post('organizational-members/update-order', [OrganizationalController::class, 'updateOrder'])->name('backoffice.organizational.update-order');

    // 연혁 관리
    Route::get('history', [HistoryController::class, 'index'])->name('backoffice.history.index');
    Route::post('histories', [HistoryController::class, 'store'])->name('backoffice.history.store');
    Route::put('histories/{id}', [HistoryController::class, 'update'])->name('backoffice.history.update');
    Route::delete('histories/{id}', [HistoryController::class, 'destroy'])->name('backoffice.history.destroy');

    // 교육 안내 관리
    Route::get('education', [EducationController::class, 'index'])->name('backoffice.education.index');
    Route::post('education', [EducationController::class, 'update'])->name('backoffice.education.update');

    // 교육 프로그램 관리
    Route::resource('education-programs', EducationProgramController::class, [
        'names' => 'backoffice.education-programs'
    ]);

    // 교육 신청내역 관리
    Route::get('education-applications', [EducationApplicationController::class, 'index'])
        ->name('backoffice.education-applications.index');
    Route::get('education-applications/create', [EducationApplicationController::class, 'create'])
        ->name('backoffice.education-applications.create');
    Route::post('education-applications', [EducationApplicationController::class, 'store'])
        ->name('backoffice.education-applications.store');
    Route::post('education-applications/batch-payment-complete', [EducationApplicationController::class, 'batchPaymentComplete'])
        ->name('backoffice.education-applications.batch-payment-complete');
    Route::post('education-applications/batch-complete', [EducationApplicationController::class, 'batchComplete'])
        ->name('backoffice.education-applications.batch-complete');
    // 구체적인 라우트를 먼저 정의 (순서 중요!)
    Route::get('education-applications/{education_application}/edit', [EducationApplicationController::class, 'edit'])
        ->name('backoffice.education-applications.edit');
    Route::put('education-applications/{education_application}', [EducationApplicationController::class, 'update'])
        ->name('backoffice.education-applications.update');
    Route::delete('education-applications/{education_application}', [EducationApplicationController::class, 'destroy'])
        ->name('backoffice.education-applications.destroy');
    Route::put('education-applications/{program}/status', [EducationApplicationController::class, 'updateStatus'])
        ->name('backoffice.education-applications.update-status');
    Route::post('education-applications/{program}/export', [EducationApplicationController::class, 'export'])
        ->name('backoffice.education-applications.export');
    Route::get('education-applications/{program}/export', [EducationApplicationController::class, 'export'])
        ->name('backoffice.education-applications.export.get');
    // show는 가장 마지막에 (다른 모든 경로와 매칭되지 않을 때)
    Route::get('education-applications/{program}', [EducationApplicationController::class, 'show'])
        ->name('backoffice.education-applications.show');

    // 온라인 교육 신청내역 관리
    Route::get('online-education-applications', [EducationApplicationController::class, 'onlineIndex'])
        ->name('backoffice.online-education-applications.index');
    Route::get('online-education-applications/create', [EducationApplicationController::class, 'onlineCreate'])
        ->name('backoffice.online-education-applications.create');
    Route::post('online-education-applications', [EducationApplicationController::class, 'store'])
        ->name('backoffice.online-education-applications.store');
    Route::post('online-education-applications/batch-payment-complete', [EducationApplicationController::class, 'batchPaymentComplete'])
        ->name('backoffice.online-education-applications.batch-payment-complete');
    Route::post('online-education-applications/batch-complete', [EducationApplicationController::class, 'batchComplete'])
        ->name('backoffice.online-education-applications.batch-complete');
    Route::get('online-education-applications/{education_application}/edit', [EducationApplicationController::class, 'edit'])
        ->name('backoffice.online-education-applications.edit');
    Route::put('online-education-applications/{education_application}', [EducationApplicationController::class, 'update'])
        ->name('backoffice.online-education-applications.update');
    Route::delete('online-education-applications/{education_application}', [EducationApplicationController::class, 'destroy'])
        ->name('backoffice.online-education-applications.destroy');
    Route::put('online-education-applications/{program}/status', [EducationApplicationController::class, 'updateStatus'])
        ->name('backoffice.online-education-applications.update-status');
    Route::post('online-education-applications/{program}/export', [EducationApplicationController::class, 'export'])
        ->name('backoffice.online-education-applications.export');
    Route::get('online-education-applications/{program}/export', [EducationApplicationController::class, 'export'])
        ->name('backoffice.online-education-applications.export.get');
    Route::get('online-education-applications/{program}', [EducationApplicationController::class, 'onlineShow'])
        ->name('backoffice.online-education-applications.show');

    // 자격증 신청내역 관리
    Route::get('certification-applications', [EducationApplicationController::class, 'certificationIndex'])
        ->name('backoffice.certification-applications.index');
    Route::get('certification-applications/create', [EducationApplicationController::class, 'certificationCreate'])
        ->name('backoffice.certification-applications.create');
    Route::post('certification-applications', [EducationApplicationController::class, 'store'])
        ->name('backoffice.certification-applications.store');
    Route::post('certification-applications/batch-payment-complete', [EducationApplicationController::class, 'batchPaymentComplete'])
        ->name('backoffice.certification-applications.batch-payment-complete');
    Route::post('certification-applications/{program}/scores', [EducationApplicationController::class, 'batchScores'])
        ->name('backoffice.certification-applications.batch-scores');
    Route::get('certification-applications/{education_application}/edit', [EducationApplicationController::class, 'edit'])
        ->name('backoffice.certification-applications.edit');
    Route::put('certification-applications/{education_application}', [EducationApplicationController::class, 'update'])
        ->name('backoffice.certification-applications.update');
    Route::delete('certification-applications/{education_application}', [EducationApplicationController::class, 'destroy'])
        ->name('backoffice.certification-applications.destroy');
    Route::put('certification-applications/{program}/status', [EducationApplicationController::class, 'updateStatus'])
        ->name('backoffice.certification-applications.update-status');
    Route::post('certification-applications/{program}/export', [EducationApplicationController::class, 'export'])
        ->name('backoffice.certification-applications.export');
    Route::get('certification-applications/{program}/export', [EducationApplicationController::class, 'export'])
        ->name('backoffice.certification-applications.export.get');
    Route::get('certification-applications/{program}', [EducationApplicationController::class, 'certificationShow'])
        ->name('backoffice.certification-applications.show');

    // 세미나/해외연수 신청내역 관리
    Route::get('seminar-training-applications', [EducationApplicationController::class, 'seminarTrainingIndex'])
        ->name('backoffice.seminar-training-applications.index');
    Route::get('seminar-training-applications/create', [EducationApplicationController::class, 'seminarTrainingCreate'])
        ->name('backoffice.seminar-training-applications.create');
    Route::post('seminar-training-applications', [EducationApplicationController::class, 'store'])
        ->name('backoffice.seminar-training-applications.store');
    Route::post('seminar-training-applications/batch-payment-complete', [EducationApplicationController::class, 'batchPaymentComplete'])
        ->name('backoffice.seminar-training-applications.batch-payment-complete');
    Route::post('seminar-training-applications/batch-complete', [EducationApplicationController::class, 'batchComplete'])
        ->name('backoffice.seminar-training-applications.batch-complete');
    Route::get('seminar-training-applications/{education_application}/edit', [EducationApplicationController::class, 'edit'])
        ->name('backoffice.seminar-training-applications.edit');
    Route::put('seminar-training-applications/{education_application}', [EducationApplicationController::class, 'update'])
        ->name('backoffice.seminar-training-applications.update');
    Route::delete('seminar-training-applications/{education_application}', [EducationApplicationController::class, 'destroy'])
        ->name('backoffice.seminar-training-applications.destroy');
    Route::get('seminar-training-applications/{education_application}/roommate-requests', [EducationApplicationController::class, 'roommateRequests'])
        ->name('backoffice.seminar-training-applications.roommate-requests');
    Route::post('seminar-training-applications/{education_application}/roommate-requests/approve', [EducationApplicationController::class, 'approveRoommateRequest'])
        ->name('backoffice.seminar-training-applications.roommate-requests.approve');
    Route::post('seminar-training-applications/{education_application}/roommate-requests/reject', [EducationApplicationController::class, 'rejectRoommateRequest'])
        ->name('backoffice.seminar-training-applications.roommate-requests.reject');
    Route::put('seminar-training-applications/{program}/status', [EducationApplicationController::class, 'updateStatus'])
        ->name('backoffice.seminar-training-applications.update-status');
    Route::post('seminar-training-applications/{program}/export', [EducationApplicationController::class, 'export'])
        ->name('backoffice.seminar-training-applications.export');
    Route::get('seminar-training-applications/{program}/export', [EducationApplicationController::class, 'export'])
        ->name('backoffice.seminar-training-applications.export.get');
    Route::get('seminar-training-applications/{program}', [EducationApplicationController::class, 'seminarTrainingShow'])
        ->name('backoffice.seminar-training-applications.show');

    // 증빙 출력 (영수증/수료증/이수증) - 신청 ID 기준
    Route::get('print/receipt/{education_application}', [EducationApplicationController::class, 'printReceipt'])
        ->name('backoffice.print.receipt');
    Route::get('print/certificate_completion/{education_application}', [EducationApplicationController::class, 'printCertificateCompletion'])
        ->name('backoffice.print.certificate_completion');
    Route::get('print/certificate_finish/{education_application}', [EducationApplicationController::class, 'printCertificateFinish'])
        ->name('backoffice.print.certificate_finish');
    Route::get('print/admission_ticket/{education_application}', [EducationApplicationController::class, 'printAdmissionTicket'])
        ->name('backoffice.print.admission_ticket');
    Route::get('print/certificate_qualification/{education_application}', [EducationApplicationController::class, 'printCertificateQualification'])
        ->name('backoffice.print.certificate_qualification');
    Route::get('print/qualification_certificate/{education_application}', [EducationApplicationController::class, 'printQualificationCertificate'])
        ->name('backoffice.print.qualification_certificate');

    // 신청별 즉시 업데이트 (결제상태, 세금계산서, 이수여부)
    Route::patch('applications/{education_application}/payment-status', [EducationApplicationController::class, 'updatePaymentStatus'])
        ->name('backoffice.applications.update-payment-status');
    Route::patch('applications/{education_application}/tax-invoice-status', [EducationApplicationController::class, 'updateTaxInvoiceStatus'])
        ->name('backoffice.applications.update-tax-invoice-status');
    Route::patch('applications/{education_application}/completion-status', [EducationApplicationController::class, 'updateCompletionStatus'])
        ->name('backoffice.applications.update-completion-status');

    // 온라인 교육 관리
    Route::resource('online-educations', OnlineEducationController::class, [
        'names' => 'backoffice.online-educations'
    ]);
    Route::get('online-educations/lectures/search', [OnlineEducationController::class, 'searchLectures'])
        ->name('backoffice.online-educations.lectures.search');
    Route::delete('online-educations/lectures/{id}', [OnlineEducationController::class, 'deleteLecture'])
        ->name('backoffice.online-educations.lectures.delete')
        ->whereNumber('id');

    // 자격증 관리
    Route::resource('certifications', CertificationController::class, [
        'names' => 'backoffice.certifications'
    ]);

    // 세미나/해외연수 관리
    Route::resource('seminar-trainings', SeminarTrainingController::class, [
        'names' => 'backoffice.seminar-trainings',
    ]);

    // 강의 영상 관리
    Route::resource('lecture-videos', LectureVideoController::class, [
        'names' => 'backoffice.lecture-videos',
    ]);

    // 탈퇴회원 관리 (members 제외)
    Route::get('withdrawn', [MemberController::class, 'withdrawn'])->name('backoffice.withdrawn');
    Route::post('withdrawn/{id}/restore', [MemberController::class, 'restore'])->name('backoffice.withdrawn.restore');
    Route::post('withdrawn/{id}/force-delete', [MemberController::class, 'forceDelete'])->name('backoffice.withdrawn.force-delete');
    Route::post('withdrawn/force-delete-multiple', [MemberController::class, 'forceDeleteMultiple'])->name('backoffice.withdrawn.force-delete-multiple');
    
    // 회원 관리
    
    Route::resource('members', MemberController::class, [
        'names' => 'backoffice.members'
    ]);
    Route::post('members/check-email', [MemberController::class, 'checkDuplicateEmail'])->name('backoffice.members.check-email');
    Route::post('members/check-phone', [MemberController::class, 'checkDuplicatePhone'])->name('backoffice.members.check-phone');
    Route::post('members/delete-multiple', [MemberController::class, 'destroyMultiple'])->name('backoffice.members.delete-multiple');
    Route::get('members/export', [MemberController::class, 'export'])->name('backoffice.members.export');

    // 학교(회원교) 관리
    Route::resource('schools', SchoolController::class, [
        'names' => 'backoffice.schools'
    ]);
    Route::get('schools/export', [SchoolController::class, 'export'])->name('backoffice.schools.export');

    // 회원 문의 관리
    Route::resource('inquiries', InquiryController::class, [
        'names' => 'backoffice.inquiries'
    ])->except(['create', 'store', 'edit']);
    Route::post('inquiries/{id}/reply', [InquiryController::class, 'updateReply'])
        ->name('backoffice.inquiries.reply');
    Route::post('inquiries/delete-multiple', [InquiryController::class, 'destroyMultiple'])
        ->name('backoffice.inquiries.delete-multiple');

    // 세션 연장
    Route::post('session/extend', [App\Http\Controllers\Backoffice\SessionController::class, 'extend'])
        ->name('backoffice.session.extend');
});
