function voltarMktCaixa() {
    $('#carregando-mkt-uga').animate({
        'opacity': 0
    }, 'slow', function() {
        $(this).css('display', 'none');

        $('#conteudo-mkt-uga').css('opacity', 0).css('display', 'block');

        $('#conteudo-mkt-uga').animate({
            'opacity': 1
        }, 'slow', function() {});
    });
}

function mktUgaCadastrar() {
    //var idEmpresa = 0;//deve ser trocado manualmente conforme cada cliente
    $('#conteudo-mkt-uga').animate({
        'opacity': 0
    }, 'slow', function() {
        $(this).css('display', 'none');
        $('#carregando-mkt-uga').html('<img src="img/loading.gif"/>');
        $('#carregando-mkt-uga img').show();
        $('#carregando-mkt-uga').css({
            'color': '#666',
            'font-weight': 'bold',
            'text-align': 'center',
            'height': '40px',
            'line-height': '40px',
            'margin-bottom': '30px'
        });
        $('#carregando-mkt-uga').css('opacity', 0).css('display', 'block');
        $('#carregando-mkt-uga').animate({
            'opacity': 1
        }, 'slow', function() {
            $.ajaxSetup({
                url: 'controller/boletimController',
                type: "POST",
                success: function(data) {
                    var s = data;
                    switch (s.status) {
                        case 0:
                            $('#carregando-mkt-uga').html('<span class="error">Não foi possível cadastrar seu e-mail.</span>');
                            break;
                        case 1:
                            $('#carregando-mkt-uga').html('<span class="done">Cadastro realizado com sucesso.</span>');
                            break;
                        case 2:
                            $('#carregando-mkt-uga').html(s.msg ? '<span class="error">' + s.msg + '</span>' : '<span class="error">Não foi possível cadastrar seu e-mail.</span>');
                            break;
                    }
                    setTimeout('voltarMktCaixa()', 1000);
                },
                data: {
                    'grupos[0]': 3,
                    'acao': 'cadastrarEmailBoletim',
                    'email': $('#emailMktUgagogo').val()
                            //'idEmpresa': idEmpresa
                },
                dataType: 'json'
                        //jsonpCallback:'jsonpcallback', isso só se for necessário
                        //jsonp: 'callback'
            });
            $.ajax();
        });
    });
}
$(function() {
    $("#cadastro-mala-mkt-uga").submit(function(e) {
        e.preventDefault();
        mktUgaCadastrar();
    });
});