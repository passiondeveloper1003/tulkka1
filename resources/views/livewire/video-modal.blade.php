<div>
    <div>
        <div class="video-modal modal fade @if ($show === true) show @endif" id="myExampleModal"
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
                    <div class="modal-body is-video d-flex flex-column align-items-md-center text-center">
                        @if ($type == 'local')
                            <video width="1024" height="768" controls>
                                <source src="{{ asset($video_link) }}" type="video/mp4">
                            </video>
                        @endif
                        @if ($type == 'youtube')
                        <iframe width="1024" height="768" src="{{ $video_link }}" title="YouTube video player"
                        frameborder="0"
                        modestbranding=1
                        controls=0
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen></iframe>

                        @endif
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
