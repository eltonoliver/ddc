<h2 class="title-section">
    <span class="title-section__name">
        Vídeos
    </span>

    <a class="title-section__more" href="javascript:history.back(-1);">
        Voltar
    </a>
</h2>

<?php if (!$_GET['v']) { ?>

    <div class="list-video" id="grid"></div>

    <ul class="paginacao" id="paginacaoVideos"></ul>

<?php } else { ?>

    <article class="post">
        <div class="post-video iframe-responsive"></div>

        <h3 class="post-title"></h3>

        <div class="post-meta">
            Em: 
        </div>

        <div class="post-text">
            <p></p>
        </div>
    </article>

<?php } ?>