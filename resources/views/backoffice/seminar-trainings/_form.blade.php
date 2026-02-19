
<form action="{{ $isEdit ? route('backoffice.seminar-trainings.update', $program) : route('backoffice.seminar-trainings.store') }}" method="POST" enctype="multipart/form-data" id="educationProgramForm">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    <div class="member-form-section">
        <h3 class="member-section-title">세미나/해외연수 정보</h3>
        <div class="member-form-list">
            <div class="member-form-row">
                <label class="member-form-label">구분</label>
                <div class="member-form-field">
                    <div class="board-radio-group">
                        <div class="board-radio-item">
                            <input type="radio" id="type_seminar" name="type" value="세미나" class="board-radio-input" @checked(old('type', $program->type ?? '') == '세미나') required>
                            <label for="type_seminar">세미나</label>
                        </div>
                        <div class="board-radio-item">
                            <input type="radio" id="type_overseas" name="type" value="해외연수" class="board-radio-input" @checked(old('type', $program->type ?? '') == '해외연수')>
                            <label for="type_overseas">해외연수</label>
                        </div>
                    </div>
                    @error('type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="member-form-row">
                <label class="member-form-label">세미나/해외연수명</label>
                <div class="member-form-field">
                    <input type="text" class="board-form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $program->name ?? '') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="member-form-row">
                <label class="member-form-label">썸네일</label>
                <div class="member-form-field">
                    <input type="file" class="board-form-control @error('thumbnail') is-invalid @enderror" id="thumbnail" name="thumbnail" accept="image/*">
                    @if($isEdit && $program->thumbnail_path)
                        <div class="mt-2 thumbnail-container">
                            <img src="{{ $program->thumbnail_path }}" alt="썸네일" class="thumbnail-image">
                            <button type="button" class="btn-delete-simple delete-thumbnail-btn">삭제</button>
                            <input type="hidden" name="delete_thumbnail" id="delete_thumbnail" value="0">
                        </div>
                    @endif
                    @error('thumbnail')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="member-form-row">
                <label class="member-form-label">교육기간</label>
                <div class="member-form-field">
                    <div class="period-time-row">
                        <input type="date" class="board-form-control" name="period_start" value="{{ old('period_start', $program->period_start ? $program->period_start->format('Y-m-d') : '') }}">
                        <span class="period-sep">~</span>
                        <input type="date" class="board-form-control" name="period_end" value="{{ old('period_end', $program->period_end ? $program->period_end->format('Y-m-d') : '') }}">
                    </div>
                    @error('period_start')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    @error('period_end')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="member-form-row member-form-row-inline">
                <div class="member-form-inline-item">
                    <label class="member-form-label">교육시간</label>
                    <div class="member-form-field">
                        <input type="text" class="board-form-control" name="period_time" value="{{ old('period_time', $program->period_time ?? '') }}">
                    </div>
                </div>
                <div class="member-form-inline-item">
                    <label class="member-form-label">합숙여부</label>
                    <div class="member-form-field">
                        <div class="board-radio-group">
                            <div class="board-radio-item">
                                <input type="radio" id="is_accommodation_1" name="is_accommodation" value="1" class="board-radio-input" @checked(old('is_accommodation', $program->is_accommodation ?? false) == 1)>
                                <label for="is_accommodation_1">합숙</label>
                            </div>
                            <div class="board-radio-item">
                                <input type="radio" id="is_accommodation_0" name="is_accommodation" value="0" class="board-radio-input" @checked(old('is_accommodation', $program->is_accommodation ?? false) == 0)>
                                <label for="is_accommodation_0">비합숙</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="member-form-row">
                <label class="member-form-label">총차시/기수</label>
                <div class="member-form-field">
                    <input type="text" class="board-form-control" name="total_sessions_class" value="{{ old('total_sessions_class', $program->total_sessions_class ?? '') }}">
                </div>
            </div>

            <div class="member-form-row">
                <label class="member-form-label">교육장소</label>
                <div class="member-form-field">
                    <input type="text" class="board-form-control" name="location" value="{{ old('location', $program->location ?? '') }}">
                </div>
            </div>

            <div class="member-form-row">
                <label class="member-form-label">교육대상</label>
                <div class="member-form-field">
                    <input type="text" class="board-form-control" name="target" value="{{ old('target', $program->target ?? '') }}">
                </div>
            </div>

            <div class="member-form-row">
                <label class="member-form-label">교육 개요</label>
                <div class="member-form-field">
                    <textarea class="board-form-control board-form-textarea summernote-editor" id="education_overview" name="education_overview" rows="10">{{ old('education_overview', $program->education_overview ?? '') }}</textarea>
                    @error('education_overview')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="member-form-row">
                <label class="member-form-label">교육일정</label>
                <div class="member-form-field">
                    <textarea class="board-form-control board-form-textarea summernote-editor" id="education_schedule" name="education_schedule" rows="10">{{ old('education_schedule', $program->education_schedule ?? '') }}</textarea>
                    @error('education_schedule')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="member-form-row">
                <label class="member-form-label">참가비 및 납부안내</label>
                <div class="member-form-field">
                    <textarea class="board-form-control board-form-textarea summernote-editor" id="fee_info" name="fee_info" rows="10">{{ old('fee_info', $program->fee_info ?? '') }}</textarea>
                    @error('fee_info')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="member-form-row">
                <label class="member-form-label">취소 및 환불규정</label>
                <div class="member-form-field">
                    <textarea class="board-form-control board-form-textarea summernote-editor" id="refund_policy" name="refund_policy" rows="10">{{ old('refund_policy', $program->refund_policy ?? '') }}</textarea>
                    @error('refund_policy')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="member-form-row">
                <label class="member-form-label">교과내용</label>
                <div class="member-form-field">
                    <textarea class="board-form-control board-form-textarea summernote-editor" id="curriculum" name="curriculum" rows="10">{{ old('curriculum', $program->curriculum ?? '') }}</textarea>
                    @error('curriculum')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="member-form-row">
                <label class="member-form-label">교육안내</label>
                <div class="member-form-field">
                    <textarea class="board-form-control board-form-textarea summernote-editor" id="education_notice" name="education_notice" rows="10">{{ old('education_notice', $program->education_notice ?? '') }}</textarea>
                    @error('education_notice')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="member-form-row">
                <label class="member-form-label">첨부파일</label>
                <div class="member-form-field">
                    <input type="file" class="board-form-control" name="attachments[]" multiple>
                    @if ($attachments->count() > 0)
                        <div class="mt-3">
                            <span>첨부파일: </span>
                            @foreach ($attachments as $idx => $att)
                                <span>
                                    <a href="{{ $att->path }}" target="_blank">{{ $att->name }}</a>
                                    <button type="button" class="btn-delete-simple ms-2 delete-attachment-btn" data-attachment-id="{{ $att->id }}">삭제</button>
                                </span>
                                @if ($idx < $attachments->count() - 1), @endif
                            @endforeach
                            @foreach ($attachments as $att)
                                <input type="hidden" name="delete_attachments[]" id="delete_attachment_{{ $att->id }}" value="">
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <div class="member-form-row member-form-row-inline">
                <div class="member-form-inline-item">
                    <label class="member-form-label">수료기준</label>
                    <div class="member-form-field">
                        <input type="text" class="board-form-control" name="completion_criteria" value="{{ old('completion_criteria', $program->completion_criteria ?? '') }}">
                    </div>
                </div>
                <div class="member-form-inline-item">
                    <label class="member-form-label">연회비</label>
                    <div class="member-form-field">
                        <input type="number" class="board-form-control" name="annual_fee" value="{{ old('annual_fee', $program->annual_fee ? (int) $program->annual_fee : '') }}" min="0" step="1">
                    </div>
                </div>
            </div>

            <div class="member-form-row">
                <label class="member-form-label">{{ old('certificate_type', $program->certificate_type ?? '이수증') }}</label>
                <div class="member-form-field">
                    <div class="board-radio-group">
                        <div class="board-radio-item">
                            <input type="radio" id="certificate_type_completion" name="certificate_type" value="이수증" class="board-radio-input" @checked(old('certificate_type', $program->certificate_type ?? '이수증') == '이수증') required>
                            <label for="certificate_type_completion">이수증</label>
                        </div>
                        <div class="board-radio-item">
                            <input type="radio" id="certificate_type_certificate" name="certificate_type" value="수료증" class="board-radio-input" @checked(old('certificate_type', $program->certificate_type ?? '') == '수료증')>
                            <label for="certificate_type_certificate">수료증</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="member-form-row">
                <label class="member-form-label">설문조사 URL</label>
                <div class="member-form-field">
                    <input type="url" class="board-form-control" name="survey_url" value="{{ old('survey_url', $program->survey_url ?? '') }}">
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

            <div class="member-form-row">
                <label class="member-form-label">교육이수시간</label>
                <div class="member-form-field">
                    <div class="input-group input-group-inline">
                        <input type="number" class="board-form-control" name="completion_hours" value="{{ old('completion_hours', $program->completion_hours ?? '') }}" min="0">
                        <span class="input-group-text">시간</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="member-form-section">
        <h3 class="member-section-title">접수 정보</h3>
        <div class="member-form-list">
            <div class="member-form-row">
                <label class="member-form-label">신청기간</label>
                <div class="member-form-field">
                    <div class="application-period-row">
                        <input type="date" class="board-form-control" name="application_start_date" value="{{ $appStartDate }}">
                        <select class="board-form-control application-hour-select" name="application_start_hour">
                            @for ($h = 0; $h <= 23; $h++)
                                <option value="{{ $h }}" @selected($appStartHour == $h)>{{ sprintf('%02d', $h) }}시</option>
                            @endfor
                        </select>
                        <span class="period-sep">~</span>
                        <input type="date" class="board-form-control" name="application_end_date" value="{{ $appEndDate }}">
                        <select class="board-form-control application-hour-select" name="application_end_hour">
                            @for ($h = 0; $h <= 23; $h++)
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
                <label class="member-form-label">정원</label>
                <div class="member-form-field">
                    <div class="capacity-row">
                        <input type="number" class="board-form-control" name="capacity" value="{{ old('capacity', $program->capacity ?? '') }}" min="0">
                        <span class="input-group-text">명</span>
                        <label class="board-checkbox-item capacity-unlimited-check">
                            <input type="checkbox" name="capacity_unlimited" value="1" @checked(old('capacity_unlimited', $program->capacity_unlimited ?? false) == 1)>
                            <span>제한없음</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="member-form-row member-form-row-inline">
                <div class="member-form-inline-item">
                    <label class="member-form-label">회원교당 정원</label>
                    <div class="member-form-field">
                        <div class="capacity-row">
                            <input type="number" class="board-form-control" name="capacity_per_school" value="{{ old('capacity_per_school', $program->capacity_per_school ?? '') }}" min="0">
                            <span class="input-group-text">명</span>
                            <label class="board-checkbox-item capacity-unlimited-check">
                                <input type="checkbox" name="capacity_per_school_unlimited" value="1" @checked(old('capacity_per_school_unlimited', $program->capacity_per_school_unlimited ?? false) == 1)>
                                <span>제한없음</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="member-form-inline-item">
                    <label class="member-form-label">접수상태</label>
                    <div class="member-form-field">
                        <select class="board-form-control" name="application_status" required>
                            <option value="">선택</option>
                            <option value="접수중" @selected(old('application_status', $program->application_status ?? '') == '접수중')>접수중</option>
                            <option value="접수마감" @selected(old('application_status', $program->application_status ?? '') == '접수마감')>접수마감</option>
                            <option value="접수예정" @selected(old('application_status', $program->application_status ?? '') == '접수예정')>접수예정</option>
                            <option value="비공개" @selected(old('application_status', $program->application_status ?? '') == '비공개')>비공개</option>
                        </select>
                        @error('application_status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="member-form-section">
        <h3 class="member-section-title">결제 및 환불 정보</h3>
        <div class="member-form-list">
            <div class="member-form-row">
                <label class="member-form-label">참가비</label>
                <div class="member-form-field">
                    <table class="board-table">
                        <thead>
                            <tr><th>구분</th><th>2인 1실</th><th>1인실</th><th>비숙박</th></tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th>회원교</th>
                                <td><input type="number" class="board-form-control form-control-sm" name="fee_member_twin" value="{{ old('fee_member_twin', $program->fee_member_twin ? (int) $program->fee_member_twin : '') }}" min="0" step="1"></td>
                                <td><input type="number" class="board-form-control form-control-sm" name="fee_member_single" value="{{ old('fee_member_single', $program->fee_member_single ? (int) $program->fee_member_single : '') }}" min="0" step="1"></td>
                                <td><input type="number" class="board-form-control form-control-sm" name="fee_member_no_stay" value="{{ old('fee_member_no_stay', $program->fee_member_no_stay ? (int) $program->fee_member_no_stay : '') }}" min="0" step="1"></td>
                            </tr>
                            <tr>
                                <th>비회원교</th>
                                <td><input type="number" class="board-form-control form-control-sm" name="fee_guest_twin" value="{{ old('fee_guest_twin', $program->fee_guest_twin ? (int) $program->fee_guest_twin : '') }}" min="0" step="1"></td>
                                <td><input type="number" class="board-form-control form-control-sm" name="fee_guest_single" value="{{ old('fee_guest_single', $program->fee_guest_single ? (int) $program->fee_guest_single : '') }}" min="0" step="1"></td>
                                <td><input type="number" class="board-form-control form-control-sm" name="fee_guest_no_stay" value="{{ old('fee_guest_no_stay', $program->fee_guest_no_stay ? (int) $program->fee_guest_no_stay : '') }}" min="0" step="1"></td>
                            </tr>
                        </tbody>
                    </table>
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
                <label class="member-form-label">입금계좌</label>
                <div class="member-form-field">
                    <input type="text" class="board-form-control" name="deposit_account" value="{{ old('deposit_account', $program->deposit_account ?? '') }}">
                </div>
            </div>

            <div class="member-form-row">
                <label class="member-form-label">입금기한</label>
                <div class="member-form-field">
                    <div class="input-group input-group-inline">
                        <select class="board-form-control" name="deposit_deadline_days">
                            <option value="">선택</option>
                            @for ($d = 1; $d <= 7; $d++)
                                <option value="{{ $d }}" @selected(old('deposit_deadline_days', $program->deposit_deadline_days) == $d)>{{ $d }}일</option>
                            @endfor
                        </select>
                        <span class="input-group-text">접수일 기준</span>
                    </div>
                    @error('deposit_deadline_days')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="member-form-row">
                <label class="member-form-label">환불규정</label>
                <div class="member-form-field">
                    <table class="board-table refund-table">
                        <thead>
                            <tr><th>구분</th><th>수수료</th><th>취소 기한</th></tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th>2인 1실</th>
                                <td><input type="number" class="board-form-control form-control-sm" name="refund_twin_fee" value="{{ old('refund_twin_fee', $program->refund_twin_fee ? (int) $program->refund_twin_fee : '') }}" min="0" step="1"></td>
                                <td>
                                    <span class="refund-deadline-prefix">교육 시작일 기준</span>
                                    <select class="board-form-control form-control-sm refund-deadline-days" name="refund_twin_deadline_days">
                                        <option value="">선택</option>
                                        @for ($d = 1; $d <= 30; $d++)
                                            <option value="{{ $d }}" @selected($refundTwin == $d)>{{ $d }}일 전</option>
                                        @endfor
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>1인실</th>
                                <td><input type="number" class="board-form-control form-control-sm" name="refund_single_fee" value="{{ old('refund_single_fee', $program->refund_single_fee ? (int) $program->refund_single_fee : '') }}" min="0" step="1"></td>
                                <td>
                                    <span class="refund-deadline-prefix">교육 시작일 기준</span>
                                    <select class="board-form-control form-control-sm refund-deadline-days" name="refund_single_deadline_days">
                                        <option value="">선택</option>
                                        @for ($d = 1; $d <= 30; $d++)
                                            <option value="{{ $d }}" @selected($refundSingle == $d)>{{ $d }}일 전</option>
                                        @endfor
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>비숙박</th>
                                <td><input type="number" class="board-form-control form-control-sm" name="refund_no_stay_fee" value="{{ old('refund_no_stay_fee', $program->refund_no_stay_fee ? (int) $program->refund_no_stay_fee : '') }}" min="0" step="1"></td>
                                <td>
                                    <span class="refund-deadline-prefix">교육 시작일 기준</span>
                                    <select class="board-form-control form-control-sm refund-deadline-days" name="refund_no_stay_deadline_days">
                                        <option value="">선택</option>
                                        @for ($d = 1; $d <= 30; $d++)
                                            <option value="{{ $d }}" @selected($refundNoStay == $d)>{{ $d }}일 전</option>
                                        @endfor
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>당일취소</th>
                                <td><input type="number" class="board-form-control form-control-sm" name="refund_same_day_fee" value="{{ old('refund_same_day_fee', $program->refund_same_day_fee ? (int) $program->refund_same_day_fee : '') }}" min="0" step="1"></td>
                                <td>-</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="board-form-actions">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> 저장</button>
        <a href="{{ route('backoffice.seminar-trainings.index') }}" class="btn btn-secondary">취소</a>
    </div>
</form>
