import RtmClient from './rtm-client';

(function ($) {
    "use strict";

    const chatView = $('#chatView');
    const agoraLoading = $('.agora-loading');

    const chatItemHtml = (message, memberName, date) => {
        return `<div class="chat-card mt-25 mx-15">
            <div>
                <span class="font-12 text-gray">${memberName}</span>
                <span class="ml-5 pl-5 border-left font-12 text-gray border-gray200">${date}</span>
            </div>

            <p class="bg-gray200 p-15 text-gray font-14 font-weight-500 mt-1 rounded-sm">${message}</p>
        </div>`;
    };

    const joinedHtml = (username) => {
        return `<div class="user-card d-flex align-items-center border border-gray200 p-15 mx-15 mt-25 rounded-sm">
            <div class="avatar">
                <img src="${userDefaultAvatar}" alt="" class="img-cover rounded-circle">
            </div>
            <div class="ml-10">
                <span class="font-14 font-weight-500 d-block text-dark">${username}</span>
                <span class="font-12 text-gray">${joinedToChannel}</span>
            </div>
        </div>`;
    };

    function handleLogin(rtm, callback) {
        if (rtm._logined) {
            return false;
        }

        try {
            rtm.init(appId);

            window.rtm = rtm;

            rtm.login(accountName, rtmToken).then(() => {
                console.log('login');
                rtm._logined = true;

                callback();
            }).catch((err) => {
                console.log(err);
            });
        } catch (err) {
            console.error(err);
        }
    }

    function handleJoinToChannel(rtm, callback) {

        if (!rtm._logined) {
            return false;
        }

        rtm.joinChannel(channelName).then(() => {
            chatView.append(joinedHtml(rtm.accountName));

            updateChatViewScroll();

            rtm.channels[channelName].joined = true;

            callback();
        }).catch((err) => {
            console.error(err);
        });
    }


    $(() => {
        const rtm = new RtmClient();

        // login user by token
        handleLogin(rtm, function () {

            // join to channel
            handleJoinToChannel(rtm, function () {
                agoraLoading.addClass('d-none');

                rtm.on('MemberJoined', ({channelName, args}) => {
                    const memberId = args[0];

                    chatView.append(joinedHtml(memberId));

                    updateChatViewScroll()
                });

                rtm.on('MemberLeft', ({channelName, args}) => {
                    const memberId = args[0];

                    //
                });

                rtm.on('ChannelMessage', ({channelName, args}) => {
                    const [message, memberId, other] = args;

                    const date = new Date(other.serverReceivedTs).toLocaleTimeString();
                    chatView.append(chatItemHtml(message.text, memberId, date));

                    updateChatViewScroll();
                });
            });
        });
    });

    function updateChatViewScroll() {
        const $chatView = $('#chatView');

        $chatView.scrollTop($chatView[0].scrollHeight);
    }

    function sendMessage() {
        if (!rtm._logined) {
            alert('Please Login First');
            return false;
        }

        const messageInput = $('#messageInput');
        const message = messageInput.val();

        if (message && message !== '') {
            rtm.sendChannelMessage(message, channelName).then(() => {
                const date = new Date().toLocaleTimeString();

                chatView.append(chatItemHtml(message, rtm.accountName, date));

                updateChatViewScroll();

                messageInput.val('');
            }).catch((err) => {
                console.error(err);
            });
        }
    }

    $('body').on('click', '#sendMessage', function (e) {
        e.preventDefault();

        sendMessage();
    });
})(jQuery);

