<div>
    <div>
        <div class="modal fade @if ($show === true) show @endif" id="myExampleModal2"
            style="display: @if ($show === true) block
   @else
           none @endif;" tabindex="-1"
            role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
                <div style="width: 1024px; height: 768px;" class="modal-content animate__bounceIn">
                    <div class="modal-header">
                        <h5 class="modal-title font-20 mx-2" id="exampleModalLabel">{{ $title }}</h5>
                        <button class="close" type="button" aria-label="Close" wire:click.prevent="doClose()">
                          <img src="{{ url('/assets/default/img/close.png') }}">
                        </button>
                    </div>
                    <div class="modal-body d-flex flex-column align-items-md-center text-center">
                      <div class="row w-100 d-flex justify-content-center">
                        <div class="col-12 d-flex flex-column align-items-center">
                            <div class="form-group mt-4 ml-2 w-100">
                                <label class="font-weight-bold font-20" for="comment">Zoom Link </label>
                                <input wire:model="zoom_url" name="title"
                                    class="form-control @error('title') is-invalid @enderror" rows="5" />
                                <div class="invalid-feedback">
                                    @error('zoom_url')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row w-100 d-flex justify-content-center">
                      <div class="col-12 d-flex flex-column align-items-center">
                          <div class="form-group mt-4 ml-2 w-100">
                              <label class="font-weight-bold font-20" for="comment">Zoom Admin Link </label>
                              <input wire:model="zoom_admin_url" name="title"
                                  class="form-control @error('title') is-invalid @enderror" rows="5" />
                              <div class="invalid-feedback">
                                  @error('zoom_admin_url')
                                      {{ $message }}
                                  @enderror
                              </div>
                          </div>
                      </div>
                  </div>
                  <button wire:click="updateLesson()"
                class="btn btn-sm btn-primary mt-20">{{ trans('panel.update') }}</button>
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
