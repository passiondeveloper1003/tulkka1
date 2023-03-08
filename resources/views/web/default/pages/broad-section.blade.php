<div class="broad-container container px-lg-50 px-xl-0">
    <div class="row">
        <div class="col-12 d-flex flex-column align-items-center">
            <h3 class="font-40 position-relative text-center">{{ trans('update.get_to_know') }}
                <div class="custom-highlight position-absolute right-0"><img src="/assets/default/img/highlight.png" />
                <!-- <img src="/assets/default/img/rating.svg" />     -->
                </div>
            </h3>
        </div>

        <div class="col-12 mt-20 mt-md-40 position-relative">
            <span class="pag-left position-absolute bg-primary rounded p-2 d-none d-lg-block"><i
                    class="fa-solid fa-chevron-left text-white "></i></span>
            <div style="width:100%;height;400px" class="swiper-container question-swiper-container  ">
                <div class=" swiper-wrapper">
                    @forelse($instructors as $instructor)
                        <div class="swiper-slide position-relative">
                            <div class="broad-card p-10 bg-white d-flex flex-column justify-content-center rounded"
                                onclick='Livewire.emit("showVideoModal","{{ $instructor->full_name . ' Instruction Video' }}", "{{ $instructor->video_demo }}"
                              )'>
                                <div class="broad-card-header">
                                    <img src="/assets/default/img/picture.jpg" width="24" heading="24" />
                                    <span>Kyle Walker</span>
                                </div>
                                <div class="position-relative">
                                    <img src="{{ '/store/' . $instructor->video_demo_thumb }}" alt="" class="w-100 broad-card-video">
                                    <div class="hero-video-icon d-flex align-items-center justify-content-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-play"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
                                    </div>
                                </div>

                                <div class="broad-card-bottom w-100 d-flex align-items-center justify-content-between">
                                    <a class="btn btn-sm btn-primary rounded font-12" href="{{url('users/'.$instructor->id.'/profile')}}">{{trans('update.teacher_profile')}}</a>
                                    <div class="d-flex align-items-center broad-card-btn">
                                        <img src="/assets/default/img/ratings.svg" alt="rating" />
                                        4.8
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" swiper-slide position-relative">
                          <div class="broad-card p-10 bg-white d-flex flex-column justify-content-center rounded"
                              onclick='Livewire.emit("showVideoModal","{{ $instructor->full_name . ' Instruction Video' }}", "{{ $instructor->video_demo }}"
                            )'>
                            <div class="broad-card-header">
                                    <img src="/assets/default/img/picture.jpg" width="24" heading="24" />
                                    <span>Kyle Walker</span>
                                </div>
                            
                                <div class="position-relative">
                                    <img src="{{ '/store/' . $instructor->video_demo_thumb }}" alt="" class="w-100 broad-card-video">
                                    <div class="hero-video-icon d-flex align-items-center justify-content-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-play"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
                                    </div>
                                </div>

                              <div class="broad-card-bottom w-100 d-flex align-items-center justify-content-between">
                                  <a class="btn btn-sm btn-primary rounded font-12" href="{{url('users/'.$instructor->id.'/profile')}}">{{trans('update.teacher_profile')}}</a>
                                     <div class="d-flex align-items-center broad-card-btn">
                                        <img src="/assets/default/img/ratings.svg" alt="rating" />
                                        4.8
                                    </div>
                                </div>
                          </div>
                        </div>
                        <div class=" swiper-slide position-relative">
                          <div class="broad-card p-10 bg-white d-flex flex-column justify-content-center rounded"
                              onclick='Livewire.emit("showVideoModal","{{ $instructor->full_name . ' Instruction Video' }}", "{{ $instructor->video_demo }}"
                            )'>
                            <div class="broad-card-header">
                                <img src="/assets/default/img/picture.jpg" width="24" heading="24" />
                                <span>Kyle Walker</span>
                            </div>
                            
                                <div class="position-relative">
                                    <img src="{{ '/store/' . $instructor->video_demo_thumb }}" alt="" class="w-100 broad-card-video">
                                    <div class="hero-video-icon d-flex align-items-center justify-content-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-play"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
                                    </div>
                                </div>

                              <div class="broad-card-bottom w-100 d-flex align-items-center justify-content-between">
                                  <a class="btn btn-sm btn-primary rounded font-12" href="{{url('users/'.$instructor->id.'/profile')}}">{{trans('update.teacher_profile')}}</a>
                                     <div class="d-flex align-items-center broad-card-btn">
                                        <img src="/assets/default/img/ratings.svg" alt="rating" />
                                        4.8
                                    </div>
                                </div>
                          </div>
                        </div>
                        <div class=" swiper-slide position-relative">
                          <div class="broad-card p-10 bg-white d-flex flex-column justify-content-center rounded"
                              onclick='Livewire.emit("showVideoModal","{{ $instructor->full_name . ' Instruction Video' }}", "{{ $instructor->video_demo }}"
                            )'>
                            <div class="broad-card-header">
                                    <img src="/assets/default/img/picture.jpg" width="24" heading="24" />
                                    <span>Kyle Walker</span>
                                </div>
                            
                                <div class="position-relative">
                                    <img src="{{ '/store/' . $instructor->video_demo_thumb }}" alt="" class="w-100 broad-card-video">
                                    <div class="hero-video-icon d-flex align-items-center justify-content-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-play"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
                                    </div>
                                </div>

                              <div class="broad-card-bottom w-100 d-flex align-items-center justify-content-between">
                                  <a class="btn btn-sm btn-primary rounded font-12" href="{{url('users/'.$instructor->id.'/profile')}}">{{trans('update.teacher_profile')}}</a>
                                     <div class="d-flex align-items-center broad-card-btn">
                                        <img src="/assets/default/img/ratings.svg" alt="rating" />
                                        4.8
                                    </div>
                                </div>
                          </div>
                        </div>
                        <div class=" swiper-slide position-relative">
                          <div class="broad-card p-10 bg-white d-flex flex-column justify-content-center rounded"
                              onclick='Livewire.emit("showVideoModal","{{ $instructor->full_name . ' Instruction Video' }}", "{{ $instructor->video_demo }}"
                            )'>
                            <div class="broad-card-header">
                                    <img src="/assets/default/img/picture.jpg" width="24" heading="24" />
                                    <span>Kyle Walker</span>
                                </div>
                            
                                <div class="position-relative">
                                    <img src="{{ '/store/' . $instructor->video_demo_thumb }}" alt="" class="w-100 broad-card-video">
                                    <div class="hero-video-icon d-flex align-items-center justify-content-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-play"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
                                    </div>
                                </div>

                              <div class="broad-card-bottom w-100 d-flex align-items-center justify-content-between">
                                  <a class="btn btn-sm btn-primary rounded font-12" href="{{url('users/'.$instructor->id.'/profile')}}">{{trans('update.teacher_profile')}}</a>
                                     <div class="d-flex align-items-center broad-card-btn">
                                        <img src="/assets/default/img/ratings.svg" alt="rating" />
                                        4.8
                                    </div>
                                </div>
                          </div>
                        </div>
                    @empty
                    @endforelse
                </div>
            </div>
            <span class="pag-right position-absolute bg-primary rounded p-2 d-none d-lg-block"><i
                    class="fa-solid fa-chevron-right text-white"></i></span>
        </div>
    </div>
</div>
