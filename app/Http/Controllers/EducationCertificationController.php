<?php

namespace App\Http\Controllers;

class EducationCertificationController extends Controller
{
    private function menuMeta(): array
    {
        return [
            'gNum' => '01',
            'sNum' => '03',
            'gName' => '교육 · 자격증',
            'sName' => '교육ㆍ자격증 신청',
        ];
    }

    public function application_ec()
    {
        return view('education_certification.application_ec', $this->menuMeta());
    }

    public function application_ec_view()
    {
        return view('education_certification.application_ec_view', $this->menuMeta());
    }

    public function application_ec_apply()
    {
        return view('education_certification.application_ec_apply', $this->menuMeta());
    }

    public function application_ec_apply_end()
    {
        return view('education_certification.application_ec_apply_end', $this->menuMeta());
    }

    public function application_ec_view_type2()
    {
        return view('education_certification.application_ec_view_type2', $this->menuMeta());
    }

    public function application_ec_receipt()
    {
        return view('education_certification.application_ec_receipt', $this->menuMeta());
    }

    public function application_ec_receipt_end()
    {
        return view('education_certification.application_ec_receipt_end', $this->menuMeta());
    }

    public function application_ec_e_learning()
    {
        return view('education_certification.application_ec_e_learning', $this->menuMeta());
    }
}
