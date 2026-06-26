{{-- 회원 검색 모달(education-applications/_member_search_modal)과 동일한 마크업·클래스 --}}
<div class="modal fade" id="schoolSearchModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">학교 검색</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="board-filter member-search-modal-filter">
                    <form id="schoolSearchForm" method="get" action="#" onsubmit="return false;">
                        <div class="filter-row">
                            <div class="filter-group">
                                <label for="popSchoolKeyword" class="filter-label">학교명</label>
                                <input type="text" id="popSchoolKeyword" name="school_name" class="filter-input" placeholder="학교명을 검색해주세요." autocomplete="off">
                            </div>
                            <div class="filter-group">
                                <div class="filter-buttons">
                                    <button type="button" class="btn btn-primary" id="popSchoolSearch">
                                        <i class="fas fa-search"></i> 검색
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="table-responsive school-search-table-wrap">
                    <table class="board-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>학교명</th>
                                <th>선택</th>
                            </tr>
                        </thead>
                        <tbody id="schoolSearchResults">
                            <tr>
                                <td colspan="3" class="text-center">검색 버튼을 클릭하여 회원교를 검색하세요.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
