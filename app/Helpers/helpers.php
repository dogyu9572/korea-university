<?php

/**
 * 날짜를 한글 요일 포함 형식으로 반환합니다. (예: 2025.11.04.(화))
 */
function format_date_ko($date): string
{
    if (!$date) {
        return '';
    }
    $dow = ['일', '월', '화', '수', '목', '금', '토'];
    $d = $date instanceof \Carbon\Carbon ? $date : \Carbon\Carbon::parse($date);
    $dayOfWeek = $dow[$d->dayOfWeek] ?? '';

    return $d->format('Y.m.d') . '.' . '(' . $dayOfWeek . ')';
}

/**
 * 기간 문자열을 반환합니다. (예: 2025.11.04.(화) ~ 11.28.(금))
 * end가 없으면 start + time 또는 start만 반환합니다.
 */
function format_period_ko($start, $end = null, ?string $time = null): string
{
    $startStr = format_date_ko($start);
    $endStr = $end ? format_date_ko($end) : '';

    if ($startStr && $endStr) {
        return $startStr . ' ~ ' . $endStr;
    }
    if ($startStr && $time) {
        return $startStr . ' ' . $time;
    }

    return $startStr ?: '-';
}

/**
 * 교육/자격증/온라인 신청 버튼 상태를 반환합니다.
 *
 * @return array{class: string, text: string, url: string}
 */
function get_application_button_state(string $status, string $programType, int $id): array
{
    if ($status === '접수중') {
        $routes = [
            'education' => [route('education_certification.application_ec_apply') . '?education_id=', '신청하기'],
            'certification' => [route('education_certification.application_ec_receipt') . '?certification_id=', '시험 접수하기'],
            'online' => [route('education_certification.application_ec_e_learning') . '?online_education_id=', '신청하기'],
        ];
        [$url, $text] = $routes[$programType] ?? [route('education_certification.application_ec_apply'), '신청하기'];

        return [
            'class' => 'btn btn_write btn_wbb',
            'text' => $text,
            'url' => $url . $id,
        ];
    }

    if ($status === '접수마감') {
        $text = $programType === 'education' || $programType === 'online' ? '수강신청마감' : '접수마감';

        return [
            'class' => 'btn btn_end',
            'text' => $text,
            'url' => 'javascript:void(0);',
        ];
    }

    return [
        'class' => 'btn btn_wkk',
        'text' => '개설예정',
        'url' => 'javascript:void(0);',
    ];
}

/**
 * 교육 참가비 문자열을 반환합니다. (fee_member_*, fee_guest_* 컬럼 조합)
 */
function format_education_fee(object $education): string
{
    $fields = ['fee_member_twin', 'fee_member_single', 'fee_member_no_stay', 'fee_guest_twin', 'fee_guest_single', 'fee_guest_no_stay'];
    $parts = [];
    foreach ($fields as $f) {
        if (!empty($education->$f)) {
            $parts[] = number_format($education->$f) . '원';
        }
    }

    return implode(', ', $parts) ?: '';
}
