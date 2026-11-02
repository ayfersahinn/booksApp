console.log("ajax-list.js yüklendi");

$(document).ready(function () {
    $(document).on("click", "#adminBookList .pagination a", function (e) {
        e.preventDefault();

        const url = this.href;

        console.log("Tıklanan URL:", url);

        loadAdminList(url, "/admin/books", "#adminBookList");

        window.history.pushState(null, "", url);
    });
});

function loadAdminList(url, endpoint, selector) {
    const $list = $(selector);

    console.log("AJAX isteği başlıyor...");

    $.ajax({
        url: endpoint + new URL(url, window.location.origin).search,
        type: "GET",
        dataType: "html",

        beforeSend: function () {
            $list.css("opacity", "0.5");
        },

        success: function (html) {
            console.log("AJAX başarılı.");
            console.log("Gelen HTML:", html);

            const $newList = $(html).filter(selector);

            console.log("Yeni liste bulundu mu?", $newList.length);

            if ($newList.length) {
                $list.replaceWith($newList);
            }
        },

        error: function (xhr) {
            console.error("AJAX hatası:", xhr.status);
            console.error(xhr.responseText);
        },

        complete: function () {
            $(selector).css("opacity", "1");
        },
    });
}

$(document).on("submit", "#adminBookFilter", function (e) {
    e.preventDefault();

    const $form = $(this);
    const endpoint = $form.attr("action");
    const selector = $form.data("target");

    const params = $form.serialize();

    const url = endpoint + (params ? "?" + params : "");

    loadAdminList(url, endpoint, selector);

    window.history.pushState(null, "", url);
});

$(document).on("change", "#adminBookFilter select", function () {
    $("#adminBookFilter").trigger("submit");
});

$(document).on("click", "#clearBookFilters", function () {
    const $form = $("#adminBookFilter");
    $form[0].reset();

    const endpoint = $form.attr("action");
    const selector = $form.data("target");

    loadAdminList(endpoint, endpoint, selector);

    window.history.pushState(null, "", endpoint);
});
