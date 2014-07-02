function isset() {
    var a = arguments, b = a.length, c = 0, d;
    if (b === 0) {
        throw new Error("Empty isset")
    }
    while (c !== b) {
        if (a[c] === d || a[c] === null) {
            return false
        }
        c++
    }
    return true
}
function ugaAlert(a, b) {
    $("#" + a).mouseenter(function(a) {
        var c = null;
        c = document.getElementById("layer_popup_ugaMsg") ? $("#layer_popup_ugaMsg") : $('<div id="layer_popup_ugaMsg"></div>');
        $(c).css({background: "#FFFFFF", position: "absolute", "z-index": 1e4, display: "none", padding: 10, border: "1px solid #ccc"}).html(b);
        $("body").append($(c));
        var d = $(c).height();
        var e = $(c).width();
        var f = a.pageX + 10 + "px";
        var g = a.pageY - 30 + "px";
        $(c).css({left: f, top: g}).fadeIn("fast");
        $(this).mouseleave(function(a) {
            $(c).fadeOut("fast")
        })
    })
}
function popupFrameFechar() {
    $("#layer_popup_frame").remove();
    $("#layer_popup_conteudo_frame").remove()
}
function popupFrame(a, b, c) {
    b = b ? b : 400;
    c = c ? c : 400;
    var d = null;
    var e = null;
    var f = null;
    var g = null;
    d = document.getElementById("layer_popup_frame") ? $("#layer_popup_frame") : $('<div id="layer_popup_frame"></div>');
    e = document.getElementById("layer_popup_conteudo_frame") ? $("#layer_popup_conteudo_frame") : $('<div id="layer_popup_conteudo_frame"></div>');
    f = document.getElementById("conteudo_popup_frame") ? $("#conteudo_popup_frame") : $('<div id="conteudo_popup_frame"></div>');
    g = document.getElementById("conteudo_btn_fechar") ? $("#conteudo_btn_fechar") : $('<div id="conteudo_btn_fechar"></div>');
    $(d).css({height: $("body").outerHeight(), width: $("body").outerWidth(), opacity: .5, background: "#000", position: "absolute", top: 0, left: 0, "z-index": 1e4, display: "inline"});
    $(e).css({height: $("body").outerHeight(), width: $("body").outerWidth(), opacity: 1, background: "transparent", position: "absolute", top: 0, left: 0, "z-index": 10001, display: "inline"});
    $(f).css({"min-height": b, width: c, margin: "auto", background: "#FFF", "margin-top": 50});
    $(g).html('<div><img src="' + url_raiz + '/admin/js/modalMessage/botFechar.png" border="0"/></div>');
    $(g).children("div").each(function(a, b) {
        $(this).css({"text-align": "right", padding: 5});
        $(this).children("img").each(function(a, b) {
            $(this).css("cursor", "pointer");
            $(this).attr("onClick", "popupFrameFechar()")
        })
    });
    $(e).append(f);
    $("body").append(d).append(e);
    $(f).html($(g).html() + '<iframe height="' + (b - 20) + '" width="' + c + '" style="border:0px;" src="' + a + '"></iframe><br style="clear:both;"><br style="clear:both;">')
}
function urldecode(a) {
    var b = {};
    var c = a.toString();
    var d = function(a, b, c) {
        var d = [];
        d = c.split(a);
        return d.join(b)
    };
    b["'"] = "%27";
    b["("] = "%28";
    b[")"] = "%29";
    b["*"] = "%2A";
    b["~"] = "%7E";
    b["!"] = "%21";
    b["%20"] = "+";
    b["€"] = "%80";
    b[""] = "%81";
    b["‚"] = "%82";
    b["ƒ"] = "%83";
    b["„"] = "%84";
    b["…"] = "%85";
    b["†"] = "%86";
    b["‡"] = "%87";
    b["ˆ"] = "%88";
    b["‰"] = "%89";
    b["Š"] = "%8A";
    b["‹"] = "%8B";
    b["Œ"] = "%8C";
    b[""] = "%8D";
    b["Ž"] = "%8E";
    b[""] = "%8F";
    b[""] = "%90";
    b["‘"] = "%91";
    b["’"] = "%92";
    b["“"] = "%93";
    b["”"] = "%94";
    b["•"] = "%95";
    b["–"] = "%96";
    b["—"] = "%97";
    b["˜"] = "%98";
    b["™"] = "%99";
    b["š"] = "%9A";
    b["›"] = "%9B";
    b["œ"] = "%9C";
    b[""] = "%9D";
    b["ž"] = "%9E";
    b["Ÿ"] = "%9F";
    for (replace in b) {
        search = b[replace];
        c = d(search, replace, c)
    }
    c = decodeURIComponent(c);
    return c
}
function urlencode(a) {
    var b = {}, c = [];
    var d = (a + "").toString();
    var e = function(a, b, c) {
        var d = [];
        d = c.split(a);
        return d.join(b)
    };
    b["'"] = "%27";
    b["("] = "%28";
    b[")"] = "%29";
    b["*"] = "%2A";
    b["~"] = "%7E";
    b["!"] = "%21";
    b["%20"] = "+";
    b["€"] = "%80";
    b[""] = "%81";
    b["‚"] = "%82";
    b["ƒ"] = "%83";
    b["„"] = "%84";
    b["…"] = "%85";
    b["†"] = "%86";
    b["‡"] = "%87";
    b["ˆ"] = "%88";
    b["‰"] = "%89";
    b["Š"] = "%8A";
    b["‹"] = "%8B";
    b["Œ"] = "%8C";
    b[""] = "%8D";
    b["Ž"] = "%8E";
    b[""] = "%8F";
    b[""] = "%90";
    b["‘"] = "%91";
    b["’"] = "%92";
    b["“"] = "%93";
    b["”"] = "%94";
    b["•"] = "%95";
    b["–"] = "%96";
    b["—"] = "%97";
    b["˜"] = "%98";
    b["™"] = "%99";
    b["š"] = "%9A";
    b["›"] = "%9B";
    b["œ"] = "%9C";
    b[""] = "%9D";
    b["ž"] = "%9E";
    b["Ÿ"] = "%9F";
    d = encodeURIComponent(d);
    for (search in b) {
        replace = b[search];
        d = e(search, replace, d)
    }
    return d.replace(/(\%([a-z0-9]{2}))/g, function(a, b, c) {
        return"%" + c.toUpperCase()
    });
    return d
}
function mudaTamanho(a, b) {
    if (!document.getElementById)
        return;
    var c = null, d = tamanhoInicial, e, f, g;
    d += b;
    if (b == 0)
        d = 3;
    if (d < 0)
        d = 0;
    if (d > 6)
        d = 6;
    tamanhoInicial = d;
    if (!(c = document.getElementById(a)))
        c = document.getElementsByTagName(a)[0];
    c.style.fontSize = tamanhos[d];
    for (e = 0; e < tagAlvo.length; e++) {
        g = c.getElementsByTagName(tagAlvo[e]);
        for (f = 0; f < g.length; f++)
            g[f].style.fontSize = tamanhos[d]
    }
}
function submitenter(a, b) {
    var c;
    if (window.event) {
        c = window.event.keyCode
    } else if (b) {
        c = b.which
    } else {
        return true
    }
    if (c == 13) {
        enviaFormBusca(a.id);
        return false
    } else {
        return true
    }
}
function enviaFormLogin() {
    var a = document.getElementById("login");
    var b = document.getElementById("senha");
    if (!a.value) {
        a.focus();
        return false
    } else if (!b.value) {
        b.focus();
        return false
    } else {
        document.formLoginExt.submit()
    }
}
function ocultaPai(a) {
    if (a.value == 1) {
        document.getElementById("idCategoriaPai").value = "";
        document.getElementById("idCategoriaPai").style.top = "auto";
        document.getElementById("categoriaPai").style.display = "";
        $("#categoriaPai").animate({opacity: 1}, "medium")
    } else {
        $("#categoriaPai").animate({opacity: 0}, "medium", function() {
            $("#categoriaPai").css("display", "none")
        });
        document.getElementById("idCategoriaPai").value = "";
        document.getElementById("idCategoriaPai").style.top = ""
    }
}
function deselecionar_tudo(a, b) {
    for (i = 0; i < document.forms[a].elements.length; i++) {
        if (document.forms[a].elements[i].type == "checkbox" && document.forms[a].elements[i].className == b) {
            document.forms[a].elements[i].checked = 0
        }
    }
}
function selecionar_tudo(a, b) {
    for (i = 0; i < document.forms[a].elements.length; i++) {
        if (document.forms[a].elements[i].type == "checkbox" && document.forms[a].elements[i].className == b) {
            document.forms[a].elements[i].checked = 1
        }
    }
}
function verificaString(a, b) {
    return a.name.search(b)
}
function selecionar(a, b, c) {
    if (a == "dias") {
        if (c == "marcar") {
            selecionar_tudo(b, a)
        } else {
            deselecionar_tudo(b, a)
        }
    } else {
        if (c == "marcar") {
            selecionar_tudo(b, a)
        } else {
            deselecionar_tudo(b, a)
        }
    }
}
function autorizarPublicacao(a) {
    var a = document.getElementById(a);
    if (a.style.display == "none") {
        a.style.display = "inline";
        document.getElementById("opConcordaTermos").value = 1
    } else {
        a.style.display = "none";
        document.getElementById("opConcordaTermos").value = 0
    }
}
function concatenaCampos(a, b) {
    var c = a.value;
    var d = document.getElementById(b).value;
    document.getElementById(b).value = "- Cliente: " + d + "<br/>- Loja: " + c
}
function trataTelefone(a, b, c) {
    var d = a.value;
    stringLimpa = tiraCaracteres(d);
    var e = stringLimpa.length;
    var f = stringLimpa.substr(0, 2);
    var g = stringLimpa.substr(2, e);
    document.getElementById(b).value = f;
    document.getElementById(c).value = g
}
function limpaStringNumerica(a, b) {
    var c = a.value;
    stringLimpa = tiraCaracteres(c);
    document.getElementById(b).value = stringLimpa
}
function tiraCaracteres(a) {
    exp = /\-|\.|\/|\(|\)| /g;
    novaString = a.replace(exp, "");
    return novaString
}
function preencheCampo2(a, b) {
    var c = a.id;
    if (document.getElementById(c).value == "") {
        document.getElementById(c).value = b
    }
}
function limpaCampo2(a) {
    var b = a.id;
    document.getElementById(b).value = ""
}
function adicionarOrcamento(a, b) {
    var c = document.getElementById("idNovoProduto");
    if (c.value == "") {
        alert("Selecione um produto.");
        c.focus();
        return false
    }
    if (confirm("Deseja realmente ADICIONAR este item?")) {
        location.href = a + "?idProduto=" + c.value + "&idPedido=" + b + "&acao=AdicionarAdmin"
    }
}
function excluirOrcamento(a) {
    if (confirm("Deseja realmente EXCLUIR este item?")) {
        location.href = a
    }
}
function excluirItem(a, b, c, d) {
    if (confirm("Deseja realmente EXCLUIR este item?")) {
        location.href = "" + b + "?acao=" + c + "&" + d + "=" + a
    }
}
function excluirCategoria(a) {
    if (confirm("Deseja realmente EXCLUIR este item?")) {
        location.href = "controller/act-categoria?acao=Excluir&idCategoria=" + a
    }
}
function excluirMenu(a) {
    if (confirm("Deseja realmente EXCLUIR este menu e todos os seus filhos?")) {
        location.href = "controller/act-menu?acao=Excluir&idMenu=" + a
    }
}
function excluirConteudo(a) {
    if (confirm("Deseja realmente EXCLUIR este item?")) {
        location.href = "controller/act-conteudo?acao=Excluir&idConteudo=" + a
    }
}
function limitadorCampo(a, b, c, d) {
    if (b != 0) {
        if (a.value.length > b) {
            a.value = a.value.substring(0, b);
            alert("Por favor, limite seu comentario ao maximo de " + b + " caracteres.")
        }
    }
    document.getElementById(c).innerHTML = a.value.length + "/" + b
}
function mascara(a, b) {
    var c = a.value.length;
    var d = b.substring(0, 1);
    var e = b.substring(c);
    if (e.substring(0, 1) != d) {
        a.value += e.substring(0, 1)
    }
}
function validaFormularioVazioElementos(a) {
    var b = 0;
    for (b = 0; b < a.length; b++) {
        if (a.elements[b].style.top == "auto") {
            if (a.elements[b].type == "text" || a.elements[b].type == "file" || a.elements[b].type == "password" || a.elements[b].type == "textarea" || a.elements[b].type == "select-one") {
                if (!validaCampoVazio(a.elements[b])) {
                    return false;
                    break
                }
            } else {
            }
            ;
        }
    }
    a.submit()
}
function validaFormularioSeguro(a) {
    var b = 0;
    for (b = 0; b < a.length; b++) {
        if (a.elements[b].style.top == "auto") {
            if (a.elements[b].type == "text" || a.elements[b].type == "file" || a.elements[b].type == "password" || a.elements[b].type == "textarea" || a.elements[b].type == "select-one") {
                if (!validaCampoVazio(a.elements[b])) {
                    return false;
                    break
                }
            } else {
            }
            ;
        }
    }
    return true;/*$(a).jqcrypt({keyname:"jqckval",callback:function(a){a.submit()}})*/
}
function validaFormularioVazio(a) {
    var b = 0;
    for (b = 0; b < a.length; b++) {
        if (a.elements[b].style.top == "auto") {
            if (a.elements[b].type == "text" || a.elements[b].type == "file" || a.elements[b].type == "password" || a.elements[b].type == "textarea" || a.elements[b].type == "select-one") {
                if (!validaCampoVazio(a.elements[b])) {
                    return false;
                    break
                }
            } else {
            }
            ;
        }
    }
    return true
}
function validaCampoVazio(a, b) {
    var c = a.id;
    var d = document.getElementById(c);
    if (d.value.length == 0) {
        alert("Campo obrigatorio.");
        d.focus();
        return false
    } else if (a.name.search("mail") > 0) {
        if (!/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(d.value)) {
            alert("E-mail invalido!\nInforme corretamente seu endereco de e-mail!");
            d.value = "";
            d.focus();
            return false
        } else {
            return true
        }
    } else {
        return true
    }
}
function enviaAlteracaoExterno(a, b, c, d, e) {
    var f = "item_quant_" + c;
    var g = document.getElementById(f).value;
    location.href = d + "controller/orcamento-controller?id=" + a + "&pedido=" + b + "&nrQuantidade=" + g + "&retorno=" + e + "&acao=Atualizar"
}
function enviaAlteracao(a, b, c, d, e) {
    var f = "item_quant_" + c;
    var g = document.getElementById(f).value;
    var h = "item_valor_" + c;
    var i = document.getElementById(h).value;
    location.href = d + "controller/orcamento-controller?id=" + a + "&pedido=" + b + "&nrValor=" + i + "&nrQuantidade=" + g + "&retorno=" + e + "&acao=Atualizar"
}
function mostraPreview() {
    var a = document.getElementById("txtPreview");
    if (strcmp(a.innerHTML, "Pré-visualizar") > 0) {
        mudaImagem();
        document.getElementById("preview").style.display = "inline";
        document.getElementById("txtPreview").innerHTML = "Ocultar vistualização"
    } else {
        document.getElementById("preview").style.display = "none";
        document.getElementById("txtPreview").innerHTML = "Pré-visualizar"
    }
}
function mudaImagem() {
    var a = document.getElementById("tipo_index");
    if (a.value == 1) {
        document.getElementById("imgPreview").src = "img/modelo1.gif"
    } else if (a.value == 2) {
        document.getElementById("imgPreview").src = "img/logo.png"
    } else if (a.value == 3) {
        document.getElementById("imgPreview").src = "img/logo.png"
    }
}
function strcmp(a, b) {
    return a == b ? 0 : a > b ? 1 : -1
}
var tagAlvo = new Array("p", "div", "a");
var tamanhos = new Array("xx-small", "x-small", "small", "medium", "large", "x-large", "xx-large");
var tamanhoInicial = 3

function limpaCampo(campo) {
    var id = campo.id;
    $('#' + id).val('');
}

function preencheCampo(campo, valor) {
    var id = campo.id;
    if ($('#' + id).val() == '') {
        $('#' + id).val(valor);
    }
}

function popupHtmlFechar() {
    $("#layer_popup_frame").animate({'opacity': 0}, 'fast', function() {
        $("#layer_popup_frame").remove();
    });
    $("#layer_popup_conteudo_frame").animate({'opacity': 0}, 'fast', function() {
        $("#layer_popup_conteudo_frame").remove();
    });
    $("#conteudo_popup_frame").remove();
}

function popupHtml(a, b, c, o, isFrame) {
    var topFrame = 0;
    var leftFrame = 0;
    var framePosition = null;
    if (o) {
        leftFrame = ($(window).width() / 2) - (c / 2);
        topFrame = ($(window).height() / 2) - (b / 2);
        framePosition = 'fixed';
    }

    if (!o) {
        $('html,body').animate({'scrollTop': 0}, 'medium', function() {
        });
    }

    b = b ? b : 400;
    c = c ? c : 400;
    var d = null;
    var e = null;
    var f = null;
    var g = null;
    d = document.getElementById("layer_popup_frame") ?
            $("#layer_popup_frame") :
            $('<div id="layer_popup_frame"></div>');
    e = document.getElementById("layer_popup_conteudo_frame") ?
            $("#layer_popup_conteudo_frame") :
            $('<div id="layer_popup_conteudo_frame"></div>');
    f = document.getElementById("conteudo_popup_frame") ?
            $("#conteudo_popup_frame") :
            $('<div id="conteudo_popup_frame"></div>');
    g = document.getElementById("conteudo_btn_fechar") ?
            $("#conteudo_btn_fechar") :
            $('<div id="conteudo_btn_fechar"></div>');
    $(d).css({
        height: $("body").outerHeight(),
        width: $("body").outerWidth(),
        opacity: .0,
        background: "#000",
        position: "absolute",
        top: 0,
        left: 0,
        "z-index": 1e4,
        display: "inline"});
    $(e).css({
        height: $("body").outerHeight(),
        width: $("body").outerWidth(),
        opacity: 1,
        background: "transparent",
        position: "absolute",
        top: 0,
        left: 0,
        "z-index": 10001,
        display: "inline"});

    $(f).css({
        "min-height": b,
        width: c,
        margin: "auto",
        background: "#FFF",
        "margin-top": framePosition ? 0 : 50,
        'opacity': 0,
        position: framePosition,
        left: leftFrame,
        top: topFrame,
        borderRadius: 10
    });

    $(g).html('<div><img src="' + url_raiz + 'img/botFechar.png" border="0"/></div>');
    $(g).children("div").each(function(a, b) {
        $(this).css({'background-color': 'transparent', 'position': 'relative'});
        $(this).children("img").each(function(a, b) {
            $(this).css({"cursor": "pointer", 'position': 'absolute', 'left': c - 10, 'top': '-10px'});
            $(this).attr("onClick", "popupFrameFechar()")
        })
    });

    $(e).append(f);
    $("body").append(d).append(e);

    var htmlPop = null;
    if (isFrame) {
        htmlPop = '<iframe height="' + (b) + '" width="' + c + '" style="border:0px;" scrolling="no" src="' + a + '" id="framPop"></iframe>';
    } else {
        var imgL = $('<p></p>').append($('<img>')
                .attr('src', url_raiz + 'img/ajax-loader.gif')
                .css({
            position: 'relative',
            left: (c / 2) - 64,
            top: (b / 2) - 7
        }));
        htmlPop = '<p style="padding:30px 0 20px 0;">' + $(imgL).html() + '</p>';
    }

    $(f).html(
            $(g).html() + htmlPop
            );

    if (!isFrame) {
        $.ajax({
            url: a,
            success: function(data) {
                $(f).children('p').remove();
                $(f).append(data);
            }
        });
    }

    $(d).animate({'opacity': .9}, 'fast');
    $(f).animate({'opacity': 1}, 'fast');
}
$(function() {
    if ($("#hrAbertura").length > 0)
        $("input#hrAbertura").mask("99:99");
});