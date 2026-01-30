<?php

namespace App\Http\Controllers;

class SeminarTrainingController extends Controller
{
    private function menuMeta(): array
    {
        return [
            'gNum' => '02',
            'sNum' => '03',
            'gName' => '세미나 · 해외연수',
            'sName' => '세미나ㆍ해외연수 신청',
        ];
    }

    public function application_st()
    {
        return view('seminars_training.application_st', $this->menuMeta());
    }

    public function application_st_view()
    {
        return view('seminars_training.application_st_view', $this->menuMeta());
    }

    public function application_st_apply()
    {
        return view('seminars_training.application_st_apply', $this->menuMeta());
    }

    public function application_st_apply_end()
    {
        return view('seminars_training.application_st_apply_end', $this->menuMeta());
    }
}
