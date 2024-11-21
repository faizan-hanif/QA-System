=== QA System ===
Contributors: Faizan Hanif
Tags: questions, answers, email notifications, AJAX, WordPress form
Requires at least: 5.0
Tested up to: 6.1
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==

The **QA System** plugin allows users to submit questions via a customizable frontend form on your WordPress site. Administrators can manage the questions, provide answers, and send email notifications to the users who submitted the questions.

This plugin includes a fully customizable question submission form, an intuitive backend interface for admins, and AJAX functionality to update the frontend dynamically. Admins can also configure email settings and templates for both question and answer notifications.

Features:
- **Frontend Form**: Users can submit questions with optional name and email fields.
- **Consent Option**: Users can give consent to receive an email when their question is answered.
- **Backend Management**: Admins can view submitted questions, provide answers, and manage the question list.
- **Email Notifications**: Automatic emails are sent to users and admins on question submission and answer posting.
- **Email Templates**: Customize email templates for question submission and answer notifications.
- **AJAX Handling**: The plugin uses AJAX to dynamically update the frontend without page reloads.
- **Pagination**: The question list is paginated to improve performance and reduce page load.

== Installation ==

1. Download the plugin file.
2. Upload the `qa-system` folder to your `wp-content/plugins/` directory.
3. Activate the plugin through the 'Plugins' menu in WordPress.
4. After activation, go to the **QA System** menu in the admin panel to access **Question Form Settings**.
5. Configure the email settings, templates, and form fields as per your requirements.

== Configuration ==

After activating the plugin, you can configure the following settings:

1. **QA System Settings**:
   - Navigate to **QA System > Question Form Settings** in the WordPress admin.
   - You can customize the following settings:
     - **Admin Email**: Email address for admin notifications.
     - **Sender Name**: Name to be shown as the sender in emails.
     - **Sender Email**: Email address to be used as the sender.
  
2. **Email Templates**:
   - You can customize the email templates for question submission and answer notifications:
     - **Question Submission Email Template**: Email content that will be sent to users after submitting a question.
     - **Answer Submission Email Template**: Email content that will be sent to users when their question is answered.

3. **Frontend Form**:
   - Users can submit questions by filling out a simple form. The form includes:
     - Name 
     - Email 
     - Question
     - Consent checkbox to receive email notifications
  
4. **Backend Management**:
   - Admins can view all questions in the backend under **QA System > Questions**.
   - Admins can edit answers, delete questions, and manage the entire QA system from this page.

== Changelog ==

= 1.0.0 =
- Initial release with functionality for submitting questions and providing answers.
- Admin interface to view and manage questions.
- Email notification system for both question submissions and answer responses.
- Customizable email templates for submission and answer notifications.
- AJAX handling for dynamic updates without page reload.
- Pagination of question list for improved performance.

== Frequently Asked Questions ==

= How do I access the question form? =
The question form is available on the frontend of your site. You can place the form using a shortcode `[qa_system]`. Please consult the plugin settings page to customize the form fields.

= How can I view the submitted questions? =
Go to **QA System > Questions** in the WordPress admin dashboard. Here you can view, edit, and respond to submitted questions.

= Can I customize the emails sent to users? =
Yes, you can customize the email templates for both question submissions and answer responses from the plugin's settings page under **QA System > Question Form Settings**.

= Is this plugin compatible with all WordPress themes? =
Yes, the plugin is built to work with any WordPress theme. However, it is recommended to test the plugin with your theme to ensure the frontend form and backend functionality work seamlessly.

== Screenshots ==

1. **Frontend Question Form**: Displays the form with fields for name, email, question, and consent.
2. **Backend Question Management**: View and manage submitted questions, add answers, and delete questions.
3. **Plugin Settings**: Configure email settings, templates, and form options in the WordPress admin.

== Support ==

For support or feature requests, please contact us at [faizanhanif43@hotmail.com](mailto:faizanhanif43@hotmail.com).

== License ==

This plugin is released under the GPL v2 license. See [LICENSE](http://www.gnu.org/licenses/gpl-2.0.html) for more information.

