@extends(getTemplate() .'.panel.layouts.panel_layout')

@push('styles_top')

@endpush
@section('content')
    @include('web.default.panel.quizzes.create_quiz_form')
@endsection

@push('scripts_bottom')
    <script>
        var saveSuccessLang = '{{ trans('webinars.success_store') }}';
        var quizzesSectionLang = '{{ trans('quiz.quizzes_section') }}';
    </script>

    <script src="/assets/default/js/panel/quiz.min.js"></script>
    <script src="/assets/default/js/panel/webinar_content_locale.min.js"></script>
@endpush
