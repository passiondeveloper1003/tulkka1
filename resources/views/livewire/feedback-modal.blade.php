<div>
    <div>
        <div class="modal fade @if ($show === true) show @endif" id="myExampleModal"
            style="display: @if ($show === true) block
       @else
               none @endif;"
            tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
                <div class="modal-content animate__bounceIn">
                    <div class="modal-header">
                        <h5 class="modal-title mx-2" id="exampleModalLabel">Feedback</h5>
                        <button class="close" type="button" aria-label="Close" wire:click.prevent="doClose()">
                          <img src="{{ url('/assets/default/img/close.png') }}">
                        </button>
                    </div>
                    <div class="modal-body d-flex flex-column align-items-md-center text-center p-40">
                        <h4>Please give your feedback to student</h4>


                        <div class="reviews-stars row align-items-center w-100 justify-content-between mt-40">


                            <div
                                class="col-6 col-md-4 d-flex flex-column align-items-center justify-content-center barrating-stars">
                                <div class="form-group mt-4 ml-2">
                                    <label class="font-weight-bold" for="grammar">Grammar</label>
                                    <textarea style="min-width: 250px; min-height: 200px" wire:model="grammar"
                                        class="form-control @error('grammar') is-invalid @enderror" rows="5"></textarea>
                                    <div class="invalid-feedback">
                                        @error('grammar')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                                <span class="font-14 text-gray">{{ trans('panel.grammar') }}</span>
                                <div>
                                    <h5 class="mt-5">Your rate</h5>
                                    <svg wire:click="setGrammarRate(1)" style="width: 32px; height: 32px;"
                                        class="cursor-pointer block @if ($grammarRate >= 1) text-warning @endif"
                                        fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path
                                            d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                                    </svg>
                                    <svg wire:click="setGrammarRate(2)" style="width: 32px; height: 32px;"
                                        class="cursor-pointer block @if ($grammarRate >= 2) text-warning @endif"
                                        fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path
                                            d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                                    </svg>
                                    <svg wire:click="setGrammarRate(3)" style="width: 32px; height: 32px;"
                                        class="cursor-pointer block @if ($grammarRate >= 3) text-warning @endif"
                                        fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path
                                            d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                                    </svg>
                                    <svg wire:click="setGrammarRate(4)" style="width: 32px; height: 32px;"
                                        class="cursor-pointer block @if ($grammarRate >= 4) text-warning @endif"
                                        fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path
                                            d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                                    </svg>
                                    <svg wire:click="setGrammarRate(5)" style="width: 32px; height: 32px;"
                                        class="cursor-pointer block @if ($grammarRate >= 5) text-warning @endif"
                                        fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path
                                            d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                                    </svg>
                                </div>
                            </div>

                            <div
                                class="col-6 col-md-4 d-flex flex-column align-items-center justify-content-center barrating-stars">
                                <div class="form-group mt-4">
                                    <label class="font-weight-bold" for="pronunciation">Pronunciation</label>
                                    <textarea wire:model="pronunciation" style="min-width: 250px; min-height: 200px" name="pronunciation"
                                        class="form-control @error('pronunciation') is-invalid @enderror" rows="5"></textarea>
                                    <div class="invalid-feedback">
                                        @error('pronunciation')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                                <span class="font-14 text-gray">{{ trans('panel.pronunciation') }}</span>
                                <div>
                                    <h5 class="mt-5">Your rate</h5>
                                    <svg wire:click="setPronRate(1)" style="width: 32px; height: 32px;"
                                        class="cursor-pointer block @if ($pronunciationRate >= 1) text-warning @endif"
                                        fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path
                                            d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                                    </svg>
                                    <svg wire:click="setPronRate(2)" style="width: 32px; height: 32px;"
                                        class="cursor-pointer block @if ($pronunciationRate >= 2) text-warning @endif"
                                        fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path
                                            d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                                    </svg>
                                    <svg wire:click="setPronRate(3)" style="width: 32px; height: 32px;"
                                        class="cursor-pointer block @if ($pronunciationRate >= 3) text-warning @endif"
                                        fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path
                                            d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                                    </svg>
                                    <svg wire:click="setPronRate(4)" style="width: 32px; height: 32px;"
                                        class="cursor-pointer block @if ($pronunciationRate >= 4) text-warning @endif"
                                        fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path
                                            d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                                    </svg>
                                    <svg wire:click="setPronRate(5)" style="width: 32px; height: 32px;"
                                        class="cursor-pointer block @if ($pronunciationRate >= 5) text-warning @endif"
                                        fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path
                                            d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                                    </svg>
                                </div>

                            </div>

                            <div
                                class="col-6 col-md-4 d-flex flex-column align-items-center justify-content-center barrating-stars">
                                <div class="form-group mt-4 ml-2">
                                    <label class="font-weight-bold" for="comment">Speaking</label>
                                    <textarea wire:model="speaking" style="min-width: 250px; min-height: 200px" name="comment"
                                        class="form-control @error('comment') is-invalid @enderror" rows="5"></textarea>
                                    <div class="invalid-feedback">
                                        @error('comment')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                                <span class="font-14 text-gray">{{ trans('panel.speaking') }}</span>
                                <div>
                                    <h5 class="mt-5">Your rate</h5>
                                    <svg wire:click="setSpeakingRate(1)" style="width: 32px; height: 32px;"
                                        class="cursor-pointer block @if ($speakingRate >= 1) text-warning @endif"
                                        fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path
                                            d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                                    </svg>
                                    <svg wire:click="setSpeakingRate(2)" style="width: 32px; height: 32px;"
                                        class="cursor-pointer block @if ($speakingRate >= 2) text-warning @endif"
                                        fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path
                                            d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                                    </svg>
                                    <svg wire:click="setSpeakingRate(3)" style="width: 32px; height: 32px;"
                                        class="cursor-pointer block @if ($speakingRate >= 3) text-warning @endif"
                                        fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path
                                            d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                                    </svg>
                                    <svg wire:click="setSpeakingRate(4)" style="width: 32px; height: 32px;"
                                        class="cursor-pointer block @if ($speakingRate >= 4) text-warning @endif"
                                        fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path
                                            d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                                    </svg>
                                    <svg wire:click="setSpeakingRate(5)" style="width: 32px; height: 32px;"
                                        class="cursor-pointer block @if ($speakingRate >= 5) text-warning @endif"
                                        fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path
                                            d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                                    </svg>
                                </div>
                            </div>
                            <div
                                class="col-6 col-md-12 d-flex flex-column align-items-center justify-content-center barrating-stars">
                                <div class="form-group mt-4 ml-2 w-100">
                                    <label class="font-weight-bold" for="comment">Comment</label>
                                    <textarea wire:model="comment" style="min-width: 250px; min-height: 200px" name="comment"
                                        class="form-control @error('comment') is-invalid @enderror" rows="5"></textarea>
                                    <div class="invalid-feedback">
                                        @error('comment')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>

                        </div>
                        <button wire:click="sendFeedback()"
                            class="btn btn-sm btn-primary mt-20">{{ trans('panel.send_feedback') }}</button>

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
