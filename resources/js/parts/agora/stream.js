(function ($) {
    "use strict";

    var liveEndedHtml = `<div class="no-result default-no-result d-flex align-items-center justify-content-center flex-column w-100 h-100">
        <div class="no-result-logo">
            <img src="/assets/default/img/no-results/support.png" alt="">
        </div>
        <div class="d-flex align-items-center flex-column mt-30 text-center">
            <h2 class="text-dark-blue">${liveEndedLang}</h2>
            <p class="mt-5 text-center text-gray font-weight-500">${redirectToPanelInAFewMomentLang}</p>
        </div>
    </div>`;

    var featherIconsConf = {width: 20, height: 20};

    // create Agora client
    var client = AgoraRTC.createClient({
        mode: "live",
        codec: "vp8",
    });

    var localTracks = {
        videoTrack: null,
        audioTrack: null,
        screenAudioTrack: null,
        screenVideoTrack: null,
        shareScreenActived: false
    };

    var remoteUsers = {};

    // Agora client options
    var options = {
        appid: appId,
        channel: channelName,
        uid: null,
        token: rtcToken,
        role: streamRole, // host or audience
        audienceLatency: 2
    };

    var $streamPlayerEl = $('#stream-player');
    var $shareScreenButton = $('#shareScreen');

    async function handleJoinOrCreateStream() {
        try {

            if (options.role === "audience") {
                client.setClientRole(options.role, {level: options.audienceLatency});

                client.on("user-published", handleUserPublished);
                client.on("user-unpublished", handleUserUnpublished);
                client.on("user-left", handleEndLive);
            } else {
                client.setClientRole(options.role);
            }

            client.on("user-joined", handlePeerOnline);

            // join the channel
            options.uid = await client.join(
                options.appid,
                options.channel,
                options.token || null,
                options.uid || null
            );

            if (options.role === "host") {
                // create local audio and video tracks
                localTracks.audioTrack = await AgoraRTC.createMicrophoneAudioTrack();
                localTracks.videoTrack = await AgoraRTC.createCameraVideoTrack();
                // play local video track
                localTracks.videoTrack.play("stream-player");

                // publish local tracks to channel
                await client.publish([localTracks.videoTrack, localTracks.audioTrack]);

                const startAt = (streamStartAt && streamStartAt > 0) ? (new Date().getTime() / 1000) - streamStartAt : 0;
                handleTimer(startAt);

                console.log("publish success");

                $(".agora-stream-loading").addClass('d-none');
            }
        } catch (error) {
            console.error(error);
        }
    }

    handleJoinOrCreateStream();

    function handlePeerOnline(evt) {
        console.log('#################### Online')
        console.log(evt)
    }

    async function subscribe(user, mediaType) {
        const uid = user.uid;
        // subscribe to a remote user
        await client.subscribe(user, mediaType);
        console.log("subscribe success");
        if (mediaType === 'video') {
            const player = $(`
              <div id="player-wrapper-${uid}" class="w-100 h-100">
                <div id="player-${uid}" class="player"></div>
              </div>
            `);

            $streamPlayerEl.html(player);
            user.videoTrack.play(`player-${uid}`);
        }

        if (mediaType === 'audio') {
            user.audioTrack.play();
        }

        $(".agora-stream-loading").addClass('d-none');
        $("#notStartedAlert").removeClass('d-flex');
        $("#notStartedAlert").addClass('d-none');

        const startAt = (streamStartAt && streamStartAt > 0) ? (new Date().getTime() / 1000) - streamStartAt : 0;
        handleTimer(startAt);
    }

    async function leave() {
        for (let trackName in localTracks) {
            const track = localTracks[trackName];

            if (track) {
                track.stop();
                track.close();
                localTracks[trackName] = undefined;
            }
        }

        // remove remote users and player views

        // leave the channel
        await client.leave();

        if (redirectAfterLeave) {
            window.location = redirectAfterLeave;
        }
        console.log("client leaves channel success");
    }

    function handleUserPublished(user, mediaType) {
        const id = user.uid;
        remoteUsers[id] = user;

        subscribe(user, mediaType);
    }

    function handleUserUnpublished(user, mediaType) {
        if (mediaType === 'video') {
            const id = user.uid;
            delete remoteUsers[id];
            $(`#player-wrapper-${id}`).html('');
        }
    }

    function handleEndLive(user, mediaType) {
        const id = user.uid;

        $(`#player-wrapper-${id}`).html(liveEndedHtml);

        setTimeout(() => {
            if (redirectAfterLeave) {
                window.location = redirectAfterLeave;
            }
        }, 5000);
    }

    async function handleShareScreen() {
        if (!localTracks.shareScreenActived) {
            let screenTrack;


            // join a channel and create local tracks, we can use Promise.all to run them concurrently
            [screenTrack] = await Promise.all([
                AgoraRTC.createScreenVideoTrack({
                    encoderConfig: {
                        framerate: 30,
                        height: 720,
                        width: 1280
                    }
                }, "auto")
            ]);

            if (screenTrack instanceof Array) {
                localTracks.screenVideoTrack = screenTrack[0];
                localTracks.screenAudioTrack = screenTrack[1];
            } else {
                localTracks.screenVideoTrack = screenTrack;
            }

            // play local video track
            if (localTracks.screenVideoTrack) {

                $shareScreenButton.prop('disabled', true);

                localTracks.screenVideoTrack.play("stream-player");
                // publish local tracks to channel

                handleCameraEffect(true);

                await client.publish([localTracks.screenVideoTrack, localTracks.audioTrack]);

                localTracks.shareScreenActived = true;

                localTracks.screenVideoTrack.on("track-ended", () => {

                    $shareScreenButton.prop('disabled', false);

                    client.unpublish(localTracks.screenVideoTrack).then(() => {
                        localTracks.screenVideoTrack.stop();
                        localTracks.screenVideoTrack.close();

                        localTracks.shareScreenActived = false;

                        handleCameraEffect(false);
                    });
                });
            }
        }
    }

    $('body').on('click', '#leave', function (e) {
        const $this = $(this);
        const sessionId = $this.attr('data-id');

        $this.addClass('loadingbar primary').prop('disabled', true);

        const path = '/panel/sessions/' + sessionId + '/endAgora';

        $.get(path, function (result) {
            if (result && result.code === 200) {
                leave();
            }
        });
    });

    $('body').on('click', '#shareScreen', function (e) {
        handleShareScreen();
    });

    $('body').on('click', '#microphoneEffect', function (e) {
        const $this = $(this);

        let icon = feather.icons['mic'].toSvg(featherIconsConf);

        if (localTracks.audioTrack) {
            if ($this.hasClass('active')) {
                $this.removeClass('active');
                $this.addClass('disabled');

                icon = feather.icons['mic-off'].toSvg(featherIconsConf);

                client.unpublish(localTracks.audioTrack);
            } else {
                $this.addClass('active');
                $this.removeClass('disabled');

                client.publish(localTracks.audioTrack);
            }
        }

        $this.find('.icon').html(icon);
    });

    $('body').on('click', '#cameraEffect', function (e) {
        const $this = $(this);

        if (!localTracks.shareScreenActived) {
            handleCameraEffect($this.hasClass('active'));
        }
    });

    async function handleCameraEffect(isActive = false) {
        const $button = $('#cameraEffect');

        let icon = feather.icons['video'].toSvg(featherIconsConf);

        if (isActive) {
            $button.removeClass('active');
            $button.addClass('disabled');

            icon = feather.icons['video-off'].toSvg(featherIconsConf);

            if (localTracks.videoTrack) {
                localTracks.videoTrack.stop();
                localTracks.videoTrack.close();

                client.unpublish(localTracks.videoTrack);
            }
        } else {
            $button.addClass('active');
            $button.removeClass('disabled');

            localTracks.videoTrack = await AgoraRTC.createCameraVideoTrack();

            localTracks.videoTrack.play("stream-player");
            client.publish(localTracks.videoTrack);
        }

        $button.find('.icon').html(icon);
    }

    function handleTimer(startAt = 0) {
        const streamTimer = $('#streamTimer');

        const hoursLabel = streamTimer.find('.hours');
        const minutesLabel = streamTimer.find('.minutes');
        const secondsLabel = streamTimer.find('.seconds');

        let totalSeconds = startAt;

        setInterval(setTime, 1000);

        function setTime() {
            ++totalSeconds;
            const seconds = pad(Math.floor((totalSeconds) % 60));
            const minutes = pad(Math.floor((totalSeconds / 60) % 60));
            const hours = pad(Math.floor((totalSeconds / (60 * 60)) % 24));

            hoursLabel.html(hours);
            minutesLabel.html(minutes);
            secondsLabel.html(seconds);
        }

        function pad(val) {
            var valString = val + "";
            if (valString.length < 2) {
                return "0" + valString;
            } else {
                return valString;
            }
        }
    }

    $('body').on('click', '#collapseBtn', function () {
        const $tabs = $('.agora-tabs');

        $tabs.toggleClass('show');
    });
})(jQuery);
