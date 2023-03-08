<?php

use Illuminate\Database\Seeder;
use \App\Models\Section;

class SectionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Dashboards 1 - 24
        Section::updateOrCreate(['id' => 1], ['name' => 'admin_general_dashboard', 'caption' => 'General Dashboard']);
        Section::updateOrCreate(['id' => 2], ['name' => 'admin_general_dashboard_show', 'section_group_id' => 1, 'caption' => 'General Dashboard page']);
        Section::updateOrCreate(['id' => 3], ['name' => 'admin_general_dashboard_quick_access_links', 'section_group_id' => 1, 'caption' => 'Quick access links in General Dashboard']);
        Section::updateOrCreate(['id' => 4], ['name' => 'admin_general_dashboard_daily_sales_statistics', 'section_group_id' => 1, 'caption' => 'Daily Sales Type Statistics Section']);
        Section::updateOrCreate(['id' => 5], ['name' => 'admin_general_dashboard_income_statistics', 'section_group_id' => 1, 'caption' => 'Income Statistics Section']);
        Section::updateOrCreate(['id' => 6], ['name' => 'admin_general_dashboard_total_sales_statistics', 'section_group_id' => 1, 'caption' => 'Total Sales Statistics Section']);
        Section::updateOrCreate(['id' => 7], ['name' => 'admin_general_dashboard_new_sales', 'section_group_id' => 1, 'caption' => 'New Sales Section']);
        Section::updateOrCreate(['id' => 8], ['name' => 'admin_general_dashboard_new_comments', 'section_group_id' => 1, 'caption' => 'New Comments Section']);
        Section::updateOrCreate(['id' => 9], ['name' => 'admin_general_dashboard_new_tickets', 'section_group_id' => 1, 'caption' => 'New Tickets Section']);
        Section::updateOrCreate(['id' => 10], ['name' => 'admin_general_dashboard_new_reviews', 'section_group_id' => 1, 'caption' => 'New Reviews Section']);
        Section::updateOrCreate(['id' => 11], ['name' => 'admin_general_dashboard_sales_statistics_chart', 'section_group_id' => 1, 'caption' => 'Sales Statistics Chart']);
        Section::updateOrCreate(['id' => 12], ['name' => 'admin_general_dashboard_recent_comments', 'section_group_id' => 1, 'caption' => 'Recent comments Section']);
        Section::updateOrCreate(['id' => 13], ['name' => 'admin_general_dashboard_recent_tickets', 'section_group_id' => 1, 'caption' => 'Recent tickets Section']);
        Section::updateOrCreate(['id' => 14], ['name' => 'admin_general_dashboard_recent_webinars', 'section_group_id' => 1, 'caption' => 'Recent webinars Section']);
        Section::updateOrCreate(['id' => 15], ['name' => 'admin_general_dashboard_recent_courses', 'section_group_id' => 1, 'caption' => 'Recent courses Section']);
        Section::updateOrCreate(['id' => 16], ['name' => 'admin_general_dashboard_users_statistics_chart', 'section_group_id' => 1, 'caption' => 'Users Statistics Chart']);
        Section::updateOrCreate(['id' => 17], ['name' => 'admin_clear_cache', 'section_group_id' => 1, 'caption' => 'Clear cache']);

        // Marketing Dashboards 25 - 49
        Section::updateOrCreate(['id' => 25], ['name' => 'admin_marketing_dashboard', 'caption' => 'Marketing Dashboard']);
        Section::updateOrCreate(['id' => 26], ['name' => 'admin_marketing_dashboard_show', 'section_group_id' => 25, 'caption' => 'Marketing Dashboard page']);


        // Roles 50 - 99
        Section::updateOrCreate(['id' => 50], ['name' => 'admin_roles', 'caption' => trans('/admin/pages/roles.admin_roles')]);
        Section::updateOrCreate(['id' => 51], ['name' => 'admin_roles_list', 'section_group_id' => 50, 'caption' => trans('/admin/pages/roles.admin_roles_list')]);
        Section::updateOrCreate(['id' => 52], ['name' => 'admin_roles_create', 'section_group_id' => 50, 'caption' => trans('/admin/pages/roles.admin_roles_create')]);
        Section::updateOrCreate(['id' => 53], ['name' => 'admin_roles_edit', 'section_group_id' => 50, 'caption' => trans('/admin/pages/roles.admin_roles_edit')]);
        Section::updateOrCreate(['id' => 54], ['name' => 'admin_roles_delete', 'section_group_id' => 50, 'caption' => trans('/admin/pages/roles.admin_roles_delete')]);

        // Users 100 - 149
        Section::updateOrCreate(['id' => 100], ['name' => 'admin_users', 'caption' => trans('/admin/pages/users.admin_users')]);
        Section::updateOrCreate(['id' => 101], ['name' => 'admin_staffs_list', 'section_group_id' => 100, 'caption' => 'Staffs list']);
        Section::updateOrCreate(['id' => 102], ['name' => 'admin_users_list', 'section_group_id' => 100, 'caption' => 'Students list']);
        Section::updateOrCreate(['id' => 103], ['name' => 'admin_instructors_list', 'section_group_id' => 100, 'caption' => 'Instructors list']);
        Section::updateOrCreate(['id' => 104], ['name' => 'admin_organizations_list', 'section_group_id' => 100, 'caption' => 'Organizations list']);
        Section::updateOrCreate(['id' => 105], ['name' => 'admin_users_create', 'section_group_id' => 100, 'caption' => trans('/admin/pages/users.admin_users_create')]);
        Section::updateOrCreate(['id' => 106], ['name' => 'admin_users_edit', 'section_group_id' => 100, 'caption' => trans('/admin/pages/users.admin_users_edit')]);
        Section::updateOrCreate(['id' => 107], ['name' => 'admin_users_delete', 'section_group_id' => 100, 'caption' => trans('/admin/pages/users.admin_users_delete')]);
        Section::updateOrCreate(['id' => 108], ['name' => 'admin_users_export_excel', 'section_group_id' => 100, 'caption' => 'List Export excel']);
        Section::updateOrCreate(['id' => 109], ['name' => 'admin_users_badges', 'section_group_id' => 100, 'caption' => trans('/admin/pages/users.admin_users_badges')]);
        Section::updateOrCreate(['id' => 110], ['name' => 'admin_users_badges_edit', 'section_group_id' => 100, 'caption' => 'Badges edit']);
        Section::updateOrCreate(['id' => 111], ['name' => 'admin_users_badges_delete', 'section_group_id' => 100, 'caption' => 'Badges delete']);
        Section::updateOrCreate(['id' => 112], ['name' => 'admin_users_impersonate', 'section_group_id' => 100, 'caption' => 'users impersonate (login by users)']);
        Section::updateOrCreate(['id' => 113], ['name' => 'admin_become_instructors_list', 'section_group_id' => 100, 'caption' => 'Lists of requests for become instructors']);
        Section::updateOrCreate(['id' => 114], ['name' => 'admin_become_instructors_reject', 'section_group_id' => 100, 'caption' => 'Reject requests for become instructors']);
        Section::updateOrCreate(['id' => 115], ['name' => 'admin_become_instructors_delete', 'section_group_id' => 100, 'caption' => 'Delete requests for become instructors']);
        Section::updateOrCreate(['id' => 116], ['name' => 'admin_update_user_registration_package', 'section_group_id' => 100, 'caption' => 'Edit user registration package']);
        Section::updateOrCreate(['id' => 117], ['name' => 'admin_update_user_meeting_settings', 'section_group_id' => 100, 'caption' => 'Edit user meeting settings']);

        // Webinar 150 - 199
        Section::updateOrCreate(['id' => 150], ['name' => 'admin_webinars', 'caption' => trans('/admin/pages/webinars.admin_webinars')]);
        Section::updateOrCreate(['id' => 151], ['name' => 'admin_webinars_list', 'section_group_id' => 150, 'caption' => trans('/admin/pages/webinars.admin_webinars_list')]);
        Section::updateOrCreate(['id' => 152], ['name' => 'admin_webinars_create', 'section_group_id' => 150, 'caption' => trans('/admin/pages/webinars.admin_webinars_create')]);
        Section::updateOrCreate(['id' => 153], ['name' => 'admin_webinars_edit', 'section_group_id' => 150, 'caption' => trans('/admin/pages/webinars.admin_webinars_edit')]);
        Section::updateOrCreate(['id' => 154], ['name' => 'admin_webinars_delete', 'section_group_id' => 150, 'caption' => trans('/admin/pages/webinars.admin_webinars_delete')]);
        Section::updateOrCreate(['id' => 155], ['name' => 'admin_webinars_export_excel', 'section_group_id' => 150, 'caption' => 'Export excel webinars list']);
        Section::updateOrCreate(['id' => 156], ['name' => 'admin_feature_webinars', 'section_group_id' => 150, 'caption' => 'Feature webinars list']);
        Section::updateOrCreate(['id' => 157], ['name' => 'admin_feature_webinars_create', 'section_group_id' => 150, 'caption' => 'create feature webinar']);
        Section::updateOrCreate(['id' => 158], ['name' => 'admin_feature_webinars_export_excel', 'section_group_id' => 150, 'caption' => 'Feature webinar export excel']);
        Section::updateOrCreate(['id' => 159], ['name' => 'admin_webinar_students_lists', 'section_group_id' => 150, 'caption' => 'Webinar students Lists']);
        Section::updateOrCreate(['id' => 160], ['name' => 'admin_webinar_students_delete', 'section_group_id' => 150, 'caption' => 'Webinar students delete']);
        Section::updateOrCreate(['id' => 161], ['name' => 'admin_webinar_notification_to_students', 'section_group_id' => 150, 'caption' => 'Send notification to course students']);
        Section::updateOrCreate(['id' => 162], ['name' => 'admin_webinar_statistics', 'section_group_id' => 150, 'caption' => 'Course statistics']);
        Section::updateOrCreate(['id' => 163], ['name' => 'admin_agora_history_list', 'section_group_id' => 150, 'caption' => 'Agora history lists']);
        Section::updateOrCreate(['id' => 164], ['name' => 'admin_agora_history_export', 'section_group_id' => 150, 'caption' => 'Agora history export']);
        Section::updateOrCreate(['id' => 165], ['name' => 'admin_course_question_forum_list', 'section_group_id' => 150, 'caption' => 'Forum Question Lists']);
        Section::updateOrCreate(['id' => 166], ['name' => 'admin_course_question_forum_answers', 'section_group_id' => 150, 'caption' => 'Forum Answers Lists']);


        // Categories 200 - 149
        Section::updateOrCreate(['id' => 200], ['name' => 'admin_categories', 'caption' => trans('/admin/pages/categories.admin_categories')]);
        Section::updateOrCreate(['id' => 201], ['name' => 'admin_categories_list', 'section_group_id' => 200, 'caption' => trans('/admin/pages/categories.admin_categories_list')]);
        Section::updateOrCreate(['id' => 202], ['name' => 'admin_categories_create', 'section_group_id' => 200, 'caption' => trans('/admin/pages/categories.admin_categories_create')]);
        Section::updateOrCreate(['id' => 203], ['name' => 'admin_categories_edit', 'section_group_id' => 200, 'caption' => trans('/admin/pages/categories.admin_categories_edit')]);
        Section::updateOrCreate(['id' => 204], ['name' => 'admin_categories_delete', 'section_group_id' => 200, 'caption' => trans('/admin/pages/categories.admin_categories_delete')]);
        Section::updateOrCreate(['id' => 205], ['name' => 'admin_trending_categories', 'section_group_id' => 200, 'caption' => 'Trends Categories List']);
        Section::updateOrCreate(['id' => 206], ['name' => 'admin_create_trending_categories', 'section_group_id' => 200, 'caption' => 'Create Trend Categories']);
        Section::updateOrCreate(['id' => 207], ['name' => 'admin_edit_trending_categories', 'section_group_id' => 200, 'caption' => 'Edit Trend Categories']);
        Section::updateOrCreate(['id' => 208], ['name' => 'admin_delete_trending_categories', 'section_group_id' => 200, 'caption' => 'Delete Trend Categories']);

        // tags 250 - 299
        Section::updateOrCreate(['id' => 250], ['name' => 'admin_tags', 'caption' => trans('/admin/pages/tags.admin_tags')]);
        Section::updateOrCreate(['id' => 251], ['name' => 'admin_tags_list', 'section_group_id' => 250, 'caption' => trans('/admin/pages/tags.admin_tags_list')]);
        Section::updateOrCreate(['id' => 252], ['name' => 'admin_tags_create', 'section_group_id' => 250, 'caption' => trans('/admin/pages/tags.admin_tags_create')]);
        Section::updateOrCreate(['id' => 253], ['name' => 'admin_tags_edit', 'section_group_id' => 250, 'caption' => trans('/admin/pages/tags.admin_tags_edit')]);
        Section::updateOrCreate(['id' => 254], ['name' => 'admin_tags_delete', 'section_group_id' => 250, 'caption' => trans('/admin/pages/tags.admin_tags_delete')]);

        // Filters 300 - 349
        Section::updateOrCreate(['id' => 300], ['name' => 'admin_filters', 'caption' => trans('/admin/pages/filters.admin_filters')]);
        Section::updateOrCreate(['id' => 301], ['name' => 'admin_filters_list', 'section_group_id' => 300, 'caption' => trans('/admin/pages/filters.admin_filters_list')]);
        Section::updateOrCreate(['id' => 302], ['name' => 'admin_filters_create', 'section_group_id' => 300, 'caption' => trans('/admin/pages/filters.admin_filters_create')]);
        Section::updateOrCreate(['id' => 303], ['name' => 'admin_filters_edit', 'section_group_id' => 300, 'caption' => trans('/admin/pages/filters.admin_filters_edit')]);
        Section::updateOrCreate(['id' => 304], ['name' => 'admin_filters_delete', 'section_group_id' => 300, 'caption' => trans('/admin/pages/filters.admin_filters_delete')]);

        // Quiz 350 - 399
        Section::updateOrCreate(['id' => 350], ['name' => 'admin_quizzes', 'caption' => trans('/admin/pages/quiz.admin_quizzes')]);
        Section::updateOrCreate(['id' => 351], ['name' => 'admin_quizzes_list', 'section_group_id' => 350, 'caption' => trans('/admin/pages/quiz.admin_quizzes_list')]);
        Section::updateOrCreate(['id' => 352], ['name' => 'admin_quizzes_create', 'section_group_id' => 350, 'caption' => 'Create Quiz']);
        Section::updateOrCreate(['id' => 353], ['name' => 'admin_quizzes_edit', 'section_group_id' => 350, 'caption' => 'Edit Quiz']);
        Section::updateOrCreate(['id' => 354], ['name' => 'admin_quizzes_delete', 'section_group_id' => 350, 'caption' => 'Delete Quiz']);
        Section::updateOrCreate(['id' => 355], ['name' => 'admin_quizzes_results', 'section_group_id' => 350, 'caption' => 'Quizzes results']);
        Section::updateOrCreate(['id' => 356], ['name' => 'admin_quizzes_results_delete', 'section_group_id' => 350, 'caption' => 'Quizzes results delete']);
        Section::updateOrCreate(['id' => 357], ['name' => 'admin_quizzes_lists_excel', 'section_group_id' => 350, 'caption' => 'Quizzes export excel']);

        // QuizResult 400 - 449
        Section::updateOrCreate(['id' => 400], ['name' => 'admin_quiz_result', 'caption' => trans('/admin/pages/quizResults.admin_quiz_result')]);
        Section::updateOrCreate(['id' => 401], ['name' => 'admin_quiz_result_list', 'section_group_id' => 400, 'caption' => trans('/admin/pages/quizResults.admin_quiz_result_list')]);
        Section::updateOrCreate(['id' => 402], ['name' => 'admin_quiz_result_create', 'section_group_id' => 400, 'caption' => trans('/admin/pages/quizResults.admin_quiz_result_create')]);
        Section::updateOrCreate(['id' => 403], ['name' => 'admin_quiz_result_edit', 'section_group_id' => 400, 'caption' => trans('/admin/pages/quizResults.admin_quiz_result_edit')]);
        Section::updateOrCreate(['id' => 404], ['name' => 'admin_quiz_result_delete', 'section_group_id' => 400, 'caption' => trans('/admin/pages/quizResults.admin_quiz_result_delete')]);
        Section::updateOrCreate(['id' => 405], ['name' => 'admin_quiz_result_export_excel', 'section_group_id' => 400, 'caption' => 'quiz result export excel']);

        // Certificate 450 - 499
        Section::updateOrCreate(['id' => 450], ['name' => 'admin_certificate', 'caption' => trans('/admin/pages/certificates.admin_certificate')]);
        Section::updateOrCreate(['id' => 451], ['name' => 'admin_certificate_list', 'section_group_id' => 450, 'caption' => trans('/admin/pages/certificates.admin_certificate_list')]);
        Section::updateOrCreate(['id' => 452], ['name' => 'admin_certificate_create', 'section_group_id' => 450, 'caption' => trans('/admin/pages/certificates.admin_certificate_create')]);
        Section::updateOrCreate(['id' => 453], ['name' => 'admin_certificate_edit', 'section_group_id' => 450, 'caption' => trans('/admin/pages/certificates.admin_certificate_edit')]);
        Section::updateOrCreate(['id' => 454], ['name' => 'admin_certificate_delete', 'section_group_id' => 450, 'caption' => trans('/admin/pages/certificates.admin_certificate_delete')]);
        Section::updateOrCreate(['id' => 455], ['name' => 'admin_certificate_template_list', 'section_group_id' => 450, 'caption' => 'Certificate template lists']);
        Section::updateOrCreate(['id' => 456], ['name' => 'admin_certificate_template_create', 'section_group_id' => 450, 'caption' => 'Certificate template create']);
        Section::updateOrCreate(['id' => 457], ['name' => 'admin_certificate_template_edit', 'section_group_id' => 450, 'caption' => 'Certificate template edit']);
        Section::updateOrCreate(['id' => 458], ['name' => 'admin_certificate_template_delete', 'section_group_id' => 450, 'caption' => 'Certificate template delete']);
        Section::updateOrCreate(['id' => 459], ['name' => 'admin_certificate_export_excel', 'section_group_id' => 450, 'caption' => 'Certificates export excel']);
        Section::updateOrCreate(['id' => 460], ['name' => 'admin_course_certificate_list', 'section_group_id' => 450, 'caption' => 'Course Competition Certificates']);

        // Discounts 500 - 549
        Section::updateOrCreate(['id' => 500], ['name' => 'admin_discount_codes', 'caption' => 'Discount codes']);
        Section::updateOrCreate(['id' => 501], ['name' => 'admin_discount_codes_list', 'section_group_id' => 500, 'caption' => 'Discount codes list']);
        Section::updateOrCreate(['id' => 502], ['name' => 'admin_discount_codes_create', 'section_group_id' => 500, 'caption' => 'Discount codes create']);
        Section::updateOrCreate(['id' => 503], ['name' => 'admin_discount_codes_edit', 'section_group_id' => 500, 'caption' => 'Discount codes edit']);
        Section::updateOrCreate(['id' => 504], ['name' => 'admin_discount_codes_delete', 'section_group_id' => 500, 'caption' => 'Discount codes delete']);
        Section::updateOrCreate(['id' => 505], ['name' => 'admin_discount_codes_export', 'section_group_id' => 500, 'caption' => 'Discount codes export excel']);

        // Groups 550 - 599
        Section::updateOrCreate(['id' => 550], ['name' => 'admin_group', 'caption' => trans('/admin/pages/groups.admin_group')]);
        Section::updateOrCreate(['id' => 551], ['name' => 'admin_group_list', 'section_group_id' => 550, 'caption' => trans('/admin/pages/groups.admin_group_list')]);
        Section::updateOrCreate(['id' => 552], ['name' => 'admin_group_create', 'section_group_id' => 550, 'caption' => trans('/admin/pages/groups.admin_group_create')]);
        Section::updateOrCreate(['id' => 553], ['name' => 'admin_group_edit', 'section_group_id' => 550, 'caption' => trans('/admin/pages/groups.admin_group_edit')]);
        Section::updateOrCreate(['id' => 554], ['name' => 'admin_group_delete', 'section_group_id' => 550, 'caption' => trans('/admin/pages/groups.admin_group_delete')]);
        Section::updateOrCreate(['id' => 555], ['name' => 'admin_update_group_registration_package', 'section_group_id' => 550, 'caption' => 'Update group registration package']);

        // Payment Channel 600 - 649
        Section::updateOrCreate(['id' => 600], ['name' => 'admin_payment_channel', 'caption' => trans('/admin/pages/paymentChannels.admin_payment_channel')]);
        Section::updateOrCreate(['id' => 601], ['name' => 'admin_payment_channel_list', 'section_group_id' => 600, 'caption' => trans('/admin/pages/paymentChannels.admin_payment_channel_list')]);
        Section::updateOrCreate(['id' => 602], ['name' => 'admin_payment_channel_toggle_status', 'section_group_id' => 600, 'caption' => 'active or inactive channel']);
        Section::updateOrCreate(['id' => 603], ['name' => 'admin_payment_channel_edit', 'section_group_id' => 600, 'caption' => trans('/admin/pages/paymentChannels.admin_payment_channel_edit')]);

        // Setting 650 - 699
        Section::updateOrCreate(['id' => 650], ['name' => 'admin_settings', 'caption' => 'settings']);
        Section::updateOrCreate(['id' => 651], ['name' => 'admin_settings_general', 'section_group_id' => 650, 'caption' => 'General settings']);
        Section::updateOrCreate(['id' => 652], ['name' => 'admin_settings_financial', 'section_group_id' => 650, 'caption' => 'Financial settings']);
        Section::updateOrCreate(['id' => 653], ['name' => 'admin_settings_personalization', 'section_group_id' => 650, 'caption' => 'Personalization settings']);
        Section::updateOrCreate(['id' => 654], ['name' => 'admin_settings_notifications', 'section_group_id' => 650, 'caption' => 'Notifications settings']);
        Section::updateOrCreate(['id' => 655], ['name' => 'admin_settings_seo', 'section_group_id' => 650, 'caption' => 'Seo settings']);
        Section::updateOrCreate(['id' => 656], ['name' => 'admin_settings_customization', 'section_group_id' => 650, 'caption' => 'Customization settings']);


        // blog 700 - 749
        Section::updateOrCreate(['id' => 700], ['name' => 'admin_blog', 'caption' => 'Blog']);
        Section::updateOrCreate(['id' => 701], ['name' => 'admin_blog_lists', 'section_group_id' => 700, 'caption' => 'Blog lists']);
        Section::updateOrCreate(['id' => 702], ['name' => 'admin_blog_create', 'section_group_id' => 700, 'caption' => 'Blog create']);
        Section::updateOrCreate(['id' => 703], ['name' => 'admin_blog_edit', 'section_group_id' => 700, 'caption' => 'Blog edit']);
        Section::updateOrCreate(['id' => 704], ['name' => 'admin_blog_delete', 'section_group_id' => 700, 'caption' => 'Blog delete']);
        Section::updateOrCreate(['id' => 705], ['name' => 'admin_blog_categories', 'section_group_id' => 700, 'caption' => 'Blog categories list']);
        Section::updateOrCreate(['id' => 706], ['name' => 'admin_blog_categories_create', 'section_group_id' => 700, 'caption' => 'Blog categories create']);
        Section::updateOrCreate(['id' => 707], ['name' => 'admin_blog_categories_edit', 'section_group_id' => 700, 'caption' => 'Blog categories edit']);
        Section::updateOrCreate(['id' => 708], ['name' => 'admin_blog_categories_delete', 'section_group_id' => 700, 'caption' => 'Blog categories delete']);

        // sales 750 - 799
        Section::updateOrCreate(['id' => 750], ['name' => 'admin_sales', 'caption' => 'Sales']);
        Section::updateOrCreate(['id' => 751], ['name' => 'admin_sales_list', 'section_group_id' => 750, 'caption' => 'Sales List']);
        Section::updateOrCreate(['id' => 752], ['name' => 'admin_sales_refund', 'section_group_id' => 750, 'caption' => 'Sales Refund']);
        Section::updateOrCreate(['id' => 753], ['name' => 'admin_sales_invoice', 'section_group_id' => 750, 'caption' => 'Sales invoice']);
        Section::updateOrCreate(['id' => 754], ['name' => 'admin_sales_export', 'section_group_id' => 750, 'caption' => 'Sales Export Excel']);

        // documents 800 - 849
        Section::updateOrCreate(['id' => 800], ['name' => 'admin_documents', 'caption' => 'Balances']);
        Section::updateOrCreate(['id' => 801], ['name' => 'admin_documents_list', 'section_group_id' => 800, 'caption' => 'Balances List']);
        Section::updateOrCreate(['id' => 802], ['name' => 'admin_documents_create', 'section_group_id' => 800, 'caption' => 'Balances Create']);
        Section::updateOrCreate(['id' => 803], ['name' => 'admin_documents_print', 'section_group_id' => 800, 'caption' => 'Balances print']);

        // payouts 850 - 899
        Section::updateOrCreate(['id' => 850], ['name' => 'admin_payouts', 'caption' => 'Payout']);
        Section::updateOrCreate(['id' => 851], ['name' => 'admin_payouts_list', 'section_group_id' => 850, 'caption' => 'Payout List']);
        Section::updateOrCreate(['id' => 852], ['name' => 'admin_payouts_reject', 'section_group_id' => 850, 'caption' => 'Payout Reject']);
        Section::updateOrCreate(['id' => 853], ['name' => 'admin_payouts_payout', 'section_group_id' => 850, 'caption' => 'Payout accept']);
        Section::updateOrCreate(['id' => 854], ['name' => 'admin_payouts_export_excel', 'section_group_id' => 850, 'caption' => 'Payout export excel']);

        // offline_payments 900 - 949
        Section::updateOrCreate(['id' => 900], ['name' => 'admin_offline_payments', 'caption' => 'Offline Payments']);
        Section::updateOrCreate(['id' => 901], ['name' => 'admin_offline_payments_list', 'section_group_id' => 900, 'caption' => 'Offline Payments List']);
        Section::updateOrCreate(['id' => 902], ['name' => 'admin_offline_payments_reject', 'section_group_id' => 900, 'caption' => 'Offline Payments Reject']);
        Section::updateOrCreate(['id' => 903], ['name' => 'admin_offline_payments_approved', 'section_group_id' => 900, 'caption' => 'Offline Payments Approved']);
        Section::updateOrCreate(['id' => 904], ['name' => 'admin_offline_payments_export_excel', 'section_group_id' => 900, 'caption' => 'Offline Payments export excel']);


        // supports 950 - 999
        Section::updateOrCreate(['id' => 950], ['name' => 'admin_supports', 'caption' => 'Supports']);
        Section::updateOrCreate(['id' => 951], ['name' => 'admin_supports_list', 'section_group_id' => 950, 'caption' => 'Supports List']);
        Section::updateOrCreate(['id' => 952], ['name' => 'admin_support_send', 'section_group_id' => 950, 'caption' => 'Send Support']);
        Section::updateOrCreate(['id' => 953], ['name' => 'admin_supports_reply', 'section_group_id' => 950, 'caption' => 'Supports reply']);
        Section::updateOrCreate(['id' => 954], ['name' => 'admin_supports_delete', 'section_group_id' => 950, 'caption' => 'Supports delete']);
        Section::updateOrCreate(['id' => 955], ['name' => 'admin_support_departments', 'section_group_id' => 950, 'caption' => 'Support departments lists']);
        Section::updateOrCreate(['id' => 956], ['name' => 'admin_support_department_create', 'section_group_id' => 950, 'caption' => 'Create support department']);
        Section::updateOrCreate(['id' => 957], ['name' => 'admin_support_departments_edit', 'section_group_id' => 950, 'caption' => 'Edit support departments']);
        Section::updateOrCreate(['id' => 958], ['name' => 'admin_support_departments_delete', 'section_group_id' => 950, 'caption' => 'Delete support department']);
        Section::updateOrCreate(['id' => 959], ['name' => 'admin_support_course_conversations', 'section_group_id' => 950, 'caption' => 'Course conversations']);

        // Subscribes 1000 - 1049
        Section::updateOrCreate(['id' => 1000], ['name' => 'admin_subscribe', 'caption' => 'Subscribes']);
        Section::updateOrCreate(['id' => 1001], ['name' => 'admin_subscribe_list', 'section_group_id' => 1000, 'caption' => 'Subscribes list']);
        Section::updateOrCreate(['id' => 1002], ['name' => 'admin_subscribe_create', 'section_group_id' => 1000, 'caption' => 'Subscribes create']);
        Section::updateOrCreate(['id' => 1003], ['name' => 'admin_subscribe_edit', 'section_group_id' => 1000, 'caption' => 'Subscribes edit']);
        Section::updateOrCreate(['id' => 1004], ['name' => 'admin_subscribe_delete', 'section_group_id' => 1000, 'caption' => 'Subscribes delete']);


        // Notifications 1050 - 1074
        Section::updateOrCreate(['id' => 1050], ['name' => 'admin_notifications', 'caption' => 'Notifications']);
        Section::updateOrCreate(['id' => 1051], ['name' => 'admin_notifications_list', 'section_group_id' => 1050, 'caption' => 'Notifications list']);
        Section::updateOrCreate(['id' => 1052], ['name' => 'admin_notifications_send', 'section_group_id' => 1050, 'caption' => 'Send Notifications']);
        Section::updateOrCreate(['id' => 1053], ['name' => 'admin_notifications_edit', 'section_group_id' => 1050, 'caption' => 'Edit and details Notifications']);
        Section::updateOrCreate(['id' => 1054], ['name' => 'admin_notifications_delete', 'section_group_id' => 1050, 'caption' => 'Delete Notifications']);
        Section::updateOrCreate(['id' => 1055], ['name' => 'admin_notifications_markAllRead', 'section_group_id' => 1050, 'caption' => 'Mark All Read Notifications']);
        Section::updateOrCreate(['id' => 1056], ['name' => 'admin_notifications_templates', 'section_group_id' => 1050, 'caption' => 'Notifications templates']);
        Section::updateOrCreate(['id' => 1057], ['name' => 'admin_notifications_template_create', 'section_group_id' => 1050, 'caption' => 'Create notification template']);
        Section::updateOrCreate(['id' => 1058], ['name' => 'admin_notifications_template_edit', 'section_group_id' => 1050, 'caption' => 'Edit notification template']);
        Section::updateOrCreate(['id' => 1059], ['name' => 'admin_notifications_template_delete', 'section_group_id' => 1050, 'caption' => 'Delete notification template']);
        Section::updateOrCreate(['id' => 1060], ['name' => 'admin_notifications_posted_list', 'section_group_id' => 1050, 'caption' => 'Notifications Posted list']);

        // Noticeboards 1075 - 1099
        Section::updateOrCreate(['id' => 1075], ['name' => 'admin_noticeboards', 'caption' => 'Noticeboards']);
        Section::updateOrCreate(['id' => 1076], ['name' => 'admin_noticeboards_list', 'section_group_id' => 1075, 'caption' => 'Noticeboards list']);
        Section::updateOrCreate(['id' => 1077], ['name' => 'admin_noticeboards_send', 'section_group_id' => 1075, 'caption' => 'Send Noticeboards']);
        Section::updateOrCreate(['id' => 1078], ['name' => 'admin_noticeboards_edit', 'section_group_id' => 1075, 'caption' => 'Edit Noticeboards']);
        Section::updateOrCreate(['id' => 1079], ['name' => 'admin_noticeboards_delete', 'section_group_id' => 1075, 'caption' => 'Delete Noticeboards']);
        Section::updateOrCreate(['id' => 1080], ['name' => 'admin_course_noticeboards_list', 'section_group_id' => 1075, 'caption' => 'Course Noticeboards list']);
        Section::updateOrCreate(['id' => 1081], ['name' => 'admin_course_noticeboards_send', 'section_group_id' => 1075, 'caption' => 'Send Course Noticeboards']);
        Section::updateOrCreate(['id' => 1082], ['name' => 'admin_course_noticeboards_edit', 'section_group_id' => 1075, 'caption' => 'Edit Course Noticeboards']);
        Section::updateOrCreate(['id' => 1083], ['name' => 'admin_course_noticeboards_delete', 'section_group_id' => 1075, 'caption' => 'Delete Course Noticeboards']);


        // promotions 1100 - 1149
        Section::updateOrCreate(['id' => 1100], ['name' => 'admin_promotion', 'caption' => 'Promotions']);
        Section::updateOrCreate(['id' => 1101], ['name' => 'admin_promotion_list', 'section_group_id' => 1100, 'caption' => 'Promotions list']);
        Section::updateOrCreate(['id' => 1102], ['name' => 'admin_promotion_create', 'section_group_id' => 1100, 'caption' => 'Promotion create']);
        Section::updateOrCreate(['id' => 1103], ['name' => 'admin_promotion_edit', 'section_group_id' => 1100, 'caption' => 'Promotion edit']);
        Section::updateOrCreate(['id' => 1104], ['name' => 'admin_promotion_delete', 'section_group_id' => 1100, 'caption' => 'Promotion delete']);


        // testimonials 1150 - 1199
        Section::updateOrCreate(['id' => 1150], ['name' => 'admin_testimonials', 'caption' => 'testimonials']);
        Section::updateOrCreate(['id' => 1151], ['name' => 'admin_testimonials_list', 'section_group_id' => 1150, 'caption' => 'testimonials list']);
        Section::updateOrCreate(['id' => 1152], ['name' => 'admin_testimonials_create', 'section_group_id' => 1150, 'caption' => 'testimonials create']);
        Section::updateOrCreate(['id' => 1153], ['name' => 'admin_testimonials_edit', 'section_group_id' => 1150, 'caption' => 'testimonials edit']);
        Section::updateOrCreate(['id' => 1154], ['name' => 'admin_testimonials_delete', 'section_group_id' => 1150, 'caption' => 'testimonials delete']);

        // admin_advertising 1200 - 1229
        Section::updateOrCreate(['id' => 1200], ['name' => 'admin_advertising', 'caption' => 'advertising']);
        Section::updateOrCreate(['id' => 1201], ['name' => 'admin_advertising_banners', 'section_group_id' => 1200, 'caption' => 'advertising banners list']);
        Section::updateOrCreate(['id' => 1202], ['name' => 'admin_advertising_banners_create', 'section_group_id' => 1200, 'caption' => 'create advertising banner']);
        Section::updateOrCreate(['id' => 1203], ['name' => 'admin_advertising_banners_edit', 'section_group_id' => 1200, 'caption' => 'edit advertising banner']);
        Section::updateOrCreate(['id' => 1204], ['name' => 'admin_advertising_banners_delete', 'section_group_id' => 1200, 'caption' => 'delete advertising banner']);


        // admin newsletters 1230 - 1249
        Section::updateOrCreate(['id' => 1230], ['name' => 'admin_newsletters', 'caption' => 'Newsletters']);
        Section::updateOrCreate(['id' => 1231], ['name' => 'admin_newsletters_lists', 'section_group_id' => 1230, 'caption' => 'Newsletters lists']);
        Section::updateOrCreate(['id' => 1232], ['name' => 'admin_newsletters_send', 'section_group_id' => 1230, 'caption' => 'Send Newsletters']);
        Section::updateOrCreate(['id' => 1233], ['name' => 'admin_newsletters_history', 'section_group_id' => 1230, 'caption' => 'Newsletters histories']);
        Section::updateOrCreate(['id' => 1234], ['name' => 'admin_newsletters_delete', 'section_group_id' => 1230, 'caption' => 'Delete newsletters item']);
        Section::updateOrCreate(['id' => 1235], ['name' => 'admin_newsletters_export_excel', 'section_group_id' => 1230, 'caption' => 'Export excel newsletters item']);

        // contact 1250 - 1299
        Section::updateOrCreate(['id' => 1250], ['name' => 'admin_contacts', 'caption' => 'Contacts']);
        Section::updateOrCreate(['id' => 1251], ['name' => 'admin_contacts_lists', 'section_group_id' => 1250, 'caption' => 'Contacts lists']);
        Section::updateOrCreate(['id' => 1252], ['name' => 'admin_contacts_reply', 'section_group_id' => 1250, 'caption' => 'Contacts reply']);
        Section::updateOrCreate(['id' => 1253], ['name' => 'admin_contacts_delete', 'section_group_id' => 1250, 'caption' => 'Contacts delete']);

        // special offers 1300 - 1349
        Section::updateOrCreate(['id' => 1300], ['name' => 'admin_product_discount', 'caption' => 'product discount']);
        Section::updateOrCreate(['id' => 1301], ['name' => 'admin_product_discount_list', 'section_group_id' => 1300, 'caption' => 'product discount list']);
        Section::updateOrCreate(['id' => 1302], ['name' => 'admin_product_discount_create', 'section_group_id' => 1300, 'caption' => 'create product discount']);
        Section::updateOrCreate(['id' => 1303], ['name' => 'admin_product_discount_edit', 'section_group_id' => 1300, 'caption' => 'edit product discount']);
        Section::updateOrCreate(['id' => 1304], ['name' => 'admin_product_discount_delete', 'section_group_id' => 1300, 'caption' => 'delete product discount']);
        Section::updateOrCreate(['id' => 1305], ['name' => 'admin_product_discount_export', 'section_group_id' => 1300, 'caption' => 'delete product export excel']);

        // pages 1350 - 1399
        Section::updateOrCreate(['id' => 1350], ['name' => 'admin_pages', 'caption' => 'pages']);
        Section::updateOrCreate(['id' => 1351], ['name' => 'admin_pages_list', 'section_group_id' => 1350, 'caption' => 'pages list']);
        Section::updateOrCreate(['id' => 1352], ['name' => 'admin_pages_create', 'section_group_id' => 1350, 'caption' => 'pages create']);
        Section::updateOrCreate(['id' => 1353], ['name' => 'admin_pages_edit', 'section_group_id' => 1350, 'caption' => 'pages edit']);
        Section::updateOrCreate(['id' => 1354], ['name' => 'admin_pages_toggle', 'section_group_id' => 1350, 'caption' => 'pages toggle publish/draft']);
        Section::updateOrCreate(['id' => 1355], ['name' => 'admin_pages_delete', 'section_group_id' => 1350, 'caption' => 'pages delete']);

        // Comments 1400 - 1449
        Section::updateOrCreate(['id' => 1400], ['name' => 'admin_comments', 'caption' => 'Comments']);
        Section::updateOrCreate(['id' => 1401], ['name' => 'admin_webinar_comments', 'section_group_id' => 1400, 'caption' => 'Classes comments']);
        Section::updateOrCreate(['id' => 1402], ['name' => 'admin_webinar_comments_edit', 'section_group_id' => 1400, 'caption' => 'Classes comments edit']);
        Section::updateOrCreate(['id' => 1403], ['name' => 'admin_webinar_comments_reply', 'section_group_id' => 1400, 'caption' => 'Classes comments reply']);
        Section::updateOrCreate(['id' => 1404], ['name' => 'admin_webinar_comments_delete', 'section_group_id' => 1400, 'caption' => 'Classes comments delete']);
        Section::updateOrCreate(['id' => 1405], ['name' => 'admin_webinar_comments_status', 'section_group_id' => 1400, 'caption' => 'Classes comments status (active or pending)']);
        Section::updateOrCreate(['id' => 1406], ['name' => 'admin_blog_comments', 'section_group_id' => 1400, 'caption' => 'Blog comments']);
        Section::updateOrCreate(['id' => 1407], ['name' => 'admin_blog_comments_edit', 'section_group_id' => 1400, 'caption' => 'Blog comments edit']);
        Section::updateOrCreate(['id' => 1408], ['name' => 'admin_blog_comments_reply', 'section_group_id' => 1400, 'caption' => 'Blog comments reply']);
        Section::updateOrCreate(['id' => 1409], ['name' => 'admin_blog_comments_delete', 'section_group_id' => 1400, 'caption' => 'Blog comments delete']);
        Section::updateOrCreate(['id' => 1410], ['name' => 'admin_blog_comments_status', 'section_group_id' => 1400, 'caption' => 'Blog comments status (active or pending)']);
        Section::updateOrCreate(['id' => 1411], ['name' => 'admin_product_comments', 'section_group_id' => 1400, 'caption' => 'Product comments']);
        Section::updateOrCreate(['id' => 1412], ['name' => 'admin_product_comments_edit', 'section_group_id' => 1400, 'caption' => 'Product comments edit']);
        Section::updateOrCreate(['id' => 1413], ['name' => 'admin_product_comments_reply', 'section_group_id' => 1400, 'caption' => 'Product comments reply']);
        Section::updateOrCreate(['id' => 1414], ['name' => 'admin_product_comments_delete', 'section_group_id' => 1400, 'caption' => 'Product comments delete']);
        Section::updateOrCreate(['id' => 1415], ['name' => 'admin_product_comments_status', 'section_group_id' => 1400, 'caption' => 'Product comments status (active or pending)']);
        Section::updateOrCreate(['id' => 1416], ['name' => 'admin_bundle_comments', 'section_group_id' => 1400, 'caption' => 'Bundle comments']);
        Section::updateOrCreate(['id' => 1417], ['name' => 'admin_bundle_comments_edit', 'section_group_id' => 1400, 'caption' => 'Bundle comments edit']);
        Section::updateOrCreate(['id' => 1418], ['name' => 'admin_bundle_comments_reply', 'section_group_id' => 1400, 'caption' => 'Bundle comments reply']);
        Section::updateOrCreate(['id' => 1419], ['name' => 'admin_bundle_comments_delete', 'section_group_id' => 1400, 'caption' => 'Bundle comments delete']);
        Section::updateOrCreate(['id' => 1420], ['name' => 'admin_bundle_comments_status', 'section_group_id' => 1400, 'caption' => 'Bundle comments status (active or pending)']);

        // Reports 1450 - 1499
        Section::updateOrCreate(['id' => 1450], ['name' => 'admin_reports', 'caption' => 'Reports']);
        Section::updateOrCreate(['id' => 1451], ['name' => 'admin_webinar_reports', 'section_group_id' => 1450, 'caption' => 'Classes reports']);
        Section::updateOrCreate(['id' => 1452], ['name' => 'admin_webinar_comments_reports', 'section_group_id' => 1450, 'caption' => 'Classes Comments reports']);
        Section::updateOrCreate(['id' => 1453], ['name' => 'admin_webinar_reports_delete', 'section_group_id' => 1450, 'caption' => 'Classes reports delete']);
        Section::updateOrCreate(['id' => 1454], ['name' => 'admin_blog_comments_reports', 'section_group_id' => 1450, 'caption' => 'Blog Comments reports']);
        Section::updateOrCreate(['id' => 1455], ['name' => 'admin_report_reasons', 'section_group_id' => 1450, 'caption' => 'Reports reasons']);
        Section::updateOrCreate(['id' => 1456], ['name' => 'admin_product_comments_reports', 'section_group_id' => 1450, 'caption' => 'Products Comments reports']);
        Section::updateOrCreate(['id' => 1457], ['name' => 'admin_forum_topic_post_reports', 'section_group_id' => 1450, 'caption' => 'Forum Topic Posts Reports']);

        // Additional Pages 1500 - 1549
        Section::updateOrCreate(['id' => 1500], ['name' => 'admin_additional_pages', 'caption' => 'Additional Pages']);
        Section::updateOrCreate(['id' => 1501], ['name' => 'admin_additional_pages_404', 'section_group_id' => 1500, 'caption' => '404 error page settings']);
        Section::updateOrCreate(['id' => 1502], ['name' => 'admin_additional_pages_contact_us', 'section_group_id' => 1500, 'caption' => 'Contact page settings']);
        Section::updateOrCreate(['id' => 1503], ['name' => 'admin_additional_pages_footer', 'section_group_id' => 1500, 'caption' => 'Footer settings']);
        Section::updateOrCreate(['id' => 1504], ['name' => 'admin_additional_pages_navbar_links', 'section_group_id' => 1500, 'caption' => 'Top Navbar links settings']);

        // appointments Pages 1550 - 1599
        Section::updateOrCreate(['id' => 1550], ['name' => 'admin_appointments', 'caption' => 'Appointments']);
        Section::updateOrCreate(['id' => 1551], ['name' => 'admin_appointments_lists', 'section_group_id' => 1550, 'caption' => 'Appointments lists']);
        Section::updateOrCreate(['id' => 1552], ['name' => 'admin_appointments_join', 'section_group_id' => 1550, 'caption' => 'Appointments join']);
        Section::updateOrCreate(['id' => 1553], ['name' => 'admin_appointments_send_reminder', 'section_group_id' => 1550, 'caption' => 'Appointments send reminder']);
        Section::updateOrCreate(['id' => 1554], ['name' => 'admin_appointments_cancel', 'section_group_id' => 1550, 'caption' => 'Appointments cancel']);

        // reviews Pages 1600 - 1649
        Section::updateOrCreate(['id' => 1600], ['name' => 'admin_reviews', 'caption' => 'Reviews']);
        Section::updateOrCreate(['id' => 1601], ['name' => 'admin_reviews_lists', 'section_group_id' => 1600, 'caption' => 'Reviews lists']);
        Section::updateOrCreate(['id' => 1602], ['name' => 'admin_reviews_status_toggle', 'section_group_id' => 1600, 'caption' => 'Reviews status toggle (publish or hidden)']);
        Section::updateOrCreate(['id' => 1603], ['name' => 'admin_reviews_detail_show', 'section_group_id' => 1600, 'caption' => 'Review details page']);
        Section::updateOrCreate(['id' => 1604], ['name' => 'admin_reviews_delete', 'section_group_id' => 1600, 'caption' => 'Review delete']);

        // consultants Pages 1650 - 1674
        Section::updateOrCreate(['id' => 1650], ['name' => 'admin_consultants', 'caption' => 'Consultants']);
        Section::updateOrCreate(['id' => 1651], ['name' => 'admin_consultants_lists', 'section_group_id' => 1650, 'caption' => 'Consultants lists']);
        Section::updateOrCreate(['id' => 1652], ['name' => 'admin_consultants_export_excel', 'section_group_id' => 1650, 'caption' => 'Consultants export excel']);

        // Referrals 1675 - 1699
        Section::updateOrCreate(['id' => 1675], ['name' => 'admin_referrals', 'caption' => 'Referrals']);
        Section::updateOrCreate(['id' => 1676], ['name' => 'admin_referrals_history', 'section_group_id' => 1675, 'caption' => 'Referrals History']);
        Section::updateOrCreate(['id' => 1677], ['name' => 'admin_referrals_users', 'section_group_id' => 1675, 'caption' => 'Referrals users']);
        Section::updateOrCreate(['id' => 1678], ['name' => 'admin_referrals_export', 'section_group_id' => 1675, 'caption' => 'Export Referrals']);

        // regions 1725 - 1749
        Section::updateOrCreate(['id' => 1725], ['name' => 'admin_regions', 'caption' => 'Regions']);
        Section::updateOrCreate(['id' => 1726], ['name' => 'admin_regions_countries', 'section_group_id' => 1725, 'caption' => 'countries lists']);
        Section::updateOrCreate(['id' => 1727], ['name' => 'admin_regions_provinces', 'section_group_id' => 1725, 'caption' => 'provinces lists']);
        Section::updateOrCreate(['id' => 1728], ['name' => 'admin_regions_cities', 'section_group_id' => 1725, 'caption' => 'cities lists']);
        Section::updateOrCreate(['id' => 1729], ['name' => 'admin_regions_districts', 'section_group_id' => 1725, 'caption' => 'districts lists']);
        Section::updateOrCreate(['id' => 1730], ['name' => 'admin_regions_create', 'section_group_id' => 1725, 'caption' => 'create item']);
        Section::updateOrCreate(['id' => 1731], ['name' => 'admin_regions_edit', 'section_group_id' => 1725, 'caption' => 'edit item']);
        Section::updateOrCreate(['id' => 1732], ['name' => 'admin_regions_delete', 'section_group_id' => 1725, 'caption' => 'delete item']);

        // Rewards 1750 - 1774
        Section::updateOrCreate(['id' => 1750], ['name' => 'admin_rewards', 'caption' => 'Rewards']);
        Section::updateOrCreate(['id' => 1751], ['name' => 'admin_rewards_history', 'section_group_id' => 1750, 'caption' => 'Rewards history']);
        Section::updateOrCreate(['id' => 1752], ['name' => 'admin_rewards_settings', 'section_group_id' => 1750, 'caption' => 'Rewards settings']);
        Section::updateOrCreate(['id' => 1753], ['name' => 'admin_rewards_items', 'section_group_id' => 1750, 'caption' => 'Rewards items']);
        Section::updateOrCreate(['id' => 1754], ['name' => 'admin_rewards_item_delete', 'section_group_id' => 1750, 'caption' => 'Reward item delete']);

        // Registration packages 1775 - 1799
        Section::updateOrCreate(['id' => 1775], ['name' => 'admin_registration_packages', 'caption' => 'Registration packages']);
        Section::updateOrCreate(['id' => 1776], ['name' => 'admin_registration_packages_lists', 'section_group_id' => 1775, 'caption' => 'packages lists']);
        Section::updateOrCreate(['id' => 1777], ['name' => 'admin_registration_packages_new', 'section_group_id' => 1775, 'caption' => 'New package']);
        Section::updateOrCreate(['id' => 1778], ['name' => 'admin_registration_packages_edit', 'section_group_id' => 1775, 'caption' => 'Edit package']);
        Section::updateOrCreate(['id' => 1779], ['name' => 'admin_registration_packages_delete', 'section_group_id' => 1775, 'caption' => 'Delete package']);
        Section::updateOrCreate(['id' => 1780], ['name' => 'admin_registration_packages_reports', 'section_group_id' => 1775, 'caption' => 'Reports']);
        Section::updateOrCreate(['id' => 1781], ['name' => 'admin_registration_packages_settings', 'section_group_id' => 1775, 'caption' => 'Settings']);

        // Store Products 1800 - 1850
        Section::updateOrCreate(['id' => 1800], ['name' => 'admin_store', 'caption' => 'Store']);
        Section::updateOrCreate(['id' => 1801], ['name' => 'admin_store_products', 'section_group_id' => 1800, 'caption' => 'Products lists']);
        Section::updateOrCreate(['id' => 1802], ['name' => 'admin_store_new_product', 'section_group_id' => 1800, 'caption' => 'Create New Product']);
        Section::updateOrCreate(['id' => 1803], ['name' => 'admin_store_edit_product', 'section_group_id' => 1800, 'caption' => 'Edit Product']);
        Section::updateOrCreate(['id' => 1804], ['name' => 'admin_store_delete_product', 'section_group_id' => 1800, 'caption' => 'Delete Product']);
        Section::updateOrCreate(['id' => 1805], ['name' => 'admin_store_export_products', 'section_group_id' => 1800, 'caption' => 'Export excel Products']);

        // store categories and filters
        Section::updateOrCreate(['id' => 1806], ['name' => 'admin_store_categories_list', 'section_group_id' => 1800, 'caption' => 'Store Categories Lists']);
        Section::updateOrCreate(['id' => 1807], ['name' => 'admin_store_categories_create', 'section_group_id' => 1800, 'caption' => 'Create Store Category']);
        Section::updateOrCreate(['id' => 1808], ['name' => 'admin_store_categories_edit', 'section_group_id' => 1800, 'caption' => 'Edit Store Category']);
        Section::updateOrCreate(['id' => 1809], ['name' => 'admin_store_categories_delete', 'section_group_id' => 1800, 'caption' => 'Delete Store Category']);
        Section::updateOrCreate(['id' => 1810], ['name' => 'admin_store_filters_list', 'section_group_id' => 1800, 'caption' => 'Store Filters Lists']);
        Section::updateOrCreate(['id' => 1811], ['name' => 'admin_store_filters_create', 'section_group_id' => 1800, 'caption' => 'Create Store Filter']);
        Section::updateOrCreate(['id' => 1812], ['name' => 'admin_store_filters_edit', 'section_group_id' => 1800, 'caption' => 'Edit Store Filter']);
        Section::updateOrCreate(['id' => 1813], ['name' => 'admin_store_filters_delete', 'section_group_id' => 1800, 'caption' => 'Delete Store Filter']);
        // specifications
        Section::updateOrCreate(['id' => 1814], ['name' => 'admin_store_specifications', 'section_group_id' => 1800, 'caption' => 'Store Specifications']);
        Section::updateOrCreate(['id' => 1815], ['name' => 'admin_store_specifications_create', 'section_group_id' => 1800, 'caption' => 'Create New Store Specification']);
        Section::updateOrCreate(['id' => 1816], ['name' => 'admin_store_specifications_edit', 'section_group_id' => 1800, 'caption' => 'Edit Store Specification']);
        Section::updateOrCreate(['id' => 1817], ['name' => 'admin_store_specifications_delete', 'section_group_id' => 1800, 'caption' => 'Delete Store Specification']);
        // discounts
        Section::updateOrCreate(['id' => 1818], ['name' => 'admin_store_discounts', 'section_group_id' => 1800, 'caption' => 'Store Discounts Lists']);
        Section::updateOrCreate(['id' => 1819], ['name' => 'admin_store_discounts_create', 'section_group_id' => 1800, 'caption' => 'Create New Store discount']);
        Section::updateOrCreate(['id' => 1820], ['name' => 'admin_store_discounts_edit', 'section_group_id' => 1800, 'caption' => 'Edit Store discount']);
        Section::updateOrCreate(['id' => 1821], ['name' => 'admin_store_discounts_delete', 'section_group_id' => 1800, 'caption' => 'Delete Store discount']);
        // orders
        Section::updateOrCreate(['id' => 1822], ['name' => 'admin_store_products_orders', 'section_group_id' => 1800, 'caption' => 'Products Orders']);
        Section::updateOrCreate(['id' => 1823], ['name' => 'admin_store_products_orders_refund', 'section_group_id' => 1800, 'caption' => 'Products Orders Refund']);
        Section::updateOrCreate(['id' => 1824], ['name' => 'admin_store_products_orders_invoice', 'section_group_id' => 1800, 'caption' => 'Products Orders View Invoice']);
        Section::updateOrCreate(['id' => 1825], ['name' => 'admin_store_products_orders_export', 'section_group_id' => 1800, 'caption' => 'Products Orders Export Excel']);
        Section::updateOrCreate(['id' => 1826], ['name' => 'admin_store_products_orders_tracking_code', 'section_group_id' => 1800, 'caption' => 'Products Orders Tracking code']);
        // reviews
        Section::updateOrCreate(['id' => 1827], ['name' => 'admin_store_products_reviews', 'section_group_id' => 1800, 'caption' => 'Reviews lists']);
        Section::updateOrCreate(['id' => 1828], ['name' => 'admin_store_products_reviews_status_toggle', 'section_group_id' => 1800, 'caption' => 'Reviews status toggle (publish or hidden)']);
        Section::updateOrCreate(['id' => 1829], ['name' => 'admin_store_products_reviews_detail_show', 'section_group_id' => 1800, 'caption' => 'Review details page']);
        Section::updateOrCreate(['id' => 1830], ['name' => 'admin_store_products_reviews_delete', 'section_group_id' => 1800, 'caption' => 'Review delete']);
        // settings
        Section::updateOrCreate(['id' => 1831], ['name' => 'admin_store_settings', 'section_group_id' => 1800, 'caption' => 'Store settings']);
        // In-house products
        Section::updateOrCreate(['id' => 1832], ['name' => 'admin_store_in_house_products', 'section_group_id' => 1800, 'caption' => 'In-house products']);
        Section::updateOrCreate(['id' => 1833], ['name' => 'admin_store_in_house_orders', 'section_group_id' => 1800, 'caption' => 'In-house Products Orders']);
        // Sellers
        Section::updateOrCreate(['id' => 1834], ['name' => 'admin_store_products_sellers', 'section_group_id' => 1800, 'caption' => 'Products Sellers']);

        // 1850 - 1874 webinar_assignments
        Section::updateOrCreate(['id' => 1850], ['name' => 'admin_webinar_assignments', 'caption' => 'Webinar assignments']);
        Section::updateOrCreate(['id' => 1851], ['name' => 'admin_webinar_assignments_lists', 'section_group_id' => 1850, 'caption' => 'Assignments lists']);
        Section::updateOrCreate(['id' => 1852], ['name' => 'admin_webinar_assignments_students', 'section_group_id' => 1850, 'caption' => 'Assignment students']);
        Section::updateOrCreate(['id' => 1853], ['name' => 'admin_webinar_assignments_conversations', 'section_group_id' => 1850, 'caption' => 'Assignment students conversations']);

        // 1875 - 1899 users_not_access_content
        Section::updateOrCreate(['id' => 1875], ['name' => 'admin_users_not_access_content', 'caption' => 'Users do not have access to the content']);
        Section::updateOrCreate(['id' => 1876], ['name' => 'admin_users_not_access_content_lists', 'section_group_id' => 1875, 'caption' => 'Users lists']);
        Section::updateOrCreate(['id' => 1877], ['name' => 'admin_users_not_access_content_toggle', 'section_group_id' => 1875, 'caption' => 'Toggle active/inactive users to view content']);

        // 1900 - 1924 Bundles
        Section::updateOrCreate(['id' => 1900], ['name' => 'admin_bundles', 'caption' => 'Bundles']);
        Section::updateOrCreate(['id' => 1901], ['name' => 'admin_bundles_list', 'section_group_id' => 1900, 'caption' => 'Bundles Lists']);
        Section::updateOrCreate(['id' => 1902], ['name' => 'admin_bundles_create', 'section_group_id' => 1900, 'caption' => 'Create new Bundle']);
        Section::updateOrCreate(['id' => 1903], ['name' => 'admin_bundles_edit', 'section_group_id' => 1900, 'caption' => 'Edit bundle']);
        Section::updateOrCreate(['id' => 1904], ['name' => 'admin_bundles_delete', 'section_group_id' => 1900, 'caption' => 'Delete bundle']);
        Section::updateOrCreate(['id' => 1905], ['name' => 'admin_bundles_export_excel', 'section_group_id' => 1900, 'caption' => 'Export excel']);

        // 1925 - 1949 Forums
        Section::updateOrCreate(['id' => 1925], ['name' => 'admin_forum', 'caption' => 'Forums']);
        Section::updateOrCreate(['id' => 1926], ['name' => 'admin_forum_list', 'section_group_id' => 1925, 'caption' => 'Forums Lists']);
        Section::updateOrCreate(['id' => 1927], ['name' => 'admin_forum_create', 'section_group_id' => 1925, 'caption' => 'Forums create']);
        Section::updateOrCreate(['id' => 1928], ['name' => 'admin_forum_edit', 'section_group_id' => 1925, 'caption' => 'Forums edit']);
        Section::updateOrCreate(['id' => 1929], ['name' => 'admin_forum_delete', 'section_group_id' => 1925, 'caption' => 'Forums delete']);
        Section::updateOrCreate(['id' => 1930], ['name' => 'admin_forum_topics_lists', 'section_group_id' => 1925, 'caption' => 'Forums topics lists']);
        Section::updateOrCreate(['id' => 1931], ['name' => 'admin_forum_topics_create', 'section_group_id' => 1925, 'caption' => 'Forums topics create']);
        Section::updateOrCreate(['id' => 1932], ['name' => 'admin_forum_topics_delete', 'section_group_id' => 1925, 'caption' => 'Forums topics delete']);
        Section::updateOrCreate(['id' => 1933], ['name' => 'admin_forum_topics_posts', 'section_group_id' => 1925, 'caption' => 'Forums topic posts']);
        Section::updateOrCreate(['id' => 1934], ['name' => 'admin_forum_topics_create_posts', 'section_group_id' => 1925, 'caption' => 'Forums topic store posts']);

        // 1950 - 1974 featured topics
        Section::updateOrCreate(['id' => 1950], ['name' => 'admin_featured_topics', 'caption' => 'Featured topics']);
        Section::updateOrCreate(['id' => 1951], ['name' => 'admin_featured_topics_list', 'section_group_id' => 1950, 'caption' => 'Featured topics Lists']);
        Section::updateOrCreate(['id' => 1952], ['name' => 'admin_featured_topics_create', 'section_group_id' => 1950, 'caption' => 'Featured topics create']);
        Section::updateOrCreate(['id' => 1953], ['name' => 'admin_featured_topics_edit', 'section_group_id' => 1950, 'caption' => 'Featured topics edit']);
        Section::updateOrCreate(['id' => 1954], ['name' => 'admin_featured_topics_delete', 'section_group_id' => 1950, 'caption' => 'Featured topics delete']);

        // 1975 - 1999 recommended topics
        Section::updateOrCreate(['id' => 1975], ['name' => 'admin_recommended_topics', 'caption' => 'Recommended topics']);
        Section::updateOrCreate(['id' => 1976], ['name' => 'admin_recommended_topics_list', 'section_group_id' => 1975, 'caption' => 'Recommended topics Lists']);
        Section::updateOrCreate(['id' => 1977], ['name' => 'admin_recommended_topics_create', 'section_group_id' => 1975, 'caption' => 'Recommended topics create']);
        Section::updateOrCreate(['id' => 1978], ['name' => 'admin_recommended_topics_edit', 'section_group_id' => 1975, 'caption' => 'Recommended topics edit']);
        Section::updateOrCreate(['id' => 1979], ['name' => 'admin_recommended_topics_delete', 'section_group_id' => 1975, 'caption' => 'Recommended topics delete']);

        // 2000 - 2014 advertising modal
        Section::updateOrCreate(['id' => 2000], ['name' => 'admin_advertising_modal', 'caption' => 'Advertising modal']);
        Section::updateOrCreate(['id' => 2001], ['name' => 'admin_advertising_modal_config', 'section_group_id' => 2000, 'caption' => 'Set Advertising modal']);

        // 2015 - 2029 Enrollment
        Section::updateOrCreate(['id' => 2015], ['name' => 'admin_enrollment', 'caption' => 'Enrollment']);
        Section::updateOrCreate(['id' => 2016], ['name' => 'admin_enrollment_history', 'section_group_id' => 2015, 'caption' => 'Enrollment History']);
        Section::updateOrCreate(['id' => 2017], ['name' => 'admin_enrollment_add_student_to_items', 'section_group_id' => 2015, 'caption' => 'Enrollment Add Student To Items']);
        Section::updateOrCreate(['id' => 2018], ['name' => 'admin_enrollment_block_access', 'section_group_id' => 2015, 'caption' => 'Enrollment Block Access']);
        Section::updateOrCreate(['id' => 2019], ['name' => 'admin_enrollment_enable_access', 'section_group_id' => 2015, 'caption' => 'Enrollment Enable Access']);
        Section::updateOrCreate(['id' => 2020], ['name' => 'admin_enrollment_export', 'section_group_id' => 2015, 'caption' => 'Enrollment Export History']);

        // 2030 - 2049 Delete Account Requests
        Section::updateOrCreate(['id' => 2030], ['name' => 'admin_delete_account_requests', 'caption' => 'Delete Account Requests']);
        Section::updateOrCreate(['id' => 2031], ['name' => 'admin_delete_account_requests_lists', 'section_group_id' => 2030, 'caption' => 'Delete Account Requests Lists']);
        Section::updateOrCreate(['id' => 2032], ['name' => 'admin_delete_account_requests_confirm', 'section_group_id' => 2030, 'caption' => 'Delete Account Requests Confirm']);

    }
}
