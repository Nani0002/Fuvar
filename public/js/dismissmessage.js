$(document).ready(function () {
    $(document).on("click", ".dismiss-message", function () {
        var id = $(this).data("id");

        $.ajax({
            url: "/api/dismiss_message/" + id,
            method: "POST",
            data: {
                _token: window.Laravel.csrfToken,
            },
            success: function (resp) {
                $(this).closest(".message-div").remove();
                if(resp == 0){
                    $("#read-messages-btn").remove();
                    $("#undismissedModal").modal('hide');
                }
            }.bind(this),
            error: function (resp) {
                alert("Ismeretlen hiba történt!" + resp.responseText);
            },
        });
    });
});

$(document).ready(function () {
    $(document).on("click", ".read-messages", function () {
        $.ajax({
            url: "api/read_messages/",
            method: "POST",
            data: { _token: window.Laravel.csrfToken },
            success: function () {
                $("#message-counter").remove();
            },
            error: function (resp) {
                alert("Ismeretlen hiba történt!" + resp.responseText);
            },
        });
    });
});
