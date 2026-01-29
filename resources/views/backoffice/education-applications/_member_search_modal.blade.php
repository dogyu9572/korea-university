<!-- 회원 검색 모달 -->
<div class="modal fade" id="memberSearchModal" tabindex="-1" aria-hidden="true" data-member-search-url="{{ route('backoffice.members.index') }}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">회원 검색</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="board-filter member-search-modal-filter">
                    <form id="memberSearchForm" method="GET">
                        <div class="filter-row">
                            <div class="filter-group">
                                <label for="member_search_type" class="filter-label">검색 구분</label>
                                <select id="member_search_type" name="search_type" class="filter-select">
                                    <option value="전체">전체</option>
                                    <option value="ID">ID</option>
                                    <option value="이름">이름</option>
                                    <option value="휴대폰">휴대폰</option>
                                </select>
                            </div>
                            <div class="filter-group">
                                <label for="member_search_keyword" class="filter-label">검색어</label>
                                <input type="text" id="member_search_keyword" name="search" class="filter-input" placeholder="검색어를 입력하세요">
                            </div>
                            <div class="filter-group">
                                <div class="filter-buttons">
                                    <button type="button" class="btn btn-primary" data-action="member-search">
                                        <i class="fas fa-search"></i> 검색
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="table-responsive">
                    <table class="board-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>ID</th>
                                <th>이름</th>
                                <th>휴대폰 번호</th>
                                <th>선택</th>
                            </tr>
                        </thead>
                        <tbody id="memberSearchResults">
                            <tr>
                                <td colspan="5" class="text-center">검색 버튼을 클릭하여 회원을 검색하세요.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div id="memberSearchPagination" class="mt-3"></div>
            </div>
        </div>
    </div>
</div>
