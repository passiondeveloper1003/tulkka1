<div class="">
    <div data-action="{{ !empty($quiz) ? '/panel/quizzes/' . $quiz->id . '/update' : '/panel/quizzes/store' }}"
        class="js-content-form quiz-form webinar-form">

        <section>
            <h2 class="section-title after-line">
                {{ !empty($quiz) ? trans('public.edit') . ' (' . $quiz->title . ')' : trans('quiz.new_quiz') }}</h2>

            <div class="row">
                <div class="col-12 col-md-4">

                    @if (!empty(getGeneralSettings('content_translate')))
                        <div class="form-group mt-25">
                            <label class="input-label">{{ trans('auth.language') }}</label>
                            <select name="ajax[locale]"
                                class="form-control {{ !empty($quiz) ? 'js-webinar-content-locale' : '' }}"
                                data-webinar-id="{{ !empty($quiz) ? $quiz->webinar_id : '' }}"
                                data-id="{{ !empty($quiz) ? $quiz->id : '' }}" data-relation="quizzes"
                                data-fields="title">
                                @foreach ($userLanguages as $lang => $language)
                                    <option value="{{ $lang }}"
                                        {{ (!empty($quiz) and !empty($quiz->locale)) ? (mb_strtolower($quiz->locale) == mb_strtolower($lang) ? 'selected' : '') : ($locale == $lang ? 'selected' : '') }}>
                                        {{ $language }}</option>
                                @endforeach
                            </select>
                        </div>
                    @else
                        <input type="hidden" name="ajax[locale]" value="{{ $defaultLocale }}">
                    @endif



                    @if (!empty($chapter) or !empty($webinarChapterPages))
                        <input type="hidden" name="ajax[chapter_id]"
                            value="{{ !empty($chapter) ? $chapter->id : '' }}" class="chapter-input">
                    @else
                        <div class="form-group mt-25">
                            <label class="input-label">{{ trans('public.chapter') }}</label>

                            <select name="ajax[chapter_id]" class="js-ajax-chapter_id custom-select">
                                <option value="">{{ trans('public.no_chapter') }}</option>

                                @if (!empty($chapters) and count($chapters))
                                    @foreach ($chapters as $chapter)
                                        <option value="{{ $chapter->id }}"
                                            {{ (!empty($quiz) and $quiz->chapter_id == $chapter->id) ? 'selected' : '' }}>
                                            {{ $chapter->title }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    @endif

                    <div class="form-group @if (!empty($selectedWebinar)) mt-25 @endif">
                        <label class="input-label">{{ trans('quiz.quiz_title') }}</label>
                        <input type="text" value="{{ !empty($quiz) ? $quiz->title : old('title') }}"
                            name="ajax[title]" class="js-ajax-title form-control @error('title')  is-invalid @enderror"
                            placeholder="" />
                        <div class="invalid-feedback">
                            @error('title')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>

                    {{--             <div class="form-group">
                        <label class="input-label">{{ trans('public.time') }} <span class="braces">({{ trans('public.minutes') }})</span></label>
                        <input type="number" value="{{ !empty($quiz) ? $quiz->time : old('time') }}" name="ajax[time]" class="js-ajax-time form-control @error('time')  is-invalid @enderror" placeholder="{{ trans('forms.empty_means_unlimited') }}"/>
                        <div class="invalid-feedback">
                            @error('time')
                            {{ $message }}
                            @enderror
                        </div>
                    </div> --}}

                    {{--      <div class="form-group">
                        <label class="input-label">{{ trans('quiz.number_of_attemps') }}</label>
                        <input type="number" name="ajax[attempt]" value="{{ !empty($quiz) ? $quiz->attempt : old('attempt') }}" class="js-ajax-attempt form-control @error('attempt')  is-invalid @enderror" placeholder="{{ trans('forms.empty_means_unlimited') }}"/>
                        <div class="invalid-feedback">
                            @error('attempt')
                            {{ $message }}
                            @enderror
                        </div>
                    </div> --}}

                    <div class="form-group">
                        <label class="input-label">{{ trans('quiz.pass_mark') }}</label>
                        <input type="number" name="ajax[pass_mark]"
                            value="{{ !empty($quiz) ? $quiz->pass_mark : old('pass_mark') }}"
                            class="js-ajax-pass_mark form-control @error('pass_mark')  is-invalid @enderror"
                            placeholder="" />
                        <div class="invalid-feedback">
                            @error('pass_mark')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>

                    {{-- <div class="form-group mt-20 d-flex align-items-center justify-content-between">
                        <label class="cursor-pointer input-label" for="certificateSwitch{{ !empty($quiz) ? $quiz->id : 'record' }}">{{ trans('quiz.certificate_included') }}</label>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" name="ajax[certificate]" class="js-ajax-certificate custom-control-input" id="certificateSwitch{{ !empty($quiz) ? $quiz->id : 'record' }}" {{ !empty($quiz) && $quiz->certificate ? 'checked' : ''}}>
                            <label class="custom-control-label" for="certificateSwitch{{ !empty($quiz) ? $quiz->id : 'record' }}"></label>
                        </div>
                    </div> --}}

                    <div class="form-group mt-20 d-flex align-items-center justify-content-between">
                        <label class="cursor-pointer input-label"
                            for="statusSwitch{{ !empty($quiz) ? $quiz->id : 'record' }}">{{ trans('quiz.active_quiz') }}</label>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" name="ajax[status]" class="js-ajax-status custom-control-input"
                                id="statusSwitch{{ !empty($quiz) ? $quiz->id : 'record' }}"
                                {{ !empty($quiz) && $quiz->status == 'active' ? 'checked' : '' }}>
                            <label class="custom-control-label"
                                for="statusSwitch{{ !empty($quiz) ? $quiz->id : 'record' }}"></label>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        @if (!empty($quiz))
            <section class="mt-30">
                <div class="d-block d-md-flex justify-content-between align-items-center pb-20">
                    <h2 class="section-title after-line">{{ trans('public.questions') }}</h2>

                    <div class="d-flex align-items-center mt-20 mt-md-0">
                        <button id="add_multiple_question" data-quiz-id="{{ $quiz->id }}" type="button"
                            class="quiz-form-btn btn btn-primary btn-sm ml-10">{{ trans('quiz.add_multiple_choice') }}</button>
                        <button id="add_descriptive_question" data-quiz-id="{{ $quiz->id }}" type="button"
                            class="quiz-form-btn btn btn-primary btn-sm ml-10">{{ trans('quiz.add_descriptive') }}</button>
                    </div>
                </div>

                @if ($quizQuestions)
                    @foreach ($quizQuestions as $question)
                        <div class="quiz-question-card d-flex align-items-center mt-20">
                            <div class="flex-grow-1">
                                <h4 class="question-title">{{ $question->title }}</h4>
                                <div class="font-12 mt-5 question-infos">
                                    <span>{{ $question->type === App\Models\QuizzesQuestion::$multiple ? trans('quiz.multiple_choice') : trans('quiz.descriptive') }}
                                        | {{ trans('quiz.grade') }}: {{ $question->grade }}</span>
                                </div>
                            </div>

                            <div class="btn-group dropdown table-actions">
                                <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i data-feather="more-vertical" height="20"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <button type="button" data-question-id="{{ $question->id }}"
                                        class="edit_question btn btn-sm btn-transparent d-block">{{ trans('public.edit') }}</button>
                                    <a href="/panel/quizzes-questions/{{ $question->id }}/delete"
                                        class="delete-action btn btn-sm btn-transparent d-block">{{ trans('public.delete') }}</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </section>
        @endif

        <input type="hidden" name="ajax[is_webinar_page]" value="@if (!empty($inWebinarPage) and $inWebinarPage) 1 @else 0 @endif">

        <div class="mt-20 mb-20">
            <button type="button"
                class="js-submit-quiz-form btn btn-sm btn-primary">{{ !empty($quiz) ? trans('public.save_change') : trans('public.create') }}</button>

            @if (empty($quiz) and !empty($inWebinarPage))
                <button type="button"
                    class="btn btn-sm btn-danger ml-10 cancel-accordion">{{ trans('public.close') }}</button>
            @endif
        </div>
    </div>

    <!-- Modal -->
    @if (!empty($quiz))
        @include(getTemplate() . '.panel.quizzes.modals.multiple_question', ['quiz' => $quiz])
        @include(getTemplate() . '.panel.quizzes.modals.descriptive_question', ['quiz' => $quiz])
    @endif
