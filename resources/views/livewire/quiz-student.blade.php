<section class="mt-35">
    @if ($quiz->count() > 0)
        <section class="d-flex flex-row justify-content-between">
            <h3 class="font-weight-normal font-20">{{trans('update.share_quiz')}}</h3>
        </section>
        @if ($quiz->attachment)
            <div class="panel-section-card p-4 mt-20">
                <label class="font-weight-normal font-20 w-100" for="comment"><i
                        class="fa-solid fa-file-arrow-down mx-2 text-primary"></i>
                    {{trans('update.attachments')}}</label>
                <button wire:click="download" class="btn btn-primary mt-2"><i
                        class="fa fa-download mr-2"></i>{{trans('update.download')}}</button>
            </div>
        @endif
        @if ($quiz->answer_attachment)
            <div class="panel-section-card p-4 mt-20">
                <label class="font-weight-normal font-20 w-100" for="comment"><i
                        class="fa-solid fa-file-arrow-down mx-2 text-primary"></i>
                    Answers Attachments</label>
                <button wire:click="downloadAnswers" class="btn btn-primary mt-2"><i
                        class="fa fa-download mr-2"></i>{{trans('update.download_answers')}}</button>
            </div>
        @endif
        <div class="d-flex panel-section-card p-4 mt-30">
            <div class="col-6 col-md-6 d-flex flex-column align-items-center">
                <div class="form-group mt-4 ml-2 w-100">
                    <label class="font-weight-normal font-20" for="comment"><i
                            class="fa-solid fa-circle-info mx-2 text-secondary"></i>{{trans('update.quiz_desc')}}</label>
                    <div class="mt-4">{!! $quiz->description !!}</div>
                </div>
            </div>
        </div>

        <div class="d-flex panel-section-card p-4 mt-30">
            <div class="col-6 col-md-6 d-flex flex-column align-items-center">
                <div class="form-group mt-4 ml-2 w-100">
                    <label class="font-weight-normal font-20" for="comment"><i
                            class="fa-solid fa-feather-pointed mx-2 text-primary"></i>{{trans('update.answers')}}</label>
                    @if ($quiz->student_answers)
                        <div class="mt-4">{!! $quiz->student_answers !!}</div>
                    @else
                        <div class="text-danger mt-4">{{trans('update.waiting_answers')}}</div>
                    @endif

                    <div class="invalid-feedback">
                        @error('title')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        @if ($quiz->teacher_notes)
            <div class="d-flex panel-section-card p-4 mt-30">
                <div class="col-6 col-md-6 d-flex flex-column align-items-center">
                    <div class="form-group mt-4 ml-2 w-100">
                        <label class="font-weight-normal font-20" for="comment"><i
                                class="fa-regular fa-note-sticky mr-2 text-warning"></i>{{trans('update.teacher_notes')}}</label>
                        <div class="mt-4">{{ $quiz->teacher_notes }}</div>
                    </div>
                </div>
            </div>
        @endif




        @if ($authUser->isTeacher())
            <div class="align-items-center w-100 justify-content-between panel-section-card p-4  mt-30">
                <div class="col-6 col-md-12 d-flex flex-column align-items-center justify-content-center">
                    <div wire:ignore class="form-group mt-4 ml-2 w-100">
                        <label class="font-weight-normal font-20" for="comment"><i
                                class="fa-solid fa-quote-left mx-2 text-danger"></i>{{trans('update.teacher_notes')}}</label>
                        <textarea id="teacher_notes" wire:model="teacher_notes" style="min-width: 250px; min-height: 200px" name="description"
                            class="form-control mt-4 @error('description') is-invalid @enderror" rows="5"></textarea>
                        <div class="invalid-feedback">
                            @error('teacher_notes')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>
                <button wire:click="sendNote()"
                    class="btn btn-sm btn-primary mt-20">{{ trans('panel.send_note') }}</button>
            </div>
        @endif

        @if (!$authUser->isTeacher() && $quiz->status != 'ended')
            <div class="align-items-center w-100 justify-content-between panel-section-card p-4 mt-20">
                <div class="col-6 col-md-12 d-flex flex-column align-items-center justify-content-center">
                    <div wire:ignore class="form-group mt-4 ml-2 w-100">
                        <label class="font-weight-normal font-20" for="comment">{{trans('update.quiz_answers')}}</label>
                        <textarea id="quizAnswer" wire:model="quizAnswer" style="min-width: 250px; min-height: 200px" name="quizAnswer"
                            class="form-control @error('description') is-invalid @enderror" rows="5"></textarea>
                        <div class="invalid-feedback">
                            @error('description')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>

            </div>
        @endif
        @if ($quiz->status != 'ended' && !$authUser->isTeacher())
            <div class="mb-3 panel-section-card p-4 mt-30">
                <label class="form-label font-weight-normal font-20"><i
                        class="fa-solid fa-paperclip text-secondary mx-2"></i>{{trans('update.attachments')}}</label>
                <div class="form-label text-warning mt-2">{{trans('update.file_warn')}}
                </div>
                <input type="file" class="form-control mt-2" wire:model="answer_attachment">
                <div wire:loading wire:target="attachments">Uploading...</div>
                @error('answer_attachment')
                    <span class="error text-danger">{{ $message }}</span>
                @enderror
            </div>
        @endif
        @if ($quiz->status != 'ended' && !$authUser->isTeacher())
            <button wire:click="sendQuiz()" class="btn btn-sm btn-primary mt-20 rounded">{{ trans('panel.send_quiz') }}</button>
        @endif
        @if ($quiz->status == 'ended' && $authUser->isTeacher() && !$quiz->result)
            <div class="panel-section-card p-4 w-100 d-flex mt-30 flex-column align-items-start">
                <div class="col-6 col-md-12 d-flex flex-column align-items-center">
                    <div class="form-group mt-4 ml-2 w-100">
                        <label class="font-weight-normal font-20" for="comment">{{trans('update.quiz_result')}}</label>
                        <input wire:model="quizPoint" type="text" class="form-control">
                        <div class="invalid-feedback">
                            @error('title')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>
                <button wire:click="givePoint()"
                    class="btn btn-sm btn-primary mt-2">{{ trans('panel.send_quiz_result') }}</button>
            </div>
        @endif

        {{-- @if ($homework->status == 'pending' && $authUser->isTeacher())
          <button wire:click="acceptHomework()"
              class="btn btn-sm btn-primary mt-20">{{ trans('panel.accept_homework') }}</button>
          <button wire:click="denyHomeWork()"
              class="btn btn-sm btn-danger mt-20">{{ trans('panel.deny_homework') }}</button>
      @endif --}}
    @else
        @include(getTemplate() . '.includes.no-result', [
            'file_name' => 'quiz.png',
            'title' => trans('quiz.quiz_no_result'),
            'hint' => nl2br(trans('quiz.quiz_no_result_hint')),
        ])
    @endif


</section>
@push('scripts_bottom')
    <script src="https://cdn.tiny.cloud/1/ejpo625z8ad29xc8awjl03w176g4arzuxcj5sjxl45hzbf08/tinymce/6/tinymce.min.js"
        referrerpolicy="origin"></script>
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    <script src="/assets/default/vendors/select2/select2.min.js"></script>

    <script src="/assets/default/js/panel/quiz_list.min.js"></script>

    <script>
        tinymce.init({
            // Select the element(s) to add TinyMCE to using any valid CSS selector
            selector: "#quizAnswer",
            plugins: "preview searchreplace autolink autosave save directionality visualblocks visualchars fullscreen image link media template codesample table charmap pagebreak nonbreaking anchor advlist lists wordcount help emoticons",
            height: '700px',
            toolbar_sticky: true,
            icons: 'thin',
            autosave_restore_when_empty: true,
            setup: function(editor) {
                editor.on('init change', function() {
                    editor.save();
                });
                editor.on('change', function(e) {
                    @this.set('quizAnswer', editor.getContent());
                });
            }
        });
    </script>
    <script>
        document.addEventListener('livewire:load', function() {
            Livewire.on('quizSent', postId => {
                iziToast.success({
                    title: 'Success',
                    message: {{trans('update.quiz_sent')}},
                    position: 'topRight'
                });
            });
            Livewire.on('quizResultSent', postId => {
                iziToast.success({
                    title: 'Success',
                    message: 'Your Quiz Result Successfully sent',
                    position: 'topRight'
                });
            });
            Livewire.on('noteSent', postId => {
                iziToast.success({
                    title: 'Success',
                    message: 'Your Note Successfully Added',
                    position: 'topRight'
                });
            });
        })
    </script>
@endpush
