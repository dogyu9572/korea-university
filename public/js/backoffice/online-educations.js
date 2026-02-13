/**
 * 온라인 교육 관리 페이지 JavaScript
 */

(function() {
    let lectureIndex = 0;
    let lectures = [];

    // 기존 강의영상 데이터 로드 (수정 모드)
    function loadExistingLectures() {
        const tableBody = document.getElementById('lecturesTableBody');
        if (!tableBody) return;

        const rows = tableBody.querySelectorAll('tr[data-lecture-index]');
        rows.forEach(function(row) {
            const index = parseInt(row.getAttribute('data-lecture-index'));
            const deleteBtn = row.querySelector('.delete-lecture-btn');
            const lecture = {
                lecture_video_id: row.getAttribute('data-lecture-video-id') ? parseInt(row.getAttribute('data-lecture-video-id'), 10) : null,
                lecture_name: row.cells[2].textContent.trim(),
                instructor_name: row.cells[3].textContent.trim(),
                lecture_time: parseInt(row.cells[4].textContent.replace('분', '').trim()) || 0,
                order: index
            };
            lectures.push(lecture);
            lectureIndex = Math.max(lectureIndex, index + 1);

            // 기존 강의 삭제 버튼 이벤트
            if (deleteBtn) {
                const lectureId = deleteBtn.getAttribute('data-lecture-id');
                deleteBtn.addEventListener('click', function() {
                    if (lectureId) {
                        deleteLectureFromDB(lectureId, index);
                    } else {
                        removeLecture(index);
                    }
                });
            }
        });
        
        // 기존 강의영상 데이터를 hidden input으로 변환
        updateLecturesData();
    }

    // 강의영상 검색 모달 열기
    function openLectureSearchModal() {
        // 검색 조건 초기화
        const typeEl = document.getElementById('lecture_search_type');
        const termEl = document.getElementById('lecture_search_term');
        if (typeEl) typeEl.value = '전체';
        if (termEl) termEl.value = '';

        // 모달 표시 후 1페이지부터 조회
        $('#lectureSearchModal').modal('show');
        searchLectures(1);
    }

    // 강의영상 검색
    function searchLectures(page = 1) {
        const searchType = document.getElementById('lecture_search_type')?.value || '전체';
        const searchTerm = document.getElementById('lecture_search_term')?.value || '';
        const searchUrl = document.getElementById('lectureSearchForm')?.getAttribute('action') || '/backoffice/online-educations/lectures/search';
        const url = new URL(searchUrl, window.location.origin);
        url.searchParams.set('search_type', searchType);
        url.searchParams.set('search_term', searchTerm);
        url.searchParams.set('page', page);
        url.searchParams.set('per_page', 20);

        fetch(url, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                displayLectureSearchResults(data);
            })
            .catch(error => {
                console.error('강의영상 검색 실패:', error);
                alert('강의영상 검색 중 오류가 발생했습니다.');
            });
    }

    // 강의영상 검색 결과 표시
    function displayLectureSearchResults(data) {
        const tbody = document.getElementById('lectureSearchResults');
        if (!tbody) return;

        tbody.innerHTML = '';

        if (data.data && data.data.length > 0) {
            data.data.forEach(function(lecture, index) {
                const row = document.createElement('tr');
                const no = (data.current_page - 1) * data.per_page + index + 1;
                const title = lecture.title || '';
                const videoUrl = (lecture.video_url || '').replace(/"/g, '&quot;');
                row.innerHTML = `
                    <td>${no}</td>
                    <td>${title}</td>
                    <td>${lecture.instructor_name || ''}</td>
                    <td>${lecture.lecture_time || 0}분</td>
                    <td>
                        <button type="button" class="btn btn-primary btn-sm select-lecture-btn" 
                                data-lecture-video-id="${lecture.id}"
                                data-lecture-title="${title}"
                                data-instructor-name="${lecture.instructor_name || ''}"
                                data-lecture-time="${lecture.lecture_time || 0}"
                                data-video-url="${videoUrl}">
                            선택
                        </button>
                    </td>
                `;
                tbody.appendChild(row);
            });

            // 페이지네이션 표시
            displayLecturePagination(data);
        } else {
            tbody.innerHTML = '<tr><td colspan="5" class="text-center">검색 결과가 없습니다.</td></tr>';
        }

        // 선택 버튼 이벤트
        tbody.querySelectorAll('.select-lecture-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                selectLecture(this);
            });
        });
    }

    // 페이지네이션 표시 (회원 검색 모달과 동일 구조: board-pagination, « ‹ 번호 › »)
    function displayLecturePagination(data) {
        const paginationDiv = document.getElementById('lectureSearchPagination');
        if (!paginationDiv) return;

        const cur = data.current_page;
        const last = data.last_page || 1;

        let html = '<div class="board-pagination"><nav aria-label="페이지 네비게이션"><ul class="pagination">';

        // 첫 페이지 «
        if (cur <= 1) {
            html += '<li class="page-item disabled"><span class="page-link"><i class="fas fa-angle-double-left"></i></span></li>';
        } else {
            html += `<li class="page-item"><a class="page-link" href="#" data-page="1" title="첫 페이지로"><i class="fas fa-angle-double-left"></i></a></li>`;
        }

        // 이전 페이지 ‹
        if (cur <= 1) {
            html += '<li class="page-item disabled"><span class="page-link"><i class="fas fa-chevron-left"></i></span></li>';
        } else {
            html += `<li class="page-item"><a class="page-link" href="#" data-page="${cur - 1}" title="이전 페이지"><i class="fas fa-chevron-left"></i></a></li>`;
        }

        // 페이지 번호
        for (let i = 1; i <= last; i++) {
            if (i === cur) {
                html += `<li class="page-item active"><span class="page-link">${i}</span></li>`;
            } else {
                html += `<li class="page-item"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
            }
        }

        // 다음 페이지 ›
        if (cur >= last) {
            html += '<li class="page-item disabled"><span class="page-link"><i class="fas fa-chevron-right"></i></span></li>';
        } else {
            html += `<li class="page-item"><a class="page-link" href="#" data-page="${cur + 1}" title="다음 페이지"><i class="fas fa-chevron-right"></i></a></li>`;
        }

        // 마지막 페이지 »
        if (cur >= last) {
            html += '<li class="page-item disabled"><span class="page-link"><i class="fas fa-angle-double-right"></i></span></li>';
        } else {
            html += `<li class="page-item"><a class="page-link" href="#" data-page="${last}" title="마지막 페이지로"><i class="fas fa-angle-double-right"></i></a></li>`;
        }

        html += '</ul></nav></div>';
        paginationDiv.innerHTML = html;

        paginationDiv.querySelectorAll('a.page-link[data-page]').forEach(function(link) {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const page = parseInt(this.getAttribute('data-page'), 10) || 1;
                searchLectures(page);
            });
        });
    }

    // 강의영상 검색 폼 초기화 (검색 버튼/엔터 통합 처리)
    function initLectureSearchForm() {
        const form = document.getElementById('lectureSearchForm');
        const searchBtn = document.getElementById('btnSearchLectures');
        const termEl = document.getElementById('lecture_search_term');

        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                searchLectures(1);
            });
        }

        if (searchBtn) {
            searchBtn.addEventListener('click', function() {
                searchLectures(1);
            });
        }

        if (termEl) {
            termEl.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    searchLectures(1);
                }
            });
        }
    }

    // 강의영상 선택
    function selectLecture(button) {
        const lectureVideoId = button.getAttribute('data-lecture-video-id');
        const title = button.getAttribute('data-lecture-title');
        const instructorName = button.getAttribute('data-instructor-name');
        const lectureTime = button.getAttribute('data-lecture-time');
        const videoUrl = button.getAttribute('data-video-url') || '';

        // 이미 추가된 강의인지 확인
        if (lectures.some(l => l.lecture_video_id && l.lecture_video_id == lectureVideoId)) {
            alert('이미 추가된 강의영상입니다.');
            return;
        }

        // LectureVideo를 OnlineEducationLecture 형식으로 변환
        const lecture = {
            lecture_video_id: lectureVideoId ? parseInt(lectureVideoId, 10) : null,
            lecture_name: title,
            instructor_name: instructorName,
            lecture_time: parseInt(lectureTime) || 0,
            order: lectureIndex++
        };
        lectures.push(lecture);
        addLectureToTable(lecture, lectures.length - 1);
        
        // 모달 닫기
        $('#lectureSearchModal').modal('hide');
    }


    // 강의영상을 테이블에 추가
    function addLectureToTable(lecture, index) {
        const tbody = document.getElementById('lecturesTableBody');
        if (!tbody) return;

        const row = document.createElement('tr');
        row.setAttribute('data-lecture-index', index);
        if (lecture.lecture_video_id) row.setAttribute('data-lecture-video-id', lecture.lecture_video_id);
        row.innerHTML = `
            <td>${lectures.length}</td>
            <td>${lecture.order + 1}</td>
            <td>${lecture.lecture_name}</td>
            <td>${lecture.instructor_name}</td>
            <td>${lecture.lecture_time}분</td>
            <td>-</td>
            <td>
                <div class="board-btn-group">
                    <button type="button" class="btn btn-danger btn-sm remove-lecture-btn" data-index="${index}">
                        삭제
                    </button>
                </div>
            </td>
        `;
        tbody.appendChild(row);

        // 이벤트 리스너 추가
        row.querySelector('.remove-lecture-btn').addEventListener('click', function() {
            removeLecture(index);
        });

        updateLecturesData();
        updateTableNumbers();
    }

    // 강의영상 수정
    function editLecture(index) {
        const lecture = lectures[index];
        if (!lecture) return;

        const lectureName = prompt('강의명:', lecture.lecture_name);
        if (lectureName === null) return;

        const instructorName = prompt('강사명:', lecture.instructor_name || '');
        if (instructorName === null) return;

        const lectureTime = prompt('강의시간(분):', lecture.lecture_time || 0);
        if (lectureTime === null) return;

        lecture.lecture_name = lectureName;
        lecture.instructor_name = instructorName;
        lecture.lecture_time = parseInt(lectureTime) || 0;

        updateLectureRow(index);
        updateLecturesData();
    }

    // 강의영상 삭제
    // DB에서 강의 삭제 (기존 강의)
    function deleteLectureFromDB(lectureId, index) {
        if (!confirm('강의를 삭제하시겠습니까?')) return;

        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        const token = csrfToken ? csrfToken.getAttribute('content') : '';

        fetch(`/backoffice/online-educations/lectures/${lectureId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('강의가 삭제되었습니다.');
                // DOM에서 행 제거
                const row = document.querySelector(`tr[data-lecture-index="${index}"]`);
                if (row) row.remove();
                
                // 배열에서 제거
                lectures = lectures.filter(function(lecture) {
                    return lecture.order !== index;
                });
                
                // 인덱스 재정렬
                lectures.forEach(function(lecture, i) {
                    lecture.order = i;
                });
                lectureIndex = lectures.length;
                
                // 테이블 업데이트
                updateTableAfterDelete();
            } else {
                alert(data.message || '삭제 중 오류가 발생했습니다.');
            }
        })
        .catch(error => {
            console.error('강의 삭제 실패:', error);
            alert('삭제 중 오류가 발생했습니다.');
        });
    }

    // 메모리에서만 강의 삭제 (새로 추가한 강의)
    function removeLecture(index) {
        if (!confirm('강의를 삭제하시겠습니까?')) return;

        // DOM에서 해당 행 찾아서 즉시 제거
        const row = document.querySelector(`tr[data-lecture-index="${index}"]`);
        if (row) {
            row.remove();
        }

        // 배열에서 해당 인덱스 삭제
        lectures = lectures.filter(function(lecture) {
            return lecture.order !== index;
        });

        // 인덱스 재정렬
        lectures.forEach(function(lecture, i) {
            lecture.order = i;
        });
        lectureIndex = lectures.length;

        updateTableAfterDelete();
    }

    // 삭제 후 테이블 업데이트
    function updateTableAfterDelete() {
        const tbody = document.getElementById('lecturesTableBody');
        if (tbody) {
            const rows = tbody.querySelectorAll('tr');
            rows.forEach(function(row, i) {
                row.setAttribute('data-lecture-index', i);
                if (row.cells[0]) row.cells[0].textContent = i + 1; // No
                if (row.cells[1]) row.cells[1].textContent = i + 1; // 순서
                
                // 삭제 버튼 이벤트 재설정
                const deleteBtn = row.querySelector('.delete-lecture-btn');
                if (deleteBtn) {
                    const lectureId = deleteBtn.getAttribute('data-lecture-id');
                    deleteBtn.onclick = function() {
                        if (lectureId) {
                            deleteLectureFromDB(lectureId, i);
                        } else {
                            removeLecture(i);
                        }
                    };
                }
            });
        }

        updateLecturesData();
    }

    // 강의영상 행 업데이트
    function updateLectureRow(index) {
        const row = document.querySelector(`tr[data-lecture-index="${index}"]`);
        if (!row) return;

        const lecture = lectures[index];
        if (row.cells[2]) row.cells[2].textContent = lecture.lecture_name;
        if (row.cells[3]) row.cells[3].textContent = lecture.instructor_name;
        if (row.cells[4]) row.cells[4].textContent = lecture.lecture_time + '분';
        if (row.cells[1]) row.cells[1].textContent = lecture.order + 1;
    }

    // 테이블 번호 업데이트
    function updateTableNumbers() {
        const rows = document.querySelectorAll('#lecturesTableBody tr');
        rows.forEach(function(row, index) {
            row.cells[0].textContent = index + 1;
        });
    }

    // 강의영상 데이터 업데이트 (hidden input)
    function updateLecturesData() {
        const container = document.getElementById('lecturesDataContainer');
        if (!container) return;

        // 기존 hidden input 제거
        container.innerHTML = '';

        // lectures 배열을 각각의 hidden input으로 변환
        lectures.forEach(function(lecture, index) {
            const namePrefix = `lectures[${index}]`;
            
            const videoIdInput = document.createElement('input');
            videoIdInput.type = 'hidden';
            videoIdInput.name = `${namePrefix}[lecture_video_id]`;
            videoIdInput.value = lecture.lecture_video_id || '';
            container.appendChild(videoIdInput);

            const nameInput = document.createElement('input');
            nameInput.type = 'hidden';
            nameInput.name = `${namePrefix}[lecture_name]`;
            nameInput.value = lecture.lecture_name || '';
            container.appendChild(nameInput);

            const instructorInput = document.createElement('input');
            instructorInput.type = 'hidden';
            instructorInput.name = `${namePrefix}[instructor_name]`;
            instructorInput.value = lecture.instructor_name || '';
            container.appendChild(instructorInput);

            const timeInput = document.createElement('input');
            timeInput.type = 'hidden';
            timeInput.name = `${namePrefix}[lecture_time]`;
            timeInput.value = lecture.lecture_time || 0;
            container.appendChild(timeInput);
        });
    }

    // 무료 체크박스 처리
    function initFreeCheckbox() {
        const freeCheckbox = document.getElementById('is_free');
        const feeInput = document.getElementById('fee');
        
        if (freeCheckbox && feeInput) {
            freeCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    feeInput.value = '0';
                    feeInput.disabled = true;
                } else {
                    feeInput.disabled = false;
                }
            });
        }
    }

    // 초기화
    function init() {
        // 기존 강의영상 로드
        loadExistingLectures();

        // 추가 버튼 (강의영상 검색 모달)
        const addBtn = document.getElementById('btnAddLecture');
        if (addBtn) {
            addBtn.addEventListener('click', function() {
                openLectureSearchModal();
            });
        }

        // 무료 체크박스
        initFreeCheckbox();

        // 강의영상 검색 폼/버튼/엔터 처리
        initLectureSearchForm();

        // 폼 제출 시 강의영상 데이터 전송
        const form = document.getElementById('onlineEducationForm');
        if (form) {
            form.addEventListener('submit', function() {
                updateLecturesData();
            });
        }
    }

    // DOM 로드 완료 후 초기화
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
