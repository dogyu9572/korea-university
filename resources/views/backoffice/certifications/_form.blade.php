@php
    $isEdit = isset($certification) && $certification->exists;
    $cert = $isEdit ? $certification : new \App\Models\Certification();

    $appStart = $cert->application_start;
    $appEnd = $cert->application_end;
    $appStartDate = old('application_start_date', $appStart ? $appStart->format('Y-m-d') : '');
    $appStartHour = old('application_start_hour', $appStart ? (int)$appStart->format('H') : 0);
    $appEndDate = old('application_end_date', $appEnd ? $appEnd->format('Y-m-d') : '');
    $appEndHour = old('application_end_hour', $appEnd ? (int)$appEnd->format('H') : 23);

    $selectedVenueIds = old('venue_category_ids', $cert->venue_category_ids ?? []);
@endphp

<form action="{{ $isEdit ? route('backoffice.certifications.update', $cert) : route('backoffice.certifications.store') }}" method="POST" enctype="multipart/form-data" id="certificationForm">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    <!-- 자격증 정보 -->
    <div class="member-form-section">
        <h3 class="member-section-title">자격증 정보</h3>
        
        <div class="member-form-list">
            <div class="member-form-row">
                <label class="member-form-label">구분</label>
                <div class="member-form-field">
                    <div class="board-radio-group">
                        <div class="board-radio-item">
                            <input type="radio" id="level_1" name="level" value="1급 자격증" class="board-radio-input" @checked(old('level', $cert->level) == '1급 자격증') required>
                            <label for="level_1">1급 자격증</label>
                        </div>
                        <div class="board-radio-item">
                            <input type="radio" id="level_2" name="level" value="2급 자격증" class="board-radio-input" @checked(old('level', $cert->level) == '2급 자격증')>
                            <label for="level_2">2급 자격증</label>
                        </div>
                    </div>
                    @error('level')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="member-form-row">
                <label class="member-form-label">자격증명</label>
                <div class="member-form-field">
                    <input type="text" class="board-form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $cert->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="member-form-row">
                <label class="member-form-label">시험일</label>
                <div class="member-form-field">
                    <input type="date" class="board-form-control @error('exam_date') is-invalid @enderror" id="exam_date" name="exam_date" value="{{ old('exam_date', $cert->exam_date ? $cert->exam_date->format('Y-m-d') : '') }}" required>
                    @error('exam_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="member-form-row">
                <label class="member-form-label">시험장</label>
                <div class="member-form-field">
                    <div class="board-checkbox-group">
                        @forelse($venueCategories as $venue)
                            <label class="board-checkbox-item">
                                <input type="checkbox" name="venue_category_ids[]" value="{{ $venue->id }}" @checked(in_array($venue->id, $selectedVenueIds))>
                                <span>{{ $venue->name }}</span>
                            </label>
                        @empty
                            <span class="text-muted">시험장 카테고리가 없습니다. 코드관리에서 시험장 그룹을 먼저 등록해주세요.</span>
                        @endforelse
                    </div>
                    @error('venue_category_ids')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="member-form-row member-form-row-inline">
                <div class="member-form-inline-item">
                    <label class="member-form-label">시험방식</label>
                    <div class="member-form-field">
                        <input type="text" class="board-form-control @error('exam_method') is-invalid @enderror" id="exam_method" name="exam_method" value="{{ old('exam_method', $cert->exam_method) }}">
                        @error('exam_method')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="member-form-inline-item">
                    <label class="member-form-label">합격점수 기준</label>
                    <div class="member-form-field">
                        <input type="number" class="board-form-control @error('passing_score') is-invalid @enderror" id="passing_score" name="passing_score" value="{{ old('passing_score', $cert->passing_score) }}" min="0">
                        @error('passing_score')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="member-form-row">
                <label class="member-form-label">응시자격</label>
                <div class="member-form-field">
                    <input type="text" class="board-form-control @error('eligibility') is-invalid @enderror" id="eligibility" name="eligibility" value="{{ old('eligibility', $cert->eligibility) }}">
                    @error('eligibility')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="member-form-row">
                <label class="member-form-label">시험개요</label>
                <div class="member-form-field">
                    <textarea class="board-form-control board-form-textarea summernote-editor" id="exam_overview" name="exam_overview" rows="10">{{ old('exam_overview', $cert->exam_overview) }}</textarea>
                    @error('exam_overview')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="member-form-row">
                <label class="member-form-label">출제경향</label>
                <div class="member-form-field">
                    <textarea class="board-form-control board-form-textarea summernote-editor" id="exam_trend" name="exam_trend" rows="10">{{ old('exam_trend', $cert->exam_trend) }}</textarea>
                    @error('exam_trend')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="member-form-row">
                <label class="member-form-label">시험장 정보</label>
                <div class="member-form-field">
                    <textarea class="board-form-control board-form-textarea summernote-editor" id="exam_venue" name="exam_venue" rows="10">{{ old('exam_venue', $cert->exam_venue) }}</textarea>
                    @error('exam_venue')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="member-form-row">
                <label class="member-form-label">썸네일</label>
                <div class="member-form-field">
                    <input type="file" class="board-form-control @error('thumbnail') is-invalid @enderror" id="thumbnail" name="thumbnail" accept="image/*">
                    @if($isEdit && $cert->thumbnail_path)
                        <div class="mt-2 thumbnail-container">
                            <img src="{{ $cert->thumbnail_path }}" alt="썸네일" class="thumbnail-image">
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
                <label class="member-form-label">공개여부</label>
                <div class="member-form-field">
                    <div class="board-radio-group">
                        <div class="board-radio-item">
                            <input type="radio" id="is_public_1" name="is_public" value="1" class="board-radio-input" @checked(old('is_public', $cert->is_public ?? true) == 1)>
                            <label for="is_public_1">공개</label>
                        </div>
                        <div class="board-radio-item">
                            <input type="radio" id="is_public_0" name="is_public" value="0" class="board-radio-input" @checked(old('is_public', $cert->is_public ?? false) == 0)>
                            <label for="is_public_0">비공개</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 접수 정보 -->
    <div class="member-form-section">
        <h3 class="member-section-title">접수 정보</h3>
        
        <div class="member-form-list">
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
                <label class="member-form-label">정원</label>
                <div class="member-form-field">
                    <div class="capacity-row">
                        <input type="number" class="board-form-control @error('capacity') is-invalid @enderror" id="capacity" name="capacity" value="{{ old('capacity', $cert->capacity) }}" min="0">
                        <span class="input-group-text">명</span>
                        <label class="board-checkbox-item capacity-unlimited-check">
                            <input type="checkbox" id="capacity_unlimited" name="capacity_unlimited" value="1" @checked(old('capacity_unlimited', $cert->capacity_unlimited) == 1)>
                            <span>제한없음</span>
                        </label>
                    </div>
                    @error('capacity')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="member-form-row">
                <label class="member-form-label">접수상태</label>
                <div class="member-form-field">
                    <select class="board-form-control @error('application_status') is-invalid @enderror" id="application_status" name="application_status" required>
                        <option value="">선택</option>
                        <option value="접수중" @selected(old('application_status', $cert->application_status) == '접수중')>접수중</option>
                        <option value="접수마감" @selected(old('application_status', $cert->application_status) == '접수마감')>접수마감</option>
                        <option value="접수예정" @selected(old('application_status', $cert->application_status) == '접수예정')>접수예정</option>
                        <option value="비공개" @selected(old('application_status', $cert->application_status) == '비공개')>비공개</option>
                    </select>
                    @error('application_status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="member-form-row">
                <label class="member-form-label">결제방법</label>
                <div class="member-form-field">
                    <div class="board-checkbox-group">
                        <label class="board-checkbox-item">
                            <input type="checkbox" name="payment_methods[]" value="무통장입금" @checked(in_array('무통장입금', old('payment_methods', $cert->payment_methods ?? [])))>
                            <span>무통장 입금</span>
                        </label>
                        <label class="board-checkbox-item">
                            <input type="checkbox" name="payment_methods[]" value="방문카드결제" @checked(in_array('방문카드결제', old('payment_methods', $cert->payment_methods ?? [])))>
                            <span>방문 카드결제</span>
                        </label>
                        <label class="board-checkbox-item">
                            <input type="checkbox" name="payment_methods[]" value="온라인카드결제" @checked(in_array('온라인카드결제', old('payment_methods', $cert->payment_methods ?? [])))>
                            <span>온라인 카드결제</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="member-form-row">
                <label class="member-form-label">입금계좌</label>
                <div class="member-form-field">
                    <input type="text" class="board-form-control @error('deposit_account') is-invalid @enderror" id="deposit_account" name="deposit_account" value="{{ old('deposit_account', $cert->deposit_account) }}">
                    @error('deposit_account')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="member-form-row">
                <label class="member-form-label">입금기한</label>
                <div class="member-form-field">
                    <div class="input-group input-group-inline">
                        <select class="board-form-control" id="deposit_deadline_days" name="deposit_deadline_days">
                            <option value="">선택</option>
                            @for($d = 1; $d <= 7; $d++)
                                <option value="{{ $d }}" @selected(old('deposit_deadline_days', $cert->deposit_deadline_days) == $d)>{{ $d }}일</option>
                            @endfor
                        </select>
                        <span class="input-group-text">접수일 기준</span>
                    </div>
                    @error('deposit_deadline_days')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <div class="board-form-actions">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> 저장
        </button>
        <a href="{{ route('backoffice.certifications.index') }}" class="btn btn-secondary">
            취소
        </a>
    </div>
</form>
