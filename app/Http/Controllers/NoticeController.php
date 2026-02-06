<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Services\Backoffice\BoardPostService;
use Illuminate\Http\Request;

class NoticeController extends Controller
{
    /** 사용자 알림마당 경로 → 게시판 slug 매핑 */
    private const SLUG_MAP = [
        'notice' => 'notices',
        'faq' => 'faq',
        'data_room' => 'library',
        'past_events' => 'past_events',
        'recruitment' => 'recruitments',
    ];

    public function __construct(private BoardPostService $boardPostService)
    {
    }

    private function menuMeta(string $gNum, string $sNum, string $gName, string $sName): array
    {
        return compact('gNum', 'sNum', 'gName', 'sName');
    }

    /** 공지사항 목록 */
    public function notice(Request $request)
    {
        return $this->listByType($request, 'notice', '03', '01', '알림마당', '공지사항', 'notice', 'notice_view');
    }

    /** 공지사항 상세 */
    public function noticeView(int $id)
    {
        return $this->showByType($id, 'notices', '03', '01', '알림마당', '공지사항', 'notice', 'notice_view');
    }

    /** FAQ 목록 */
    public function faq(Request $request)
    {
        return $this->listByType($request, 'faq', '03', '02', '알림마당', 'FAQ', 'faq', null);
    }

    /** 자료실 목록 */
    public function dataRoom(Request $request)
    {
        return $this->listByType($request, 'data_room', '03', '03', '알림마당', '자료실', 'data_room', 'data_room_view');
    }

    /** 자료실 상세 */
    public function dataRoomView(int $id)
    {
        return $this->showByType($id, 'library', '03', '03', '알림마당', '자료실', 'data_room', 'data_room_view');
    }

    /** 지난 행사 목록 */
    public function pastEvents(Request $request)
    {
        return $this->listByType($request, 'past_events', '03', '04', '알림마당', '지난 행사', 'past_events', 'past_events_view');
    }

    /** 지난 행사 상세 */
    public function pastEventsView(int $id)
    {
        return $this->showByType($id, 'past_events', '03', '04', '알림마당', '지난 행사', 'past_events', 'past_events_view');
    }

    /** 채용정보 목록 */
    public function recruitment(Request $request)
    {
        return $this->listByType($request, 'recruitment', '03', '05', '알림마당', '회원교 채용정보', 'recruitment', 'recruitment_view');
    }

    /** 채용정보 상세 */
    public function recruitmentView(int $id)
    {
        return $this->showByType($id, 'recruitments', '03', '05', '알림마당', '회원교 채용정보', 'recruitment', 'recruitment_view');
    }

    private function listByType(Request $request, string $type, string $gNum, string $sNum, string $gName, string $sName, string $listView, ?string $detailRoute): \Illuminate\View\View|\Illuminate\Http\Response
    {
        $slug = self::SLUG_MAP[$type] ?? $type;
        $board = Board::where('slug', $slug)->first();

        if (!$board) {
            $posts = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 15);
            return view("notice.{$listView}", array_merge(
                $this->menuMeta($gNum, $sNum, $gName, $sName),
                ['posts' => $posts, 'board' => null, 'listRoute' => $listView, 'detailRoute' => $detailRoute]
            ));
        }

        $posts = $this->boardPostService->getPosts($slug, $request, true);
        return view("notice.{$listView}", array_merge(
            $this->menuMeta($gNum, $sNum, $gName, $sName),
            ['posts' => $posts, 'board' => $board, 'listRoute' => $listView, 'detailRoute' => $detailRoute]
        ));
    }

    private function showByType(int $id, string $slug, string $gNum, string $sNum, string $gName, string $sName, string $listRoute, string $detailRoute): \Illuminate\View\View|\Illuminate\Http\Response
    {
        $board = Board::where('slug', $slug)->first();
        if (!$board) {
            abort(404);
        }

        $post = $this->boardPostService->getPost($slug, $id, true);
        if (!$post) {
            abort(404);
        }

        $prevPost = $this->boardPostService->getPrevPost($slug, $post, true);
        $nextPost = $this->boardPostService->getNextPost($slug, $post, true);

        return view("notice.{$detailRoute}", array_merge(
            $this->menuMeta($gNum, $sNum, $gName, $sName),
            ['post' => $post, 'board' => $board, 'listRoute' => $listRoute, 'detailRoute' => $detailRoute, 'prevPost' => $prevPost, 'nextPost' => $nextPost]
        ));
    }
}
