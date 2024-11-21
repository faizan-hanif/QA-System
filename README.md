# QA System

**Contributors**: Faizan Hanif  
**Tags**: questions, answers, email notifications, AJAX, WordPress form  
**Requires at least**: 5.0  
**Tested up to**: 6.1  
**Stable tag**: 1.0.0  
**License**: GPLv2 or later  
**License URI**: [http://www.gnu.org/licenses/gpl-2.0.html](http://www.gnu.org/licenses/gpl-2.0.html)  

---

## Description

The **QA System** plugin allows users to submit questions via a customizable frontend form on your WordPress site. Administrators can manage the questions, provide answers, and send email notifications to users who submitted the questions.

### Features
- **Frontend Form**: Submit questions with optional name and email fields.
- **Consent Option**: Users can consent to receive emails when their question is answered.
- **Backend Management**: View, answer, and manage questions via the admin dashboard.
- **Email Notifications**: Automatic emails for question submissions and answers.
- **Email Templates**: Fully customizable email templates.
- **AJAX Handling**: Dynamic frontend updates without page reloads.
- **Pagination**: Improves performance by paginating the question list.

---

## Installation

1. Download the plugin file.
2. Upload the `qa-system` folder to your `wp-content/plugins/` directory.
3. Activate the plugin through the **Plugins** menu in WordPress.
4. Navigate to **QA System** in the WordPress admin panel to access **Question Form Settings**.
5. Configure email settings, templates, and form fields as needed.

---

## Configuration

### QA System Settings
1. Navigate to **QA System > Question Form Settings** in the WordPress admin dashboard.
2. Customize the following:
   - **Admin Email**: Address for admin notifications.
   - **Sender Name**: Name displayed as the email sender.
   - **Sender Email**: Email address used for sending notifications.

### Email Templates
- **Question Submission Email Template**: Customize email content sent to users after submitting a question.
- **Answer Submission Email Template**: Customize email content sent when their question is answered.

### Frontend Form
The question form includes:
- Name  
- Email  
- Question  
- Consent checkbox for receiving email notifications.

### Backend Management
Admins can:
- View all submitted questions.
- Provide answers or delete questions.
- Manage the QA system from **QA System > Questions**.

---

## Changelog

### 1.0.0
- Initial release:
  - Submit questions and provide answers.
  - Admin interface for managing questions.
  - Email notification system for submissions and responses.
  - Customizable email templates.
  - AJAX functionality for dynamic frontend updates.
  - Pagination for better performance.

---

## Frequently Asked Questions

### How do I access the question form?
The question form is available via the `[qa_system]` shortcode. Customize it through the plugin settings.

### How can I view submitted questions?
Visit **QA System > Questions** in the admin dashboard to view, edit, and answer questions.

### Can I customize the emails sent to users?
Yes, customize the email templates under **QA System > Question Form Settings**.

### Is this plugin compatible with all WordPress themes?
Yes, but testing with your theme is recommended to ensure compatibility.

---

## Screenshots

1. **Frontend Question Form**: Form with fields for name, email, question, and consent.
2. **Backend Question Management**: Admin interface for managing questions.
3. **Plugin Settings**: Configure email settings, templates, and form options.

---

## Support

For support or feature requests, please contact:  
📧 [faizanhanif43@hotmail.com](mailto:faizanhanif43@hotmail.com)

---

## License

This plugin is licensed under the GPL v2.  
For more details, see [GPL License](http://www.gnu.org/licenses/gpl-2.0.html).
