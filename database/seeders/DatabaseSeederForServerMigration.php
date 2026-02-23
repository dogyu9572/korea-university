<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * 서버 이관용 시더 실행기 (db:export-seeders 로 생성된 ExportSeeder 들을 FK 순서대로 실행)
 *
 * 이관 후 새 서버에서:
 *   1. php artisan migrate  (테이블 생성)
 *   2. php artisan db:seed --class=DatabaseSeederForServerMigration  (위 명령 한 번만 실행)
 * → 현재 서버와 동일한 데이터로 화면에 보임 (제외한 회원/교육/신청/로그 테이블은 빈 상태)
 */
class DatabaseSeederForServerMigration extends Seeder
{
    public function run(): void
    {
        $this->call(UserExportSeeder::class);
        $this->call(AdminGroupExportSeeder::class);
        $this->call(AdminMenuExportSeeder::class);
        $this->call(BoardSkinExportSeeder::class);
        $this->call(CategoryExportSeeder::class);
        $this->call(BoardTemplateExportSeeder::class);
        $this->call(SettingExportSeeder::class);
        $this->call(UserMenuPermissionExportSeeder::class);
        $this->call(AdminGroupMenuPermissionExportSeeder::class);
        $this->call(BoardExportSeeder::class);
        $this->call(BannerExportSeeder::class);
        $this->call(PopupExportSeeder::class);
        $this->call(SchoolExportSeeder::class);
        $this->call(OrganizationalChartExportSeeder::class);
        $this->call(BoardNoticeExportSeeder::class);
        $this->call(BoardBylawExportSeeder::class);
        $this->call(BoardFaqExportSeeder::class);
        $this->call(BoardLibraryExportSeeder::class);
        $this->call(BoardPastEventExportSeeder::class);
        $this->call(BoardRecruitmentExportSeeder::class);
        $this->call(BoardCommentExportSeeder::class);
        $this->call(InquiryExportSeeder::class);
        $this->call(InquiryReplyExportSeeder::class);
        $this->call(InquiryFileExportSeeder::class);
        $this->call(InquiryReplyFileExportSeeder::class);
        $this->call(HistoryExportSeeder::class);
        $this->call(EducationContentExportSeeder::class);
        $this->call(OrganizationalMemberExportSeeder::class);
    }
}