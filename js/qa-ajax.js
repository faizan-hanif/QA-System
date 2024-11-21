jQuery(document).ready(function($) {
    function loadQuestions(page = 1) {
        $.ajax({
            url: qa_ajax_obj.ajax_url,
            type: 'POST',
            data: {
                action: 'qa_get_questions',
                page: page,
            },
            success: function(response) {
                $('#qa-list').html(response);
            },
            error: function(xhr) {
                console.log(xhr); // Log any unexpected errors
                alert('Failed to load questions: ' + xhr.statusText);
            },
        });
    }

    $('#qa-submit').on('click', function() {
        const name = $('#qa_name').val().trim();
        const question = $('#qa_question').val().trim();
        const email = $('#qa_email').val().trim();
        const consent = $('#qa_consent').is(':checked') ? 1 : 0;

        if (!name || !question) {
            alert('Name and question are required.');
            return;
        }

        $.ajax({
            url: qa_ajax_obj.ajax_url,
            type: 'POST',
            dataType: 'json', // Ensure JSON response is expected
            data: {
                action: 'qa_submit_question',
                name: name,
                question: question,
                email: email,
                consent: consent,
            },
           success: function(response) {
                // Hide the loading animation
                $('#loading').hide();

                // Show popup with success message
                if (response.success) {
                    $('#popup-message').text('Your question has been submitted successfully!').addClass('success').fadeIn();
					$('#qa-form')[0].reset();    // Reset the form
                    loadQuestions();            // Reload the questions
                } else {
                    $('#popup-message').text('There was an error submitting your question. Please try again.').addClass('error').fadeIn();
                }

                // Hide the popup after 5 seconds
                setTimeout(function() {
                    $('#popup-message').fadeOut().removeClass('success error');
                }, 5000);
            },
            error: function() {
                // Hide the loading animation
                $('#loading').hide();

                // Show error popup message
                $('#popup-message').text('Something went wrong. Please try again later.').addClass('error').fadeIn();
                setTimeout(function() {
                    $('#popup-message').fadeOut().removeClass('success error');
                }, 3000);
            },
        });
    });

    loadQuestions();

    $(document).on('click', '.qa-pagination a', function(e) {
        e.preventDefault();
        const page = $(this).data('page');
        loadQuestions(page);
    });
});

jQuery(document).ready(function($) {
    // Handle the form submission
    $('#question-form').on('submit', function(e) {
        e.preventDefault();

        // Show loading animation
        $('#loading').show();

        // Get form data
        var formData = {
            'action': 'submit_question', // Custom action for AJAX
            'question': $('#question').val(),
            'name': $('#name').val(),
            'email': $('#email').val(),
            'consent': $('#consent').is(':checked') ? 1 : 0
        };

        // Make the AJAX request
        $.ajax({
            url: ajaxurl, // WordPress AJAX URL
            method: 'POST',
            data: formData,
            success: function(response) {
                // Hide the loading animation
                $('#loading').hide();

                // Show popup with success message
                if (response.success) {
                    $('#popup-message').text('Your question has been submitted successfully!').addClass('success').fadeIn();
                } else {
                    $('#popup-message').text('There was an error submitting your question. Please try again.').addClass('error').fadeIn();
                }

                // Hide the popup after 3 seconds
                setTimeout(function() {
                    $('#popup-message').fadeOut().removeClass('success error');
                }, 3000);
            },
            error: function() {
                // Hide the loading animation
                $('#loading').hide();

                // Show error popup message
                $('#popup-message').text('Something went wrong. Please try again later.').addClass('error').fadeIn();
                setTimeout(function() {
                    $('#popup-message').fadeOut().removeClass('success error');
                }, 3000);
            }
        });
    });
});
