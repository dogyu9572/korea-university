@php
    $isEdit = isset($onlineEducation) && $onlineEducation->exists;
    $program = $isEdit ? $onlineEducation : new \App\Models\EducationProgram();
    $attachments = $isEdit ? $program->attachments : collect([]);
    $lectures = $isEdit ? $program->lectures : collect([]);

    $appStart = $program->application_start;
    $appEnd = $program->application_end;
    $appStartDate = old('application_start_date', $appStart ? $appStart->format('Y-m-d') : '');
    $appStartHour = old('application_start_hour', $appStart ? (int)$appStart->format('H') : 0);
    $appEndDate = old('application_end_date', $appEnd ? $appEnd->format('Y-m-d') : '');
    $appEndHour = old('application_end_hour', $appEnd ? (int)$appEnd->format('H') : 23);
@endphp

<form action="{{ $isEdit ? route('backoffice.online-educations.update', $program) : route('backoffice.online-educations.store') }}" method="POST" enctype="multipart/form-data" id="onlineEducationForm">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    <!-- 교육 정보 -->
    <div class="member-form-section">
        <h3 class="member-section-title">교육 정보</h3>
        
        <div class="member-form-list">
            <div class="member-form-row">
                <label class="member-form-label">접수상태</label>
                <div class="member-form-field">
                    <select class="board-form-control @error('application_status') is-invalid @enderror" id="application_status" name="application_status" required>
                        <option value="">선택</option>
                        <option value="접수중" @selected(old('application_status', $program->application_status) == '접수중')>접수중</option>
                        <option value="접수마감" @selected(old('application_status', $program->application_status) == '접수마감')>접수마감</option>
                        <option value="접수예정" @selected(old('application_status', $program->application_status) == '접수예정')>접수예정</option>
                        <option value="비공개" @selected(old('application_status', $program->application_status) == '비공개')>비공개</option>
                    </select>
                    @error('application_status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="member-form-row">
                <label class="member-form-label">교육구분</label>
                <div class="member-form-field">
                    <input type="text" class="board-form-control @error('education_class') is-invalid @enderror" id="education_class" name="education_class" value="{{ old('education_class', $program->education_class) }}">
                    @error('education_class')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="member-form-row">
                <label class="member-form-label">교육명</label>
                <div class="member-form-field">
                    <input type="text" class="board-form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $program->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="member-form-row">
                <label class="member-form-label">교육기간</label>
                <div class="member-form-field">
                    <div class="period-time-row">
                        <input type="date" class="board-form-control @error('period_start') is-invalid @enderror" id="period_start" name="period_start" value="{{ old('period_start', $program->period_start ? $program->period_start->format('Y-m-d') : '') }}">
                        <select class="board-form-control application-hour-select" id="period_start_hour" name="period_start_hour">
                            @for($h = 0; $h <= 23; $h++)
                                <option value="{{ $h }}" @selected(old('period_start_hour', 0) == $h)>{{ sprintf('%02d', $h) }}시</option>
                            @endfor
                        </select>
                        <span class="period-sep">~</span>
                        <input type="date" class="board-form-control @error('period_end') is-invalid @enderror" id="period_end" name="period_end" value="{{ old('period_end', $program->period_end ? $program->period_end->format('Y-m-d') : '') }}">
                        <select class="board-form-control application-hour-select" id="period_end_hour" name="period_end_hour">
                            @for($h = 0; $h <= 23; $h++)
                                <option value="{{ $h }}" @selected(old('period_end_hour', 0) == $h)>{{ sprintf('%02d', $h) }}시</option>
                            @endfor
                        </select>
                    </div>
                    @error('period_start')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    @error('period_end')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="member-form-row">
                <label class="member-form-label">신청기간</label>
                <div class="member-form-field">
                    <div class="application-period-row">
                        <input type="date" class="board-form-control" id="application_start_date" name="application_start_date" value="{{ $appStartDate }}">
                        <select class="board-form-control application-hour-select" id="application_start_hour" name="application_start_hour">
                            @for($h = 0; $h <= 23; $h++)
                                <option value="{{ $h }}" @selected($appStartHour == $h)>{{ sprintf('%02d', $h) }}시</option>
                            @endfor
                        </select>
                        <span class="period-sep">~</span>
                        <input type="date" class="board-form-control" id="application_end_date" name="application_end_date" value="{{ $appEndDate }}">
                        <select class="board-form-control application-hour-select" id="application_end_hour" name="application_end_hour">
                            @for($h = 0; $h <= 23; $h++)
                                <option value="{{ $h }}" @selected($appEndHour == $h)>{{ sprintf('%02d', $h) }}시</option>
                            @endfor
                        </select>
                    </div>
                    @error('application_start_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    @error('application_end_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="member-form-row">
                <label class="member-form-label">상세내용</label>
                <div class="member-form-field">
                    <textarea class="board-form-control board-form-textarea summernote-editor" id="content" name="content" rows="10">{{ old('content', $program->content) }}</textarea>
                    @error('content')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="member-form-row">
                <label class="member-form-label">교육비</label>
                <div class="member-form-field">
                    <div class="capacity-row">
                        <input type="number" class="board-form-control @error('fee') is-invalid @enderror" id="fee" name="fee" value="{{ old('fee', $program->fee ? (int)$program->fee : '') }}" min="0" step="1" {{ old('is_free', $program->is_free) ? 'disabled' : '' }}>
                        <span class="input-group-text">원</span>
                        <label class="board-checkbox-item capacity-unlimited-check">
                            <input type="checkbox" id="is_free" name="is_free" value="1" @checked(old('is_free', $program->is_free) == 1)>
                            <span>무료</span>
                        </label>
                    </div>
                    @error('fee')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="member-form-row">
                <label class="member-form-label">결제방법</label>
                <div class="member-form-field">
                    <div class="board-checkbox-group">
                        <label class="board-checkbox-item">
                            <input type="checkbox" name="payment_methods[]" value="무통장입금" @checked(in_array('무통장입금', old('payment_methods', $program->payment_methods ?? [])))>
                            <span>무통장 입금</span>
                        </label>
                        <label class="board-checkbox-item">
                            <input type="checkbox" name="payment_methods[]" value="방문카드결제" @checked(in_array('방문카드결제', old('payment_methods', $program->payment_methods ?? [])))>
                            <span>방문 카드결제</span>
                        </label>
                        <label class="board-checkbox-item">
                            <input type="checkbox" name="payment_methods[]" value="온라인카드결제" @checked(in_array('온라인카드결제', old('payment_methods', $program->payment_methods ?? [])))>
                            <span>온라인 카드결제</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="member-form-row">
                <label class="member-form-label">신청정원</label>
                <div class="member-form-field">
                    <div class="capacity-row">
                        <input type="number" class="board-form-control @error('capacity') is-invalid @enderror" id="capacity" name="capacity" value="{{ old('capacity', $program->capacity) }}" min="0">
                        <span class="input-group-text">명</span>
                        <label class="board-checkbox-item capacity-unlimited-check">
                            <input type="checkbox" id="capacity_unlimited" name="capacity_unlimited" value="1" @checked(old('capacity_unlimited', $program->capacity_unlimited) == 1)>
                            <span>제한없음</span>
                        </label>
                    </div>
                    @error('capacity')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="member-form-row">
                <label class="member-form-label">첨부파일</label>
                <div class="member-form-field">
                    <input type="file" class="board-form-control" id="attachments" name="attachments[]" multiple>
                    @if($attachments->count() > 0)
                        <div class="mt-3">
                            <span>첨부파일: </span>
                            @foreach($attachments as $index => $attachment)
                                <span>
                                    <a href="{{ $attachment->path }}" target="_blank">{{ $attachment->name }}</a>
                                    <button type="button" class="btn-delete-simple ms-2 delete-attachment-btn" data-attachment-id="{{ $attachment->id }}">
                                        삭제
                                    </button>
                                </span>
                                @if($index < $attachments->count() - 1), @endif
                            @endforeach
                            @foreach($attachments as $attachment)
                                <input type="hidden" name="delete_attachments[]" id="delete_attachment_{{ $attachment->id }}" value="">
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <div class="member-form-row">
                <label class="member-form-label">썸네일</label>
                <div class="member-form-field">
                    <input type="file" class="board-form-control @error('thumbnail') is-invalid @enderror" id="thumbnail" name="thumbnail" accept="image/*">
                    @if($isEdit && $program->thumbnail_path)
                        <div class="mt-2 thumbnail-container">
                            <img src="{{ $program->thumbnail_path }}" alt="썸네일" class="thumbnail-image">
                            <button type="button" class="btn-delete-simple delete-thumbnail-btn">
                                삭제
                            </button>
                            <input type="hidden" name="delete_thumbnail" id="delete_thumbnail" value="0">
                        </div>
                    @endif
                    @error('thumbnail')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="member-form-row">
                <label class="member-form-label">설문조사 URL</label>
                <div class="member-form-field">
                    <input type="url" class="board-form-control @error('survey_url') is-invalid @enderror" id="survey_url" name="survey_url" value="{{ old('survey_url', $program->survey_url) }}">
                    @error('survey_url')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="member-form-row member-form-row-inline">
                <div class="member-form-inline-item">
                    <label class="member-form-label">이수증/수료증</label>
                    <div class="member-form-field">
                        <div class="board-radio-group">
                            <div class="board-radio-item">
                                <input type="radio" id="certificate_type_completion" name="certificate_type" value="이수증" class="board-radio-input" @checked(old('certificate_type', $program->certificate_type) == '이수증') required>
                                <label for="certificate_type_completion">이수증</label>
                            </div>
                            <div class="board-radio-item">
                                <input type="radio" id="certificate_type_certificate" name="certificate_type" value="수료증" class="board-radio-input" @checked(old('certificate_type', $program->certificate_type) == '수료증')>
                                <label for="certificate_type_certificate">수료증</label>
                            </div>
                        </div>
                        @error('certificate_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="member-form-inline-item">
                    <label class="member-form-label">교육이수시간</label>
                    <div class="member-form-field">
                        <div class="input-group input-group-inline">
                            <input type="number" class="board-form-control @error('completion_hours') is-invalid @enderror" id="completion_hours" name="completion_hours" value="{{ old('completion_hours', $program->completion_hours) }}" min="0">
                            <span class="input-group-text">시간</span>
                        </div>
                        @error('completion_hours')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="member-form-row">
                <label class="member-form-label">공개여부</label>
                <div class="member-form-field">
                    <div class="board-radio-group">
                        <div class="board-radio-item">
                            <input type="radio" id="is_public_1" name="is_public" value="1" class="board-radio-input" @checked(old('is_public', $program->is_public ?? true) == 1)>
                            <label for="is_public_1">공개</label>
                        </div>
                        <div class="board-radio-item">
                            <input type="radio" id="is_public_0" name="is_public" value="0" class="board-radio-input" @checked(old('is_public', $program->is_public ?? false) == 0)>
                            <label for="is_public_0">비공개</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 강의영상 목록 -->
    <div class="member-form-section">
        <h3 class="member-section-title">강의영상 목록</h3>
        
        <div class="member-form-list">
            <div class="member-form-row">
                <div class="member-form-field" style="width: 100%;">
                    <div class="table-responsive">
                        <table class="board-table" id="lecturesTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>순서</th>
                                    <th>강의명</th>
                                    <th>강사명</th>
                                    <th>강의시간</th>
                                    <th>등록일</th>
                                    <th>관리</th>
                                </tr>
                            </thead>
                            <tbody id="lecturesTableBody">
                                @foreach($lectures as $index => $lecture)
                                    <tr data-lecture-index="{{ $index }}">
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $lecture->order + 1 }}</td>
                                        <td>{{ $lecture->lecture_name }}</td>
                                        <td>{{ $lecture->instructor_name }}</td>
                                        <td>{{ $lecture->lecture_time }}시간</td>
                                        <td>{{ $lecture->created_at->format('Y.m.d') }}</td>
                                        <td>
                                            <div class="board-btn-group">
                                                <button type="button" class="btn btn-primary btn-sm edit-lecture-btn" data-lecture-id="{{ $lecture->id }}">
                                                    수정
                                                </button>
                                                <button type="button" class="btn btn-danger btn-sm delete-lecture-btn" data-lecture-id="{{ $lecture->id }}">
                                                    삭제
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        <button type="button" class="btn btn-success" id="btnAddLecture">
                            추가
                        </button>
                    </div>
                    <div id="lecturesDataContainer"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="board-form-actions">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> 저장
        </button>
        <a href="{{ route('backoffice.online-educations.index') }}" class="btn btn-secondary">
            취소
        </a>
    </div>
</form>

@include('backoffice.online-educations._lecture_search_modal')
