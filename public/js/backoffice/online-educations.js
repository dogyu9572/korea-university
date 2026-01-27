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
            const editBtn = row.querySelector('.edit-lecture-btn');
            const lecture = {
                id: editBtn?.getAttribute('data-lecture-id') || null,
                lecture_name: row.cells[2].textContent.trim(),
                instructor_name: row.cells[3].textContent.trim(),
                lecture_time: parseInt(row.cells[4].textContent.replace('시간', '').trim()) || 0,
                order: index
            };
            lectures.push(lecture);
            lectureIndex = Math.max(lectureIndex, index + 1);

            // 기존 강의 수정/삭제 버튼 이벤트
            if (editBtn) {
                editBtn.addEventListener('click', function() {
                    editLecture(index);
                });
            }
            const deleteBtn = row.querySelector('.delete-lecture-btn');
            if (deleteBtn) {
                deleteBtn.addEventListener('click', function() {
                    removeLecture(index);
                });
            }
        });
        
        // 기존 강의영상 데이터를 hidden input으로 변환
        updateLecturesData();
    }

    // 강의영상 검색 모달 열기
    function openLectureSearchModal() {
        $('#lectureSearchModal').modal('show');
        searchLectures();
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
                row.innerHTML = `
                    <td>${no}</td>
                    <td>${lecture.lecture_name}</td>
                    <td>${lecture.instructor_name}</td>
                    <td>${lecture.lecture_time || 0}시간</td>
                    <td>
                        <button type="button" class="btn btn-primary btn-sm select-lecture-btn" 
                                data-lecture-id="${lecture.id}"
                                data-lecture-name="${lecture.lecture_name}"
                                data-instructor-name="${lecture.instructor_name}"
                                data-lecture-time="${lecture.lecture_time || 0}">
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

    // 페이지네이션 표시
    function displayLecturePagination(data) {
        const paginationDiv = document.getElementById('lectureSearchPagination');
        if (!paginationDiv) return;

        if (data.last_page <= 1) {
            paginationDiv.innerHTML = '';
            return;
        }

        let html = '<nav><ul class="pagination justify-content-center">';
        
        // 이전 페이지
        if (data.current_page > 1) {
            html += `<li class="page-item"><a class="page-link" href="#" data-page="${data.current_page - 1}">&lt;</a></li>`;
        }

        // 페이지 번호
        for (let i = 1; i <= data.last_page; i++) {
            if (i === 1 || i === data.last_page || (i >= data.current_page - 2 && i <= data.current_page + 2)) {
                const active = i === data.current_page ? 'active' : '';
                html += `<li class="page-item ${active}"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
            } else if (i === data.current_page - 3 || i === data.current_page + 3) {
                html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
            }
        }

        // 다음 페이지
        if (data.current_page < data.last_page) {
            html += `<li class="page-item"><a class="page-link" href="#" data-page="${data.current_page + 1}">&gt;</a></li>`;
        }

        html += '</ul></nav>';
        paginationDiv.innerHTML = html;

        // 페이지 클릭 이벤트
        paginationDiv.querySelectorAll('.page-link[data-page]').forEach(function(link) {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const page = parseInt(this.getAttribute('data-page'));
                searchLectures(page);
            });
        });
    }

    // 강의영상 선택
    function selectLecture(button) {
        const lectureId = button.getAttribute('data-lecture-id');
        const lectureName = button.getAttribute('data-lecture-name');
        const instructorName = button.getAttribute('data-instructor-name');
        const lectureTime = button.getAttribute('data-lecture-time');

        // 이미 추가된 강의인지 확인
        if (lectures.some(l => l.id && l.id == lectureId)) {
            alert('이미 추가된 강의영상입니다.');
            return;
        }

        // 강의영상 추가
        const lecture = {
            id: lectureId,
            lecture_name: lectureName,
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
        row.innerHTML = `
            <td>${lectures.length}</td>
            <td>${lecture.order + 1}</td>
            <td>${lecture.lecture_name}</td>
            <td>${lecture.instructor_name}</td>
            <td>${lecture.lecture_time}시간</td>
            <td>-</td>
            <td>
                <div class="board-btn-group">
                    <button type="button" class="btn btn-primary btn-sm edit-lecture-row-btn" data-index="${index}">
                        수정
                    </button>
                    <button type="button" class="btn btn-danger btn-sm remove-lecture-btn" data-index="${index}">
                        삭제
                    </button>
                </div>
            </td>
        `;
        tbody.appendChild(row);

        // 이벤트 리스너 추가
        row.querySelector('.edit-lecture-row-btn').addEventListener('click', function() {
            editLecture(index);
        });
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

        const lectureTime = prompt('강의시간(시간):', lecture.lecture_time || 0);
        if (lectureTime === null) return;

        lecture.lecture_name = lectureName;
        lecture.instructor_name = instructorName;
        lecture.lecture_time = parseInt(lectureTime) || 0;

        updateLectureRow(index);
        updateLecturesData();
    }

    // 강의영상 삭제
    function removeLecture(index) {
        if (!confirm('강의영상을 삭제하시겠습니까?')) return;

        lectures.splice(index, 1);
        const row = document.querySelector(`tr[data-lecture-index="${index}"]`);
        if (row) {
            row.remove();
        }

        // 인덱스 재정렬
        lectures.forEach(function(lecture, i) {
            lecture.order = i;
        });
        lectureIndex = lectures.length;

        updateLecturesData();
        updateTableNumbers();
    }

    // 강의영상 행 업데이트
    function updateLectureRow(index) {
        const row = document.querySelector(`tr[data-lecture-index="${index}"]`);
        if (!row) return;

        const lecture = lectures[index];
        if (row.cells[2]) row.cells[2].textContent = lecture.lecture_name;
        if (row.cells[3]) row.cells[3].textContent = lecture.instructor_name;
        if (row.cells[4]) row.cells[4].textContent = lecture.lecture_time + '시간';
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

        // 추가 버튼
        const addBtn = document.getElementById('btnAddLecture');
        if (addBtn) {
            addBtn.addEventListener('click', function() {
                openLectureSearchModal();
            });
        }


        // 무료 체크박스
        initFreeCheckbox();

        // 검색 버튼
        const searchBtn = document.getElementById('btnSearchLectures');
        if (searchBtn) {
            searchBtn.addEventListener('click', function() {
                searchLectures(1);
            });
        }

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
