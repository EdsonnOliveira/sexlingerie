function calcularFrete() {
    var cep = document.getElementById('cepDestino').value;

    if (cep.length >= 9) {
        var cep = $("#cepDestino").val();

        $.post('PHP/frete.php',{cep:cep},function(data){
            $("#entrega").html(data);
        });
    }
}