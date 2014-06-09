<div id="divRadio">
    <h2 class="title-section">
        <span class="title-section__name">
            Rádio DDC
        </span>
    </h2>

    <audio preload="auto"></audio>

    <ol id="radio" class="list-radio">
        <?php
            $strRadio = "SELECT nmNomeArquivo,nmTituloArquivo FROM tb_arquivo a left join tb_arquivo_categoria t on t.idArquivo=a.idArquivo WHERE idCategoria = 5 AND inPublicar = 1 order by nmTituloArquivo";
            $qryRadio = $db->query($strRadio);

            foreach ($qryRadio as $musica) {
        ?>
            <li>
                <a href="#" data-src="arquivos/enviados/media/<?php echo $musica["nmNomeArquivo"]; ?>">
                    <?php echo $musica["nmTituloArquivo"]; ?>
                </a>
            </li>
        <?php } ?>
    </ol>
</div>