const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix
.js('resources/js/app.js', 'public/assets/default/js')
//
// scss
.sass('resources/sass/app.scss', 'public/assets/default/css')
 .sass('resources/sass/panel.scss', 'public/assets/default/css')
 .sass('resources/sass/rtl-app.scss', 'public/assets/default/css')

// agora
// .sass('resources/sass/agora/agora.scss', 'public/assets/default/agora')
// js
// .js('resources/js/parts/agora/message.js', 'public/assets/default/agora/message.min.js')
// .js('resources/js/parts/agora/stream.js', 'public/assets/default/agora/stream.min.js')

// Learning Page
// .sass('resources/sass/learningPage/styles.scss', 'public/assets/default/learning_page')
// js
// .js('resources/js/parts/learningPage/scripts.js', 'public/assets/learning_page/scripts.min.js')
// .js('resources/js/parts/learningPage/forum.js', 'public/assets/learning_page/forum.min.js')

// scripts
.babel('resources/js/parts/main.js', 'public/assets/default/js/parts/main.min.js')
// .babel('resources/js/parts/cookie-security.js', 'public/assets/default/js/parts/cookie-security.min.js')

.babel('resources/js/parts/webinar_show.js', 'public/assets/default/js/parts/webinar_show.min.js')
.babel('resources/js/parts/video_player_helpers.js', 'public/assets/default/js/parts/video_player_helpers.min.js')
.babel('resources/js/parts/home.js', 'public/assets/default/js/parts/home.min.js')
.babel('resources/js/parts/login.js', 'public/assets/default/js/parts/login.min.js')
// .babel('resources/js/parts/comment.js', 'public/assets/default/js/parts/comment.min.js')
// .babel('resources/js/parts/time-counter-down.js', 'public/assets/default/js/parts/time-counter-down.min.js')
// .babel('resources/js/parts/navbar.js', 'public/assets/default/js/parts/navbar.min.js')
// .babel('resources/js/parts/certificate_validation.js', 'public/assets/default/js/parts/certificate_validation.min.js')
// .babel('resources/js/parts/cart.js', 'public/assets/default/js/parts/cart.min.js')
// .babel('resources/js/parts/payment.js', 'public/assets/default/js/parts/payment.min.js')
// .babel('resources/js/parts/text_lesson.js', 'public/assets/default/js/parts/text_lesson.min.js')
.babel('resources/js/parts/top_nav_flags.js', 'public/assets/default/js/parts/top_nav_flags.min.js')
// .babel('resources/js/parts/categories.js', 'public/assets/default/js/parts/categories.min.js')
// .babel('resources/js/parts/contact.js', 'public/assets/default/js/parts/contact.min.js')
.babel('resources/js/parts/instructors.js', 'public/assets/default/js/parts/instructors.min.js')
// .babel('resources/js/parts/quiz-start.js', 'public/assets/default/js/parts/quiz-start.min.js')
// .babel('resources/js/parts/profile.js', 'public/assets/default/js/parts/profile.min.js')
// .babel('resources/js/parts/img_cropit.js', 'public/assets/default/js/parts/img_cropit.min.js')
// .babel('resources/js/parts/become_instructor.js', 'public/assets/default/js/parts/become_instructor.min.js')
.babel('resources/js/parts/instructor-finder-wizard.js', 'public/assets/default/js/parts/instructor-finder-wizard.min.js')
.js('resources/js/parts/instructor-finder.js', 'public/assets/default/js/parts/instructor-finder.min.js')
// .babel('resources/js/parts/get-regions.js', 'public/assets/default/js/parts/get-regions.min.js')
// .js('resources/js/parts/products_lists.js', 'public/assets/default/js/parts/products_lists.min.js')
// .js('resources/js/parts/product_show.js', 'public/assets/default/js/parts/product_show.min.js')
// .js('resources/js/parts/create_topics.js', 'public/assets/default/js/parts/create_topics.min.js')
// .js('resources/js/parts/topic_posts.js', 'public/assets/default/js/parts/topic_posts.min.js')


// .babel('resources/js/panel/public.js', 'public/assets/default/js/panel/public.min.js')
// .babel('resources/js/panel/webinar.js', 'public/assets/default/js/panel/webinar.min.js')
// .babel('resources/js/panel/webinar_content_locale.js', 'public/assets/default/js/panel/webinar_content_locale.min.js')
// .babel('resources/js/panel/join_webinar.js', 'public/assets/default/js/panel/join_webinar.min.js')
// .babel('resources/js/panel/make_next_session.js', 'public/assets/default/js/panel/make_next_session.min.js')
// .babel('resources/js/panel/quiz.js', 'public/assets/default/js/panel/quiz.min.js')
// .babel('resources/js/panel/comments.js', 'public/assets/default/js/panel/comments.min.js')
// .babel('resources/js/panel/blog_comments.js', 'public/assets/default/js/panel/blog_comments.min.js')
.babel('resources/js/panel/user_setting.js', 'public/assets/default/js/panel/user_setting.min.js')
.babel('resources/js/panel/chat.js', 'public/assets/default/js/panel/chat.min.js')
// .babel('resources/js/panel/user_settings_tab.js', 'public/assets/default/js/panel/user_settings_tab.min.js')
// .babel('resources/js/panel/certificates.js', 'public/assets/default/js/panel/certificates.min.js')
//
// .babel('resources/js/panel/financial/account.js', 'public/assets/default/js/panel/financial/account.min.js')
// .babel('resources/js/panel/financial/payout.js', 'public/assets/default/js/panel/financial/payout.min.js')
// .babel('resources/js/panel/financial/subscribes.js', 'public/assets/default/js/panel/financial/subscribes.min.js')

// .babel('resources/js/panel/marketing/promotions.js', 'public/assets/default/js/panel/marketing/promotions.min.js')
// .babel('resources/js/panel/marketing/special_offers.js', 'public/assets/default/js/panel/marketing/special_offers.min.js')

// .js('resources/js/panel/meeting/meeting.js', 'public/assets/default/js/panel/meeting/meeting.min.js')
.babel('resources/js/panel/meeting/reserve_meeting.js', 'public/assets/default/js/panel/meeting/reserve_meeting.min.js')
// .js('resources/js/panel/meeting/contact-info.js', 'public/assets/default/js/panel/meeting/contact-info.min.js')
// .babel('resources/js/panel/meeting/join_modal.js', 'public/assets/default/js/panel/meeting/join_modal.min.js')
// .babel('resources/js/panel/meeting/requests.js', 'public/assets/default/js/panel/meeting/requests.min.js')
//
// .babel('resources/js/panel/noticeboard.js', 'public/assets/default/js/panel/noticeboard.min.js')
// .babel('resources/js/panel/notifications.js', 'public/assets/default/js/panel/notifications.min.js')
// .babel('resources/js/panel/quiz_list.js', 'public/assets/default/js/panel/quiz_list.min.js')
// .babel('resources/js/panel/conversations.js', 'public/assets/default/js/panel/conversations.min.js')
// .babel('resources/js/panel/dashboard.js', 'public/assets/default/js/panel/dashboard.min.js')
// .babel('resources/js/panel/reward.js', 'public/assets/default/js/panel/reward.min.js')

// .js('resources/js/panel/new_product.js', 'public/assets/default/js/panel/new_product.min.js')
// .js('resources/js/panel/store/sale.js', 'public/assets/default/js/panel/store/sale.min.js')
// .js('resources/js/panel/store/my-purchase.js', 'public/assets/default/js/panel/store/my-purchase.min.js')
// .js('resources/js/panel/new_bundle.js', 'public/assets/default/js/panel/new_bundle.min.js')
// .js('resources/js/panel/course_statistics.js', 'public/assets/default/js/panel/course_statistics.min.js')
// .js('resources/js/panel/my_assignments.js', 'public/assets/default/js/panel/my_assignments.min.js')

//
// admin
//
// .babel('resources/js/admin/dashboard.js', 'public/assets/admin/js/dashboard.min.js')
// .babel('resources/js/admin/marketing_dashboard.js', 'public/assets/admin/js/marketing_dashboard.min.js')
// .babel('resources/js/admin/webinar.js', 'public/assets/admin/js/webinar.min.js')
// .babel('resources/js/admin/quiz.js', 'public/assets/default/js/admin/quiz.min.js')
// .babel('resources/js/admin/contact_us.js', 'public/assets/default/js/admin/contact_us.min.js')
// .babel('resources/js/admin/appointments.js', 'public/assets/default/js/admin/appointments.min.js')
// .babel('resources/js/admin/categories.js', 'public/assets/default/js/admin/categories.min.js')
// .babel('resources/js/admin/certificates.js', 'public/assets/default/js/admin/certificates.min.js')
// .babel('resources/js/admin/comments.js', 'public/assets/default/js/admin/comments.min.js')
// .babel('resources/js/admin/contacts.js', 'public/assets/default/js/admin/contacts.min.js')
// .babel('resources/js/admin/filters.js', 'public/assets/default/js/admin/filters.min.js')
// .babel('resources/js/admin/noticeboards.js', 'public/assets/default/js/admin/noticeboards.min.js')
// .babel('resources/js/admin/notifications.js', 'public/assets/default/js/admin/notifications.min.js')
// .babel('resources/js/admin/reports.js', 'public/assets/default/js/admin/reports.min.js')
// .babel('resources/js/admin/reviews.js', 'public/assets/default/js/admin/reviews.min.js')
// .babel('resources/js/admin/roles.js', 'public/assets/default/js/admin/roles.min.js')
// .babel('resources/js/admin/settings/account_types.js', 'public/assets/default/js/admin/settings/account_types.min.js')
// .babel('resources/js/admin/settings/site_bank_accounts.js', 'public/assets/default/js/admin/settings/site_bank_accounts.min.js')
// .babel('resources/js/admin/settings/general_basic.js', 'public/assets/default/js/admin/settings/general_basic.min.js')
// .js('resources/js/admin/settings/cookie_settings.js', 'public/assets/default/js/admin/settings/cookie_settings.min.js')
// .babel('resources/js/admin/user_edit.js', 'public/assets/default/js/admin/user_edit.min.js')
// .babel('resources/js/admin/webinar_reports.js', 'public/assets/default/js/admin/webinar_reports.min.js')
// .babel('resources/js/admin/new_registration_packages.js', 'public/assets/default/js/admin/new_registration_packages.min.js')
// .babel('resources/js/admin/registration_packages_settings.js', 'public/assets/default/js/admin/registration_packages_settings.min.js')
// .babel('resources/js/admin/regions/create.js', 'public/assets/default/js/admin/regions_create.min.js')
// .js('resources/js/admin/rewards/rewards_items.js', 'public/assets/default/js/admin/rewards_items.min.js')
// .js('resources/js/admin/rewards/rewards_settings.js', 'public/assets/default/js/admin/rewards_settings.min.js')
// .js('resources/js/admin/discount.js', 'public/assets/default/js/admin/discount.min.js')
// .js('resources/js/admin/newsletter.js', 'public/assets/default/js/admin/newsletter.min.js')
// .js('resources/js/admin/new_product.js', 'public/assets/default/js/admin/new_product.min.js')
// .js('resources/js/admin/webinar_students.js', 'public/assets/default/js/admin/webinar_students.min.js')
// .js('resources/js/admin/store/orders.js', 'public/assets/default/js/admin/store/orders.min.js')
// .js('resources/js/admin/store/specification.js', 'public/assets/default/js/admin/store/specification.min.js')
// .js('resources/js/admin/not_access_to_content.js', 'public/assets/default/js/admin/not_access_to_content.min.js')
// .js('resources/js/admin/home_sections.js', 'public/assets/default/js/admin/home_sections.min.js')
// .js('resources/js/admin/advertising_modal.js', 'public/assets/default/js/admin/advertising_modal.min.js')
// .js('resources/js/admin/course-forum-answers.js', 'public/assets/default/js/admin/course-forum-answers.min.js')
;
