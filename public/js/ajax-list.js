console.log("ajax-list.js yüklendi");

$(document).ready(function () {
    // Form gönderimi: arama ve filtreleme
    $(document).on("submit", "form[data-ajax-filter]", function (e) {
        e.preventDefault();

        const $form = $(this);
        const endpoint = $form.attr("action");
        const target = $form.data("target");
        const params = $form.serialize();

        const url = endpoint + (params ? "?" + params : "");

        loadAdminList(url, target);
        window.history.pushState({ target }, "", url);
    });

    // Dropdown değişince otomatik filtreleme
    $(document).on("change", "form[data-ajax-filter] select", function () {
        $(this).closest("form").trigger("submit");
    });

    // Sayfalama
    $(document).on("click", "[data-ajax-list] .pagination a", function (e) {
        e.preventDefault();

        const $list = $(this).closest("[data-ajax-list]");
        const target = "#" + $list.attr("id");
        const url = this.href;

        loadAdminList(url, target);
        window.history.pushState({ target }, "", url);
    });

    // Filtreleri temizleme
    $(document).on("click", "[data-clear-filter]", function () {
        const $form = $($(this).data("form"));

        if (!$form.length) return;

        $form[0].reset();

        const endpoint = $form.attr("action");
        const target = $form.data("target");

        loadAdminList(endpoint, target);
        window.history.pushState({ target }, "", endpoint);
    });

    // Geri / ileri
    window.addEventListener("popstate", function (e) {
        if (e.state?.target) {
            loadAdminList(window.location.href, e.state.target);
        }
    });
});

function loadAdminList(url, target) {
    const $list = $(target);

    if (!$list.length) {
        console.error("Liste bulunamadı:", target);
        return;
    }

    const endpoint = new URL(url, window.location.origin);

    $.ajax({
        url: endpoint.pathname + endpoint.search,
        type: "GET",
        dataType: "html",

        beforeSend: function () {
            $list.css("opacity", "0.5");
        },

        success: function (html) {
            const $response = $("<div>").append($.parseHTML(html));
            const $newList = $response.find(target);

            if ($newList.length) {
                $list.replaceWith($newList);
            } else {
                console.error("Yanıtta liste bulunamadı:", target);
            }
        },

        error: function (xhr) {
            console.error("Liste yüklenemedi:", xhr.status);
        },

        complete: function () {
            $(target).css("opacity", "1");
        },
    });
}
