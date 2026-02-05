<td>
    @if($application->is_completed && $application->is_survey_completed)
        <a href="{{ route('backoffice.print.certificate_completion', $application) }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-success">수료증</a>
        <a href="{{ route('backoffice.print.certificate_finish', $application) }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-success">이수증</a>
    @else
        <span class="text-muted">-</span>
    @endif
</td>
<td>
    @if($application->payment_status === '입금완료')
        <a href="{{ route('backoffice.print.receipt', $application) }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-info">영수증</a>
    @else
        <span class="text-muted">-</span>
    @endif
</td>
