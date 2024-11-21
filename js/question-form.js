jQuery(document).ready(function($) {
    // Handle the form submission
    $('#question-form').on('submit', function(e) {
        e.preventDefault();  // Prevent default form submission

        // Show loading animation
        $('#loading').show();

        // Collect form data
        var formData = {
            'action': 'submit_question', // Custom action for AJAX
            'question': $('#question').val(),
            'name': $('#name').val(),
            'email': $('#email').val(),
            'consent': $('#consent').is(':checked') ? 1 : 0
        };

        // AJAX request
        $.ajax({
            url: ajaxurl, // WordPress AJAX URL (localized from PHP)
            method: 'POST',
            data: formData,
            success: function(response) {
                // Hide the loading animation
                $('#loading').hide();

                // Show success or error message in the popup
                if (response.success) {
                    $('#popup-message').text('Your question has been submitted successfully!').addClass('success').fadeIn();
                } else {
                    $('#popup-message').text('There was an error submitting your question. Please try again.').addClass('error').fadeIn();
                }

                // Hide the popup message after 3 seconds
                setTimeout(function() {
                    $('#popup-message').fadeOut().removeClass('success error');
                }, 3000);
            },
            error: function() {
                // Hide loading and show error message
                $('#loading').hide();
                $('#popup-message').text('Something went wrong. Please try again later.').addClass('error').fadeIn();
                setTimeout(function() {
                    $('#popup-message').fadeOut().removeClass('success error');
                }, 3000);
            }
        });
    });
});
