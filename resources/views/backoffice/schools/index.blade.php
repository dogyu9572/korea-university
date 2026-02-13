@extends('backoffice.layouts.app')

@section('title', '학교(회원교)관리')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/backoffice/boards.css') }}">
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
    <div class="board-page-header">
        <div class="board-page-buttons">
            <a href="{{ route('backoffice.schools.export', request()->query()) }}" class="btn btn-secondary">
                <i class="fas fa-file-excel"></i> 엑셀다운로드
            </a>
            <a href="{{ route('backoffice.schools.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> 신규등록
            </a>
        </div>
    </div>

    <div class="board-card">
        <div class="board-card-body">
            <!-- 검색 필터 -->
            <div class="board-filter">
                <form method="GET" action="{{ route('backoffice.schools.index') }}" class="filter-form" id="searchForm">
                    <input type="hidden" name="per_page" value="{{ $perPage }}">
                    <div class="filter-row">
                        <div class="filter-group">
                            <label for="branch" class="filter-label">지회</label>
                            <select id="branch" name="branch" class="filter-select">
                                @foreach($branches as $key => $value)
                                    <option value="{{ $key }}" @selected($filters['branch'] == $key)>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-group">
                            <label for="year" class="filter-label">연도</label>
                            <select id="year" name="year" class="filter-select">
                                @foreach($years as $key => $value)
                                    <option value="{{ $key }}" @selected($filters['year'] == $key)>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-group">
                            <label class="filter-label">회원교</label>
                            <div class="board-radio-group">
                                <div class="board-radio-item">
                                    <input type="radio" id="is_member_school_all" name="is_member_school" value="" class="board-radio-input" @checked($filters['is_member_school'] == '')>
                                    <label for="is_member_school_all">전체</label>
                                </div>
                                <div class="board-radio-item">
                                    <input type="radio" id="is_member_school_y" name="is_member_school" value="Y" class="board-radio-input" @checked($filters['is_member_school'] == 'Y')>
                                    <label for="is_member_school_y">Y</label>
                                </div>
                                <div class="board-radio-item">
                                    <input type="radio" id="is_member_school_n" name="is_member_school" value="N" class="board-radio-input" @checked($filters['is_member_school'] == 'N')>
                                    <label for="is_member_school_n">N</label>
                                </div>
                            </div>
                        </div>
                        <div class="filter-group">
                            <label for="school_name" class="filter-label">학교명</label>
                            <input type="text" id="school_name" name="school_name" class="filter-input"
                                placeholder="학교명을 입력하세요" value="{{ $filters['school_name'] ?? '' }}">
                        </div>
                        <div class="filter-group">
                            <div class="filter-buttons">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> 검색
                                </button>
                                <a href="{{ route('backoffice.schools.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-undo"></i> 초기화
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- 목록 개수 선택 -->
            <div class="board-list-header">
                <div class="list-info">
                    <span class="list-count">Total : {{ $schools->total() }}</span>
                </div>
                <div class="list-controls">
                    <form method="GET" action="{{ route('backoffice.schools.index') }}" class="per-page-form">
                        <input type="hidden" name="branch" value="{{ $filters['branch'] ?? '' }}">
                        <input type="hidden" name="year" value="{{ $filters['year'] ?? '' }}">
                        <input type="hidden" name="is_member_school" value="{{ $filters['is_member_school'] ?? '' }}">
                        <input type="hidden" name="school_name" value="{{ $filters['school_name'] ?? '' }}">
                        <label for="per_page" class="per-page-label">표시 개수:</label>
                        <select name="per_page" id="per_page" class="per-page-select" onchange="this.form.submit()">
                            <option value="20" @selected($perPage == 20)>20개</option>
                            <option value="50" @selected($perPage == 50)>50개</option>
                            <option value="100" @selected($perPage == 100)>100개</option>
                        </select>
                    </form>
                </div>
            </div>

            <div class="table-responsive">
                <table class="board-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>연도</th>
                            <th>학교명</th>
                            <th>지회</th>
                            <th>회원교</th>
                            <th>URL</th>
                            <th>관리</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($schools as $index => $school)
                            <tr data-id="{{ $school->id }}">
                                <td>{{ $schools->total() - ($schools->currentPage() - 1) * $schools->perPage() - $index }}</td>
                                <td>{{ $school->year ?? '-' }}</td>
                                <td>{{ $school->school_name }}</td>
                                <td>{{ $school->branch }}</td>
                                <td>{{ $school->is_member_school ? 'Y' : 'N' }}</td>
                                <td>
                                    @if($school->url)
                                        {{ $school->url }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <div class="board-btn-group">
                                        <a href="{{ route('backoffice.schools.edit', $school->id) }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-edit"></i> 수정
                                        </a>
                                        <form action="{{ route('backoffice.schools.destroy', $school->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('정말 삭제하시겠습니까?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash"></i> 삭제
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">등록된 학교가 없습니다.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <x-pagination :paginator="$schools" />
        </div>
    </div>
</div>
@endsection
