$(function() {
    $(".fancybox").fancybox();
    //Drag to share
    var images = $(".fancybox img"), title = $("title").text() || document.title;
    //make images draggable                      
    images.draggable({
        //create draggable helper
        helper: function() {
            return $("<div>").attr("id", "helper").html("<span>" + title + "</span><img id='thumb' src='" + $(this).attr("src") + "'>").appendTo($(this).parents('.grid'));
        },
        cursor: "pointer",
        cursorAt: {left: -10, top: 20},
        zIndex: 99999,
        //show overlay and targets
        start: function() {
            $("<div>").attr("id", "overlay").css("opacity", 0.7).appendTo(".grid");
            $("#tip").remove();
            $(this).unbind("mouseenter");
            $("#targets").css("left", ($("body").width() / 2) - $("#targets").width() / 2).slideDown();
        },
        //remove targets and overlay
        stop: function() {
            $("#targets").slideUp();
            $(".share", "#targets").remove();
            $("#overlay").remove();
            $(this).bind("mouseenter", createTip);
        }
    });
    //make targets droppable
    $("#targets li").droppable({
        tolerance: "pointer",
        //show info when over target
        over: function() {
            $(".share", "#targets").remove();
            $("<span>").addClass("share").text("Compartilhar no " + $(this).attr("id")).addClass("active").appendTo($(this)).fadeIn();
        },
        drop: function(event, ui) {
            var id = $(this).attr("id"),
                    draggable = ui.draggable,
                    currentUrl = draggable.attr("linkCurto"),
                    baseUrl = $(this).find("a").attr("href");
            if (id.indexOf("twitter") != -1) {
                window.open(baseUrl + "/home?status=" + title + ": " + currentUrl, 'compartilharTwitter', "width=300, height=300, top=0, left=0, scrollbars=no, status=no, toolbar=no, location=no, directories=no, menubar=no, resizable=no, fullscreen=no");
            } else if (id.indexOf("facebook") != -1) {
                window.open(baseUrl + "/sharer.php?u=" + currentUrl + "&t=" + title, 'compartilharFacebook', "width=300, height=300, top=0, left=0, scrollbars=no, status=no, toolbar=no, location=no, directories=no, menubar=no, resizable=no, fullscreen=no");
            }
        }
    });
    var createTip = function(e) {
        //create tool tip if it doesn't exist
        ($("#tip").length === 0) ? $("<div>").html("<span>Arraste para compartilhar</span><span class='arrow'></span>").attr("id", "tip").css({left: e.pageX + 30, top: e.pageY - 16}).appendTo("body").fadeIn(500) : null;
    };
    images.bind("mouseenter", createTip);
    images.mousemove(function(e) {
        //move tooltip
        $("#tip").css({left: e.pageX + 30, top: e.pageY - 16});
    });
    images.mouseleave(function() {
        //remove tooltip
        $("#tip").remove();
    });
});