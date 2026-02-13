<?php

namespace Database\Seeders;

use App\Models\School;
use Illuminate\Database\Seeder;

class SchoolSeeder extends Seeder
{
    /**
     * 지회구분 → 지회명(DB branch) 매핑
     */
    private function branchMap(): array
    {
        return [
            '경기인천강원' => '경기인천강원지회',
            '서울' => '서울지회',
            '대전충청세종' => '대전충청세종지회',
            '부산울산경남' => '부산울산경남지회',
            '대구경북' => '대구경북지회',
            '광주전남제주' => '광주전남제주지회',
            '전북' => '전북지회',
        ];
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (School::exists()) {
            return;
        }

        $branchMap = $this->branchMap();
        $rows = $this->getSchoolsData();

        $now = now();
        $inserts = [];

        foreach ($rows as $row) {
            $inserts[] = [
                'branch' => $branchMap[$row['branch_key']] ?? $row['branch_key'] . '지회',
                'year' => null,
                'school_name' => $row['school_name'],
                'is_member_school' => true,
                'url' => $row['url'] ?: null,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        School::insert($inserts);
    }

    /**
     * 회원교 146건 원본 데이터 (지회구분, 회원교명, 홈페이지)
     */
    private function getSchoolsData(): array
    {
        return [
            ['branch_key' => '경기인천강원', 'school_name' => '가천대학교', 'url' => 'https://www.gachon.ac.kr/kor/index.do'],
            ['branch_key' => '경기인천강원', 'school_name' => '가톨릭관동대학교', 'url' => 'http://uicf.cku.ac.kr'],
            ['branch_key' => '서울', 'school_name' => '가톨릭대학교', 'url' => 'https://iacf.catholic.ac.kr/main'],
            ['branch_key' => '경기인천강원', 'school_name' => '강원대학교', 'url' => 'https://uicf.kangwon.ac.kr/'],
            ['branch_key' => '경기인천강원', 'school_name' => '강원도립대학교', 'url' => 'https://www.gw.ac.kr/iac'],
            ['branch_key' => '서울', 'school_name' => '건국대학교', 'url' => 'https://iacf.kku.ac.kr/main.do'],
            ['branch_key' => '대전충청세종', 'school_name' => '건국대학교 글로컬캠퍼스', 'url' => 'https://iacf.kku.ac.kr/main.do'],
            ['branch_key' => '대전충청세종', 'school_name' => '건양대학교', 'url' => 'https://kuicf.konyang.ac.kr/kuicf.do'],
            ['branch_key' => '서울', 'school_name' => '경기대학교', 'url' => 'http://sanhak.kyonggi.ac.kr/'],
            ['branch_key' => '부산울산경남', 'school_name' => '경남대학교', 'url' => 'https://iacf.kyungnam.ac.kr/'],
            ['branch_key' => '경기인천강원', 'school_name' => '경동대학교', 'url' => 'https://www.kduniv.ac.kr/sanhak/Main.do'],
            ['branch_key' => '대구경북', 'school_name' => '경북대학교', 'url' => 'https://iac.knu.ac.kr/iachome/'],
            ['branch_key' => '부산울산경남', 'school_name' => '경상국립대학교', 'url' => 'https://www.gnu.ac.kr/research/main.do'],
            ['branch_key' => '부산울산경남', 'school_name' => '경성대학교', 'url' => 'https://cms1.ks.ac.kr/koiac/Main.do'],
            ['branch_key' => '대구경북', 'school_name' => '경운대학교', 'url' => 'https://www.ikw.ac.kr/iacf/main.tc'],
            ['branch_key' => '대구경북', 'school_name' => '경일대학교', 'url' => 'https://iacf.kiu.ac.kr/center/index.htm'],
            ['branch_key' => '서울', 'school_name' => '경희대학교', 'url' => 'https://research.khu.ac.kr/research/user/main/view.do'],
            ['branch_key' => '경기인천강원', 'school_name' => '경희대학교 국제캠퍼스', 'url' => 'https://research.khu.ac.kr/research/user/main/view.do'],
            ['branch_key' => '대구경북', 'school_name' => '계명대학교', 'url' => 'https://sanhak.kmu.ac.kr/'],
            ['branch_key' => '서울', 'school_name' => '고려대학교', 'url' => 'https://rms.korea.ac.kr/nrpt/openHome/index.do'],
            ['branch_key' => '대전충청세종', 'school_name' => '고려대학교 세종캠퍼스', 'url' => 'https://sejong.korea.ac.kr/sites/innovation/index.do'],
            ['branch_key' => '부산울산경남', 'school_name' => '고신대학교', 'url' => 'https://www.kosinrnd.com/'],
            ['branch_key' => '대전충청세종', 'school_name' => '과학기술연합대학원대학교', 'url' => 'https://www.ust.ac.kr/icore/index.do'],
            ['branch_key' => '서울', 'school_name' => '광운대학교', 'url' => 'https://iacf.kw.ac.kr/'],
            ['branch_key' => '광주전남제주', 'school_name' => '광주대학교', 'url' => 'https://sanhak.gwangju.ac.kr/'],
            ['branch_key' => '광주전남제주', 'school_name' => '광주여자대학교', 'url' => 'https://sanhak.kwu.ac.kr/index.do'],
            ['branch_key' => '경기인천강원', 'school_name' => '국립강릉원주대학교', 'url' => 'https://iacg.gwnu.ac.kr/iacg/index.do'],
            ['branch_key' => '대구경북', 'school_name' => '국립경국대학교', 'url' => 'https://iacf.gknu.ac.kr/'],
            ['branch_key' => '대전충청세종', 'school_name' => '국립공주대학교', 'url' => 'https://sanhak.kongju.ac.kr/M00015/index.do'],
            ['branch_key' => '전북', 'school_name' => '국립군산대학교', 'url' => 'https://www.kunsan.ac.kr/sanhak/index.kunsan'],
            ['branch_key' => '대구경북', 'school_name' => '국립금오공과대학교', 'url' => 'https://iacf.kumoh.ac.kr/iacf/index.do?sso=ok'],
            ['branch_key' => '광주전남제주', 'school_name' => '국립목포대학교', 'url' => 'http://iacf.mokpo.ac.kr/'],
            ['branch_key' => '광주전남제주', 'school_name' => '국립목포해양대학교', 'url' => 'https://iacf.mmu.ac.kr/OC'],
            ['branch_key' => '부산울산경남', 'school_name' => '국립부경대학교', 'url' => 'https://sh.pknu.ac.kr/kor/Main.do'],
            ['branch_key' => '광주전남제주', 'school_name' => '국립순천대학교', 'url' => 'https://www.scnu.ac.kr/siacf/main.do'],
            ['branch_key' => '경기인천강원', 'school_name' => '국립암센터 국제암대학원대학교', 'url' => 'https://ncc-gcsp.ac.kr/kr_index.jsp'],
            ['branch_key' => '부산울산경남', 'school_name' => '국립창원대학교', 'url' => 'https://sanhak.changwon.ac.kr/'],
            ['branch_key' => '대전충청세종', 'school_name' => '국립한국교통대학교', 'url' => 'https://sanhak.ut.ac.kr/kr/'],
            ['branch_key' => '부산울산경남', 'school_name' => '국립한국해양대학교', 'url' => 'https://www.kmou.ac.kr/sanhak/main.do'],
            ['branch_key' => '대전충청세종', 'school_name' => '국립한밭대학교', 'url' => 'https://iucf.hanbat.ac.kr'],
            ['branch_key' => '서울', 'school_name' => '국민대학교', 'url' => 'https://research.kookmin.ac.kr/'],
            ['branch_key' => '대구경북', 'school_name' => '김천대학교', 'url' => 'https://iacf.gimcheon.ac.kr/'],
            ['branch_key' => '광주전남제주', 'school_name' => '남부대학교', 'url' => 'http://iacf.nambu.ac.kr/iacf/main/main.asp'],
            ['branch_key' => '대전충청세종', 'school_name' => '남서울대학교', 'url' => 'https://iacf.nsu.ac.kr/'],
            ['branch_key' => '경기인천강원', 'school_name' => '단국대학교', 'url' => 'https://iacf.dankook.ac.kr/web/iacf'],
            ['branch_key' => '대전충청세종', 'school_name' => '단국대학교 천안캠퍼스', 'url' => 'https://iacf.dankook.ac.kr/web/iacf'],
            ['branch_key' => '대구경북', 'school_name' => '대구가톨릭대학교', 'url' => 'https://iacf.cu.ac.kr/'],
            ['branch_key' => '대구경북', 'school_name' => '대구경북과학기술원', 'url' => 'https://www.dgist.ac.kr/ouic/'],
            ['branch_key' => '대구경북', 'school_name' => '대구대학교', 'url' => 'https://iacf.daegu.ac.kr/front/'],
            ['branch_key' => '대구경북', 'school_name' => '대구한의대학교', 'url' => 'http://iacf.dhu.ac.kr/'],
            ['branch_key' => '부산울산경남', 'school_name' => '대동대학교', 'url' => 'https://sh.daedong.ac.kr/sh/Main.do'],
            ['branch_key' => '대전충청세종', 'school_name' => '대전대학교', 'url' => 'https://sanhak.dju.ac.kr/'],
            ['branch_key' => '경기인천강원', 'school_name' => '대진대학교', 'url' => 'https://tech.daejin.ac.kr/tech/index.do'],
            ['branch_key' => '서울', 'school_name' => '덕성여자대학교', 'url' => 'https://sanhak.duksung.ac.kr/'],
            ['branch_key' => '서울', 'school_name' => '동국대학교', 'url' => 'https://rnd.dongguk.edu/ko/page/main.do'],
            ['branch_key' => '대구경북', 'school_name' => '동국대학교 WISE캠퍼스', 'url' => 'https://dce.dongguk.ac.kr/iacf/'],
            ['branch_key' => '서울', 'school_name' => '동덕여자대학교', 'url' => 'https://iacf.dongduk.ac.kr/'],
            ['branch_key' => '부산울산경남', 'school_name' => '동명대학교', 'url' => 'https://www.tu.ac.kr/sanhak/index.do'],
            ['branch_key' => '부산울산경남', 'school_name' => '동서대학교', 'url' => 'https://uni.dongseo.ac.kr/sanhak/'],
            ['branch_key' => '광주전남제주', 'school_name' => '동신대학교', 'url' => 'https://iacf.dsu.ac.kr/iacf/'],
            ['branch_key' => '부산울산경남', 'school_name' => '동아대학교', 'url' => 'https://research.donga.ac.kr/sites/research/index.do'],
            ['branch_key' => '대구경북', 'school_name' => '동양대학교', 'url' => 'https://sanhak.dyu.ac.kr/'],
            ['branch_key' => '부산울산경남', 'school_name' => '동의대학교', 'url' => 'https://www.deu.ac.kr/www/content/92'],
            ['branch_key' => '경기인천강원', 'school_name' => '명지대학교', 'url' => 'https://mjuresearch.mju.ac.kr'],
            ['branch_key' => '대전충청세종', 'school_name' => '목원대학교', 'url' => 'http://sanhak.mokwon.ac.kr/'],
            ['branch_key' => '대전충청세종', 'school_name' => '배재대학교', 'url' => 'https://sanhakinfo.pcu.ac.kr/'],
            ['branch_key' => '대전충청세종', 'school_name' => '백석대학교', 'url' => 'https://sanhak.bu.ac.kr/sanhak/index.do'],
            ['branch_key' => '부산울산경남', 'school_name' => '부산대학교', 'url' => 'https://sanhak.pusan.ac.kr/'],
            ['branch_key' => '부산울산경남', 'school_name' => '부산외국어대학교', 'url' => 'https://sanhak.bufs.ac.kr/'],
            ['branch_key' => '경기인천강원', 'school_name' => '부천대학교', 'url' => 'https://dept.bc.ac.kr/home/index.do'],
            ['branch_key' => '서울', 'school_name' => '삼육대학교', 'url' => 'https://www.syu.ac.kr/rnd/'],
            ['branch_key' => '서울', 'school_name' => '상명대학교', 'url' => 'https://iacf.smu.ac.kr/web/index'],
            ['branch_key' => '경기인천강원', 'school_name' => '상지대학교', 'url' => 'https://www.sangji.ac.kr/sanhak/index.do'],
            ['branch_key' => '서울', 'school_name' => '서강대학교', 'url' => 'http://resdept.sogang.ac.kr/kr/index.jsp'],
            ['branch_key' => '서울', 'school_name' => '서울과학기술대학교', 'url' => 'https://iac.seoultech.ac.kr/'],
            ['branch_key' => '서울', 'school_name' => '서울대학교', 'url' => 'https://snurnd.snu.ac.kr/'],
            ['branch_key' => '서울', 'school_name' => '서울시립대학교', 'url' => 'http://research.uos.ac.kr/'],
            ['branch_key' => '대전충청세종', 'school_name' => '서원대학교', 'url' => 'https://www.seowon.ac.kr/iac/index.do'],
            ['branch_key' => '대전충청세종', 'school_name' => '선문대학교', 'url' => 'https://iucf.sunmoon.ac.kr/'],
            ['branch_key' => '경기인천강원', 'school_name' => '성결대학교', 'url' => 'https://www.sungkyul.ac.kr/sandan/index.do'],
            ['branch_key' => '경기인천강원', 'school_name' => '성균관대학교 자연과학캠퍼스', 'url' => 'https://ranbiz.skku.edu/'],
            ['branch_key' => '서울', 'school_name' => '성신여자대학교', 'url' => 'https://www.sungshin.ac.kr/acm/index.do'],
            ['branch_key' => '대전충청세종', 'school_name' => '세명대학교', 'url' => 'http://www.semyung.ac.kr/san.do'],
            ['branch_key' => '서울', 'school_name' => '세종대학교', 'url' => 'https://rnd.sejong.ac.kr/index.do'],
            ['branch_key' => '광주전남제주', 'school_name' => '송원대학교', 'url' => 'https://www.songwon.ac.kr/'],
            ['branch_key' => '경기인천강원', 'school_name' => '수원대학교', 'url' => 'https://iuc.suwon.ac.kr/'],
            ['branch_key' => '서울', 'school_name' => '숙명여자대학교', 'url' => 'http://research.sookmyung.ac.kr'],
            ['branch_key' => '대전충청세종', 'school_name' => '순천향대학교', 'url' => 'https://sanhak.sch.ac.kr/site/index.php'],
            ['branch_key' => '서울', 'school_name' => '숭실대학교', 'url' => 'https://research.ssu.ac.kr/'],
            ['branch_key' => '부산울산경남', 'school_name' => '신라대학교', 'url' => 'https://sanhak.silla.ac.kr/sanhak/'],
            ['branch_key' => '경기인천강원', 'school_name' => '아주대학교', 'url' => 'https://iacf.ajou.ac.kr/iacf/index.jsp'],
            ['branch_key' => '경기인천강원', 'school_name' => '안양대학교', 'url' => 'https://enter.anyang.ac.kr/iacf/index.do'],
            ['branch_key' => '서울', 'school_name' => '연세대학교', 'url' => 'https://research.yonsei.ac.kr/research/index.do'],
            ['branch_key' => '경기인천강원', 'school_name' => '연세대학교 미래캠퍼스', 'url' => 'http://wresearch.yonsei.ac.kr/index.php'],
            ['branch_key' => '대구경북', 'school_name' => '영남대학교', 'url' => 'https://www.yu.ac.kr/rnd/index.do'],
            ['branch_key' => '부산울산경남', 'school_name' => '영산대학교', 'url' => 'https://iacf.ysu.ac.kr/'],
            ['branch_key' => '경기인천강원', 'school_name' => '용인대학교', 'url' => 'http://sanhak.yongin.ac.kr'],
            ['branch_key' => '전북', 'school_name' => '우석대학교', 'url' => 'https://sanhak.woosuk.ac.kr/'],
            ['branch_key' => '대전충청세종', 'school_name' => '우송대학교', 'url' => 'https://sanhak.wsu.ac.kr/'],
            ['branch_key' => '부산울산경남', 'school_name' => '울산대학교', 'url' => 'https://fic.ulsan.ac.kr/open_content/main_page'],
            ['branch_key' => '전북', 'school_name' => '원광대학교', 'url' => 'https://sanhak.wku.ac.kr/'],
            ['branch_key' => '대구경북', 'school_name' => '위덕대학교', 'url' => 'https://sandan.uu.ac.kr/'],
            ['branch_key' => '서울', 'school_name' => '이화여자대학교', 'url' => 'https://research.ewha.ac.kr/research/index.do'],
            ['branch_key' => '부산울산경남', 'school_name' => '인제대학교', 'url' => 'http://sanhak.inje.ac.kr/'],
            ['branch_key' => '경기인천강원', 'school_name' => '인천대학교', 'url' => 'https://www.inu.ac.kr/user/indexMain.do?siteId=rnd'],
            ['branch_key' => '경기인천강원', 'school_name' => '인하공업전문대학교', 'url' => 'https://iacf.inhatc.ac.kr/iacf/index.do'],
            ['branch_key' => '경기인천강원', 'school_name' => '인하대학교', 'url' => 'https://rnd.inha.ac.kr/index.htm'],
            ['branch_key' => '광주전남제주', 'school_name' => '전남대학교', 'url' => 'https://sanhak.jnu.ac.kr/sanhak/index.do'],
            ['branch_key' => '전북', 'school_name' => '전북대학교', 'url' => 'http://if.jbnu.ac.kr/'],
            ['branch_key' => '전북', 'school_name' => '전주대학교', 'url' => 'https://www.jj.ac.kr/sanhak/'],
            ['branch_key' => '광주전남제주', 'school_name' => '제주대학교', 'url' => 'https://iucf.jejunu.ac.kr/newhome/'],
            ['branch_key' => '광주전남제주', 'school_name' => '조선대학교', 'url' => 'https://iacf.chosun.ac.kr/iacf/index.do'],
            ['branch_key' => '경기인천강원', 'school_name' => '중부대학교', 'url' => 'https://www.iacf.co.kr:5444/index.php'],
            ['branch_key' => '서울', 'school_name' => '중앙대학교', 'url' => 'https://iacf.cau.ac.kr/page/main.php'],
            ['branch_key' => '대전충청세종', 'school_name' => '중원대학교', 'url' => 'http://sanhak.jwu.ac.kr/site/deptSiteView.jwu'],
            ['branch_key' => '경기인천강원', 'school_name' => '차의과학대학교', 'url' => 'https://research2.cha.ac.kr/'],
            ['branch_key' => '경기인천강원', 'school_name' => '청운대학교', 'url' => 'https://iacf.chungwoon.ac.kr/iacf/index.do'],
            ['branch_key' => '대전충청세종', 'school_name' => '청주대학교', 'url' => 'https://www.cju.ac.kr/iacf/index.do'],
            ['branch_key' => '광주전남제주', 'school_name' => '초당대학교', 'url' => 'https://sanhak.cdu.ac.kr/index.do'],
            ['branch_key' => '대전충청세종', 'school_name' => '충남대학교', 'url' => 'https://iuc.cnu.ac.kr/iuc/index.do'],
            ['branch_key' => '대전충청세종', 'school_name' => '충북대학교', 'url' => 'https://sanhak.cbnu.ac.kr/'],
            ['branch_key' => '경기인천강원', 'school_name' => '평택대학교', 'url' => 'https://ica.ptu.ac.kr/ica/index.do'],
            ['branch_key' => '대구경북', 'school_name' => '포항공과대학교', 'url' => 'https://aif.postech.ac.kr/'],
            ['branch_key' => '경기인천강원', 'school_name' => '한경국립대학교', 'url' => 'https://sanhak.hknu.ac.kr/sanhak/index.do'],
            ['branch_key' => '경기인천강원', 'school_name' => '한국공학대학교', 'url' => 'https://sanhak.kpu.ac.kr'],
            ['branch_key' => '대전충청세종', 'school_name' => '한국과학기술원', 'url' => 'https://itvc.kaist.ac.kr/'],
            ['branch_key' => '대전충청세종', 'school_name' => '한국교원대학교', 'url' => 'https://crms.knue.ac.kr/'],
            ['branch_key' => '대전충청세종', 'school_name' => '한국기술교육대학교', 'url' => 'https://sandan.koreatech.ac.kr/kor/Main.do'],
            ['branch_key' => '광주전남제주', 'school_name' => '한국에너지공과대학교', 'url' => 'https://www.kentech.ac.kr/main.do'],
            ['branch_key' => '서울', 'school_name' => '한국외국어대학교', 'url' => 'http://iucf.hufs.ac.kr/'],
            ['branch_key' => '부산울산경남', 'school_name' => '한국전력국제원자력대학원대학교', 'url' => 'https://www.kings.ac.kr/home.do'],
            ['branch_key' => '대전충청세종', 'school_name' => '한국전통문화대학교', 'url' => 'https://www.nuch.ac.kr/nucheia/main.do'],
            ['branch_key' => '경기인천강원', 'school_name' => '한국항공대학교', 'url' => 'http://research.kau.ac.kr/index/main.php'],
            ['branch_key' => '대전충청세종', 'school_name' => '한남대학교', 'url' => 'http://sh.hnu.kr/kor/main/'],
            ['branch_key' => '대구경북', 'school_name' => '한동대학교', 'url' => 'https://iacf.handong.edu:444/main/'],
            ['branch_key' => '경기인천강원', 'school_name' => '한림대학교', 'url' => 'https://sanhak.hallym.ac.kr:60443/'],
            ['branch_key' => '대전충청세종', 'school_name' => '한서대학교', 'url' => 'https://sanhak.hanseo.ac.kr/'],
            ['branch_key' => '서울', 'school_name' => '한성대학교', 'url' => 'https://www.hansung.ac.kr/sites/rnd/index.do'],
            ['branch_key' => '서울', 'school_name' => '한양대학교', 'url' => 'https://research.hanyang.ac.kr/'],
            ['branch_key' => '경기인천강원', 'school_name' => '한양대학교 에리카캠퍼스', 'url' => 'https://ericaresearch.hanyang.ac.kr/about/vision.php'],
            ['branch_key' => '경기인천강원', 'school_name' => '협성대학교', 'url' => 'https://iacf.uhs.ac.kr/'],
            ['branch_key' => '광주전남제주', 'school_name' => '호남대학교', 'url' => 'https://iacf.honam.ac.kr/'],
            ['branch_key' => '대전충청세종', 'school_name' => '호서대학교', 'url' => 'https://iacf.hoseo.ac.kr/index.php?mode=homepage'],
            ['branch_key' => '전북', 'school_name' => '호원대학교', 'url' => 'https://sanhak.howon.info/'],
            ['branch_key' => '서울', 'school_name' => '홍익대학교', 'url' => 'https://research.hongik.ac.kr/index.do'],
            ['branch_key' => '대전충청세종', 'school_name' => '홍익대학교 세종캠퍼스', 'url' => 'https://cooper.hongik.ac.kr/cooper/index.do'],
        ];
    }
}
