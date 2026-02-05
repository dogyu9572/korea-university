/**
 * 세미나·해외연수 신청 리스트 - 탭/정렬/페이지네이션 클릭 시 스크롤 유지 (AJAX)
 */
(function () {
    const basePath = '/seminars_training/application_st';

    function isApplicationStLink(href) {
        if (!href || typeof href !== 'string') return false;
        try {
            const url = new URL(href, window.location.origin);
            const path = url.pathname.replace(/\/$/, '');
            return path === basePath.replace(/\/$/, '');
        } catch {
            return false;
        }
    }

    function replaceContent(html) {
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const main = document.querySelector('main.sub_wrap');

        if (!main) return;

        const selectors = ['.round_tabs', '.board_top', '.thum_list', '.board_bottom'];
        const frag = doc.querySelector('main.sub_wrap');

        if (!frag) return;

        selectors.forEach(function (sel) {
            const newEl = frag.querySelector(sel);
            const currentEl = main.querySelector(sel);
            if (newEl && currentEl) {
                currentEl.replaceWith(newEl.cloneNode(true));
            }
        });
    }

    function loadPage(url, pushState) {
        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function (res) {
                if (!res.ok) throw new Error('Network error');
                return res.text();
            })
            .then(function (html) {
                replaceContent(html);
                if (pushState) {
                    history.pushState({}, '', url);
                }
            })
            .catch(function () {
                window.location.href = url;
            });
    }

    function handleClick(e) {
        const a = e.target.closest('a');
        if (!a || !a.href) return;

        if (!isApplicationStLink(a.href)) return;
        if (a.target === '_blank' || a.hasAttribute('download')) return;

        e.preventDefault();
        loadPage(a.href, true);
    }

    function init() {
        const main = document.querySelector('main.sub_wrap');
        if (!main) return;

        main.addEventListener('click', function (e) {
            const a = e.target.closest('a');
            if (!a) return;

            if (!a.closest('.round_tabs') && !a.closest('.list_filter') && !a.closest('.board_bottom .paging')) {
                return;
            }
            handleClick(e);
        });

        window.addEventListener('popstate', function () {
            if (window.location.pathname.indexOf('application_st') === -1) return;
            loadPage(window.location.href, false);
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
