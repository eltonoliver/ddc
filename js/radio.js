$(function() {
    $(".radio-ddc").click(function(event) {
        event.preventDefault();
        window.open(url_raiz + 'radio/ajax/1', 'Rádio', 'status=no,toolbar=no,location=no,menubar=no,resizable=yes,scrollbars=yes,height=160,width=320');
    });
    if ($("#divRadio").length > 0) {
        var alturaRadio = 180 + $("#radio").height();
        window.resizeTo(320, alturaRadio);
        // Setup the player to autoplay the next track
        var a = audiojs.createAll({
            trackEnded: function() {
                var next = $('ol li.playing').next();
                if (!next.length)
                    next = $('ol li').first();
                next.addClass('playing').siblings().removeClass('playing');
                audio.load($('a', next).attr('data-src'));
                audio.play();
            }
        });
        // Load in the first track
        var audio = a[0];
        first = $('ol a').attr('data-src');
        $('ol li').first().addClass('playing');
        audio.load(first);
        //audio.play();
        if (audio.settings.useFlash) {
            window.setTimeout("$('ol li').first().click();", 1000);
        } else {
            audio.play();
        }

        // Load in a track on click
        $('ol li').click(function(e) {
            e.preventDefault();
            $(this).addClass('playing').siblings().removeClass('playing');
            audio.load($('a', this).attr('data-src'));
            audio.play();
        });
        $('.play').click(function() {
            audio.play();
        });
        $('.pause').click(function() {
            audio.pause();
        });
        $('.prev').click(function() {
            var prev = $('li.playing').prev();
            if (!prev.length)
                prev = $('ol li').last();
            prev.click();
        });
        $('.next').click(function() {
            var next = $('li.playing').next();
            if (!next.length)
                next = $('ol li').first();
            next.click();
        });
        // Keyboard shortcuts
        $(document).keydown(function(e) {
            var unicode = e.charCode ? e.charCode : e.keyCode;
            // right arrow
            if (unicode == 39) {
                var next = $('li.playing').next();
                if (!next.length)
                    next = $('ol li').first();
                next.click();
                // back arrow
            } else if (unicode == 37) {
                var prev = $('li.playing').prev();
                if (!prev.length)
                    prev = $('ol li').last();
                prev.click();
                // spacebar
            } else if (unicode == 32) {
                audio.playPause();
            }
        })
    }
});