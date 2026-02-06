<?php

namespace App\Http\Controllers;

use App\Services\HomeService;

class HomeController extends Controller
{
    public function index(HomeService $homeService)
    {
        $gNum = "main";
        $gName = "";
        $sName = "";
        
        $galleryPosts = $this->getDummyPosts('갤러리', 4);
        $noticePosts = $homeService->getNoticePostsForMain();
        $dataRoomPosts = $homeService->getDataRoomPostsForMain();

        $popups = collect();
        $banners = collect();
        $educationSlides = $homeService->getEducationSlidesForMain();
        $seminarTrainingSlides = $homeService->getSeminarTrainingSlidesForMain();

        return view('home.index', compact('gNum', 'gName', 'sName', 'galleryPosts', 'noticePosts', 'dataRoomPosts', 'popups', 'banners', 'educationSlides', 'seminarTrainingSlides'));
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
