$(function () {
    const button = $("#ida");

    button.click(function (event) {
        event.preventDefault();
        var travelId = $("#id").val();
        window.location.href =
            "/seatsearch?travelId=" + encodeURIComponent(travelId.toString());
    });
});
