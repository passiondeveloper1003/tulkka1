<div class="rounded d-flex w-100 chat-page" id="frame">
    <div class="chat-content">
        @if ($selectedContact)
            <div class="contact-profile rounded">
                <img class="chat-img" src="{{ $selectedContact->getAvatar() }}" alt="" />
                <p class="font-12 ml-10">{{ $selectedContact->full_name }}</p>
            </div>

            <div class="messages">
                <ul class="chat-messages-container">
                    @if (isset($messages) && count($messages) > 0)
                        @foreach ($messages as $message)
                            <li class="@if ($message->from_user == auth()->user()->id) sent @else replies @endif font-12">
                                <p class="@if ($message->from_user == auth()->user()->id) bg-tgray @else bg-primary @endif">
                                    @if (filter_var($message->body, FILTER_VALIDATE_URL) && !$message->attachment)
                                        <a class="text-black" href="{{ $message->body }}">{{ $message->body }}</a>
                                    @else
                                        {{ $message->body }}
                                    @endif
                                    @if ($message->attachment)
                                        <a target="_blank" href="{{ $message->attachment }}"
                                            class="text-dark-blue d-block">
                                            File : {{ $message->attachment_name }}
                                        </a>
                                    @endif
                                </p>


                            </li>
                        @endforeach
                    @endif
                </ul>
                <div class="chat-message-input">
                    <div class="wrap w-100 d-flex">
                        <input wire:keydown.enter="sendMessage()" class="rounded chat-message-text flex-grow-1 pl-10 pl-xl-40"
                            wire:model.debounce.300ms="writtenMessage" type="text"
                            placeholder="{{ trans('update.chat_message') }}" style="color: #ccc;" />
                        <input id="mainFile" class="d-none" type="file" wire:model="chatfile">
                        <!-- <span class="p-2 bg-primary rounded position-absolute chat-zoom h-100 d-none d-lg-block">
                            <img src="/assets/default/img/section-icons/screen.svg" width="14" height="14" style="margin-top: -3px;"/>
                        </span> -->
                        <span class="p-2 bg-primary rounded position-absolute chat-attachment h-100 d-none d-lg-block"><i id="chatFile"
                                class=" fa fa-paperclip attachment"></i></span>

                        <span wire:click="sendMessage()" class="submit rounded bg-primary h-100 chat-send position-absolute p-1 p-xl-2 text-white text-center d-flex justify-content-center align-items-center">
                            <span class="d-none d-md-block">{{ trans('update.send') }}</span>
                            <span class="d-md-none"><img src="/assets/default/img/section-icons/send.svg" /></span>                            
                        </span>
                    </div>
                </div>
            </div>

        @endif
    </div>
    
    <div class="rounded p-md-2 chat-sidepanel" id="sidepanel">
        <div class="rounded chat-search input-group d-none d-md-flex" id="search">
            <input wire:model="search" type="text" placeholder="{{ trans('update.search_contacts') }}"
                class="form-control" />
            <span class="fa-solid fa-magnifying-glass text-gray search-icon"></span>
        </div>
        <div id="contacts">
            <ul>
                @foreach ($students as $student)
                    <li wire:click="setSelectedContact({{ $student->id }},'{{ $student->full_name }}')"
                        class="contact">
                        <div class="wrap">
                            <span class="contact-status @if (\Carbon\Carbon::createFromTimestamp($student->updated_at)->diffInMinutes(\Carbon\Carbon::now()->subMinute(20)) < 25) online @endif"></span>
                            <div class="text-center text-md-left">
                                <img class="chat-img" src="{{ $student->getAvatar() }}" alt="" />


                                <span class="font-12 d-none d-md-inline">{{ $student->full_name }}</span>
                                @if (auth()->user()->recivedUnreadedMessagesFrom($student->id)->count() > 0)
                                    <p class="text-danger">You have
                                        {{ auth()->user()->recivedUnreadedMessagesFrom($student->id)->count() }}
                                        unread
                                        messages </p>
                                @endif

                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
