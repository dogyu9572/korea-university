<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * 서버 이관용: create/add/modify 를 하나로 합친 통합 마이그레이션.
 * 새 서버에서 빈 DB에 migrate 시 이 파일만으로 최종 스키마 생성.
 */
return new class extends Migration
{
    public function up(): void
    {
        $this->createUsersAndSessions();
        $this->createCacheTables();
        $this->createJobTables();
        $this->createAdminMenus();
        $this->createAdminGroups();
        $this->createUserMenuPermissions();
        $this->createAdminGroupMenuPermissions();
        $this->createCategories();
        $this->createBoardSkins();
        $this->createBoardTemplates();
        $this->createSettings();
        $this->createBoards();
        $this->createBanners();
        $this->createPopups();
        $this->createBoardNotices();
        $this->createBoardGallerys();
        $this->createBoardGreetings();
        $this->createBoardComments();
        $this->createBoardTest2();
        $this->createVisitorLogs();
        $this->createDailyVisitorStats();
        $this->createUserAccessLogs();
        $this->createAdminAccessLogs();
        $this->createMembers();
        $this->createSchools();
        $this->createOrganizationalCharts();
        $this->createOrganizationalMembers();
        $this->createInquiries();
        $this->createInquiryReplies();
        $this->createInquiryFiles();
        $this->createInquiryReplyFiles();
        $this->createHistories();
        $this->createEducations();
        $this->createEducationAttachments();
        $this->createEducationContents();
        $this->createOnlineEducations();
        $this->createOnlineEducationAttachments();
        $this->createLectureVideos();
        $this->createLectureVideoAttachments();
        $this->createOnlineEducationLectures();
        $this->createCertifications();
        $this->createCertificationAttachments();
        $this->createSeminarTrainings();
        $this->createSeminarTrainingAttachments();
        $this->createEducationApplications();
        $this->createEducationApplicationAttachments();
    }

    public function down(): void
    {
        $tables = [
            'education_application_attachments', 'education_applications', 'seminar_training_attachments',
            'seminar_trainings', 'certification_attachments', 'certifications', 'online_education_lectures',
            'lecture_video_attachments', 'lecture_videos', 'online_education_attachments', 'online_educations',
            'education_contents', 'education_attachments', 'educations', 'histories', 'inquiry_reply_files',
            'inquiry_files', 'inquiry_replies', 'inquiries', 'organizational_members', 'organizational_charts',
            'schools', 'members', 'admin_access_logs', 'user_access_logs', 'daily_visitor_stats', 'visitor_logs',
            'board_test2', 'board_comments', 'board_greetings', 'board_gallerys', 'board_notices',
            'popups', 'banners', 'boards', 'settings', 'board_templates', 'board_skins', 'categories',
            'admin_group_menu_permissions', 'user_menu_permissions', 'admin_groups', 'admin_menus',
            'failed_jobs', 'job_batches', 'jobs', 'cache_locks', 'cache', 'sessions', 'password_reset_tokens', 'users',
        ];
        foreach ($tables as $table) {
            Schema::dropIfExists($table);
        }
    }

    private function createUsersAndSessions(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('login_id')->unique()->nullable();
            $table->enum('role', ['super_admin', 'admin', 'manager', 'user'])->default('user');
            $table->foreignId('admin_group_id')->nullable()->comment('관리자 권한 그룹 ID');
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_login_at')->nullable();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('department')->nullable();
            $table->string('position')->nullable();
            $table->string('contact', 50)->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    private function createCacheTables(): void
    {
        Schema::create('cache', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->mediumText('value');
            $table->integer('expiration');
        });
        Schema::create('cache_locks', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->string('owner');
            $table->integer('expiration');
        });
    }

    private function createJobTables(): void
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('queue')->index();
            $table->longText('payload');
            $table->unsignedTinyInteger('attempts');
            $table->unsignedInteger('reserved_at')->nullable();
            $table->unsignedInteger('available_at');
            $table->unsignedInteger('created_at');
        });
        Schema::create('job_batches', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->integer('total_jobs');
            $table->integer('pending_jobs');
            $table->integer('failed_jobs');
            $table->longText('failed_job_ids');
            $table->mediumText('options')->nullable();
            $table->integer('cancelled_at')->nullable();
            $table->integer('created_at');
            $table->integer('finished_at')->nullable();
        });
        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
        });
    }

    private function createAdminMenus(): void
    {
        Schema::create('admin_menus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('admin_menus')->onDelete('cascade');
            $table->string('name');
            $table->string('url')->nullable();
            $table->string('icon')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->string('permission_key', 100)->nullable();
            $table->timestamps();
        });
    }

    private function createAdminGroups(): void
    {
        Schema::create('admin_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('그룹명');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    private function createUserMenuPermissions(): void
    {
        Schema::create('user_menu_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('menu_id')->constrained('admin_menus')->onDelete('cascade');
            $table->boolean('granted')->default(true);
            $table->timestamps();
            $table->unique(['user_id', 'menu_id'], 'unique_user_menu_permission');
            $table->index(['user_id', 'granted']);
            $table->index('menu_id');
        });
    }

    private function createAdminGroupMenuPermissions(): void
    {
        Schema::create('admin_group_menu_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('admin_groups')->onDelete('cascade');
            $table->foreignId('menu_id')->constrained('admin_menus')->onDelete('cascade');
            $table->boolean('granted')->default(true);
            $table->timestamps();
            $table->unique(['group_id', 'menu_id'], 'unique_group_menu_permission');
            $table->index(['group_id', 'granted']);
            $table->index('menu_id');
        });
    }

    private function createCategories(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('categories')->onDelete('cascade');
            $table->string('code', 50)->nullable();
            $table->string('name', 100);
            $table->tinyInteger('depth')->default(0);
            $table->integer('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index('code');
            $table->index('parent_id');
        });
    }

    private function createBoardSkins(): void
    {
        Schema::create('board_skins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('directory');
            $table->text('description')->nullable();
            $table->string('thumbnail')->nullable();
            $table->json('options')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });
    }

    private function createBoardTemplates(): void
    {
        Schema::create('board_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('skin_id');
            $table->json('field_config')->nullable();
            $table->json('custom_fields_config')->nullable();
            $table->boolean('enable_notice')->default(true);
            $table->boolean('enable_sorting')->default(false);
            $table->boolean('enable_category')->default(true);
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');
            $table->boolean('is_single_page')->default(false);
            $table->integer('list_count')->default(15);
            $table->string('permission_read')->default('all');
            $table->string('permission_write')->default('member');
            $table->string('permission_comment')->default('member');
            $table->boolean('is_system')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->foreign('skin_id')->references('id')->on('board_skins');
            $table->index('is_system');
            $table->index('is_active');
        });
    }

    private function createSettings(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('site_title')->nullable();
            $table->string('site_url')->nullable();
            $table->string('admin_email')->nullable();
            $table->string('company_name')->nullable();
            $table->string('company_address')->nullable();
            $table->string('company_tel')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('favicon_path')->nullable();
            $table->text('footer_text')->nullable();
            $table->timestamps();
        });
    }

    private function createBoards(): void
    {
        Schema::create('boards', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('skin_id');
            $table->unsignedBigInteger('template_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('table_created')->default(false);
            $table->integer('list_count')->default(15);
            $table->boolean('enable_notice')->default(true);
            $table->boolean('is_single_page')->default(false);
            $table->boolean('enable_sorting')->default(false);
            $table->integer('hot_threshold')->default(100);
            $table->string('permission_read')->default('all');
            $table->string('permission_write')->default('member');
            $table->string('permission_comment')->default('member');
            $table->timestamps();
            $table->softDeletes();
            $table->json('field_config')->nullable();
            $table->json('custom_fields_config')->nullable();
            $table->foreign('skin_id')->references('id')->on('board_skins');
            $table->foreign('template_id')->references('id')->on('board_templates')->onDelete('set null');
        });
    }

    private function createBanners(): void
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('main_text')->nullable();
            $table->string('sub_text')->nullable();
            $table->string('url')->nullable();
            $table->enum('url_target', ['_self', '_blank'])->default('_self');
            $table->datetime('start_date')->nullable();
            $table->datetime('end_date')->nullable();
            $table->boolean('use_period')->default(false);
            $table->boolean('is_active')->default(true);
            $table->string('desktop_image')->nullable();
            $table->string('mobile_image')->nullable();
            $table->string('video_url')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    private function createPopups(): void
    {
        Schema::create('popups', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->datetime('start_date')->nullable();
            $table->datetime('end_date')->nullable();
            $table->boolean('use_period')->default(false);
            $table->integer('width')->default(400);
            $table->integer('height')->default(300);
            $table->integer('position_top')->default(100);
            $table->integer('position_left')->default(100);
            $table->string('url')->nullable();
            $table->enum('url_target', ['_self', '_blank'])->default('_blank');
            $table->enum('popup_type', ['image', 'html'])->default('image');
            $table->enum('popup_display_type', ['normal', 'layer'])->default('normal');
            $table->string('popup_image')->nullable();
            $table->longText('popup_content')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    private function createBoardNotices(): void
    {
        Schema::create('board_notices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('title');
            $table->text('content');
            $table->string('author_name');
            $table->string('password')->nullable();
            $table->boolean('is_notice')->default(false);
            $table->boolean('is_secret')->default(false);
            $table->boolean('is_active')->default(true);
            $table->string('category')->nullable();
            $table->json('attachments')->nullable();
            $table->integer('view_count')->default(0);
            $table->integer('sort_order')->default(0);
            $table->json('custom_fields')->nullable();
            $table->string('thumbnail')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['is_notice', 'created_at']);
            $table->index(['category', 'created_at']);
            $table->index(['user_id', 'created_at']);
            $table->index('thumbnail');
            $table->index('sort_order');
        });
    }

    private function createBoardGallerys(): void
    {
        Schema::create('board_gallerys', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('title');
            $table->text('content');
            $table->string('author_name');
            $table->string('password')->nullable();
            $table->boolean('is_notice')->default(false);
            $table->boolean('is_secret')->default(false);
            $table->boolean('is_active')->default(true);
            $table->string('category')->nullable();
            $table->json('attachments')->nullable();
            $table->integer('view_count')->default(0);
            $table->integer('sort_order')->default(0);
            $table->json('custom_fields')->nullable();
            $table->string('thumbnail')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['is_notice', 'created_at']);
            $table->index(['category', 'created_at']);
            $table->index(['user_id', 'created_at']);
            $table->index('thumbnail');
            $table->index('sort_order');
        });
    }

    private function createBoardGreetings(): void
    {
        Schema::create('board_greetings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('title');
            $table->text('content');
            $table->string('author_name');
            $table->string('password')->nullable();
            $table->boolean('is_notice')->default(false);
            $table->boolean('is_secret')->default(false);
            $table->boolean('is_active')->default(true);
            $table->string('category')->nullable();
            $table->json('attachments')->nullable();
            $table->integer('view_count')->default(0);
            $table->integer('sort_order')->default(0);
            $table->json('custom_fields')->nullable();
            $table->string('thumbnail')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['is_notice', 'created_at']);
            $table->index(['category', 'created_at']);
            $table->index(['user_id', 'created_at']);
            $table->index('thumbnail');
            $table->index('sort_order');
        });
    }

    private function createBoardComments(): void
    {
        Schema::create('board_comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('post_id');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('author_name');
            $table->string('password')->nullable();
            $table->text('content');
            $table->integer('depth')->default(0);
            $table->boolean('is_secret')->default(false);
            $table->timestamps();
            $table->softDeletes();
            $table->index(['post_id', 'created_at']);
            $table->index('parent_id');
            $table->foreign('parent_id')->references('id')->on('board_comments')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    private function createBoardTest2(): void
    {
        Schema::create('board_test2', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('title');
            $table->text('content');
            $table->string('author_name');
            $table->string('password')->nullable();
            $table->boolean('is_notice')->default(false);
            $table->boolean('is_secret')->default(false);
            $table->string('category')->nullable();
            $table->json('attachments')->nullable();
            $table->integer('view_count')->default(0);
            $table->integer('sort_order')->default(0);
            $table->json('custom_fields')->nullable();
            $table->string('thumbnail')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->index(['is_notice', 'created_at']);
            $table->index(['category', 'created_at']);
            $table->index(['user_id', 'created_at']);
            $table->index('thumbnail');
            $table->index('sort_order');
        });
    }

    private function createVisitorLogs(): void
    {
        Schema::create('visitor_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('ip_address', 45);
            $table->text('user_agent')->nullable();
            $table->string('page_url', 500);
            $table->string('referer', 500)->nullable();
            $table->string('session_id', 100)->nullable();
            $table->boolean('is_unique')->default(false);
            $table->timestamps();
            $table->index('user_id');
            $table->index('created_at');
            $table->index(['ip_address', 'created_at']);
            $table->index(['is_unique', 'created_at']);
        });
    }

    private function createDailyVisitorStats(): void
    {
        Schema::create('daily_visitor_stats', function (Blueprint $table) {
            $table->id();
            $table->date('visit_date')->unique();
            $table->integer('visitor_count')->default(0);
            $table->integer('page_views')->default(0);
            $table->integer('unique_visitors')->default(0);
            $table->timestamps();
            $table->index('visit_date');
        });
    }

    private function createUserAccessLogs(): void
    {
        Schema::create('user_access_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('name');
            $table->string('ip_address', 45);
            $table->text('user_agent')->nullable();
            $table->string('referer', 500)->nullable();
            $table->timestamp('login_at');
            $table->timestamps();
            $table->index('user_id');
            $table->index('login_at');
            $table->index('name');
        });
    }

    private function createAdminAccessLogs(): void
    {
        Schema::create('admin_access_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('name');
            $table->string('ip_address', 45);
            $table->text('user_agent')->nullable();
            $table->string('referer', 500)->nullable();
            $table->timestamp('accessed_at');
            $table->timestamps();
            $table->index('admin_id');
            $table->index('accessed_at');
            $table->index('name');
        });
    }

    private function createMembers(): void
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->enum('join_type', ['email', 'kakao', 'naver']);
            $table->string('email')->nullable()->unique();
            $table->string('login_id')->nullable()->unique();
            $table->string('password')->nullable();
            $table->string('name');
            $table->string('phone_number')->unique();
            $table->string('birth_date', 8)->nullable();
            $table->string('address_postcode')->nullable();
            $table->string('address_base')->nullable();
            $table->string('address_detail')->nullable();
            $table->string('school_name');
            $table->boolean('is_school_representative')->default(false);
            $table->boolean('email_marketing_consent')->default(false);
            $table->timestamp('email_marketing_consent_at')->nullable();
            $table->boolean('kakao_marketing_consent')->default(false);
            $table->timestamp('kakao_marketing_consent_at')->nullable();
            $table->boolean('sms_marketing_consent')->default(false);
            $table->timestamp('terms_agreed_at')->nullable();
            $table->timestamp('withdrawn_at')->nullable();
            $table->timestamps();
            $table->index('name');
            $table->index('school_name');
            $table->index('join_type');
            $table->index('created_at');
        });
    }

    private function createSchools(): void
    {
        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->string('branch');
            $table->integer('year')->nullable()->comment('연도');
            $table->string('school_name');
            $table->boolean('is_member_school')->default(false);
            $table->string('url')->nullable();
            $table->timestamps();
            $table->index('branch');
            $table->index('year');
            $table->index('school_name');
            $table->index('is_member_school');
        });
    }

    private function createOrganizationalCharts(): void
    {
        Schema::create('organizational_charts', function (Blueprint $table) {
            $table->id();
            $table->text('content')->nullable();
            $table->timestamps();
        });
    }

    private function createOrganizationalMembers(): void
    {
        Schema::create('organizational_members', function (Blueprint $table) {
            $table->id();
            $table->enum('category', ['회장', '부회장', '사무국', '지회', '감사', '고문']);
            $table->string('name');
            $table->string('position')->nullable();
            $table->string('affiliation')->nullable();
            $table->string('phone')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    private function createInquiries(): void
    {
        Schema::create('inquiries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('members')->onDelete('cascade');
            $table->enum('category', ['교육', '자격증', '세미나', '해외연수', '기타']);
            $table->string('title');
            $table->text('content');
            $table->enum('status', ['답변대기', '답변완료'])->default('답변대기');
            $table->integer('views')->default(0);
            $table->timestamps();
            $table->index('member_id');
            $table->index('category');
            $table->index('status');
            $table->index('created_at');
        });
    }

    private function createInquiryReplies(): void
    {
        Schema::create('inquiry_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inquiry_id')->constrained('inquiries')->onDelete('cascade');
            $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('author');
            $table->text('content');
            $table->enum('status', ['답변대기', '답변완료'])->default('답변대기');
            $table->date('reply_date')->nullable();
            $table->timestamps();
            $table->index('inquiry_id');
            $table->index('status');
        });
    }

    private function createInquiryFiles(): void
    {
        Schema::create('inquiry_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inquiry_id')->constrained('inquiries')->onDelete('cascade');
            $table->string('file_path');
            $table->string('file_name');
            $table->integer('file_size');
            $table->timestamps();
            $table->index('inquiry_id');
        });
    }

    private function createInquiryReplyFiles(): void
    {
        Schema::create('inquiry_reply_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inquiry_reply_id')->constrained('inquiry_replies')->onDelete('cascade');
            $table->string('file_path');
            $table->string('file_name');
            $table->integer('file_size');
            $table->timestamps();
            $table->index('inquiry_reply_id');
        });
    }

    private function createHistories(): void
    {
        Schema::create('histories', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->date('date');
            $table->date('date_end')->nullable();
            $table->integer('year');
            $table->text('content');
            $table->enum('is_visible', ['Y', 'N'])->default('Y');
            $table->timestamps();
            $table->index('year');
            $table->index('date');
            $table->index('is_visible');
        });
    }

    private function createEducations(): void
    {
        Schema::create('educations', function (Blueprint $table) {
            $table->id();
            $table->enum('education_type', ['정기교육', '수시교육']);
            $table->string('education_class')->nullable();
            $table->boolean('is_public')->default(true);
            $table->string('application_status');
            $table->string('name');
            $table->date('period_start')->nullable();
            $table->date('period_end')->nullable();
            $table->string('period_time')->nullable();
            $table->boolean('is_accommodation')->default(false);
            $table->string('location')->nullable();
            $table->string('target')->nullable();
            $table->string('completion_criteria')->nullable();
            $table->string('survey_url')->nullable();
            $table->string('certificate_type')->default('이수증');
            $table->unsignedInteger('completion_hours')->nullable();
            $table->dateTime('application_start')->nullable();
            $table->dateTime('application_end')->nullable();
            $table->unsignedInteger('capacity')->nullable();
            $table->boolean('capacity_unlimited')->default(false);
            $table->json('payment_methods')->nullable();
            $table->text('deposit_account')->nullable();
            $table->unsignedInteger('deposit_deadline_days')->nullable();
            $table->decimal('fee_member_twin', 10, 2)->nullable();
            $table->decimal('fee_member_single', 10, 2)->nullable();
            $table->decimal('fee_member_no_stay', 10, 2)->nullable();
            $table->decimal('fee_guest_twin', 10, 2)->nullable();
            $table->decimal('fee_guest_single', 10, 2)->nullable();
            $table->decimal('fee_guest_no_stay', 10, 2)->nullable();
            $table->decimal('refund_twin_fee', 10, 2)->nullable();
            $table->decimal('refund_single_fee', 10, 2)->nullable();
            $table->decimal('refund_no_stay_fee', 10, 2)->nullable();
            $table->string('refund_twin_deadline')->nullable();
            $table->string('refund_single_deadline')->nullable();
            $table->string('refund_no_stay_deadline')->nullable();
            $table->decimal('refund_same_day_fee', 10, 2)->nullable();
            $table->string('thumbnail_path')->nullable();
            $table->text('education_overview')->nullable();
            $table->text('education_schedule')->nullable();
            $table->text('fee_info')->nullable();
            $table->text('refund_policy')->nullable();
            $table->text('curriculum')->nullable();
            $table->text('education_notice')->nullable();
            $table->timestamps();
        });
    }

    private function createEducationAttachments(): void
    {
        Schema::create('education_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('education_id')->constrained('educations')->onDelete('cascade');
            $table->string('path');
            $table->string('name');
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
        });
    }

    private function createEducationContents(): void
    {
        Schema::create('education_contents', function (Blueprint $table) {
            $table->id();
            $table->text('education_guide')->nullable();
            $table->text('certification_guide')->nullable();
            $table->text('expert_level_1')->nullable();
            $table->text('expert_level_2')->nullable();
            $table->text('seminar_guide')->nullable();
            $table->text('overseas_training_guide')->nullable();
            $table->timestamps();
        });
    }

    private function createOnlineEducations(): void
    {
        Schema::create('online_educations', function (Blueprint $table) {
            $table->id();
            $table->string('education_class')->nullable();
            $table->boolean('is_public')->default(true);
            $table->string('application_status');
            $table->string('name');
            $table->date('period_start')->nullable();
            $table->date('period_end')->nullable();
            $table->string('period_time')->nullable();
            $table->string('target')->nullable();
            $table->string('completion_criteria')->nullable();
            $table->string('survey_url')->nullable();
            $table->string('certificate_type')->default('이수증');
            $table->unsignedInteger('completion_hours')->nullable();
            $table->dateTime('application_start')->nullable();
            $table->dateTime('application_end')->nullable();
            $table->unsignedInteger('capacity')->nullable();
            $table->boolean('capacity_unlimited')->default(false);
            $table->json('payment_methods')->nullable();
            $table->text('deposit_account')->nullable();
            $table->unsignedInteger('deposit_deadline_days')->nullable();
            $table->decimal('fee', 10, 2)->nullable();
            $table->boolean('is_free')->default(false);
            $table->string('thumbnail_path')->nullable();
            $table->text('education_overview')->nullable();
            $table->text('education_schedule')->nullable();
            $table->text('fee_info')->nullable();
            $table->text('refund_policy')->nullable();
            $table->text('curriculum')->nullable();
            $table->text('education_notice')->nullable();
            $table->timestamps();
        });
    }

    private function createOnlineEducationAttachments(): void
    {
        Schema::create('online_education_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('online_education_id')->constrained('online_educations')->onDelete('cascade');
            $table->string('path');
            $table->string('name');
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
        });
    }

    private function createLectureVideos(): void
    {
        Schema::create('lecture_videos', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->unsignedInteger('lecture_time');
            $table->string('instructor_name');
            $table->string('video_url')->nullable();
            $table->string('thumbnail_path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index('is_active');
            $table->index('created_at');
        });
    }

    private function createLectureVideoAttachments(): void
    {
        Schema::create('lecture_video_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lecture_video_id')->constrained('lecture_videos')->onDelete('cascade');
            $table->string('path');
            $table->string('name');
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
            $table->index('lecture_video_id');
            $table->index('order');
        });
    }

    private function createOnlineEducationLectures(): void
    {
        Schema::create('online_education_lectures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('online_education_id')->constrained('online_educations')->onDelete('cascade');
            $table->foreignId('lecture_video_id')->nullable()->constrained('lecture_videos')->onDelete('set null');
            $table->string('lecture_name');
            $table->string('instructor_name');
            $table->unsignedInteger('lecture_time');
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
            $table->index('order');
        });
    }

    private function createCertifications(): void
    {
        Schema::create('certifications', function (Blueprint $table) {
            $table->id();
            $table->string('level');
            $table->string('name');
            $table->date('exam_date');
            $table->json('venue_category_ids')->nullable();
            $table->string('exam_method')->nullable();
            $table->unsignedInteger('passing_score')->nullable();
            $table->text('eligibility')->nullable();
            $table->text('exam_overview')->nullable();
            $table->text('exam_trend')->nullable();
            $table->text('exam_venue')->nullable();
            $table->string('thumbnail_path')->nullable();
            $table->boolean('is_public')->default(true);
            $table->dateTime('application_start')->nullable();
            $table->dateTime('application_end')->nullable();
            $table->unsignedInteger('capacity')->nullable();
            $table->boolean('capacity_unlimited')->default(false);
            $table->string('application_status');
            $table->json('payment_methods')->nullable();
            $table->text('deposit_account')->nullable();
            $table->unsignedTinyInteger('deposit_deadline_days')->nullable();
            $table->decimal('exam_fee', 10, 2)->nullable()->comment('응시료');
            $table->timestamps();
            $table->index('exam_date');
            $table->index('application_status');
            $table->index('level');
        });
    }

    private function createCertificationAttachments(): void
    {
        Schema::create('certification_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('certification_id')->constrained('certifications')->onDelete('cascade');
            $table->string('path');
            $table->string('name');
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
        });
    }

    private function createSeminarTrainings(): void
    {
        Schema::create('seminar_trainings', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['세미나', '해외연수']);
            $table->string('education_class')->nullable();
            $table->string('total_sessions_class')->nullable()->comment('총차시/기수');
            $table->boolean('is_public')->default(true);
            $table->string('application_status');
            $table->string('name');
            $table->date('period_start')->nullable();
            $table->date('period_end')->nullable();
            $table->string('period_time')->nullable();
            $table->boolean('is_accommodation')->default(false);
            $table->string('location')->nullable();
            $table->string('target')->nullable();
            $table->string('completion_criteria')->nullable();
            $table->string('survey_url')->nullable();
            $table->string('certificate_type')->default('이수증');
            $table->unsignedInteger('completion_hours')->nullable();
            $table->dateTime('application_start')->nullable();
            $table->dateTime('application_end')->nullable();
            $table->unsignedInteger('capacity')->nullable();
            $table->boolean('capacity_unlimited')->default(false);
            $table->unsignedInteger('capacity_per_school')->nullable();
            $table->boolean('capacity_per_school_unlimited')->default(false);
            $table->json('payment_methods')->nullable();
            $table->text('deposit_account')->nullable();
            $table->unsignedInteger('deposit_deadline_days')->nullable();
            $table->decimal('fee_member_twin', 10, 2)->nullable();
            $table->decimal('fee_member_single', 10, 2)->nullable();
            $table->decimal('fee_member_no_stay', 10, 2)->nullable();
            $table->decimal('fee_guest_twin', 10, 2)->nullable();
            $table->decimal('fee_guest_single', 10, 2)->nullable();
            $table->decimal('fee_guest_no_stay', 10, 2)->nullable();
            $table->decimal('refund_twin_fee', 10, 2)->nullable();
            $table->decimal('refund_single_fee', 10, 2)->nullable();
            $table->decimal('refund_no_stay_fee', 10, 2)->nullable();
            $table->string('refund_twin_deadline')->nullable();
            $table->string('refund_single_deadline')->nullable();
            $table->string('refund_no_stay_deadline')->nullable();
            $table->decimal('refund_same_day_fee', 10, 2)->nullable();
            $table->decimal('annual_fee', 10, 2)->nullable();
            $table->string('thumbnail_path')->nullable();
            $table->text('education_overview')->nullable();
            $table->text('education_schedule')->nullable();
            $table->text('fee_info')->nullable();
            $table->text('refund_policy')->nullable();
            $table->text('curriculum')->nullable();
            $table->text('education_notice')->nullable();
            $table->timestamps();
        });
    }

    private function createSeminarTrainingAttachments(): void
    {
        Schema::create('seminar_training_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seminar_training_id')->constrained('seminar_trainings')->onDelete('cascade');
            $table->string('path');
            $table->string('name');
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
        });
    }

    private function createEducationApplications(): void
    {
        Schema::create('education_applications', function (Blueprint $table) {
            $table->id();
            $table->string('application_number')->unique();
            $table->foreignId('education_id')->nullable()->constrained('educations')->onDelete('cascade');
            $table->foreignId('online_education_id')->nullable()->constrained('online_educations')->onDelete('cascade');
            $table->foreignId('certification_id')->nullable()->constrained('certifications')->onDelete('cascade');
            $table->foreignId('seminar_training_id')->nullable()->constrained('seminar_trainings')->onDelete('cascade');
            $table->foreignId('member_id')->constrained('members')->onDelete('cascade');
            $table->string('applicant_name');
            $table->string('affiliation')->nullable();
            $table->string('phone_number');
            $table->string('email')->nullable();
            $table->dateTime('application_date');
            $table->boolean('is_completed')->default(false);
            $table->string('course_status', 20)->nullable();
            $table->decimal('attendance_rate', 5, 2)->nullable();
            $table->unsignedInteger('learning_minutes')->default(0)->comment('누적 수강 시간(분)');
            $table->integer('score')->nullable();
            $table->string('pass_status', 20)->nullable();
            $table->unsignedBigInteger('exam_venue_id')->nullable();
            $table->string('exam_ticket_number', 50)->nullable();
            $table->string('qualification_certificate_number', 50)->nullable();
            $table->string('pass_confirmation_number', 50)->nullable();
            $table->string('id_photo_path', 255)->nullable();
            $table->date('birth_date')->nullable();
            $table->unsignedBigInteger('roommate_member_id')->nullable();
            $table->string('roommate_name', 100)->nullable();
            $table->string('roommate_phone', 20)->nullable();
            $table->string('certificate_number')->nullable();
            $table->boolean('is_survey_completed')->default(false);
            $table->string('receipt_number')->nullable();
            $table->string('refund_account_holder')->nullable();
            $table->string('refund_bank_name')->nullable();
            $table->string('refund_account_number')->nullable();
            $table->decimal('participation_fee', 10, 2)->nullable();
            $table->string('fee_type')->nullable();
            $table->json('payment_method')->nullable();
            $table->string('payment_status')->default('미입금');
            $table->dateTime('payment_date')->nullable();
            $table->dateTime('completed_at')->nullable()->comment('수료일');
            $table->string('tax_invoice_status')->default('미신청');
            $table->boolean('has_cash_receipt')->default(false);
            $table->string('cash_receipt_purpose')->nullable();
            $table->string('cash_receipt_number')->nullable();
            $table->boolean('has_tax_invoice')->default(false);
            $table->string('company_name')->nullable();
            $table->string('registration_number')->nullable();
            $table->string('contact_person_name')->nullable();
            $table->string('contact_person_email')->nullable();
            $table->string('contact_person_phone')->nullable();
            $table->text('request_notes')->nullable()->comment('세미나 신청 요청사항');
            $table->timestamps();
            $table->timestamp('cancelled_at')->nullable()->comment('수강 취소 일시');
            $table->string('receipt_status', 20)->default('신청완료')->comment('접수상태 (접수취소/신청완료/수료/미수료)');
            $table->index('education_id');
            $table->index('online_education_id');
            $table->index('certification_id');
            $table->index('seminar_training_id');
            $table->index('member_id');
            $table->index('application_number');
            $table->index('payment_status');
            $table->index('application_date');
            $table->index('course_status');
            $table->index('pass_status');
            $table->index('exam_venue_id');
            $table->index('roommate_member_id');
            $table->foreign('exam_venue_id')->references('id')->on('categories')->onDelete('set null');
            $table->foreign('roommate_member_id')->references('id')->on('members')->onDelete('set null');
        });
    }

    private function createEducationApplicationAttachments(): void
    {
        Schema::create('education_application_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('education_application_id')->constrained('education_applications')->onDelete('cascade');
            $table->string('path');
            $table->string('name');
            $table->string('type')->default('business_registration');
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
            $table->index('education_application_id');
            $table->index('order');
        });
    }
};
