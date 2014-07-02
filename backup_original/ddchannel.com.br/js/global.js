/* ===========================================
 * JOGAR TUDO NUM ARQUIVO JS SEPARADO
 * =========================================== */

//OPEN-HIDE SEARCH FORM FORM
$(function() {
    if ($('.oc-form-search').hasClass('oc-open')) {
        $('.oc-form-search').on('click', function(e) {
            $('.form-search__container').toggleClass('closed').toggleClass('opened');
            $(this).toggleClass('oc-open').toggleClass('oc-close');

            e.preventDefault();
        });
    }

    if ($('.oc-form-search').hasClass('oc-close')) {
        $('.oc-form-search').on('click', function(e) {
            $('.form-search__container').toggleClass('opened').toggleClass('closed');
            $(this).toggleClass('oc-close').toggleClass('oc-open');

            e.preventDefault();
        });
    }
});

//MODAL
$(function() {
    $('[data-modal]').on('click', function(e) {
        var content = $(this).attr('data-modal');

        e.preventDefault();

        //open
        $('.modal').fadeIn(function() {
            $(this)
                    .children('.modal-container')
                    .children('.modal-content')
                    .prepend(content)
                    .parent('.modal-container')
                    .slideDown();
        });

        //close
        $('.modal-close').on('click', function(e) {
            e.preventDefault();

            $(this)
                    .parent('.modal-container')
                    .slideUp(function() {
                $(this)
                        .parent('.modal')
                        .fadeOut(function() {
                    $('.modal-content').empty();
                });
            });
        });

        $('.modal-mask').on('click', function(e) {
            e.preventDefault();

            $('.modal-container')
                    .slideUp(function() {
                $(this)
                        .parent('.modal')
                        .fadeOut(function() {
                    $('.modal-content').empty();
                });
            });
        });
    });
});

//SCROLL TO TOP BUTTON
$(function() {
    $(window).scroll(function() {
        if ($(this).scrollTop() > 300) {
            $('.to-top').fadeIn();
        } else {
            $('.to-top').fadeOut();
        }
    });

    $('.to-top').click(function() {
        $('html, body').animate({scrollTop: 0}, 300);
        return false;
    });
});

//JORNALISMO POPULAR TOGGLE
$(function() {
    $('.btn-enviadas').on('click', function(e) {
        if ($('#regulamento').is(":visible")) {
            $('#regulamento').fadeOut(function() {
                $('#enviadas').fadeIn();

                $('.btn-enviadas').addClass('active');
                $('.btn-regulamento').removeClass('active');
            });
        }

        if ($('#envio-noticia').is(":visible")) {
            $('#envio-noticia').fadeOut(function() {
                $('#enviadas').fadeIn();

                $('.btn-enviadas').addClass('active');
                $('.btn-envio-noticia').removeClass('active');
            });
        }

        e.preventDefault();
    });

    $('.btn-regulamento').on('click', function(e) {
        if ($('#enviadas').is(":visible")) {
            $('#enviadas').fadeOut(function() {
                $('#regulamento').fadeIn();

                $('.btn-regulamento').addClass('active');
                $('.btn-enviadas').removeClass('active');
            });
        }

        if ($('#envio-noticia').is(":visible")) {
            $('#envio-noticia').fadeOut(function() {
                $('#regulamento').fadeIn();

                $('.btn-regulamento').addClass('active');
                $('.btn-envio-noticia').removeClass('active');
            });
        }

        e.preventDefault();
    });

    $('.btn-envio-noticia').on('click', function(e) {
        if ($('#enviadas').is(":visible")) {
            $('#enviadas').fadeOut(function() {
                $('#envio-noticia').fadeIn();

                $('.btn-envio-noticia').addClass('active');
                $('.btn-enviadas').removeClass('active');
            });
        }

        if ($('#regulamento').is(":visible")) {
            $('#regulamento').fadeOut(function() {
                $('#envio-noticia').fadeIn();

                $('.btn-envio-noticia').addClass('active');
                $('.btn-regulamento').removeClass('active');
            });
        }

        e.preventDefault();
    });

    $('#to-reg').on('click', function(e) {
        $('#envio-noticia').fadeOut(function() {
            $('#regulamento').fadeIn();

            $('.btn-regulamento').addClass('active');
            $('.btn-envio-noticia').removeClass('active');
        });

        e.preventDefault();
    });

    $('#jp_tipoDeAnexo').change(function() {
        var tipo = $('#jp_tipoDeAnexo').val();

        if (tipo == 1) {
            $('#jp_tipoDeAnexo-link').parent().fadeOut(function() {
                $('#jp_tipoDeAnexo-arquivo').parent().fadeIn();
            });
        } else if (tipo == 2) {
            $('#jp_tipoDeAnexo-arquivo').parent().fadeOut(function() {
                $('#jp_tipoDeAnexo-link').parent().fadeIn();
            });
        }
    });
});

//FADE IN IMGs
$(function() {
    //$('img').hide("fast");
    //$('img').load(function() {
    //console.log($('img:hidden').length);
    //$('img:hidden').show(3000);
    //$('img:hidden').css('cssText', 'display:block !important');
    //});
});

//MAIN-MENU
$(function() {
    $('[href="#main-menu"]').click(function(e) {
        $(this)
            .next('.main-menu')
            .toggleClass('opened');

        $(this)
            .children('.menu-close')
            .toggleClass('show');

        e.preventDefault();
    });
});


function validaCampoVazio(objCampo, caminho) {
	var idCampo = objCampo.id;
	// var NomeMsgCampo = 'msg_'+ idCampo;
	// var imgErro = document.getElementById('imgErro').value;

	var campo = document.getElementById(idCampo);
	// var msgCampo = document.getElementById(NomeMsgCampo);

	if (campo.value.length == 0) { // Campo vazio
		// var img = '<img src="'+imgErro+'"/> Preenchimento obrigatÃ³rio.';
		// msgCampo.innerHTML = img;
		alert('Campo obrigatorio.');
		campo.focus();
		return false;

	} else if (objCampo.name.search("mail") > 0) {
		if (!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(campo.value))) {
			alert("E-mail invalido!\nInforme corretamente seu endereco de e-mail!");
			campo.value = '';
			campo.focus();
			return false;
		} else {
			return true;
		}

	} else { // Campo com valor
		// var img = '<img
		// src="http://'+document.location.host+'/portal/img/icones/ok.gif"/>';
		// msgCampo.innerHTML = img;
		return true;
	}
}

function validaFormularioVazio(objFormulario) {

	var i = 0;
	for (i = 0; i < objFormulario.length; i++) {

		if (objFormulario.elements[i].style.top == 'auto') {

			if (objFormulario.elements[i].type == 'text'
					|| objFormulario.elements[i].type == 'password'
					|| objFormulario.elements[i].type == 'textarea'
					|| objFormulario.elements[i].type == 'select-one') {
				if (!validaCampoVazio(objFormulario.elements[i])) {
					return false;
					break;
				}
			} else {

			}
		}

	}
	$.post('http://ddchannel.com.br/controller/compra-controller', {
		acao : "concluirPS",
		cliente_email: $("#cliente_email").val(),
		cliente_nome: $("#cliente_nome").val(),
		cliente_telefone: $("#cliente_telefone").val(),
		cliente_cidade: $("#cliente_cidade").val(),
		cliente_uf: $("#cliente_uf").val(),
		cliente_end: $("#cliente_end").val(),
		cliente_num: $("#cliente_num").val(),
		cliente_compl: $("#cliente_compl").val(),
		cliente_bairro: $("#cliente_bairro").val(),
		cliente_cep: $("#cliente_cep").val(),
		nmConsultor: $("#nmConsultor").val(),
		mensagem: $("#mensagem").val()
	}, function(retorno) {
		return true;
	});

}

/*******************************************************************************
 * FUNCAO COM TODAS A MAKARAS OnKeyPress="formatar(this, '#####-###')"
 * OnKeyPress="formatar(this, '###.###.###-##')" OnKeyPress="formatar(this,
 * '##/##/####')"
 ******************************************************************************/
function mascara(src, mask) {
	var i = src.value.length;

	var saida = mask.substring(0, 1);

	var texto = mask.substring(i)

	if (texto.substring(0, 1) != saida)

	{

		src.value += texto.substring(0, 1);

	}
}

// Excluir um conteÃºdo
function excluirOrcamento(URL) {
	if (confirm("Deseja realmente EXCLUIR este item?")) {
		location.href = URL;
	}
}
function enviaAlteracaoExternoCompra(idProduto, idPedido, coluna, url, retorno) {
	var linha = 'item_quant_' + coluna;
	var nrQuantidade = document.getElementById(linha).value;
	location.href = url + 'controller/compra-controller?id=' + idProduto
			+ '&pedido=' + idPedido + '&nrQuantidade=' + nrQuantidade
			+ '&retorno=' + retorno + '&acao=Atualizar';
}

function MascaraCNPJ(cnpj){
        if(mascaraInteiro(cnpj)==false){
                event.returnValue = false;
        }       
        return formataCampo(cnpj, '00.000.000/0000-00', event);
}

//adiciona mascara de cep
function MascaraCep(cep){
                if(mascaraInteiro(cep)==false){
                event.returnValue = false;
        }       
        return formataCampo(cep, '00.000-000', event);
}

//adiciona mascara de data
function MascaraData(data){
        if(mascaraInteiro(data)==false){
                event.returnValue = false;
        }       
        return formataCampo(data, '00/00/0000', event);
}

//adiciona mascara ao telefone
function MascaraTelefone(tel){  
        if(mascaraInteiro(tel)==false){
                event.returnValue = false;
        }       
        return formataCampo(tel, '(00) 0000-0000', event);
}

//adiciona mascara ao CPF
function MascaraCPF(cpf){
        if(mascaraInteiro(cpf)==false){
                event.returnValue = false;
        }       
        return formataCampo(cpf, '000.000.000-00', event);
}

//valida telefone
function ValidaTelefone(tel){
        exp = /\(\d{2}\)\ \d{4}\-\d{4}/
        if(!exp.test(tel.value))
                alert('Numero de Telefone Invalido!');
}

//valida CEP
function ValidaCep(cep){
        exp = /\d{2}\.\d{3}\-\d{3}/
        if(!exp.test(cep.value))
                alert('Numero de Cep Invalido!');               
}

//valida data
function ValidaData(data){
        exp = /\d{2}\/\d{2}\/\d{4}/
        if(!exp.test(data.value))
                alert('Data Invalida!');                        
}

//valida o CPF digitado
function ValidarCPF(Objcpf){
        var cpf = Objcpf.value;
        exp = /\.|\-/g
        cpf = cpf.toString().replace( exp, "" ); 
        var digitoDigitado = eval(cpf.charAt(9)+cpf.charAt(10));
        var soma1=0, soma2=0;
        var vlr =11;
        
        for(i=0;i<9;i++){
                soma1+=eval(cpf.charAt(i)*(vlr-1));
                soma2+=eval(cpf.charAt(i)*vlr);
                vlr--;
        }       
        soma1 = (((soma1*10)%11)==10 ? 0:((soma1*10)%11));
        soma2=(((soma2+(2*soma1))*10)%11);
        
        var digitoGerado=(soma1*10)+soma2;
        if(digitoGerado!=digitoDigitado)        
                alert('CPF Invalido!');         
}

//valida numero inteiro com mascara
function mascaraInteiro(){
        if (event.keyCode < 48 || event.keyCode > 57){
                event.returnValue = false;
                return false;
        }
        return true;
}

//valida o CNPJ digitado
function ValidarCNPJ(ObjCnpj){
        var cnpj = ObjCnpj.value;
        var valida = new Array(6,5,4,3,2,9,8,7,6,5,4,3,2);
        var dig1= new Number;
        var dig2= new Number;
        
        exp = /\.|\-|\//g
        cnpj = cnpj.toString().replace( exp, "" ); 
        var digito = new Number(eval(cnpj.charAt(12)+cnpj.charAt(13)));
                
        for(i = 0; i<valida.length; i++){
                dig1 += (i>0? (cnpj.charAt(i-1)*valida[i]):0);  
                dig2 += cnpj.charAt(i)*valida[i];       
        }
        dig1 = (((dig1%11)<2)? 0:(11-(dig1%11)));
        dig2 = (((dig2%11)<2)? 0:(11-(dig2%11)));
        
        if(((dig1*10)+dig2) != digito)  
                alert('CNPJ Invalido!');
                
}

//formata de forma generica os campos
function formataCampo(campo, Mascara, evento) { 
        var boleanoMascara; 
        
        var Digitato = evento.keyCode;
        exp = /\-|\.|\/|\(|\)| /g
        campoSoNumeros = campo.value.toString().replace( exp, "" ); 
   
        var posicaoCampo = 0;    
        var NovoValorCampo="";
        var TamanhoMascara = campoSoNumeros.length;; 
        
        if (Digitato != 8) { // backspace 
                for(i=0; i<= TamanhoMascara; i++) { 
                        boleanoMascara  = ((Mascara.charAt(i) == "-") || (Mascara.charAt(i) == ".")
                                                                || (Mascara.charAt(i) == "/")) 
                        boleanoMascara  = boleanoMascara || ((Mascara.charAt(i) == "(") 
                                                                || (Mascara.charAt(i) == ")") || (Mascara.charAt(i) == " ")) 
                        if (boleanoMascara) { 
                                NovoValorCampo += Mascara.charAt(i); 
                                  TamanhoMascara++;
                        }else { 
                                NovoValorCampo += campoSoNumeros.charAt(posicaoCampo); 
                                posicaoCampo++; 
                          }              
                  }      
                campo.value = NovoValorCampo;
                  return true; 
        }else { 
                return true; 
        }
}



function FormataReais(fld, milSep, decSep, e) {
var sep = 0;
var key = '';
var i = j = 0;
var len = len2 = 0;
var strCheck = '0123456789';
var aux = aux2 = '';
var whichCode = (window.Event) ? e.which : e.keyCode;
if (whichCode == 13) return true;
key = String.fromCharCode(whichCode);  // Valor para o cÃ³digo da Chave
if (strCheck.indexOf(key) == -1) return false;  // Chave invÃ¡lida
len = fld.value.length;
for(i = 0; i < len; i++)
if ((fld.value.charAt(i) != '0') && (fld.value.charAt(i) != decSep)) break;
aux = '';
for(; i < len; i++)
if (strCheck.indexOf(fld.value.charAt(i))!=-1) aux += fld.value.charAt(i);
aux += key;
len = aux.length;
if (len == 0) fld.value = '';
if (len == 1) fld.value = '0'+ decSep + '0' + aux;
if (len == 2) fld.value = '0'+ decSep + aux;
if (len > 2) {
aux2 = '';
for (j = 0, i = len - 3; i >= 0; i--) {
if (j == 3) {
aux2 += milSep;
j = 0;
}
aux2 += aux.charAt(i);
j++;
}
fld.value = '';
len2 = aux2.length;
for (i = len2 - 1; i >= 0; i--)
fld.value += aux2.charAt(i);
fld.value += decSep + aux.substr(len - 2, len);
}
return false;
}