/**
 * 온라인교육 상세 페이지 체류시간 측정 후 이탈 시 서버 전송 (진도율 누적)
 * data-application-id, data-dwell-url 가진 요소가 있을 때만 동작
 */
(function () {
    var container = document.querySelector('[data-application-id][data-dwell-url]');
    if (!container) return;

    var applicationId = container.getAttribute('data-application-id');
    var dwellUrl = container.getAttribute('data-dwell-url');
    if (!dwellUrl) return;

    var enterTime = Date.now();
    var sent = false;

    function sendDwellTime() {
        if (sent) return;
        sent = true;

        var seconds = Math.floor((Date.now() - enterTime) / 1000);
        if (seconds <= 0) return;

        var csrfToken = document.querySelector('meta[name="csrf-token"]');
        var token = csrfToken ? csrfToken.getAttribute('content') : '';

        var formData = new FormData();
        formData.append('_token', token);
        formData.append('seconds', String(seconds));

        if (navigator.sendBeacon) {
            navigator.sendBeacon(dwellUrl, formData);
        } else {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', dwellUrl, false);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.setRequestHeader('Accept', 'application/json');
            var boundary = '----FormBoundary' + Date.now();
            xhr.setRequestHeader('Content-Type', 'multipart/form-data; boundary=' + boundary);
            var body = '--' + boundary + '\r\n';
            body += 'Content-Disposition: form-data; name="_token"\r\n\r\n' + token + '\r\n';
            body += '--' + boundary + '\r\n';
            body += 'Content-Disposition: form-data; name="seconds"\r\n\r\n' + seconds + '\r\n';
            body += '--' + boundary + '--\r\n';
            xhr.send(body);
        }
    }

    function onLeave() {
        sendDwellTime();
    }

    window.addEventListener('beforeunload', onLeave);
    window.addEventListener('pagehide', onLeave);
})();
