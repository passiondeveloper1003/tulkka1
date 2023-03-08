<div>
    <div>
        <div class="modal fade @if ($show === true) show @endif" id="myExampleModal"
            style="display: @if ($show === true) block
   @else
           none @endif;" tabindex="-1"
            role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
                <div class="modal-content animate__bounceIn">
                    <div class="modal-header">
                        <h5 class="modal-title font-20 mx-2" id="exampleModalLabel">Quiz</h5>
                        <button class="close" type="button" aria-label="Close" wire:click.prevent="doClose()">
                          <img src="{{ url('/assets/default/img/close.png') }}">
                        </button>
                    </div>
                    <div class="modal-body d-flex flex-column align-items-md-center text-center p-20">
                        <div class="row w-100 d-flex justify-content-center">
                            <div class="col-12 d-flex flex-column align-items-center">
                                <div class="form-group mt-4 ml-2 w-100">
                                    <label class="font-weight-bold font-20" for="comment">Quiz Title</label>
                                    <input wire:model="title" name="title"
                                        class="form-control @error('title') is-invalid @enderror" rows="5" />
                                    <div class="invalid-feedback">
                                        @error('title')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row w-100 d-flex justify-content-center">
                            <div class="col-12 d-flex flex-column align-items-center">
                                <div class="form-group mt-4 ml-2 w-100">
                                    <label class="font-weight-bold font-20" for="comment">Students</label>
                                    <select wire:model="students_ids" multiple="multiple"
                                        data-search-option="just_teacher_role" class="form-control search-user-select2"
                                        data-placeholder="Search teachers">
                                        @if ($authUser->students() != null && count($authUser->students()) > 0)
                                            @foreach ($authUser->students() as $student)
                                                <option value="{{ $student->id }}">{{ $student->full_name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <div class="invalid-feedback">
                                        @error('title')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row align-items-center w-100 justify-content-between ">
                            <div class="col-6 col-md-12 d-flex flex-column align-items-center justify-content-center">
                                <div wire:ignore class="form-group mt-4 ml-2 w-100">
                                    <label class="font-weight-bold font-20" for="comment">Quiz Description</label>
                                    <textarea id="description" wire:model="description" style="min-width: 250px; min-height: 200px" name="description"
                                        class="form-control @error('description') is-invalid @enderror" rows="5"></textarea>
                                    <div class="invalid-feedback">
                                        @error('description')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="mb-3 panel-section-card p-4 mt-30 w-100">
                            <label class="form-label font-weight-bold font-20"><i
                                    class="fa-solid fa-paperclip text-secondary mr-2"></i>Attachments</label>
                            <div class="form-label text-warning mt-2">If you need to send more than 1 file please use
                                zip files</div>
                            <input type="file" class="form-control mt-2" wire:model="attachment">
                            <div wire:loading wire:target="attachment">Uploading...</div>
                            @error('attachment')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <button wire:click="sendQuiz()"
                            class="btn btn-sm btn-primary mt-20">{{ trans('panel.send_quiz') }}</button>

                    </div>
                </div>

            </div>
        </div>
        <!-- Let's also add the backdrop / overlay here -->
        <div class="modal-backdrop fade show" id="backdrop"
            style="display: @if ($show === true) block
   @else
           none @endif;"></div>
    </div>

</div>


@push('scripts_bottom')
    <script src="https://cdn.tiny.cloud/1/ejpo625z8ad29xc8awjl03w176g4arzuxcj5sjxl45hzbf08/tinymce/6/tinymce.min.js"
        referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            // Select the element(s) to add TinyMCE to using any valid CSS selector
            selector: "#description",
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
                    @this.set('description', editor.getContent());
                });
            }
        });
    </script>
    <script>
        document.addEventListener('livewire:load', function() {
            Livewire.on('quizSent', postId => {
                iziToast.success({
                    title: 'Success',
                    message: 'Your Quiz Successfully sent',
                    position: 'topRight'
                });
            });
        })
    </script>
@endpush
