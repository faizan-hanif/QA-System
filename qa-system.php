<?php
/**
 * Plugin Name: QA System
 * Plugin URI: https://faizanhanif.com
 * Description: A robust QA system for WordPress, allowing users to submit questions, admins to provide answers, and email notifications to be sent. Includes AJAX functionality and customizable email templates.
 * Version: 1.0.0
 * Author: Faizan Hanif
 * Author URI: https://faizanhanif.com
 * Text Domain: qa-system
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * 
 * This plugin allows users to submit questions via a customizable frontend form on your WordPress site.
 * Administrators can manage the questions, provide answers, and send email notifications to the users who submitted the questions.
 *
 * Features:
 * - Customizable frontend question submission form (name, email, question, and consent to receive notifications).
 * - Admin interface to view and respond to questions.
 * - Email notifications sent to both admins and users for question submissions and answers.
 * - Full email template customization (question submission and answer submission templates).
 * - AJAX functionality for seamless dynamic content updates without page reloads.
 * - Pagination of the question list to improve page load performance.
 * - Fully customizable settings in the backend for email configurations and templates.
 *
 * Installation:
 * 1. Upload the `qa-system` folder to your `wp-content/plugins/` directory.
 * 2. Activate the plugin through the 'Plugins' menu in WordPress.
 * 3. Go to the **QA System** menu in the WordPress admin to access the settings page and configure the plugin.
 *
 * Usage:
 * - The question form can be embedded on any page or post using the shortcode `[qa_system]`.
 * - Admins can manage the submitted questions and provide answers from the **QA System** menu in the WordPress admin.
 * - Customize email templates for question and answer notifications from the plugin's settings page.
 *
 * Support:
 * For any questions or support, please contact [faizanhanif43@hotmail.com](mailto:faizanhanif43@hotmail.com).
 * 
 * @package QA System & Question Form
 */




// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Activation hook to create database table
function qa_system_activate() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'qa_system';

    $sql = "CREATE TABLE $table_name (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) DEFAULT NULL,
        question TEXT NOT NULL,
        answer TEXT DEFAULT NULL,
        email VARCHAR(255) DEFAULT NULL,
        consent TINYINT(1) DEFAULT 0,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) CHARSET=utf8mb4;";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
register_activation_hook(__FILE__, 'qa_system_activate');

// Load assets
function qa_enqueue_assets() {
    wp_enqueue_style('qa-style', plugin_dir_url(__FILE__) . 'css/style.css');
    wp_enqueue_script('qa-ajax', plugin_dir_url(__FILE__) . 'js/qa-ajax.js', ['jquery'], null, true);
    wp_localize_script('qa-ajax', 'qa_ajax_obj', [
        'ajax_url' => admin_url('admin-ajax.php'),
    ]);
}
add_action('wp_enqueue_scripts', 'qa_enqueue_assets');

// Shortcode to display the Q&A form and list
function qa_render_shortcode() {
    ob_start();
    ?>
<!-- Loading Animation -->
<div id="loading" style="display:none;">Loading...</div>

<!-- Popup Message -->
<div id="popup-message" style="display:none;"></div>
    <form id="qa-form">
        <label for="qa_name">Your Name:</label><br>
        <input type="text" id="qa_name" name="qa_name" placeholder="Your name" required /><br>
        <label for="qa_email">Your Email:</label><br>
        <input type="email" id="qa_email" name="qa_email" placeholder="Email Address" required /><br>
		
        <label for="qa_question">Ask a Question:</label><br>
        <textarea id="qa_question" name="qa_question" placeholder="Type your question here." required></textarea><br>

        <label>
            <input type="checkbox" id="qa_consent" name="qa_consent" value="1" required/>
            I agree to receive an email with the answer to my question.
        </label><br>
<p>
	By clicking the "Submit Question" button, you agree to the processing of your personal data. The policy of the "B2Youth" Bureau regarding the processing of personal data can be found at the link.
		</p>
        <button type="button" id="qa-submit">Submit Question</button>
    </form>
    <hr>
    <div id="qa-list"></div>
    <?php
    return ob_get_clean();
}
add_shortcode('qa_system', 'qa_render_shortcode');


function qa_submit_question() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'qa_system';

    // Sanitize form data
    $name = sanitize_text_field($_POST['name']);
    $email = sanitize_email($_POST['email']);
    $question = sanitize_textarea_field($_POST['question']);
    $consent = isset($_POST['consent']) ? 1 : 0;

    // Get admin email from settings
    $admin_email = get_option('question_form_admin_email');
    $sender_name = get_option('question_form_sender_name');
    $sender_email = get_option('question_form_sender_email');
	
	// Get email templates from options
    $question_template = get_option('question_submission_email_template', 'Thank you for your question! We will respond soon.');
    $answer_template = get_option('answer_submission_email_template', 'Your question has been answered. Please check the details below:');


    // Insert the question into the database
    $result = $wpdb->insert(
        $table_name,
        [
            'name' => $name,
            'email' => $email,
            'question' => $question,
            'created_at' => current_time('mysql'),
            'consent' => $consent
        ]
    );

    // Check if the insertion was successful
    if ($result) {
        // Send an email to the user if consent was given
        if ($consent) {
            wp_mail($email, 'Your Question Submission', $question_template, [
                'From: ' . $sender_name . ' <' . $sender_email . '>',
                'Content-Type: text/plain; charset=UTF-8'
            ]);
        }

        // Send an email to the admin (support) with the submitted question
        $subject = 'New Question Submitted';
        $message = "You have a new question submitted:\n\n";
        $message .= "Name: $name\n";
        $message .= "Email: $email\n";
        $message .= "Question: $question\n";

        wp_mail($admin_email, $subject, $message, [
            'From: ' . $sender_name . ' <' . $sender_email . '>',
            'Content-Type: text/plain; charset=UTF-8'
        ]);

        // Return success response
        wp_send_json_success();
    } else {
        // Return error response
        wp_send_json_error();
    }

    wp_die(); // End the AJAX request
}
add_action('wp_ajax_qa_submit_question', 'qa_submit_question');
add_action('wp_ajax_nopriv_qa_submit_question', 'qa_submit_question');


// AJAX handler for fetching questions
function qa_get_questions() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'qa_system';

    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $per_page = 10;
    $offset = ($page - 1) * $per_page;

    $questions = $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM $table_name ORDER BY created_at DESC LIMIT %d OFFSET %d",
        $per_page,
        $offset
    ));

    $total_questions = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
    $total_pages = ceil($total_questions / $per_page);

    ob_start();
    echo '<ul>';
    foreach ($questions as $q) {
        echo '<li class="qa-item">';
        echo '<div class="qa-question"><strong>' . esc_html($q->name) . ' (' . esc_html($q->created_at) . '):</strong> ' . esc_html($q->question) . '</div>';
        if ($q->answer) {
            echo '<div class="qa-answer"><strong>Answer:</strong> ' . esc_html($q->answer) . '</div>';
        }
        echo '</li>';
    }
    echo '</ul>';

    echo '<div class="qa-pagination">';
    for ($i = 1; $i <= $total_pages; $i++) {
        echo '<a href="#" data-page="' . esc_attr($i) . '">' . esc_html($i) . '</a> ';
    }
    echo '</div>';

    wp_die(ob_get_clean());
}
add_action('wp_ajax_qa_get_questions', 'qa_get_questions');
add_action('wp_ajax_nopriv_qa_get_questions', 'qa_get_questions');

// Change the sender email address
function custom_wp_mail_from($original_email_address) {
    return 'support@b2youth.com'; // Set your custom email address here
}
add_filter('wp_mail_from', 'custom_wp_mail_from');

// Change the sender name
function custom_wp_mail_from_name($original_email_from) {
    return 'B2Youth Support'; // Set the sender name here
}
add_filter('wp_mail_from_name', 'custom_wp_mail_from_name');

function qa_send_email_notification($question_id, $answer) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'qa_system';

    // Retrieve question and user's email
    $question = $wpdb->get_row("SELECT * FROM $table_name WHERE id = $question_id");
    if ($question && $question->consent) {
        $subject = "Your Question Has Been Answered";
        $message = "Hello " . $question->name . ",\n\n";
        $message .= "Your question: " . $question->question . "\n\n";
        $message .= "Answer: " . $answer . "\n\n";
        $message .= "Thank you for using our consultation system.";

        // Send email
        wp_mail($question->email, $subject, $message);
    }
}



// Admin menu
function qa_admin_menu() {
    add_menu_page(
        'Question & Answer System',      // Page title
        'Q&A System',                   // Menu title
        'manage_options',               // Capability
        'qa-system',                    // Menu slug
        'qa_admin_page',                // Callback function
        'dashicons-format-chat',        // Icon
        20                              // Position
    );
}
add_action('admin_menu', 'qa_admin_menu');

// Add menu page for the settings under QA System menu
function question_form_settings_menu() {
    // Make sure QA System menu exists first, and add the settings submenu under it
    add_submenu_page(
        'qa-system', // Parent menu slug (replace with your actual QA system menu slug)
        'Question Form Settings', // Page Title
        'Question Form Settings', // Menu Title
        'manage_options',         // Capability
        'question-form-settings', // Menu Slug
        'question_form_settings_page', // Function to display settings page
		        'qa_form_page',                // Callback function
        'dashicons-format-chat',        // Icon
        20                              // Position
    );
}
add_action('admin_menu', 'question_form_settings_menu');

// Display the settings page
function question_form_settings_page() {
    ?>
    <div class="wrap">
        <h1>Question Form Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('question_form_settings_group');
            do_settings_sections('question-form-settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Register settings fields for Email and Templates
function question_form_settings_init() {
    // Register the settings group
    register_setting('question_form_settings_group', 'question_form_admin_email', 'sanitize_email');
    register_setting('question_form_settings_group', 'question_form_sender_name');
    register_setting('question_form_settings_group', 'question_form_sender_email', 'sanitize_email');

    // Add settings section
    add_settings_section(
        'question_form_settings_section',
        'Email Settings',
        null,
        'question-form-settings'
    );

    // Add settings fields
    add_settings_field(
        'question_form_admin_email', 
        'Admin Email', 
        'question_form_admin_email_field', 
        'question-form-settings', 
        'question_form_settings_section'
    );
    add_settings_field(
        'question_form_sender_name', 
        'Sender Name', 
        'question_form_sender_name_field', 
        'question-form-settings', 
        'question_form_settings_section'
    );
    add_settings_field(
        'question_form_sender_email', 
        'Sender Email', 
        'question_form_sender_email_field', 
        'question-form-settings', 
        'question_form_settings_section'
    );
    
//     // Add email templates section
//     add_settings_section(
//         'question_form_email_templates_section', 
//         'Email Templates', 
//         null, 
//         'question-form-settings'
//     );

//     // Add email template fields
//     add_settings_field(
//         'question_submission_email_template', 
//         'Question Submission Email Template', 
//         'question_submission_email_template_field', 
//         'question-form-settings', 
//         'question_form_email_templates_section'
//     );
//     add_settings_field(
//         'answer_submission_email_template', 
//         'Answer Submission Email Template', 
//         'answer_submission_email_template_field', 
//         'question-form-settings', 
//         'question_form_email_templates_section'
//     );
}
add_action('admin_init', 'question_form_settings_init');

// Email template field callback functions
function question_form_admin_email_field() {
    $value = get_option('question_form_admin_email');
    echo '<input type="email" name="question_form_admin_email" value="' . esc_attr($value) . '" class="regular-text" />';
}

function question_form_sender_name_field() {
    $value = get_option('question_form_sender_name');
    echo '<input type="text" name="question_form_sender_name" value="' . esc_attr($value) . '" class="regular-text" />';
}

function question_form_sender_email_field() {
    $value = get_option('question_form_sender_email');
    echo '<input type="email" name="question_form_sender_email" value="' . esc_attr($value) . '" class="regular-text" />';
}

// function question_submission_email_template_field() {
//     $template = get_option('question_submission_email_template', 'Thank you for your question! We will respond soon.');
//     echo '<textarea name="question_submission_email_template" class="large-text" rows="5">' . esc_textarea($template) . '</textarea>';
// }

// function answer_submission_email_template_field() {
//     $template = get_option('answer_submission_email_template', 'Your question has been answered. Please check the details below:');
//     echo '<textarea name="answer_submission_email_template" class="large-text" rows="5">' . esc_textarea($template) . '</textarea>';
// }


function qa_admin_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'qa_system';

    // Handle the form submission for answers
    if (isset($_POST['qa_answer']) && isset($_POST['question_id'])) {
        $answer = sanitize_textarea_field($_POST['qa_answer']);
        $question_id = intval($_POST['question_id']);
        
        if (empty($answer)) {
            echo '<div class="error"><p>Please provide an answer before submitting.</p></div>';
            return;
        }

        // Update the question with the new answer
        $updated = $wpdb->update(
            $table_name,
            ['answer' => $answer],
            ['id' => $question_id],
            ['%s'], // format for the answer
            ['%d']  // format for the ID
        );

        if ($updated !== false) {
            // Send the email notification if consent was given
            qa_send_email_notification($question_id, $answer);
            echo '<div class="updated"><p>Answer submitted successfully!</p></div>';
        } else {
            echo '<div class="error"><p>Failed to submit the answer. Please try again.</p></div>';
        }
    }

    // Get all questions from the database
    $questions = $wpdb->get_results("SELECT * FROM $table_name ORDER BY created_at DESC");

    ?>
    <div class="wrap">
        <h1>Manage Questions & Answers</h1>
        <table class="widefat fixed">
            <thead>
                <tr>
                    <th>Name & Email</th>
                    <th>Question</th>
                    <th>Answer</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($questions as $question) { ?>
                    <tr>
                        <td><?php echo esc_html($question->name); ?><br><?php echo esc_html($question->email); ?></td>
                        <td><?php echo esc_html($question->question); ?></td>
                        <td>
                            <?php if ($question->answer) { ?>
                                <!-- Display the existing answer with a textarea for editing -->
                                <form method="POST">
                                    <textarea name="qa_answer" rows="4" style="width: 100%;"><?php echo esc_textarea($question->answer); ?></textarea>
                                    <input type="hidden" name="question_id" value="<?php echo $question->id; ?>" />
                                    <button type="submit">Update Answer</button>
                                </form>
                            <?php } else { ?>
                                <!-- Display an empty textarea if no answer exists -->
                                <form method="POST">
                                    <textarea name="qa_answer" rows="4" style="width: 100%;"></textarea>
                                    <input type="hidden" name="question_id" value="<?php echo $question->id; ?>" />
                                    <button type="submit">Submit Answer</button>
                                </form>
                            <?php } ?>
                        </td>
                        <td>
                            <!-- Button to delete a question -->
                            <form method="POST" onsubmit="return confirm('Are you sure you want to delete this question?');">
                                <input type="hidden" name="delete_question_id" value="<?php echo $question->id; ?>" />
                                <button type="submit" name="delete_question">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <?php

    // Handle deletion of a question
    if (isset($_POST['delete_question']) && isset($_POST['delete_question_id'])) {
        $delete_id = intval($_POST['delete_question_id']);
        $wpdb->delete($table_name, ['id' => $delete_id], ['%d']);
        echo '<div class="updated"><p>Question deleted successfully.</p></div>';
    }
}
