function showLastVideoPopup(data) {
    var feed = data.feed;
    var entry = feed.entry[0];
    var playerUrl = entry.media$group.media$content[0].url;
    var idVideo = entry.media$group.yt$videoid.$t;
    $('#youtube').html('<div class="video"><iframe id="ytplayer" type="text/html" width="100%" height="300" src="//www.youtube.com/embed/' + idVideo + '?html5=1"  frameborder="0" allowfullscreen></iframe><a class="more-videos" href="videos">Mais vídeos</a></div>');
}
function showLastVideo(data) {
    var feed = data.feed;
    var entry = feed.entry[0];
    var playerUrl = entry.media$group.media$content[0].url;
    var descricaoVideo = entry.media$group.media$description.$t.substr(0, 250);
    $('#lastVideo .i-box-img').html('<iframe id="ytplayer" type="text/html" width="285" height="214" src="' + playerUrl + '"  frameborder="0" allowfullscreen></iframe>');
    $('#lastVideo p').prepend(descricaoVideo);
}
function showMyVideo(data) {
    var feed = data.feed;
    var entry = feed.entry[0];
    var playerUrl = entry.media$group.media$content[0].url;
    var title = entry.title.$t;
    var d = new Date(entry.published.$t);
    var dataVideo = d.getDate() + "/" + (d.getMonth() + 1) + "/" + d.getFullYear();
    var descricaoVideo = entry.media$group.media$description.$t.substr(0, 155);
    $('.post-title').append(title);
    $('.post-meta').append(dataVideo);
    $('.post-video').append('<iframe width="640" height="480" src="' + playerUrl + '" frameborder="0" allowfullscreen></iframe>');
    $('.post-text p').html(descricaoVideo);


}
function showMyVideos(data) {
    var feed = data.feed;
    var entries = feed.entry || [];
    var html = [];
    //total de vídeos
    var totalVideos = feed.openSearch$totalResults.$t;
    //páginas de vídeos
    var paginasVideo = feed.link.length;
    var summary = feed.media$group.media$description.$t.substr(0, 780);
    $('#descricao-lista').html(summary);
    for (var i = 0; i < entries.length; i++) {
        var entry = entries[i];
        var playerUrl = entries[i].media$group.media$content[0].url;
        var title = entry.title.$t.substr(0, 45);
        var d = new Date(entries[i].published.$t);
        var dataVideo = d.getDate() + "/" + (d.getMonth() + 1) + "/" + d.getFullYear();
        var descricaoVideo = entries[i].media$group.media$description.$t.substr(0, 50) + "...";

        var idVideo = entries[i].media$group.yt$videoid.$t;
        $('.video').append('<article class="video-item"><a title="' + title + '" href="videos/v/' + idVideo + '"><img alt="' + title + '" src="//i1.ytimg.com/vi/' + idVideo + '/hqdefault.jpg" /></a><span style="display:block; width:239px; height:35px; font:font-family: arial, sans-serif; "> ' + title + ' </span></article>');
    }
    //$('.video').html(html.join(','));
    if (paginasVideo) {
        indexVideo = paginaVideo * limiteVideos - (limiteVideos - 1);
        videosPagina = indexVideo + limiteVideos - 1;
        if (videosPagina > totalVideos)
            videosPagina = totalVideos;
        if (paginaVideo != 1) {
            $("#paginacaoVideos").append('<li><a href="ddc-programas/titulo/' + tituloPrograma + '/p/' + (paginaVideo - 1) + '">Página anterior</a></li>');
        } else {
            $("#paginacaoVideos").append('<li><a class="off" href="javascript:void(0);">Página anterior</a></li>');
        }
        $("#paginacaoVideos").append('<li>( Resultados <b>' + indexVideo + '</b> a <b>' + videosPagina + '</b> de <b>' + totalVideos + '</b> )</li> ');
        if (paginaVideo != paginasVideo) {
            $("#paginacaoVideos").append('<li><a href="ddc-programas/titulo/' + tituloPrograma + '/p/' + (paginaVideo + 1) + '">Próxima página</a></li>');
        } else {
            $("#paginacaoVideos").append('<li><a class="off" href="javascript:void(0);">Próxima página</a></li>');
        }
    }
}
