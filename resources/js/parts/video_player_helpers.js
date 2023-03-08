var fileVideoPlayer;

window.makeVideoPlayerHtml = function (path, storage, height, tagId) {
    let html = '';
    let options = {
        autoplay: false,
        preload: 'auto',
    };

    if (storage === 'youtube' || storage === 'vimeo') {
        html = '<video id="' + tagId + '" class="video-js" width="100%" height="' + height + '"></video>';

        options = {
            controls: (storage !== 'vimeo'),
            ytControls: true,
            autoplay: false,
            preload: 'auto',
            techOrder: ['html5', storage],
            sources: [{
                src: path,
                type: "video/" + storage
            }]
        };
    } else {
        html = '<video id="' + tagId + '" oncontextmenu="return false;" controlsList="nodownload" class="video-js" controls preload="auto" width="100%" height="' + height + '"><source src="' + path + '" type="video/mp4"/></video>';
    }

    return {
        html: html,
        options: options,
    };
};

window.handleVideoByFileId = function (fileId, $contentEl, callback) {

    closeVideoPlayer();

    const height = $(window).width() > 991 ? 426 : 264;

    $.post('/course/getFilePath', {file_id: fileId}, function (result) {

        if (result && result.code === 200) {
            const storage = result.storage;

            const videoTagId = 'videoPlayer';

            const {html, options} = makeVideoPlayerHtml(result.path, storage, height, videoTagId);

            $contentEl.html(html);

            fileVideoPlayer = videojs(videoTagId, options);

            callback();
        } else {
            $.toast({
                heading: notAccessToastTitleLang,
                text: notAccessToastMsgLang,
                bgColor: '#f63c3c',
                textColor: 'white',
                hideAfter: 10000,
                position: 'bottom-right',
                icon: 'error'
            });
        }
    }).fail(err => {
        $.toast({
            heading: notAccessToastTitleLang,
            text: notAccessToastMsgLang,
            bgColor: '#f63c3c',
            textColor: 'white',
            hideAfter: 10000,
            position: 'bottom-right',
            icon: 'error'
        });
    });
};

window.closeVideoPlayer = function () {
    if (fileVideoPlayer !== undefined) {
        fileVideoPlayer.dispose();
    }
};

window.pauseVideoPlayer = function () {
    if (fileVideoPlayer !== undefined) {
        fileVideoPlayer.pause();
    }
};


