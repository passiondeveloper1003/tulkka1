<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationTemplate extends Model
{
    protected $table = 'notification_templates';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];

    public static $templateKeys = [
        'email' => '[u.email]',
        'mobile' => '[u.mobile]',
        'real_name' => '[u.name]',
        'instructor_name' => '[instructor.name]',
        'student_name' => '[student.name]',
        'group_title' => '[u.g.title]',
        'badge_title' => '[u.b.title]',
        'course_title' => '[c.title]',
        'quiz_title' => '[q.title]',
        'quiz_result' => '[q.result]',
        'support_ticket_title' => '[s.t.title]',
        'contact_us_title' => '[c.u.title]',
        'time_and_date' => '[time.date]',
        'link' => '[link]',
        'rate_count' => '[rate.count]',
        'amount' => '[amount]',
        'payout_account' => '[payout.account]',
        'financial_doc_desc' => '[f.d.description]',
        'financial_doc_type' => '[f.d.type]',
        'subscribe_plan_name' => '[s.p.name]',
        'promotion_plan_name' => '[p.p.name]',
        'product_title' => '[p.title]',
        'assignment_grade' => '[assignment_grade]',
        'topic_title' => '[topic_title]',
        'blog_title' => '[blog_title]',
    ];

    public static $notificationTemplateAssignSetting = [
        'admin' => ['new_comment_admin', 'support_message_admin', 'support_message_replied_admin', 'promotion_plan_admin', 'new_contact_message', 'payout_request_admin'],
        'user' => ['new_badge', 'change_user_group', 'user_access_to_content'],
        'course' => ['course_created', 'course_approve', 'course_reject', 'new_comment', 'support_message', 'support_message_replied', 'new_rating', 'new_question_in_forum', 'new_answer_in_forum'],
        'financial' => ['new_financial_document', 'payout_request', 'payout_proceed', 'offline_payment_request', 'offline_payment_approved', 'offline_payment_rejected'],
        'sale_purchase' => ['new_sales', 'new_purchase'],
        'plans' => ['new_subscribe_plan', 'promotion_plan'],
        'appointment' => ['meeting_created_teacher','new_appointment', 'new_appointment_link', 'appointment_reminder', 'meeting_finished'],
        'quiz' => ['new_certificate', 'waiting_quiz', 'waiting_quiz_result','quiz_recieved'],
        'store' => ['product_new_sale', 'product_new_purchase', 'product_new_comment', 'product_tracking_code', 'product_new_rating', 'product_receive_shipment', 'product_out_of_stock'],
        'assignment' => ['student_send_message', 'instructor_send_message', 'instructor_set_grade'],
        'topic' => ['send_post_in_topic'],
        'blog' => ['publish_instructor_blog_post', 'new_comment_for_instructor_blog_post'],
        'lesson' => ['feedback_recieved','homework_recieved','homework_answer_recieved'],
        'reminders' => ['webinar_reminder', 'meeting_reserve_reminder', 'subscribe_reminder','lesson_booked','lesson_booked_student','quiz_answer_recieved','meeting_reserve_reminder_teacher']
    ];
}
