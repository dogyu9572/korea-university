<!-- 강의영상 검색 모달 -->
<div class="modal fade" id="lectureSearchModal" tabindex="-1" role="dialog" aria-labelledby="lectureSearchModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="lectureSearchModalLabel">강의영상 검색</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- 검색 필터 -->
                <div class="board-filter">
                    <form method="GET" action="{{ route('backoffice.online-educations.lectures.search') }}" id="lectureSearchForm" class="filter-form" onsubmit="return false;">
                        <div class="filter-row">
                            <div class="filter-group">
                                <label for="lecture_search_type" class="filter-label">검색</label>
                                <select id="lecture_search_type" name="search_type" class="filter-select">
                                    <option value="전체">전체</option>
                                    <option value="강의제목">강의제목</option>
                                    <option value="강사명">강사명</option>
                                </select>
                            </div>
                            <div class="filter-group">
                                <label for="lecture_search_term" class="filter-label">검색어</label>
                                <input type="text" id="lecture_search_term" name="search_term" class="filter-input" value="">
                            </div>
                            <div class="filter-group">
                                <div class="filter-buttons">
                                    <button type="button" class="btn btn-primary" id="btnSearchLectures">
                                        <i class="fas fa-search"></i> 검색
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- 검색 결과 테이블 -->
                <div class="table-responsive mt-3">
                    <table class="board-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>강의제목</th>
                                <th>강사명</th>
                                <th>강의시간</th>
                                <th>선택</th>
                            </tr>
                        </thead>
                        <tbody id="lectureSearchResults">
                            <!-- 검색 결과가 여기에 동적으로 추가됩니다 -->
                        </tbody>
                    </table>
                </div>

                <!-- 페이지네이션 -->
                <div id="lectureSearchPagination" class="mt-3">
                    <!-- 페이지네이션이 여기에 동적으로 추가됩니다 -->
                </div>
            </div>
        </div>
    </div>
</div>
