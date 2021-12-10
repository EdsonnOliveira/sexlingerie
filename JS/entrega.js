function selectCobranca(cobrancaID) {
    var ID = cobrancaID;
    var input = document.getElementById('enderecoCobranca');

    var cobranca = document.querySelector("#cobranca" + input.value);
        cobranca.classList.remove('selected');        

    var selecionada = document.getElementById(cobrancaID);
        selecionada.classList.add('selected');

    input.setAttribute("value", ID.substring(8, 100));
}

function selectEntrega(entregaID) {
    var ID = entregaID;

    var input = document.getElementById('enderecoEntrega');

    var entregas = document.querySelector("#entrega" + input.value);
    if (document.querySelector('#entregaLoja').classList.contains('selected'))
        entregas = document.querySelector('#entregaLoja');
    entregas.classList.remove('selected');

    var selecionada = document.getElementById(entregaID);
        selecionada.classList.add('selected');

    input.setAttribute("value", "entregaLoja");
    if (entregaID != 'entregaLoja')
        input.setAttribute("value", ID.substring(7, 100));
}