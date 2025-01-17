$(document).ready(function () {
    // Set CSRF Token in Header
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    // Handle Form Submission
    $("#ajax-form").submit(function (e) {
        e.preventDefault(); // Prevent default form submission

        let name = $("#name").val();

        $.ajax({
            url: "/submit", // Replace with your route
            type: "POST",
            data: {
                name: name,
            },
            success: function (response) {
                $("#response").html(`<p>${response.message}</p>`);
            },
            error: function (xhr) {
                $("#response").html(
                    `<p>Error: ${xhr.responseJSON.message}</p>`
                );
            },
        });
    });
});
