<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
    public function application_ec()
    {
        $gNum = "01"; $sNum = "03"; $gName = "교육 · 자격증"; $sName = "교육ㆍ자격증 신청";
        return view('education_certification.application_ec', compact('gNum', 'sNum', 'gName', 'sName'));
    }
    public function application_ec_view()
    {
        $gNum = "01"; $sNum = "03"; $gName = "교육 · 자격증"; $sName = "교육ㆍ자격증 신청";
        return view('education_certification.application_ec_view', compact('gNum', 'sNum', 'gName', 'sName'));
    }
    public function application_ec_apply()
    {
        $gNum = "01"; $sNum = "03"; $gName = "교육 · 자격증"; $sName = "교육ㆍ자격증 신청";
        return view('education_certification.application_ec_apply', compact('gNum', 'sNum', 'gName', 'sName'));
    }
    public function application_ec_apply_end()
    {
        $gNum = "01"; $sNum = "03"; $gName = "교육 · 자격증"; $sName = "교육ㆍ자격증 신청";
        return view('education_certification.application_ec_apply_end', compact('gNum', 'sNum', 'gName', 'sName'));
    }
    public function application_ec_view_type2()
    {
        $gNum = "01"; $sNum = "03"; $gName = "교육 · 자격증"; $sName = "교육ㆍ자격증 신청";
        return view('education_certification.application_ec_view_type2', compact('gNum', 'sNum', 'gName', 'sName'));
    }
    public function application_ec_receipt()
    {
        $gNum = "01"; $sNum = "03"; $gName = "교육 · 자격증"; $sName = "교육ㆍ자격증 신청";
        return view('education_certification.application_ec_receipt', compact('gNum', 'sNum', 'gName', 'sName'));
    }
    public function application_ec_receipt_end()
    {
        $gNum = "01"; $sNum = "03"; $gName = "교육 · 자격증"; $sName = "교육ㆍ자격증 신청";
        return view('education_certification.application_ec_receipt_end', compact('gNum', 'sNum', 'gName', 'sName'));
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
    public function application_st()
    {
        $gNum = "02"; $sNum = "03"; $gName = "세미나 · 해외연수"; $sName = "세미나ㆍ해외연수 신청";
        return view('seminars_training.application_st', compact('gNum', 'sNum', 'gName', 'sName'));
    }
    public function application_st_view()
    {
        $gNum = "02"; $sNum = "03"; $gName = "세미나 · 해외연수"; $sName = "세미나ㆍ해외연수 신청";
        return view('seminars_training.application_st_view', compact('gNum', 'sNum', 'gName', 'sName'));
    }
    public function application_st_apply()
    {
        $gNum = "02"; $sNum = "03"; $gName = "세미나 · 해외연수"; $sName = "세미나ㆍ해외연수 신청";
        return view('seminars_training.application_st_apply', compact('gNum', 'sNum', 'gName', 'sName'));
    }
    public function application_st_apply_end()
    {
        $gNum = "02"; $sNum = "03"; $gName = "세미나 · 해외연수"; $sName = "세미나ㆍ해외연수 신청";
        return view('seminars_training.application_st_apply_end', compact('gNum', 'sNum', 'gName', 'sName'));
    }
	
    public function notice()
    {
        $gNum = "03"; $sNum = "01"; $gName = "알림마당"; $sName = "공지사항";
        return view('notice.notice', compact('gNum', 'sNum', 'gName', 'sName'));
    }
    public function notice_view()
    {
        $gNum = "03"; $sNum = "01"; $gName = "알림마당"; $sName = "공지사항";
        return view('notice.notice_view', compact('gNum', 'sNum', 'gName', 'sName'));
    }
    public function faq()
    {
        $gNum = "03"; $sNum = "02"; $gName = "알림마당"; $sName = "FAQ";
        return view('notice.faq', compact('gNum', 'sNum', 'gName', 'sName'));
    }
    public function data_room()
    {
        $gNum = "03"; $sNum = "03"; $gName = "알림마당"; $sName = "자료실";
        return view('notice.data_room', compact('gNum', 'sNum', 'gName', 'sName'));
    }
    public function data_room_view()
    {
        $gNum = "03"; $sNum = "03"; $gName = "알림마당"; $sName = "자료실";
        return view('notice.data_room_view', compact('gNum', 'sNum', 'gName', 'sName'));
    }
    public function past_events()
    {
        $gNum = "03"; $sNum = "04"; $gName = "알림마당"; $sName = "지난 행사";
        return view('notice.past_events', compact('gNum', 'sNum', 'gName', 'sName'));
    }
    public function past_events_view()
    {
        $gNum = "03"; $sNum = "04"; $gName = "알림마당"; $sName = "지난 행사";
        return view('notice.past_events_view', compact('gNum', 'sNum', 'gName', 'sName'));
    }
    public function recruitment()
    {
        $gNum = "03"; $sNum = "05"; $gName = "알림마당"; $sName = "회원교 채용정보";
        return view('notice.recruitment', compact('gNum', 'sNum', 'gName', 'sName'));
    }
    public function recruitment_view()
    {
        $gNum = "03"; $sNum = "05"; $gName = "알림마당"; $sName = "회원교 채용정보";
        return view('notice.recruitment_view', compact('gNum', 'sNum', 'gName', 'sName'));
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
	
    public function application_status()
    {
        $gNum = "05"; $sNum = "01"; $gName = "마이페이지"; $sName = "교육 신청 현황";
        return view('mypage.application_status', compact('gNum', 'sNum', 'gName', 'sName'));
    }
    public function application_status_view()
    {
        $gNum = "05"; $sNum = "01"; $gName = "마이페이지"; $sName = "교육 신청 현황";
        return view('mypage.application_status_view', compact('gNum', 'sNum', 'gName', 'sName'));
    }
    public function application_status_view2()
    {
        $gNum = "05"; $sNum = "01"; $gName = "마이페이지"; $sName = "교육 신청 현황";
        return view('mypage.application_status_view2', compact('gNum', 'sNum', 'gName', 'sName'));
    }
    public function my_qualification()
    {
        $gNum = "05"; $sNum = "02"; $gName = "마이페이지"; $sName = "나의 자격 현황";
        return view('mypage.my_qualification', compact('gNum', 'sNum', 'gName', 'sName'));
    }
    public function my_qualification_view()
    {
        $gNum = "05"; $sNum = "02"; $gName = "마이페이지"; $sName = "나의 자격 현황";
        return view('mypage.my_qualification_view', compact('gNum', 'sNum', 'gName', 'sName'));
    }
    public function my_inquiries()
    {
        $gNum = "05"; $sNum = "03"; $gName = "마이페이지"; $sName = "나의 문의";
        return view('mypage.my_inquiries', compact('gNum', 'sNum', 'gName', 'sName'));
    }
    public function my_inquiries_view()
    {
        $gNum = "05"; $sNum = "03"; $gName = "마이페이지"; $sName = "나의 문의";
        return view('mypage.my_inquiries_view', compact('gNum', 'sNum', 'gName', 'sName'));
    }
    public function my_inquiries_write()
    {
        $gNum = "05"; $sNum = "03"; $gName = "마이페이지"; $sName = "나의 문의";
        return view('mypage.my_inquiries_write', compact('gNum', 'sNum', 'gName', 'sName'));
    }
    public function edit_member_information()
    {
        $gNum = "05"; $sNum = "04"; $gName = "마이페이지"; $sName = "회원정보 수정";
        return view('mypage.edit_member_information', compact('gNum', 'sNum', 'gName', 'sName'));
    }
	
    public function login()
    {
        $gNum = "00"; $sNum = "00"; $gName = "회원"; $sName = "로그인";
        return view('member.login', compact('gNum', 'sNum', 'gName', 'sName'));
    }
    public function join()
    {
        $gNum = "00"; $sNum = "00"; $gName = "회원"; $sName = "회원가입";
        return view('member.join', compact('gNum', 'sNum', 'gName', 'sName'));
    }
    public function join_easy()
    {
        $gNum = "00"; $sNum = "00"; $gName = "회원"; $sName = "로그인";
        return view('member.join_easy', compact('gNum', 'sNum', 'gName', 'sName'));
    }
    public function join_end()
    {
        $gNum = "00"; $sNum = "00"; $gName = "회원"; $sName = "회원가입 완료";
        return view('member.join_end', compact('gNum', 'sNum', 'gName', 'sName'));
    }
    public function find_id()
    {
        $gNum = "00"; $sNum = "00"; $gName = "회원"; $sName = "아이디/비밀번호 찾기";
        return view('member.find_id', compact('gNum', 'sNum', 'gName', 'sName'));
    }
    public function find_id_end()
    {
        $gNum = "00"; $sNum = "00"; $gName = "회원"; $sName = "아이디 찾기 완료";
        return view('member.find_id_end', compact('gNum', 'sNum', 'gName', 'sName'));
    }
    public function find_pw()
    {
        $gNum = "00"; $sNum = "00"; $gName = "회원"; $sName = "아이디/비밀번호 찾기";
        return view('member.find_pw', compact('gNum', 'sNum', 'gName', 'sName'));
    }
    public function find_pw_reset()
    {
        $gNum = "00"; $sNum = "00"; $gName = "회원"; $sName = "비밀번호 재설정";
        return view('member.find_pw_reset', compact('gNum', 'sNum', 'gName', 'sName'));
    }
    public function find_pw_end()
    {
        $gNum = "00"; $sNum = "00"; $gName = "회원"; $sName = "비밀번호 변경 완료";
        return view('member.find_pw_end', compact('gNum', 'sNum', 'gName', 'sName'));
    }
    public function pop_search_school()
    {
        $gNum = "00"; $sNum = "00"; $gName = "회원"; $sName = "학교 검색 팝업";
        return view('member.pop_search_school', compact('gNum', 'sNum', 'gName', 'sName'));
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