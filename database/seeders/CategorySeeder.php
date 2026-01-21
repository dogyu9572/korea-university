<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * 카테고리 데이터를 시드합니다.
     */
    public function run(): void
    {
        // 기존 카테고리 삭제 (외래키 고려)
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Category::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // ========================================
        // FAQ 카테고리 그룹
        // ========================================

        $faqCategory = Category::create([
            'parent_id' => null,
            'code' => 'C001',
            'name' => 'FAQ',
            'depth' => 0,
            'display_order' => 1,
            'is_active' => true,
        ]);

        // 1차: FAQ 하위 카테고리
        Category::create([
            'parent_id' => $faqCategory->id,
            'code' => 'C002',
            'name' => '교육',
            'depth' => 1,
            'display_order' => 1,
            'is_active' => true,
        ]);

        Category::create([
            'parent_id' => $faqCategory->id,
            'code' => 'C003',
            'name' => '자격증',
            'depth' => 1,
            'display_order' => 2,
            'is_active' => true,
        ]);

        Category::create([
            'parent_id' => $faqCategory->id,
            'code' => 'C004',
            'name' => '세미나',
            'depth' => 1,
            'display_order' => 3,
            'is_active' => true,
        ]);

        Category::create([
            'parent_id' => $faqCategory->id,
            'code' => 'C005',
            'name' => '해외연수',
            'depth' => 1,
            'display_order' => 4,
            'is_active' => true,
        ]);

        Category::create([
            'parent_id' => $faqCategory->id,
            'code' => 'C006',
            'name' => '기타',
            'depth' => 1,
            'display_order' => 5,
            'is_active' => true,
        ]);

        $this->command->info('카테고리 시더 실행 완료!');
        $this->command->info('- FAQ 그룹: 1개 그룹, 5개 하위 카테고리 생성');
    }
}
