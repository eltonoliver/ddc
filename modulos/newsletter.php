<?php if ($arquivoModulo == 'principal') { ?>
    <div id="conteudo-mkt-uga">
        <form class="form-newsletter" method="post" name="cadastro-mala-mkt-uga" id="cadastro-mala-mkt-uga">
            <div class="fl">
                <h4 class="form-newsletter__title">Newsletter</h4>
                <p>Receba nossas atualiações no seu e-mail</p>
            </div>

            <div class="fr">
                <input name="emailMktUgagogo" id="emailMktUgagogo" placeholder="seu@email.com" type="email" value="" />
                <input type="submit" value="Cadastrar" />
            </div>
        </form>
    </div>

    <div id="carregando-mkt-uga" style="display:none;">Carregando...</div>
<?php } ?>