<?php

namespace App\Mixins\Certificate;

use App\Models\Certificate;
use App\Models\CertificateTemplate;
use App\Models\Reward;
use App\Models\RewardAccounting;
use \Barryvdh\DomPDF\Facade\Pdf;
use Intervention\Image\Facades\Image;

class MakeCertificate
{
    public function makeQuizCertificate($quizResult)
    {
        $template = CertificateTemplate::where('status', 'publish')
            ->where('type', 'quiz')
            ->first();

        if (!empty($template)) {
            $quiz = $quizResult->quiz;
            $user = $quizResult->user;

            $userCertificate = $this->saveQuizCertificate($user, $quiz, $quizResult);

            $body = $this->makeBody(
                $userCertificate,
                $user,
                $template->body,
                $quiz->webinar->title,
                $quizResult->user_grade,
                $quiz->webinar->teacher->full_name,
                $quiz->webinar->duration);

            /*$data = [
                'pageTitle' => trans('public.certificate'),
                'image' => public_path($template->image),
                'body' => $body,
            ];*/

            $img = $this->makeImage($template, $body);
            return $img->response('png');
        }

        abort(404);
    }

    private function saveQuizCertificate($user, $quiz, $quizResult)
    {
        $certificate = Certificate::where('quiz_id', $quiz->id)
            ->where('student_id', $user->id)
            ->where('quiz_result_id', $quizResult->id)
            ->first();

        $data = [
            'quiz_id' => $quiz->id,
            'student_id' => $user->id,
            'quiz_result_id' => $quizResult->id,
            'user_grade' => $quizResult->user_grade,
            'type' => 'quiz',
            'created_at' => time()
        ];

        if (!empty($certificate)) {
            $certificate->update($data);
        } else {
            $certificate = Certificate::create($data);

            $notifyOptions = [
                '[c.title]' => $quiz->webinar_title,
            ];
            sendNotification('new_certificate', $notifyOptions, $user->id);
        }

        return $certificate;
    }

    private function makeBody($userCertificate, $user, $body, $courseTitle = null, $userGrade = null, $teacherFullName = null, $duration = null)
    {
        $body = str_replace('[student]', $user->full_name, $body);
        $body = str_replace('[course]', $courseTitle, $body);
        $body = str_replace('[grade]', $userGrade, $body);
        $body = str_replace('[certificate_id]', $userCertificate->id, $body);
        $body = str_replace('[date]', dateTimeFormat($userCertificate->created_at, 'j M Y | H:i'), $body);
        $body = str_replace('[instructor_name]', $teacherFullName, $body);
        $body = str_replace('[duration]', $duration, $body);

        $userCertificateAdditional = $user->userMetas->where('name', 'certificate_additional')->first();
        $userCertificateAdditionalValue = !empty($userCertificateAdditional) ? $userCertificateAdditional->value : null;
        $body = str_replace('[user_certificate_additional]', $userCertificateAdditionalValue, $body);

        return $body;
    }

    private function makeImage($certificateTemplate, $body)
    {
        $img = Image::make(public_path($certificateTemplate->image));


        if ($certificateTemplate->rtl) {
            $Arabic = new \I18N_Arabic('Glyphs');
            $body = $Arabic->utf8Glyphs($body);
        }

        $img->text($body, $certificateTemplate->position_x, $certificateTemplate->position_y, function ($font) use ($certificateTemplate) {
            $font->file($certificateTemplate->rtl ? public_path('assets/default/fonts/vazir/Vazir-Medium.ttf') : public_path('assets/default/fonts/Montserrat-Medium.ttf'));
            $font->size($certificateTemplate->font_size);
            $font->color($certificateTemplate->text_color);
            $font->align($certificateTemplate->rtl ? 'right' : 'left');
        });

        return $img;
    }

    public function makeCourseCertificate($certificate)
    {
        $template = CertificateTemplate::where('status', 'publish')
            ->where('type', 'course')
            ->first();

        $course = $certificate->webinar;

        if (!empty($template) and !empty($course)) {
            $user = $certificate->student;

            $userCertificate = $this->saveCourseCertificate($user, $course);


            $body = $this->makeBody(
                $userCertificate,
                $user,
                $template->body,
                $course->title,
                null,
                $course->teacher->full_name,
                $course->duration);

            /*$data = [
                'pageTitle' => trans('public.certificate'),
                'image' => public_path($template->image),
                'body' => $body,
            ];

            $pdf = Pdf::loadView('web.default.certificate_template.index', $data);
            return $pdf->setPaper('a4', 'landscape')
                ->setWarnings(false)
                ->stream('course_certificate.pdf');*/

            $img = $this->makeImage($template, $body);
            return $img->response('png');
        }

        abort(404);
    }

    public function saveCourseCertificate($user, $course)
    {
        $certificate = Certificate::where('webinar_id', $course->id)
            ->where('student_id', $user->id)
            ->first();

        $data = [
            'webinar_id' => $course->id,
            'student_id' => $user->id,
            'type' => 'course',
            'created_at' => time()
        ];

        if (!empty($certificate)) {
            $certificate->update($data);
        } else {
            $certificate = Certificate::create($data);

            $notifyOptions = [
                '[c.title]' => $course->title,
            ];
            sendNotification('new_certificate', $notifyOptions, $user->id);
        }

        return $certificate;
    }
}
