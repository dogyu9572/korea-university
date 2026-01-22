@extends('backoffice.layouts.app')

@section('title', '조직도 관리')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/backoffice/organizational.css') }}">
<link rel="stylesheet" href="{{ asset('css/backoffice/summernote-custom.css') }}">
<!-- Summernote CSS -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
@endsection

@section('scripts')
<!-- jQuery, Bootstrap JS (모달 사용을 위해 필요) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Summernote JS -->
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
<script src="{{ asset('js/backoffice/organizational.js') }}"></script>
@endsection

@section('content')
@if (session('success'))
    <div class="alert alert-success board-hidden-alert">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger board-hidden-alert">
        {{ session('error') }}
    </div>
@endif

<div class="board-container">
    <div class="board-card">
        <div class="board-card-body">
            <!-- 조직도 에디터 섹션 -->
            <div class="organizational-chart-header">
                <label class="board-form-label">조직도</label>
                <button type="button" id="btnSaveChart" class="btn btn-primary">
                    <i class="fas fa-save"></i> 저장
                </button>
            </div>
            <form action="{{ route('backoffice.organizational.update-chart') }}" method="POST" id="chartForm">
                @csrf
                <div class="board-form-group">
                    <textarea class="board-form-control summernote-editor" id="chartContent" name="content">{{ old('content', $chartContent) }}</textarea>
                    @error('content')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </form>

            <!-- 구성원 정보 관리 섹션 -->
            <div class="organizational-members-table-wrapper">
                <div class="organizational-members-header">
                    <label class="board-form-label">세부 정보</label>
                    <button type="button" class="btn btn-success" id="btnAddMember">
                        <i class="fas fa-plus"></i> 구성원 추가
                    </button>
                </div>
                <table class="board-table organizational-members-table">
                    <thead>
                        <tr>
                            <th>분류</th>
                            <th>이름</th>
                            <th>직위</th>
                            <th>소속기관</th>
                            <th>휴대폰 번호</th>
                            <th>정렬</th>
                            <th>관리</th>
                        </tr>
                    </thead>
                    <tbody id="membersTableBody">
                        @forelse($members as $member)
                            <tr data-id="{{ $member->id }}">
                                <td>
                                    <select class="board-form-control form-control-sm member-category" name="category">
                                        <option value="회장" @selected($member->category == '회장')>회장</option>
                                        <option value="부회장" @selected($member->category == '부회장')>부회장</option>
                                        <option value="사무국" @selected($member->category == '사무국')>사무국</option>
                                        <option value="지회" @selected($member->category == '지회')>지회</option>
                                        <option value="감사" @selected($member->category == '감사')>감사</option>
                                        <option value="고문" @selected($member->category == '고문')>고문</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" class="board-form-control form-control-sm member-name" name="name" value="{{ $member->name }}">
                                </td>
                                <td>
                                    <input type="text" class="board-form-control form-control-sm member-position" name="position" value="{{ $member->position }}">
                                </td>
                                <td>
                                    <input type="text" class="board-form-control form-control-sm member-affiliation" name="affiliation" value="{{ $member->affiliation }}">
                                </td>
                                <td>
                                    <input type="text" class="board-form-control form-control-sm member-phone" name="phone" value="{{ $member->phone }}">
                                </td>
                                <td>
                                    <input type="number" class="board-form-control form-control-sm member-sort-order" name="sort_order" value="{{ $member->sort_order }}" min="0">
                                </td>
                                <td>
                                    <div class="board-btn-group">
                                        <button type="button" class="btn btn-primary btn-sm btn-save-member" data-id="{{ $member->id }}">
                                            <i class="fas fa-edit"></i> 수정
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm btn-delete-member" data-id="{{ $member->id }}">
                                            <i class="fas fa-trash"></i> 삭제
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr class="empty-row">
                                <td colspan="7" class="text-center">등록된 구성원이 없습니다.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
