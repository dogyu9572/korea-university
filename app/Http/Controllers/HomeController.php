<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Board;
use App\Models\Popup;
use App\Models\Banner;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function index()
    {
        $gNum = "main";
        $gName = "";
        $sName = "";
        
        // 퍼블리싱 모드: 더미 데이터 사용
        $galleryPosts = $this->getDummyPosts('갤러리', 4);
        $noticePosts = $this->getDummyPosts('공지사항', 4);
        
        // 빈 컬렉션 반환 (팝업/배너 없음)
        $popups = collect();
        $banners = collect();
        
        return view('home.index', compact('gNum', 'gName', 'sName', 'galleryPosts', 'noticePosts', 'popups', 'banners'));
    }
    
    /**
     * 퍼블리싱용 더미 데이터 생성
     */
    private function getDummyPosts($type, $limit = 4)
    {
        $posts = collect();
        
        for ($i = 1; $i <= $limit; $i++) {
            $posts->push((object)[
                'id' => $i,
                'title' => "{$type} 샘플 제목 {$i}",
                'created_at' => now()->subDays($i),
                'thumbnail' => null,
                'url' => '#'
            ]);
        }
        
        return $posts;
    }
}
