<!-- 룸메이트 요청 내역 모달 (회원 검색과 동일 레이아웃) -->
<div class="modal fade" id="roommateRequestsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">룸메이트 요청 내역</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="board-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>ID</th>
                                <th>이름</th>
                                <th>휴대폰 번호</th>
                                <th>승인</th>
                                <th>거절</th>
                            </tr>
                        </thead>
                        <tbody id="roommateRequestsResults">
                            <tr>
                                <td colspan="6" class="text-center">요청 내역을 불러오는 중입니다.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
