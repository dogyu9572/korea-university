<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Education;
use App\Models\EducationApplication;
use App\Models\Member;
use Carbon\Carbon;

class EducationApplicationSeeder extends Seeder
{
    /**
     * 교육 신청 테스트 데이터를 시드합니다.
     */
    public function run(): void
    {
        $programs = Education::query()->limit(5)->get();

        if ($programs->isEmpty()) {
            $this->command->warn('정기교육 또는 수시교육 프로그램이 없습니다. 먼저 교육 프로그램을 생성해주세요.');
            return;
        }

        // 회원 가져오기
        $members = Member::active()->limit(20)->get();

        if ($members->isEmpty()) {
            $this->command->warn('활성 회원이 없습니다. 먼저 회원을 생성해주세요.');
            return;
        }

        // 시퀀스 추적 변수
        $applicationSequence = $this->getNextApplicationSequence();
        $receiptSequence = $this->getNextReceiptSequence();
        $certificateSequences = [
            'CMP' => $this->getNextCertificateSequence('CMP'),
            'CRF' => $this->getNextCertificateSequence('CRF'),
        ];

        foreach ($programs as $program) {
            // 각 프로그램당 3~8명의 신청자 생성
            $applicationCount = rand(3, 8);
            $selectedMembers = $members->random(min($applicationCount, $members->count()));

            foreach ($selectedMembers as $index => $member) {
                $applicationDate = Carbon::now()->subDays(rand(1, 30));
                $paymentStatus = ['미입금', '입금완료'][rand(0, 1)];
                $isCompleted = $paymentStatus === '입금완료' && rand(0, 1);

                // 신청번호 생성
                $year = $applicationDate->format('Y');
                $applicationNumber = 'KUCRA-' . $year . '-' . str_pad($applicationSequence++, 4, '0', STR_PAD_LEFT);

                // 영수증 번호 생성
                $receiptNumber = null;
                if ($paymentStatus === '입금완료') {
                    $receiptNumber = 'KUCRA-REC-' . $year . '-' . str_pad($receiptSequence++, 4, '0', STR_PAD_LEFT);
                }

                // 수료증 번호 생성
                $certificateNumber = null;
                if ($isCompleted) {
                    $type = ($program->certificate_type === '수료증') ? 'CRF' : 'CMP';
                    $certificateNumber = 'KUCRA-' . $type . '-' . $year . '-' . str_pad($certificateSequences[$type]++, 4, '0', STR_PAD_LEFT);
                }

                EducationApplication::create([
                    'application_number' => $applicationNumber,
                    'education_id' => $program->id,
                    'member_id' => $member->id,
                    'applicant_name' => $member->name,
                    'affiliation' => $member->school_name,
                    'phone_number' => $member->phone_number,
                    'email' => $member->email,
                    'application_date' => $applicationDate,
                    'is_completed' => $isCompleted,
                    'certificate_number' => $certificateNumber,
                    'is_survey_completed' => $isCompleted && rand(0, 1),
                    'receipt_number' => $receiptNumber,
                    'refund_account_holder' => $member->name,
                    'refund_bank_name' => ['농협', '신한', '국민', '우리'][rand(0, 3)],
                    'refund_account_number' => str_pad(rand(1000000000, 9999999999), 10, '0', STR_PAD_LEFT),
                    'participation_fee' => $this->calculateParticipationFee($program),
                    'fee_type' => $this->getRandomFeeType(),
                    'payment_method' => json_encode(['무통장 입금']),
                    'payment_status' => $paymentStatus,
                    'payment_date' => $paymentStatus === '입금완료' ? $applicationDate->copy()->addDays(rand(1, 3)) : null,
                    'tax_invoice_status' => ['미신청', '신청완료', '발행완료'][rand(0, 2)],
                    'has_cash_receipt' => rand(0, 1),
                    'cash_receipt_purpose' => null,
                    'cash_receipt_number' => null,
                    'has_tax_invoice' => rand(0, 1),
                    'company_name' => null,
                    'registration_number' => null,
                    'contact_person_name' => null,
                    'contact_person_email' => null,
                    'contact_person_phone' => null,
                    'created_at' => $applicationDate,
                    'updated_at' => $applicationDate,
                ]);
            }
        }

        $this->command->info('교육 신청 테스트 데이터가 생성되었습니다.');
    }

    /**
     * 다음 신청번호 시퀀스 조회
     */
    private function getNextApplicationSequence(): int
    {
        $year = now()->format('Y');
        $lastNumber = EducationApplication::whereYear('created_at', $year)
            ->whereNotNull('application_number')
            ->where('application_number', 'like', 'KUCRA-' . $year . '-%')
            ->orderBy('id', 'desc')
            ->value('application_number');
        
        if ($lastNumber && preg_match('/KUCRA-\d{4}-(\d{4})/', $lastNumber, $matches)) {
            return (int)$matches[1] + 1;
        }
        
        return 1;
    }

    /**
     * 다음 영수증 번호 시퀀스 조회
     */
    private function getNextReceiptSequence(): int
    {
        $year = now()->format('Y');
        $lastNumber = EducationApplication::whereYear('created_at', $year)
            ->whereNotNull('receipt_number')
            ->where('receipt_number', 'like', 'KUCRA-REC-' . $year . '-%')
            ->orderBy('id', 'desc')
            ->value('receipt_number');
        
        if ($lastNumber && preg_match('/KUCRA-REC-\d{4}-(\d{4})/', $lastNumber, $matches)) {
            return (int)$matches[1] + 1;
        }
        
        return 1;
    }

    /**
     * 다음 수료증 번호 시퀀스 조회
     */
    private function getNextCertificateSequence(string $type): int
    {
        $year = now()->format('Y');
        $lastNumber = EducationApplication::whereYear('created_at', $year)
            ->whereNotNull('certificate_number')
            ->where('certificate_number', 'like', 'KUCRA-' . $type . '-' . $year . '-%')
            ->orderBy('id', 'desc')
            ->value('certificate_number');
        
        if ($lastNumber && preg_match('/KUCRA-' . $type . '-\d{4}-(\d{4})/', $lastNumber, $matches)) {
            return (int)$matches[1] + 1;
        }
        
        return 1;
    }

    /**
     * 참가비 계산
     */
    private function calculateParticipationFee(Education $program): ?float
    {
        $feeTypes = [
            'fee_member_twin',
            'fee_member_single',
            'fee_member_no_stay',
            'fee_guest_twin',
            'fee_guest_single',
            'fee_guest_no_stay',
        ];

        foreach ($feeTypes as $feeType) {
            if ($program->$feeType) {
                return (float) $program->$feeType;
            }
        }

        return null;
    }

    /**
     * 랜덤 참가비 타입 반환
     */
    private function getRandomFeeType(): string
    {
        $types = [
            '회원교_2인1실',
            '회원교_1인실',
            '회원교_비숙박',
            '비회원교_2인1실',
            '비회원교_1인실',
            '비회원교_비숙박',
        ];

        return $types[rand(0, count($types) - 1)];
    }
}
