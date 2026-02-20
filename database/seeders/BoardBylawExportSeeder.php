<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * 서버 이관용 시더 (db:export-seeders 로 생성됨)
 * 테이블: board_bylaws
 */
class BoardBylawExportSeeder extends Seeder
{
    public function run(): void
    {
        $table = 'board_bylaws';
        $chunks = [
        array (
  0 => 
  array (
    'id' => 1,
    'user_id' => NULL,
    'title' => '정관',
    'content' => '<div style="margin:80px 0 16px; font-size:28px; font-weight:700; line-height:1.42; color:rgb(30,33,36);">제1장 총 칙</div>
<div style="padding:24px 32px; background:rgb(247,247,247); border-radius:20px; font-size:17px; line-height:1.65; color:rgb(30,33,36);">

<p style="margin:0 0 12px 0; line-height:1.7;"><strong>제1조(목적)</strong> 본 협회는 「민법」 및 「교육부 소관 비영리법인의 설립 및 감독에 관한 규칙」에 따라 설립한 법인으로서 연구‧산학협력 기능 활성화를 위하여 회원의 업무 역량 향상을 위한 학술 활동 및 교육 지원과 회원간의 상호 협력을 도모함을 목적으로 한다.</p>

<p style="margin:0 0 12px 0; line-height:1.7;"><strong>제2조(명칭)</strong> 본 협회는 "전국대학연구‧산학협력관리자협회"라 하며 (이하 "협회"라 한다) 영문으로는 Korea University Council Research and Industry Academic Cooperation Administrator"(KUCRA) 이라 한다.</p>

<p style="margin:0 0 12px 0; line-height:1.7;"><strong>제3조(사무소의 소재지)</strong> 본 협회의 주된 사무소는 서울특별시에 둔다.</p>

<p style="margin:0 0 4px 0; line-height:1.7;"><strong>제4조(사업)</strong> ① 협회는 제1조의 목적을 달성하기 위하여 다음 사업을 수행한다.</p>
<p style="margin:0 0 4px 0; line-height:1.7; padding-left:20px;">1. 연구‧산학협력 관리자 정보교류 및 네트워크 강화</p>
<p style="margin:0 0 4px 0; line-height:1.7; padding-left:20px;">2. 연구‧산학협력 관리자를 위한 교육 및 전문가 양성</p>
<p style="margin:0 0 4px 0; line-height:1.7; padding-left:20px;">3. 연구‧산학협력 활성화를 위한 기획, 조사, 연구 및 정책의 개발</p>
<p style="margin:0 0 4px 0; line-height:1.7;">② 제1항의 목적사업의 경비를 충당하기 위하여 다음의 수익사업을 행한다.</p>
<p style="margin:0 0 12px 0; line-height:1.7; padding-left:20px;">1. 대학연구행정전문가 민간자격증 (1급, 2급) 발급‧운영 및 관리</p>

<p style="margin:0 0 4px 0; line-height:1.7;"><strong>제5조(위원회와 지회)</strong> ① 본 협회는 제4조의 목적사업을 원활하게 수행하고 협회의 운영을 효율적으로 하기 위하여 필요한 위원회 및 지회를 둘 수 있다.</p>
<p style="margin:0; line-height:1.7;">② 위원회 및 지회의 직무와 운영에 관한 사항은 이사회에서 별도로 정한다.</p>

</div>


<div style="margin:80px 0 16px; font-size:28px; font-weight:700; line-height:1.42; color:rgb(30,33,36);">제2장 회 원</div>
<div style="padding:24px 32px; background:rgb(247,247,247); border-radius:20px; font-size:17px; line-height:1.65; color:rgb(30,33,36);">

<p style="margin:0 0 12px 0; line-height:1.7;"><strong>제6조(회원의 구성)</strong> 이 협회의 회원은 본 협회의 목적에 찬동하고 소정의 연회비를 납부한 산학협력단(대학 포함)을 회원으로 한다.</p>

<p style="margin:0 0 4px 0; line-height:1.7;"><strong>제7조(회원의 권리와 의무)</strong> 회원은 협회의 사업에 평등하게 참여할 권리가 있으며, 총회를 통하여 협회의 운영에 참여하며, 발의권, 의결권, 선거권, 피선거권을 갖는다.</p>
<p style="margin:0 0 12px 0; line-height:1.7;">② 회원은 협회의 정관 및 결의사항을 준수할 의무를 지니며, 총회에서 정한 연회비를 납부하여야 한다.</p>

<p style="margin:0 0 12px 0; line-height:1.7;"><strong>제8조(회원의 탈퇴)</strong> 이 협회의 회원은 임의로 탈퇴할 수 있다.</p>

<p style="margin:0; line-height:1.7;"><strong>제9조(회원의 제명)</strong> 이 협회의 회원으로서 이 협회의 목적에 배치되는 행위 또는 명예․위신에 손상을 가져오는 행위를 하였을 때에는 이사회에서 출석 이사 3분의 2이상의 의결로 제명할 수 있다.</p>

</div>


<div style="margin:80px 0 16px; font-size:28px; font-weight:700; line-height:1.42; color:rgb(30,33,36);">제3장 임 원</div>
<div style="padding:24px 32px; background:rgb(247,247,247); border-radius:20px; font-size:17px; line-height:1.65; color:rgb(30,33,36);">

<p style="margin:0 0 4px 0; line-height:1.7;"><strong>제10조(임원의 종류와 정수)</strong> ① 이 협회에 다음의 임원을 둔다.</p>
<p style="margin:0 0 4px 0; line-height:1.7; padding-left:20px;">1. 이사 3인</p>
<p style="margin:0 0 4px 0; line-height:1.7; padding-left:20px;">2. 감사 1인</p>
<p style="margin:0 0 12px 0; line-height:1.7;">② 제1항제1호의 이사에는 회장을 포함한다.</p>

<p style="margin:0 0 4px 0; line-height:1.7;"><strong>제11조(임원의 임기)</strong> ① 임원의 임기는 3년으로 하되, 연임할 수 있다.</p>
<p style="margin:0 0 12px 0; line-height:1.7;">② 임원의 임기 중 결원이 생긴 때에는 제12조제1항과 제3항에 따라 보선하고, 보선에 의하여 취임한 임원의 임기는 전임자의 잔여기간으로 한다.</p>

<p style="margin:0 0 4px 0; line-height:1.7;"><strong>제12조(임원의 선임 방법)</strong> ① 임원은 이사회에서 추천하고, 총회에서 선출한다.</p>
<p style="margin:0 0 4px 0; line-height:1.7;">② 임기전의 임원의 해임은 총회의 의결을 거쳐야 한다.</p>
<p style="margin:0 0 12px 0; line-height:1.7;">③ 이사 또는 감사 중에 결원이 생긴 때에는 2월 이내에 이를 충원하여야 한다.</p>

<p style="margin:0 0 4px 0; line-height:1.7;"><strong>제13조(임원의 선임제한)</strong> ① 임원의 선임에 있어서 이사는 이사 상호간에 민법 제777조에 규정된 친족관계에 있는 자가 이사 정수의 3분의 1을 초과할 수 없다.</p>
<p style="margin:0 0 12px 0; line-height:1.7;">② 감사는 감사 상호간 또는 이사와 민법 제777조에 규정된 친족관계가 없어야 한다.</p>

<p style="margin:0 0 4px 0; line-height:1.7;"><strong>제14조(회장의 선출방법과 그 임기)</strong> ① 회장은 이사 중 총회에서 선출한다. 다만, 회장이 궐위되었을 때에는 지체없이 후임 회장을 선출하여야 한다.</p>
<p style="margin:0 0 12px 0; line-height:1.7;">② 회장의 임기는 이사로 재임하는 기간으로 한다.</p>

<p style="margin:0 0 4px 0; line-height:1.7;"><strong>제15조(회장 및 이사의 직무)</strong> ① 회장은 협회를 대표하고 협회의 업무를 총괄한다.</p>
<p style="margin:0 0 12px 0; line-height:1.7;">② 이사는 이사회에 출석하여 이 협회의 업무에 관한 사항을 심의․의결하며, 이사회 또는 회장으로부터 위임받은 사항을 처리한다.</p>

<p style="margin:0 0 12px 0; line-height:1.7;"><strong>제16조(회장의 직무대행)</strong> ① 회장이 사고가 있을 때에는 이사 중 최연장자인 이사가 회장의 직무를 대행한다.</p>

<p style="margin:0 0 4px 0; line-height:1.7;"><strong>제17조(감사의 직무)</strong> 감사는 다음의 직무를 행한다.</p>
<p style="margin:0 0 4px 0; line-height:1.7; padding-left:20px;">1. 법인의 재산상황을 감사하는 일</p>
<p style="margin:0 0 4px 0; line-height:1.7; padding-left:20px;">2. 이사의 업무집행의 상황을 감사하는 일</p>
<p style="margin:0 0 4px 0; line-height:1.7; padding-left:20px;">3. 재산현황 또는 업무집행에 관하여 부정, 불비한 것이 있음을 발견한 때에는 이를 총회, 이사회 및 주무관청에 보고하는 일</p>
<p style="margin:0; line-height:1.7; padding-left:20px;">4. 전호의 보고를 하기 위하여 필요있는 때에는 총회 또는 이사회 소집을 요구하는 일</p>

</div>


<div style="margin:80px 0 16px; font-size:28px; font-weight:700; line-height:1.42; color:rgb(30,33,36);">제4장 총 회</div>
<div style="padding:24px 32px; background:rgb(247,247,247); border-radius:20px; font-size:17px; line-height:1.65; color:rgb(30,33,36);">

<p style="margin:0 0 4px 0; line-height:1.7;"><strong>제18조(총회의 기능)</strong> 총회는 다음의 사항을 의결한다.</p>
<p style="margin:0 0 4px 0; line-height:1.7; padding-left:20px;">1. 임원의 선출에 관한 사항</p>
<p style="margin:0 0 4px 0; line-height:1.7; padding-left:20px;">2. 정관변경에 관한 사항</p>
<p style="margin:0 0 4px 0; line-height:1.7; padding-left:20px;">3. 협회의 해산에 관한 사항</p>
<p style="margin:0 0 4px 0; line-height:1.7; padding-left:20px;">4. 기본재산의 처분에 관한 사항</p>
<p style="margin:0 0 4px 0; line-height:1.7; padding-left:20px;">5. 예산 및 결산의 승인</p>
<p style="margin:0 0 4px 0; line-height:1.7; padding-left:20px;">6. 사업계획의 승인</p>
<p style="margin:0 0 12px 0; line-height:1.7; padding-left:20px;">7. 기타 중요한 사항</p>

<p style="margin:0 0 4px 0; line-height:1.7;"><strong>제19조(총회의 소집)</strong> ① 총회는 정기총회와 임시총회로 나누되 정기총회는 연1회, 임시총회는 회장이 수시 소집하고 그 의장이 된다.</p>
<p style="margin:0 0 4px 0; line-height:1.7;">② 회장은 회의안건을 명기하여 7일전에 각 회원에게 통지하여야 한다.</p>
<p style="margin:0 0 12px 0; line-height:1.7;">③ 총회는 제2항의 통지사항에 한하여만 의결할 수 있다.</p>

<p style="margin:0 0 4px 0; line-height:1.7;"><strong>제20조(총회의 의결정족수)</strong> ① 총회는 재적회원 과반수의 출석으로 개회한다.</p>
<p style="margin:0 0 4px 0; line-height:1.7;">② 총회의 의사는 출석한 회원 과반수의 찬성으로 의결한다. 다만, 가부동수인 경우에는 의장이 결정한다.</p>
<p style="margin:0 0 12px 0; line-height:1.7;">③ 각 회원은 1개의 결의권을 가지며, 이 결의권은 서면이나 대리인을 통해 행사할 수 있다.</p>

<p style="margin:0 0 4px 0; line-height:1.7;"><strong>제21조(총회소집의 특례)</strong> ① 회장은 다음 각 호의 1에 해당하는 소집요구가 있을 때에는 그 소집요구 일로부터 20일 이내에 총회를 소집하여야 한다.</p>
<p style="margin:0 0 4px 0; line-height:1.7; padding-left:20px;">1. 재적이사 과반수가 회의의 목적사항을 제시하여 소집을 요구한 때</p>
<p style="margin:0 0 4px 0; line-height:1.7; padding-left:20px;">2. 제17조 제4호의 규정에 의하여 감사가 소집을 요구한 때</p>
<p style="margin:0 0 4px 0; line-height:1.7; padding-left:20px;">3. 회원 5분의 1이상이 회의의 목적사항을 제시하여 소집을 요구한 때</p>
<p style="margin:0 0 4px 0; line-height:1.7;">② 총회 소집권자가 궐위되거나 또는 이를 기피함으로써 총회소집이 불가능할 때에는 재적이사 과반수 또는 회원 3분의 1이상의 찬성으로 총회를 소집할 수 있다.</p>
<p style="margin:0 0 12px 0; line-height:1.7;">③ 제2항에 의한 총회는 출석이사 중 연장자의 사회아래 그 의장을 지명한다.</p>

<p style="margin:0 0 4px 0; line-height:1.7;"><strong>제22조(총회의결 제척사유)</strong> 의장 또는 회원이 다음 각 호의 1에 해당하는 때에는 그 의결에 참여하지 못한다.</p>
<p style="margin:0 0 4px 0; line-height:1.7; padding-left:20px;">1. 임원취임 및 해임에 있어 자신에 관한 사항</p>
<p style="margin:0; line-height:1.7; padding-left:20px;">2. 금전 및 재산의 수수를 수반하는 사항 등 회원 자신과 법인과의 이해가 상반되는 사항</p>

</div>


<div style="margin:80px 0 16px; font-size:28px; font-weight:700; line-height:1.42; color:rgb(30,33,36);">제5장 이 사 회</div>
<div style="padding:24px 32px; background:rgb(247,247,247); border-radius:20px; font-size:17px; line-height:1.65; color:rgb(30,33,36);">

<p style="margin:0 0 4px 0; line-height:1.7;"><strong>제23조(이사회의 기능)</strong> 이사회는 다음의 사항을 심의․의결한다.</p>
<p style="margin:0 0 4px 0; line-height:1.7; padding-left:20px;">1. 업무집행에 관한 사항</p>
<p style="margin:0 0 4px 0; line-height:1.7; padding-left:20px;">2. 사업계획 운영에 관한 사항</p>
<p style="margin:0 0 4px 0; line-height:1.7; padding-left:20px;">3. 예산 및 결산서 작성에 관한 사항</p>
<p style="margin:0 0 4px 0; line-height:1.7; padding-left:20px;">4. 총회에서 위임받은 사항</p>
<p style="margin:0 0 4px 0; line-height:1.7; padding-left:20px;">5. 이 정관에 의하여 그 권한에 속하는 사항</p>
<p style="margin:0 0 12px 0; line-height:1.7; padding-left:20px;">6. 기타 의장이 중요하다고 인정하여 부의하는 안건의 처리</p>

<p style="margin:0 0 4px 0; line-height:1.7;"><strong>제24조(의결정족수 및 의결)</strong> ① 이사회는 이사정수의 과반수 출석으로 개의한다.</p>
<p style="margin:0 0 4px 0; line-height:1.7;">② 이사회의 의사는 출석이사 과반수의 찬성으로 의결한다. 다만, 가부동수인 경우에는 의장이 결정한다.</p>
<p style="margin:0 0 4px 0; line-height:1.7;">③ 회장은 업무의 성격 및 처리 시기 등을 고려하여 긴급히 업무를 처리할 필요가 있다고 인정되는 경우에는 이사회에 안건을 서면으로 의결을 요구할 수 있다.</p>
<p style="margin:0 0 12px 0; line-height:1.7;">④ 제3항에 의한 안건의 서면 의결은 재적이사 3분의 2이상의 찬성을 얻어야 한다.</p>

<p style="margin:0 0 4px 0; line-height:1.7;"><strong>제25조(의결제척 사유)</strong> 회장 또는 이사가 다음 각 호의 1에 해당하는 때에는 그 의결에 참여하지 못한다.</p>
<p style="margin:0 0 4px 0; line-height:1.7; padding-left:20px;">1. 임원의 취임 및 해임에 있어 자신에 관한 사항을 의결할 때</p>
<p style="margin:0 0 12px 0; line-height:1.7; padding-left:20px;">2. 금전 및 재산의 수수를 수반하는 사항 등 자신과 법인의 이해가 상반될 때</p>

<p style="margin:0 0 4px 0; line-height:1.7;"><strong>제26조(이사회의 소집)</strong> ① 이사회는 회장이 소집하고 그 의장이 된다.</p>
<p style="margin:0 0 4px 0; line-height:1.7;">② 이사회를 소집하고자 할 때에는 적어도 회의 7일전에 목적사항을 명시하여 각 이사에게 통지하여야 한다.</p>
<p style="margin:0 0 4px 0; line-height:1.7;">③ 이사회는 제2항의 통지사항에 한하여서만 의결할 수 있다. 다만, 재적이사 전원이 출석하고 출석이사 전원의 찬성이 있을 때에는 통지하지 아니한 사항이라도 이를 부의하고 의결할 수 있다.</p>
<p style="margin:0 0 12px 0; line-height:1.7;">④ 이사회 운영에 관한 세부사항은 별도로 정한다.</p>

<p style="margin:0 0 4px 0; line-height:1.7;"><strong>제27조(이사회 소집의 특례)</strong> ① 회장은 다음 각 호의 1에 해당하는 소집요구가 있을 때에는 그 소집요구일로부터 20일 이내에 이사회를 소집하여야 한다.</p>
<p style="margin:0 0 4px 0; line-height:1.7; padding-left:20px;">1. 재적이사 과반수로부터 회의의 목적사항을 제시하여 소집을 요구한 때</p>
<p style="margin:0 0 4px 0; line-height:1.7; padding-left:20px;">2. 제14조제3항제4호에 의하여 감사가 소집을 요구한 때</p>
<p style="margin:0 0 4px 0; line-height:1.7;">② 이사회 소집권자가 궐위되거나 또는 이를 기피함으로써 7일 이상 이사회 소집이 불가능할 때에는 재적이사 과반수의 찬성으로 소집할 수 있다.</p>
<p style="margin:0; line-height:1.7;">③ 제2항에 의한 이사회의 운영은 출석이사 중 연장자의 사회 아래 그 회의의 의장을 선출하여야 한다.</p>

</div>


<div style="margin:80px 0 16px; font-size:28px; font-weight:700; line-height:1.42; color:rgb(30,33,36);">제6장 재산 및 회계</div>
<div style="padding:24px 32px; background:rgb(247,247,247); border-radius:20px; font-size:17px; line-height:1.65; color:rgb(30,33,36);">

<p style="margin:0 0 4px 0; line-height:1.7;"><strong>제28조(재산의 구분)</strong> ① 이 협회의 재산은 기본재산과 운영재산으로 구분한다.</p>
<p style="margin:0 0 4px 0; line-height:1.7;">② 다음 각 호의 1에 해당하는 재산은 이를 기본재산으로 하고, 기본재산 이외의 일체의 재산은 운영재산으로 한다.</p>
<p style="margin:0 0 4px 0; line-height:1.7; padding-left:20px;">1. 설립 시 기본재산으로 출연한 재산</p>
<p style="margin:0 0 4px 0; line-height:1.7; padding-left:20px;">2. 운영재산 중 총회에서 기본재산으로 편입할 것을 의결한 재산</p>
<p style="margin:0 0 4px 0; line-height:1.7;">③ 이 협회의 기본재산은 다음과 같다.</p>
<p style="margin:0 0 4px 0; line-height:1.7; padding-left:20px;">1. 설립당시의 기본재산은 별지목록 1과 같다.</p>
<p style="margin:0 0 12px 0; line-height:1.7; padding-left:20px;">2. 현재의 기본재산은 별지목록 2와 같다.</p>

<p style="margin:0 0 4px 0; line-height:1.7;"><strong>제29조(재산의 관리)</strong> ① 제29조 제3항의 기본재산을 매도, 증여, 임대, 교환하거나, 담보에 제공하거나 의무부담 또는 권리의 포기를 하고자 할 때에는 이사회의 의결과 총회의 승인을 받아야 한다.</p>
<p style="margin:0 0 4px 0; line-height:1.7;">② 협회가 매수, 기부채납, 기타의 방법으로 재산을 취득할 때에는 지체 없이 이를 협회의 재산으로 편입조치 하여야 한다.</p>
<p style="margin:0 0 4px 0; line-height:1.7;">③ 기본재산 및 운영재산의 유지, 보존 및 기타 관리 (제1항 및 제2항의 경우를 제외한다)에 관하여는 이사장이 정하는 바에 의한다.</p>
<p style="margin:0 0 12px 0; line-height:1.7;">④ 기본재산의 목록이나 평가액에 변동이 있을 때에는 지체 없이 별지목록2(현재의 기본재산목록)를 변경하여 정관변경 절차를 밟아야 한다.</p>

<p style="margin:0 0 12px 0; line-height:1.7;"><strong>제30조(재산의 평가)</strong> 이 협회의 모든 재산의 평가는 취득당시의 시가에 의한다.</p>

<p style="margin:0 0 12px 0; line-height:1.7;"><strong>제31조(경비의 조달방법 등)</strong> 이 협회의 유지 및 운영에 필요한 경비는 기본재산의 과실, 사업수익, 회원의 회비 및 기타의 수입으로 조달한다.</p>

<p style="margin:0 0 4px 0; line-height:1.7;"><strong>제32조(회계의 구분)</strong> ① 이 협회의 회계는 목적사업회계와 수익사업회계로 구분한다.</p>
<p style="margin:0 0 4px 0; line-height:1.7;">② 제1항의 경우에 법인세법의 규정에 의한 법인세 과세대상이 되는 수익과 이에 대응하는 비용은 수익사업회계로 계리하고, 기타의 수익과 비용은 목적사업회계로 계리한다.</p>
<p style="margin:0 0 12px 0; line-height:1.7;">③ 제2항의 경우에 목적사업회계와 수익사업회계로 구분하기 곤란한 비용은 법인세에 관한 법령의 규정을 준용하여 배분한다.</p>

<p style="margin:0 0 12px 0; line-height:1.7;"><strong>제33조(회계원칙)</strong> 이 협회의 회계는 사업의 경영성과와 수지 상태를 정확하게 파악하기 위하여 모든 회계거래를 발생의 사실에 의하여 기업회계의 원칙에 따라 처리한다.</p>

<p style="margin:0 0 12px 0; line-height:1.7;"><strong>제34조(회계연도)</strong> 이 협회의 회계연도는 정부의 회계연도에 따른다.</p>

<p style="margin:0 0 12px 0; line-height:1.7;"><strong>제35조(예산외의 채무부담 등)</strong> 예산외의 채무의 부담 또는 채권의 포기는 이사회의 의결과 총회의 승인을 받아야 한다.</p>

<p style="margin:0 0 4px 0; line-height:1.7;"><strong>제36조(임원 등에 대한 재산대여 금지)</strong> ① 이 협회의 재산은 이 협회와 다음 각 호의 1에 해당하는 관계가 있는 자에 대하여는 정당한 대가없이 이를 대여하거나 사용하게 할 수 없다.</p>
<p style="margin:0 0 4px 0; line-height:1.7; padding-left:20px;">1. 이 협회의 설립자</p>
<p style="margin:0 0 4px 0; line-height:1.7; padding-left:20px;">2. 이 협회의 임원</p>
<p style="margin:0 0 4px 0; line-height:1.7; padding-left:20px;">3. 제1호 및 제2호에 해당하는 자와 민법 제777조의 규정에 의한 친족관계에 있는 자 또는 이에 해당하는 자가 임원으로 있는 다른 법인</p>
<p style="margin:0 0 4px 0; line-height:1.7; padding-left:20px;">4. 이 협회와 재산상 긴밀한 관계가 있는 자</p>
<p style="margin:0 0 12px 0; line-height:1.7;">② 제1항 각호의 규정에 해당되지 아니하는 자의 경우에도 협회의 목적에 비추어 정당한 사유가 없는 한 정당한 대가 없이 대여하거나 사용하게 할 수 없다.</p>

<p style="margin:0 0 4px 0; line-height:1.7;"><strong>제37조(예산서 및 결산서 제출)</strong> 이 협회는 매 회계연도 종료 후 2월이내에 다음 각 호의 서류를 이사회의 의결과 총회의 승인을 얻어 감독청에 제출한다.</p>
<p style="margin:0 0 4px 0; line-height:1.7; padding-left:20px;">1. 다음 사업연도의 사업계획 및 수지예산서</p>
<p style="margin:0 0 4px 0; line-height:1.7; padding-left:20px;">2. 당해 사업연도의 사업실적 및 수지결산서</p>
<p style="margin:0; line-height:1.7; padding-left:20px;">3. 당해 사업연도 말 현재의 재산목록</p>

</div>


<div style="margin:80px 0 16px; font-size:28px; font-weight:700; line-height:1.42; color:rgb(30,33,36);">제7장 보 칙</div>
<div style="padding:24px 32px; background:rgb(247,247,247); border-radius:20px; font-size:17px; line-height:1.65; color:rgb(30,33,36);">

<p style="margin:0 0 4px 0; line-height:1.7;"><strong>제38조(정관변경)</strong> 이 협회의 정관을 변경하고자 할 때에는 총회에서 출석회원 3분의 2이상의 찬성으로 의결하며, 다음 각 호의 서류를 첨부하여 감독청의 허가를 받아야 한다.</p>
<p style="margin:0 0 4px 0; line-height:1.7; padding-left:20px;">1. 변경사유서 1부</p>
<p style="margin:0 0 4px 0; line-height:1.7; padding-left:20px;">2. 정관개정안(신․구대조표를 포함한다) 1부</p>
<p style="margin:0 0 4px 0; line-height:1.7; padding-left:20px;">3. 정관의 변경에 관한 총회 또는 이사회회의록 등 관련서류 1부</p>
<p style="margin:0 0 12px 0; line-height:1.7; padding-left:20px;">4. 기본재산의 처분에 따른 정관변경의 경우에는 처분의 사유, 처분재산의 목록, 처분의 방법 등을 기재한 서류 1부.</p>

<p style="margin:0 0 12px 0; line-height:1.7;"><strong>제39조(해산)</strong> 이 협회를 해산하고자 할 때에는 총회에서 재적회원 4분의 3 이상의 찬성으로 의결하여, 청산인은 파산의 경우를 제외하고는 그 취임 후 3주간내에 해산등기를 하고 등기부등본을 첨부하여 감독청에 해산신고를 하여야 한다.</p>

<p style="margin:0 0 12px 0; line-height:1.7;"><strong>제40조(잔여재산의 귀속)</strong> 이 협회를 해산할 때의 잔여재산은 서울특별시교육청에 귀속된다.</p>

<p style="margin:0; line-height:1.7;"><strong>제41조(시행세칙)</strong> 회비징수에 관한 사항 등 이 정관의 시행에 관하여 필요한 사항은 이사회에서 정하여 총회의 승인을 얻어야 한다.</p>

</div>


<div style="margin:80px 0 16px; font-size:28px; font-weight:700; line-height:1.42; color:rgb(30,33,36);">부 칙</div>
<div style="padding:24px 32px; background:rgb(247,247,247); border-radius:20px; font-size:17px; line-height:1.65; color:rgb(30,33,36);">

<p style="margin:0 0 12px 0; line-height:1.7;"><strong>제1조(시행일)</strong> 이 정관은 감독청의 설립허가를 받은 날로부터 시행한다.</p>

<p style="margin:0 0 12px 0; line-height:1.7;"><strong>제2조(경과조치)</strong> 이 정관 시행당시 법인 설립을 위하여 발기인 등이 행한 행위는 이 정관에 의하여 행한 것으로 본다.</p>

<p style="margin:0 0 8px 0; line-height:1.7;"><strong>제3조(설립당초의 임원 및 임기)</strong> 이 협회 설립 당초의 임원 및 임기는 다음과 같다.</p>
<table style="border-collapse:collapse; width:100%; font-size:15px; margin-bottom:16px;">
  <thead>
    <tr>
      <th style="border:1px solid #ccc; padding:8px 12px; text-align:center; background:#e0e0e0; font-weight:bold;">직 위</th>
      <th style="border:1px solid #ccc; padding:8px 12px; text-align:center; background:#e0e0e0; font-weight:bold;">성 명</th>
      <th style="border:1px solid #ccc; padding:8px 12px; text-align:center; background:#e0e0e0; font-weight:bold;">주 소</th>
      <th style="border:1px solid #ccc; padding:8px 12px; text-align:center; background:#e0e0e0; font-weight:bold;">직 업</th>
      <th style="border:1px solid #ccc; padding:8px 12px; text-align:center; background:#e0e0e0; font-weight:bold;">임 기</th>
    </tr>
  </thead>
  <tbody>
    <tr><td style="border:1px solid #ccc; padding:8px 12px; text-align:center;">회장</td><td style="border:1px solid #ccc; padding:8px 12px; text-align:center;">박준철</td><td style="border:1px solid #ccc; padding:8px 12px; text-align:center;"></td><td style="border:1px solid #ccc; padding:8px 12px; text-align:center;">교직원</td><td style="border:1px solid #ccc; padding:8px 12px; text-align:center;">취임일로부터 3년</td></tr>
    <tr><td style="border:1px solid #ccc; padding:8px 12px; text-align:center;">이사</td><td style="border:1px solid #ccc; padding:8px 12px; text-align:center;">성혁건</td><td style="border:1px solid #ccc; padding:8px 12px; text-align:center;"></td><td style="border:1px solid #ccc; padding:8px 12px; text-align:center;">교직원</td><td style="border:1px solid #ccc; padding:8px 12px; text-align:center;">〃</td></tr>
    <tr><td style="border:1px solid #ccc; padding:8px 12px; text-align:center;">이사</td><td style="border:1px solid #ccc; padding:8px 12px; text-align:center;">김광제</td><td style="border:1px solid #ccc; padding:8px 12px; text-align:center;"></td><td style="border:1px solid #ccc; padding:8px 12px; text-align:center;">교직원</td><td style="border:1px solid #ccc; padding:8px 12px; text-align:center;">〃</td></tr>
    <tr><td style="border:1px solid #ccc; padding:8px 12px; text-align:center;">이사</td><td style="border:1px solid #ccc; padding:8px 12px; text-align:center;">윤진한</td><td style="border:1px solid #ccc; padding:8px 12px; text-align:center;"></td><td style="border:1px solid #ccc; padding:8px 12px; text-align:center;">교직원</td><td style="border:1px solid #ccc; padding:8px 12px; text-align:center;">〃</td></tr>
    <tr><td style="border:1px solid #ccc; padding:8px 12px; text-align:center;">이사</td><td style="border:1px solid #ccc; padding:8px 12px; text-align:center;">박충용</td><td style="border:1px solid #ccc; padding:8px 12px; text-align:center;"></td><td style="border:1px solid #ccc; padding:8px 12px; text-align:center;">교직원</td><td style="border:1px solid #ccc; padding:8px 12px; text-align:center;">〃</td></tr>
    <tr><td style="border:1px solid #ccc; padding:8px 12px; text-align:center;">이사</td><td style="border:1px solid #ccc; padding:8px 12px; text-align:center;">이재주</td><td style="border:1px solid #ccc; padding:8px 12px; text-align:center;"></td><td style="border:1px solid #ccc; padding:8px 12px; text-align:center;">교직원</td><td style="border:1px solid #ccc; padding:8px 12px; text-align:center;">〃</td></tr>
    <tr><td style="border:1px solid #ccc; padding:8px 12px; text-align:center;">이사</td><td style="border:1px solid #ccc; padding:8px 12px; text-align:center;">이해광</td><td style="border:1px solid #ccc; padding:8px 12px; text-align:center;"></td><td style="border:1px solid #ccc; padding:8px 12px; text-align:center;">교직원</td><td style="border:1px solid #ccc; padding:8px 12px; text-align:center;">〃</td></tr>
    <tr><td style="border:1px solid #ccc; padding:8px 12px; text-align:center;">이사</td><td style="border:1px solid #ccc; padding:8px 12px; text-align:center;">김민영</td><td style="border:1px solid #ccc; padding:8px 12px; text-align:center;"></td><td style="border:1px solid #ccc; padding:8px 12px; text-align:center;">교직원</td><td style="border:1px solid #ccc; padding:8px 12px; text-align:center;">〃</td></tr>
    <tr><td style="border:1px solid #ccc; padding:8px 12px; text-align:center;">이사</td><td style="border:1px solid #ccc; padding:8px 12px; text-align:center;">이상호</td><td style="border:1px solid #ccc; padding:8px 12px; text-align:center;"></td><td style="border:1px solid #ccc; padding:8px 12px; text-align:center;">교직원</td><td style="border:1px solid #ccc; padding:8px 12px; text-align:center;">〃</td></tr>
    <tr><td style="border:1px solid #ccc; padding:8px 12px; text-align:center;">이사</td><td style="border:1px solid #ccc; padding:8px 12px; text-align:center;">이상곤</td><td style="border:1px solid #ccc; padding:8px 12px; text-align:center;"></td><td style="border:1px solid #ccc; padding:8px 12px; text-align:center;">교직원</td><td style="border:1px solid #ccc; padding:8px 12px; text-align:center;">〃</td></tr>
    <tr><td style="border:1px solid #ccc; padding:8px 12px; text-align:center;">감사</td><td style="border:1px solid #ccc; padding:8px 12px; text-align:center;">이흥우</td><td style="border:1px solid #ccc; padding:8px 12px; text-align:center;"></td><td style="border:1px solid #ccc; padding:8px 12px; text-align:center;">교직원</td><td style="border:1px solid #ccc; padding:8px 12px; text-align:center;">〃</td></tr>
  </tbody>
</table>

<p style="margin:0 0 8px 0; line-height:1.7;"><strong>제4조(발기인의 기명날인)</strong> 법인을 설립하기 위하여 이 정관을 작성하고 다음과 같이 설립자 전원이 기명날인 한다.</p>
<table style="border-collapse:collapse; width:100%; font-size:15px;">
  <thead>
    <tr>
      <th style="border:1px solid #ccc; padding:8px 12px; text-align:center; background:#e0e0e0; font-weight:bold;">직 위</th>
      <th style="border:1px solid #ccc; padding:8px 12px; text-align:center; background:#e0e0e0; font-weight:bold;">성 명</th>
      <th style="border:1px solid #ccc; padding:8px 12px; text-align:center; background:#e0e0e0; font-weight:bold;">날 인</th>
      <th style="border:1px solid #ccc; padding:8px 12px; text-align:center; background:#e0e0e0; font-weight:bold;">비 고</th>
    </tr>
  </thead>
  <tbody>
    <tr><td style="border:1px solid #ccc; padding:8px 12px; text-align:center;">발 기 인</td><td style="border:1px solid #ccc; padding:8px 12px; text-align:center;">박준철</td><td style="border:1px solid #ccc; padding:8px 12px; text-align:center;"></td><td style="border:1px solid #ccc; padding:8px 12px; text-align:center;"></td></tr>
    <tr><td style="border:1px solid #ccc; padding:8px 12px; text-align:center;">발 기 인</td><td style="border:1px solid #ccc; padding:8px 12px; text-align:center;">윤대성</td><td style="border:1px solid #ccc; padding:8px 12px; text-align:center;"></td><td style="border:1px solid #ccc; padding:8px 12px; text-align:center;"></td></tr>
    <tr><td style="border:1px solid #ccc; padding:8px 12px; text-align:center;">발 기 인</td><td style="border:1px solid #ccc; padding:8px 12px; text-align:center;">이흥우</td><td style="border:1px solid #ccc; padding:8px 12px; text-align:center;"></td><td style="border:1px solid #ccc; padding:8px 12px; text-align:center;"></td></tr>
    <tr><td style="border:1px solid #ccc; padding:8px 12px; text-align:center;">발 기 인</td><td style="border:1px solid #ccc; padding:8px 12px; text-align:center;">김광제</td><td style="border:1px solid #ccc; padding:8px 12px; text-align:center;"></td><td style="border:1px solid #ccc; padding:8px 12px; text-align:center;"></td></tr>
    <tr><td style="border:1px solid #ccc; padding:8px 12px; text-align:center;">발 기 인</td><td style="border:1px solid #ccc; padding:8px 12px; text-align:center;">윤진한</td><td style="border:1px solid #ccc; padding:8px 12px; text-align:center;"></td><td style="border:1px solid #ccc; padding:8px 12px; text-align:center;"></td></tr>
  </tbody>
</table>

</div>


<div style="margin:80px 0 16px; font-size:28px; font-weight:700; line-height:1.42; color:rgb(30,33,36);">부 칙 (2025. 12. 12.)</div>
<div style="padding:24px 32px; background:rgb(247,247,247); border-radius:20px; font-size:17px; line-height:1.65; color:rgb(30,33,36);">
<p style="margin:0; line-height:1.7;"><strong>제1조(시행일)</strong> 이 정관은 감독청의 개정 승인 허가를 받은 날로부터 시행한다.</p>
</div>


<div style="margin:80px 0 16px; font-size:28px; font-weight:700; line-height:1.42; color:rgb(30,33,36);">부 칙 (2026. 2. 19.)</div>
<div style="padding:24px 32px; background:rgb(247,247,247); border-radius:20px; font-size:17px; line-height:1.65; color:rgb(30,33,36);">
<p style="margin:0; line-height:1.7;"><strong>제1조(시행일)</strong> 이 정관은 감독청의 개정 승인 허가를 받은 날로부터 시행한다.</p>
</div>',
    'author_name' => '관리자',
    'password' => NULL,
    'is_notice' => 0,
    'is_secret' => 0,
    'category' => NULL,
    'attachments' => '[]',
    'view_count' => 0,
    'sort_order' => 0,
    'custom_fields' => NULL,
    'thumbnail' => NULL,
    'is_active' => 1,
    'created_at' => '2026-01-22 02:05:36',
    'updated_at' => '2026-02-20 09:22:40',
    'deleted_at' => NULL,
  ),
)
    ];

        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table($table)->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        foreach ($chunks as $chunk) {
            if (count($chunk) > 0) {
                DB::table($table)->insert($chunk);
            }
        }
    }
}