<?php

namespace App\Http\Controllers;

class SubController extends Controller
{
    public function education()
    {
        $gNum = "01"; $sNum = "01"; $gName = "교육 · 자격증"; $sName = "교육 안내";
        return view('education_certification.education', compact('gNum', 'sNum', 'gName', 'sName'));
    }
    public function certification()
    {
        $gNum = "01"; $sNum = "02"; $gName = "교육 · 자격증"; $sName = "자격증 안내";
        return view('education_certification.certification', compact('gNum', 'sNum', 'gName', 'sName'));
    }
    public function seminar()
    {
        $gNum = "02"; $sNum = "01"; $gName = "세미나 · 해외연수"; $sName = "세미나 안내";
        return view('seminars_training.seminar', compact('gNum', 'sNum', 'gName', 'sName'));
    }
    public function overseas_training()
    {
        $gNum = "02"; $sNum = "02"; $gName = "세미나 · 해외연수"; $sName = "해외연수 안내";
        return view('seminars_training.overseas_training', compact('gNum', 'sNum', 'gName', 'sName'));
    }
    public function establishment()
    {
        $gNum = "04"; $sNum = "01"; $gName = "협회 소개"; $sName = "설립목적 및 역할";
        return view('about.establishment', compact('gNum', 'sNum', 'gName', 'sName'));
    }
    public function projects()
    {
        $gNum = "04"; $sNum = "02"; $gName = "협회 소개"; $sName = "주요사업";
        return view('about.projects', compact('gNum', 'sNum', 'gName', 'sName'));
    }
    public function history()
    {
        $gNum = "04"; $sNum = "03"; $gName = "협회 소개"; $sName = "연혁 및 회칙";
        
        // 연혁 데이터 조회 (노출 항목만, 날짜 기준 내림차순)
        $histories = \App\Models\History::visible()
            ->orderedByDate()
            ->get();
        
        return view('about.history', compact('gNum', 'sNum', 'gName', 'sName', 'histories'));
    }
    public function bylaws()
    {
        $gNum = "04"; $sNum = "03"; $gName = "협회 소개"; $sName = "연혁 및 회칙";
        return view('about.bylaws', compact('gNum', 'sNum', 'gName', 'sName'));
    }
    public function organizational()
    {
        $gNum = "04"; $sNum = "04"; $gName = "협회 소개"; $sName = "조직도";
        
        // 조직도 에디터 내용 조회
        $chartContent = \App\Models\OrganizationalChart::getContent();
        
        // 구성원 목록 조회 (카테고리별 그룹화)
        $membersByCategory = \App\Models\OrganizationalMember::getGroupedByCategory();
        
        return view('about.organizational', compact('gNum', 'sNum', 'gName', 'sName', 'chartContent', 'membersByCategory'));
    }
    public function about_institutions()
    {
        $gNum = "04"; $sNum = "05"; $gName = "협회 소개"; $sName = "회원기관 소개";
        return view('about.about_institutions', compact('gNum', 'sNum', 'gName', 'sName'));
    }

    public function terms()
    {
        $gNum = "00"; $sNum = "00"; $gName = "약관"; $sName = "이용약관";
        return view('terms.terms', compact('gNum', 'sNum', 'gName', 'sName'));
    }
    public function txt_terms()
    {
        $gNum = "00"; $sNum = "00"; $gName = "약관"; $sName = "이용약관 문구";
        return view('terms.txt_terms', compact('gNum', 'sNum', 'gName', 'sName'));
    }
    public function privacy_policy()
    {
        $gNum = "00"; $sNum = "00"; $gName = "약관"; $sName = "개인정보처리방침";
        return view('terms.privacy_policy', compact('gNum', 'sNum', 'gName', 'sName'));
    }
    public function txt_privacy_policy()
    {
        $gNum = "00"; $sNum = "00"; $gName = "약관"; $sName = "개인정보처리방침 문구";
        return view('terms.txt_privacy_policy', compact('gNum', 'sNum', 'gName', 'sName'));
    }
    public function email_collection()
    {
        $gNum = "00"; $sNum = "00"; $gName = "약관"; $sName = "이메일무단수집거부";
        return view('terms.email_collection', compact('gNum', 'sNum', 'gName', 'sName'));
    }
    public function txt_email_collection()
    {
        $gNum = "00"; $sNum = "00"; $gName = "약관"; $sName = "이메일무단수집거부 문구";
        return view('terms.txt_email_collection', compact('gNum', 'sNum', 'gName', 'sName'));
    }
	
    public function admission_ticket()
    {
        $gNum = "99"; $sNum = "00"; $gName = "인쇄"; $sName = "수험표";
        return view('print.admission_ticket', compact('gNum', 'sNum', 'gName', 'sName'));
    }
    public function receipt()
    {
        $gNum = "99"; $sNum = "00"; $gName = "인쇄"; $sName = "영수증";
        return view('print.receipt', compact('gNum', 'sNum', 'gName', 'sName'));
    }
    public function certificate()
    {
        $gNum = "99"; $sNum = "00"; $gName = "인쇄"; $sName = "이수증";
        return view('print.certificate', compact('gNum', 'sNum', 'gName', 'sName'));
    }
    public function certificate_qualification()
    {
        $gNum = "99"; $sNum = "00"; $gName = "인쇄"; $sName = "자격 확인서";
        return view('print.certificate_qualification', compact('gNum', 'sNum', 'gName', 'sName'));
    }
    public function certificate_completion()
    {
        $gNum = "99"; $sNum = "00"; $gName = "인쇄"; $sName = "수료증";
        return view('print.certificate_completion', compact('gNum', 'sNum', 'gName', 'sName'));
    }
    public function certificate_finish()
    {
        $gNum = "99"; $sNum = "00"; $gName = "인쇄"; $sName = "이수증";
        return view('print.certificate_finish', compact('gNum', 'sNum', 'gName', 'sName'));
    }
    public function pop_print()
    {
        $gNum = "99"; $sNum = "00"; $gName = "인쇄"; $sName = "이수증";
        return view('print.pop_print', compact('gNum', 'sNum', 'gName', 'sName'));
    }
}