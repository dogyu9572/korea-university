<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class ExportDbToSeedersCommand extends Command
{
    /**
     * 제외 테이블 (회원관리, 등록된 교육, 신청 내역, 접속/방문 로그, 테스트)
     */
    protected const EXCLUDED_TABLES = [
        'members',
        'educations',
        'education_attachments',
        'education_contents',
        'online_educations',
        'online_education_attachments',
        'online_education_lectures',
        'seminar_trainings',
        'seminar_training_attachments',
        'certifications',
        'certification_attachments',
        'lecture_videos',
        'lecture_video_attachments',
        'education_applications',
        'education_application_attachments',
        'user_access_logs',
        'admin_access_logs',
        'visitor_logs',
        'daily_visitor_stats',
        'board_test2',
    ];

    /**
     * 시스템/임시 테이블 (항상 제외)
     */
    protected const SYSTEM_TABLES = [
        'cache',
        'cache_locks',
        'sessions',
        'password_reset_tokens',
        'jobs',
        'job_batches',
        'failed_jobs',
        'migrations',
    ];

    /**
     * 로그성 테이블 (이미 EXCLUDED_TABLES에 포함. --exclude-logs 옵션은 하위 호환용)
     */
    protected const LOG_TABLES = [
        'user_access_logs',
        'admin_access_logs',
        'visitor_logs',
        'daily_visitor_stats',
    ];

    /**
     * 시더 실행 순서 (FK 의존성). 알 수 없는 테이블은 맨 뒤에 추가됨.
     */
    protected const TABLES_ORDER = [
        'users',
        'admin_groups',
        'admin_menus',
        'board_skins',
        'board_templates',
        'settings',
        'categories',
        'user_menu_permissions',
        'admin_group_menu_permissions',
        'boards',
        'banners',
        'popups',
        'schools',
        'organizational_charts',
        'board_notices',
        'board_gallerys',
        'board_greetings',
        'board_comments',
        'inquiries',
        'inquiry_replies',
        'inquiry_files',
        'inquiry_reply_files',
        'histories',
        'organizational_members',
    ];

    protected $signature = 'db:export-seeders
                            {--exclude-logs : 로그 테이블(user_access_logs 등) 제외}
                            {--chunk=500 : 조회/삽입 시 청크 크기}';

    protected $description = '현재 DB 데이터를 시더 파일로 추출 (회원/교육/신청 내역 제외). 서버 이관용.';

    public function handle(): int
    {
        $excludeLogs = (bool) $this->option('exclude-logs');
        $chunkSize = (int) $this->option('chunk');
        if ($chunkSize < 1) {
            $chunkSize = 500;
        }

        $dbName = config('database.connections.mysql.database');
        $key = 'Tables_in_' . $dbName;
        $allTables = collect(DB::select('SHOW TABLES'))
            ->map(fn ($row) => $row->{$key})
            ->values()
            ->all();

        $excluded = array_merge(
            self::EXCLUDED_TABLES,
            self::SYSTEM_TABLES
        );
        if ($excludeLogs) {
            $excluded = array_merge($excluded, self::LOG_TABLES);
        }
        $excluded = array_flip($excluded);

        $tables = collect($allTables)
            ->filter(fn ($table) => ! isset($excluded[$table]))
            ->values()
            ->all();

        $tables = $this->sortTablesByFkOrder($tables);

        $this->info('추출 대상 테이블: ' . count($tables) . '개');
        $seedersPath = database_path('seeders');

        foreach ($tables as $table) {
            $this->exportTableToSeeder($table, $seedersPath, $chunkSize);
        }

        $this->writeMigrationSeederCaller($tables, $seedersPath);

        $this->info('완료. 이관 후 새 서버에서 migrate 실행 뒤, 위 시더 한 번만 실행하면 현재 데이터로 표시됩니다.');
        return self::SUCCESS;
    }

    /**
     * FK 순서에 맞게 테이블 정렬. 정의된 순서 + 동적 board_* (board_greetings 다음) + 나머지.
     */
    protected function sortTablesByFkOrder(array $tables): array
    {
        $tablesSet = array_flip($tables);
        $dynamicBoard = [];
        $rest = [];
        foreach ($tables as $table) {
            if (Str::startsWith($table, 'board_') && $table !== 'board_comments'
                && ! in_array($table, ['board_notices', 'board_gallerys', 'board_greetings'], true)) {
                $dynamicBoard[] = $table;
            } elseif (! in_array($table, self::TABLES_ORDER, true)) {
                $rest[] = $table;
            }
        }
        sort($dynamicBoard);
        sort($rest);

        $result = [];
        foreach (self::TABLES_ORDER as $table) {
            if (isset($tablesSet[$table])) {
                $result[] = $table;
            }
            if ($table === 'board_greetings') {
                foreach ($dynamicBoard as $t) {
                    $result[] = $t;
                }
            }
        }
        foreach ($rest as $table) {
            $result[] = $table;
        }
        return array_values(array_unique($result));
    }

    protected function exportTableToSeeder(string $table, string $seedersPath, int $chunkSize): void
    {
        $className = Str::studly(Str::singular($table)) . 'ExportSeeder';
        $fileName = $className . '.php';
        $path = $seedersPath . DIRECTORY_SEPARATOR . $fileName;

        $chunks = [];
        $query = DB::table($table);
        if (Schema::hasColumn($table, 'id')) {
            $query->orderBy('id');
        }
        $query->chunk($chunkSize, function ($rows) use (&$chunks) {
            $chunks[] = $rows->map(fn ($row) => (array) $row)->values()->all();
        });

        $chunksExport = $this->exportChunksAsPhp($chunks);
        $tableExport = var_export($table, true);

        $content = <<<PHP
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * 서버 이관용 시더 (db:export-seeders 로 생성됨)
 * 테이블: {$table}
 */
class {$className} extends Seeder
{
    public function run(): void
    {
        \$table = {$tableExport};
        \$chunks = {$chunksExport};

        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table(\$table)->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        foreach (\$chunks as \$chunk) {
            if (count(\$chunk) > 0) {
                DB::table(\$table)->insert(\$chunk);
            }
        }
    }
}
PHP;

        file_put_contents($path, $content);
        $this->line("  생성: {$fileName} (" . array_sum(array_map('count', $chunks)) . "행)");
    }

    /**
     * 청크 배열을 PHP 소스 문자열로 변환 (대용량 시 가독성보다 한 줄로).
     */
    protected function exportChunksAsPhp(array $chunks): string
    {
        $parts = [];
        foreach ($chunks as $chunk) {
            $parts[] = var_export($chunk, true);
        }
        return "[\n        " . implode(",\n        ", $parts) . "\n    ]";
    }

    /**
     * 이관용 DatabaseSeederForServerMigration 에서 위 시더들을 FK 순서로 call 하도록 작성.
     */
    protected function writeMigrationSeederCaller(array $tables, string $seedersPath): void
    {
        $className = 'DatabaseSeederForServerMigration';
        $path = $seedersPath . DIRECTORY_SEPARATOR . $className . '.php';

        $calls = [];
        foreach ($tables as $table) {
            $seederClass = Str::studly(Str::singular($table)) . 'ExportSeeder';
            $calls[] = "        \$this->call({$seederClass}::class);";
        }
        $callsStr = implode("\n", $calls);

        $content = <<<PHP
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * 서버 이관용 시더 실행기 (db:export-seeders 로 생성된 ExportSeeder 들을 FK 순서대로 실행)
 * 사용: php artisan db:seed --class=DatabaseSeederForServerMigration
 */
class {$className} extends Seeder
{
    public function run(): void
    {
{$callsStr}
    }
}
PHP;

        file_put_contents($path, $content);
        $this->info("이관용 시더 실행기 생성: {$className}.php");
    }
}
