<div>
    <div>
        <div class="modal fade @if ($show === true) show @endif"
            style="display: @if ($show === true) block
     @else
             none @endif;" tabindex="-1"
            role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
                <div class="modal-content animate__bounceIn">
                    <div class="modal-header">
                        <h5 class="modal-title mx-2" id="exampleModalLabel">{{trans('update.goals')}}</h5>
                        <button class="close" type="button" aria-label="Close" wire:click.prevent="doClose()">
                            <img class="" src="{{ url('/assets/default/img/close.png') }}">
                        </button>
                    </div>
                    <div class="modal-body d-flex flex-column align-items-md-center text-center p-40">
                        <h4>{{ trans('panel.what_goal') }}</h4>
                        <div
                            class="reviews-stars row align-items-center w-100 justify-content-between mt-40 goal-modal p-10 rounded border">
                            <div class="form-group mb-30">
                                <label class="input-label">{{ trans('update.goals') }}:</label>
                                <div class="d-flex align-items-center flex-wrap justify-content-center">
                                    <div class="custom-control custom-checkbox">
                                        <input wire:click="addToGoals('learn_basics')" type="checkbox" name="goals[]"
                                            value="learn_basics" id="learn_basics" class="custom-control-input">
                                        <label class="custom-control-label font-14 cursor-pointer"
                                            for="learn_basics">{{ trans('update.learn_basics') }}</label>
                                    </div>

                                    <div class="custom-control custom-checkbox ml-10">
                                        <input wire:click="addToGoals('improve_proficiency')" type="checkbox"
                                            name="goals[]" value="improve_proficiency" id="improve_proficiency"
                                            class="custom-control-input">
                                        <label class="custom-control-label font-14 cursor-pointer"
                                            for="improve_proficiency">{{ trans('update.improve_proficiency') }}</label>
                                    </div>

                                    <div class="custom-control custom-checkbox ml-10">
                                        <input wire:click="addToGoals('talk_with_people')" type="checkbox"
                                            name="goals[]" value="talk_with_people" id="talk_with_people"
                                            class="custom-control-input">
                                        <label class="custom-control-label font-14 cursor-pointer"
                                            for="talk_with_people">{{ trans('update.talk_with_people') }}</label>
                                    </div>
                                    <div class="custom-control custom-checkbox ml-10">
                                        <input wire:click="addToGoals('do_business')" type="checkbox" name="goals[]"
                                            value="do_business" id="do_business" class="custom-control-input">
                                        <label class="custom-control-label font-14 cursor-pointer"
                                            for="do_business">{{ trans('update.do_business') }}</label>
                                    </div>
                                    <div class="custom-control custom-checkbox ml-10">
                                        <input wire:click="addToGoals('for_kids')" type="checkbox" name="goals[]"
                                            value="for_kids" id="for_kids" class="custom-control-input">
                                        <label class="custom-control-label font-14 cursor-pointer"
                                            for="for_kids">{{ trans('update.for_kids') }}</label>
                                    </div>
                                    <div class="custom-control custom-checkbox ml-10">
                                        <input wire:click="addToGoals('prepare_for_exams')" type="checkbox"
                                            name="goals[]" value="prepare_for_exams" id="prepare_for_exams"
                                            class="custom-control-input">
                                        <label class="custom-control-label font-14 cursor-pointer"
                                            for="prepare_for_exams">{{ trans('update.prepare_for_exams') }}</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-6 col-md-12 d-flex flex-column align-items-center justify-content-center border-top">
                                <div class="form-group mt-4 ml-2 w-100">
                                    <textarea wire:model="notes" placeholder="{{trans('update.write_a_note')}}" style="min-width: 250px; min-height: 200px;background-color:#FBFBFB; border:none;" name="comment"
                                        class="form-control @error('comment') is-invalid @enderror" rows="5"></textarea>
                                    <div class="invalid-feedback">
                                        @error('comment')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>

                        </div>
                        <button wire:click="sendGoal()"
                            class="btn p-2 rounded btn-primary mt-20 align-self-start ">{{ trans('panel.send_goal') }}</button>
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
