$(document).ready(function () {
    // Handle lock button click
    $(".lock-btn").click(function () {
        const noteId = $(this).data("id");
        $("#modalNoteId").val(noteId);

        const modal = new bootstrap.Modal(document.getElementById('passwordModal'), {
            backdrop: 'static',
            keyboard: false
        });
        modal.show();
    });

    // Handle password form submission
    $("#passwordForm").submit(function (e) {
        e.preventDefault();

        const noteId = $("#modalNoteId").val();
        const password = $("#notePassword").val();

        if (!noteId || !password) {
            alert("Note ID or Password is missing!");
            return;
        }

        $.ajax({
            url: "setPassword.php",
            type: "POST",
            contentType: "application/json", // Ensures JSON data is sent
            data: JSON.stringify({
                note_id: noteId,
                password: password
            }),
            success: function (response) {
                if (response.success) {
                    alert("Password set successfully!");
                    location.reload();
                } else {
                    alert("Error: " + response.error);
                }
            },
            error: function (xhr, status, error) {
                alert("Failed to set password. Please try again.");
                console.error("AJAX Error:", error);
                console.error("Response:", xhr.responseText);
            }
        });
    });
});