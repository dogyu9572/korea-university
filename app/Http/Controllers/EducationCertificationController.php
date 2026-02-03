<?php

namespace App\Http\Controllers;

use App\Services\EducationCertificationApplicationService;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EducationCertificationController extends Controller
{
    public function __construct(
        private EducationCertificationApplicationService $applicationService
    ) {
    }

    private function menuMeta(): array
    {
        return [
            'gNum' => '01',
            'sNum' => '03',
            'gName' => '교육 · 자격증',
            'sName' => '교육ㆍ자격증 신청',
        ];
    }

    public function application_ec(Request $request)
    {
        $programs = $this->applicationService->getList($request);
        $tabCounts = $this->applicationService->getTabCounts();
        $periodYears = $this->applicationService->getPeriodYears();

        return view('education_certification.application_ec', array_merge(
            $this->menuMeta(),
            compact('programs', 'tabCounts', 'periodYears')
        ));
    }

    public function application_ec_view(int $id)
    {
        $education = $this->applicationService->getEducationDetail($id);
        if (!$education) {
            throw new NotFoundHttpException();
        }
        $viewData = $this->applicationService->prepareEducationDetailView($education);

        return view('education_certification.application_ec_view', array_merge(
            $this->menuMeta(),
            compact('education', 'viewData')
        ));
    }

    public function application_ec_apply()
    {
        return view('education_certification.application_ec_apply', $this->menuMeta());
    }

    public function application_ec_apply_end()
    {
        return view('education_certification.application_ec_apply_end', $this->menuMeta());
    }

    public function application_ec_view_type2(int $id)
    {
        $certification = $this->applicationService->getCertificationDetail($id);
        if (!$certification) {
            throw new NotFoundHttpException();
        }
        $viewData = $this->applicationService->prepareCertificationDetailView($certification);

        return view('education_certification.application_ec_view_type2', array_merge(
            $this->menuMeta(),
            compact('certification', 'viewData')
        ));
    }

    public function application_ec_view_online(int $id)
    {
        $onlineEducation = $this->applicationService->getOnlineEducationDetail($id);
        if (!$onlineEducation) {
            throw new NotFoundHttpException();
        }
        $viewData = $this->applicationService->prepareOnlineEducationDetailView($onlineEducation);

        return view('education_certification.application_ec_view_online', array_merge(
            $this->menuMeta(),
            compact('onlineEducation', 'viewData')
        ));
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
