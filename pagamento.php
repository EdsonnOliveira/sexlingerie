<?php
    session_start();

    include("PHP/conn.php");
    include("PHP/frete.php");

    if (!isset($_SESSION['BAG']))
        header("location: index.php");

    $subTotalSacola = 0;
    $totalSacola = 0;

    $valorFrete = 0;
    $diasFrete = 0;

    $SQLCliente = $conn->prepare("SELECT * FROM clientes WHERE IDCliente=? LIMIT 1");
    $SQLCliente->execute([$_SESSION['LOGIN']['ID']]);
    $SQLCliente = $SQLCliente->fetch();

    $SQLEntrega = $conn->prepare('SELECT * FROM clientes_endereco WHERE IDEndereco=? LIMIT 1');
    $SQLEntrega->execute([$_GET['enderecoEntrega']]);
    if ($SQLEntrega->rowCount() > 0) {
        $SQLEntrega = $SQLEntrega->fetch();
        $endereco = $SQLEntrega['Endereco'];
        $bairro = $SQLEntrega['Bairro'];
        $municipio = $SQLEntrega['Municipio'] . ' - ' . $SQLEntrega['UF'];
        $cep = $SQLEntrega['CEP'];

        $frete = new frete;
        $frete->calcularFrete($SQLEntrega['CEP']);
        $valorFrete = $frete->valorFrete;
        $diasFrete = $frete->entregaFrete;
    }

    require __DIR__ .  '/vendor/autoload.php';
    
    MercadoPago\SDK::setAccessToken(TOKEN);

    $preference = new MercadoPago\Preference();

    $payer = new MercadoPago\Payer();
    $payer->name = $SQLCliente['Nome'];
    $payer->email = $SQLCliente['Email'];
    $payer->date_created = "2018-06-02T12:58:41.425-04:00";
    $payer->phone = array(
        "area_code" => substr($SQLCliente['Celular'], 1, 2),
        "number" => substr($SQLCliente['Celular'], 4, 20)
    );
        
    $CPF = str_replace('-', '', $SQLCliente['CPF']);
    $CPF = str_replace('.', '', $CPF);
    $payer->identification = array(
        "type" => "CPF",
        "number" => $CPF
    );
        
    if ($_GET['enderecoEntrega'] <> 'entregaLoja') {
        $CEP = str_replace('-', '', $SQLEntrega['CEP']);
        $CEP = str_replace('.', '', $CEP);
        $payer->address = array(
            "street_name" => $SQLEntrega['Endereco'],
            "street_number" => $SQLEntrega['Numero'],
            "zip_code" => $CEP
        );
    }

    $itens = array();
    foreach ($_SESSION['BAG'] as $IDProduto => $QNTD) {
        $SQLProd = $conn->prepare('SELECT * FROM produtos WHERE IDProduto=? LIMIT 1');
        $SQLProd->execute([$_SESSION['BAG'][$IDProduto]['IDProduto']]);
        $SQLProd = $SQLProd->fetch();

        $item = new MercadoPago\Item();
        $item->title = $SQLProd['Nome'];
        $item->quantity = $_SESSION['BAG'][$IDProduto]['Quantidade'];
        $item->unit_price = $SQLProd['ValorVenda'];

        array_push($itens, $item);
    }

    $subTotalSacola = 0;
    $totalSacola = 0;

    foreach ($_SESSION['BAG'] as $IDProduto => $QNTD) {
        $SQLProd = $conn->prepare('SELECT * FROM produtos WHERE IDProduto=? LIMIT 1');
        $SQLProd->execute([$_SESSION['BAG'][$IDProduto]['IDProduto']]);
        $SQLProd = $SQLProd->fetch();

        $totalProduto = $_SESSION['BAG'][$IDProduto]['Quantidade'] * $SQLProd['ValorVenda'];

        $subTotalSacola = $subTotalSacola + $totalProduto;
    }
    $totalSacola = $subTotalSacola;
    $parcelas = 4;

    if ($_GET['enderecoEntrega'] <> 'entregaLoja') {
        $itemFrete = new MercadoPago\Item();
        $itemFrete->title = 'Frete';
        $itemFrete->quantity = 1;
        $itemFrete->unit_price = str_replace(',', '.', $valorFrete);
        array_push($itens, $itemFrete);
    }

    $preference = new MercadoPago\Preference();
    
    $preference->payment_methods = array(
        "excluded_payment_types" => array(
            array("id" => "ticket")
        ),
        "installments" => $parcelas
    );

    $preference->items = $itens;

    $preference->back_urls = array(
        "success" => "http://localhost:8080/WEB/Sex%20Lingerie/perfil.php?enderecoCobranca=" . $_GET['enderecoCobranca'] . '&enderecoEntrega=' . $_GET['enderecoEntrega'] . '&valorFrete=' . $valorFrete . '&diasFrete=' . $diasFrete,
        "failure" => "http://localhost:8080/WEB/Sex%20Lingerie/pagamento.php?enderecoCobranca=" . $_GET['enderecoCobranca'] . '&enderecoEntrega=' . $_GET['enderecoEntrega'],
        "pending" => "http://localhost:8080/WEB/Sex%20Lingerie/perfil.php?enderecoCobranca=" . $_GET['enderecoCobranca'] . '&enderecoEntrega=' . $_GET['enderecoEntrega'] . '&valorFrete=' . $valorFrete . '&diasFrete=' . $diasFrete,
    );
    $preference->auto_return = "approved";

    $preference->save();
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <title>Pagamento | Sex Lingerie</title>
        <?php include("PHP/meta.php"); ?>
        <link rel='stylesheet' type='text/css' href='CSS/entrega.css'>
        <script src='JS/entrega.js'></script>
    </head>
    <body>
        <section class='preview small red'>
            <?php include("PHP/header.php"); ?>
            <div class='centralize'>
                <span>
                    <div class='row small white'><h1 class='txtBlack txt500'>P</h1></div>
                    <h1 class='txtBlack txt500'>Pagamento</h1>
                </span>
            </div>
        </section>
        <main>
            <div class='container'>
                <div class='entrega'>
                    <a href="bag.php" class='button btBlack btBigger btRounded txtGotham2 txtWhite txt900 txtUpper' style='margin-right:15px;'>sacola</a>
                    <div class='list'>
                        <?php
                            echo "<table>";
                                echo "<tr>";
                                    echo "<th class='txtBlack txtGotham2 txtUpper txtLeft borderRight' style='width:500px' >Produto</th>";
                                    echo "<th class='txtBlack txtGotham2 txtUpper borderRight' style='width:150px'>Valor</th>";
                                    echo "<th class='txtBlack txtGotham2 txtUpper borderRight' style='width:100px'>Qntd.</th>";
                                    echo "<th class='txtBlack txtGotham2 txtUpper' style='width:200px'>Total</th>";
                                echo "</tr>";
                            
                            foreach ($_SESSION['BAG'] as $IDProduto => $QNTD) {
                                $SQLProd = $conn->prepare('SELECT * FROM produtos WHERE IDProduto=? LIMIT 1');
                                $SQLProd->execute([$_SESSION['BAG'][$IDProduto]['IDProduto']]);
                                $SQLProd = $SQLProd->fetch();

                                $SQLColor = $conn->prepare('SELECT * FROM produtos_cor WHERE IDCor=? LIMIT 1');
                                $SQLColor->execute([$_SESSION['BAG'][$IDProduto]['Cor']]);
                                $SQLColor = $SQLColor->fetch();

                                $SQLSize = $conn->prepare('SELECT * FROM produtos_tamanho WHERE IDTamanho=? LIMIT 1');
                                $SQLSize->execute([$_SESSION['BAG'][$IDProduto]['Tamanho']]);
                                $SQLSize = $SQLSize->fetch();

                                echo "<tr>";
                                    echo "<td class='tdImg'>";
                                        echo "<img src='../Ecommerce/IMG/Product/1/" . $SQLProd['Imagem'] . "' alt='" . $SQLProd['Nome'] . "'>";
                                        echo "<div>";
                                            echo "<h2 class='txtBlack txtUpper txtGotham3'>" . $SQLProd['Nome'] . "</h2>";
                                            echo "<h3 class='txtUpper txtGrey txtGotham' style='margin-bottom: 10px'>REF: " . $SQLProd['Codigo'] . "</h3>";
                                            echo "<h3 class='txtBlack txtGotham txtUpper' style='margin-bottom: 5px'><b class='txtGotham3 txt900'>Cor: </b>" . $SQLColor['Nome'] . "</h3>";
                                            echo "<h3 class='txtBlack txtGotham txtUpper'><b class='txtGotham3 txt900'>Tamanho: </b>" . $SQLSize['Nome'] . "</h3>";
                                        echo "</div>";
                                    echo "</td>";
                                    echo "<td class='txtBlack txtGotham txtUpper txtCenter txt900' style='font-size: 20px'>R$ " . str_replace('.', ',', $SQLProd['ValorVenda']) . "</td>";
                                    echo "<td class='txtBlack txtGotham2 txtUpper txtCenter txt100'>" . $_SESSION['BAG'][$IDProduto]['Quantidade'] . "</td>";
                                    echo "<td class='txtBlack txtGotham2 txtUpper txtCenter txt100' style='font-size: 25px'>R$ " . number_format($totalProduto, 2, ',', '.') . "</td>";
                                echo "</tr>";
                            }

                            if ($_GET['enderecoEntrega'] <> 'entregaLoja')
                                $totalSacola = str_replace(',','.', $frete->valorFrete) + $subTotalSacola;
                            
                            echo "</table>";
                        ?>
                    </div>
                    <div style='display:flex;justify-content:center;margin-bottom:30px'>
                        <div class='endereco selected'>
                            <div>
                        <?php
                            if ($_GET['enderecoEntrega'] <> 'entregaLoja') {
                        ?>
                                <h1 class='txtCenter txtBlack txtGotham3 txt100 txtUpper'><?php echo $endereco; ?></h1>
                                <h2 class='txtCenter txtBlack txtGotham'><?php echo $bairro; ?></h2>
                                <h2 class='txtCenter txtBlack txtGotham'><?php echo $municipio; ?></h2>
                                <h3 class='txtCenter txtBlack txtGotham3 txt600'><?php echo $cep; ?></h3>
                        <?php 
                            } else {
                        ?>
                            <h1 class='txtCenter txtBlack txtGotham3 txt600'>Retirada <b class='txtRed txtGotham3 txt600'>grátis</b></br> na Loja</h1>
                            <h2 class='txtCenter txtBlack txtGotham txt300' style='margin-top: 25px;margin-bottom:10px;'>A partir de <b class='txtRed txtGotham'></br>7 dias</b> úteis</h2>
                        <?php 
                            }
                        ?>
                            </div>
                        </div>
                        <div class='total'>
                            <div style='top:0;'>
                                <div>
                                    <h3 class='txtGotham txtBlack txtUpper txtGotham3 txt100'>SubTotal</h3>
                                    <h3 class='txtGotham txtBlack txtUpper txtGotham3 txt100'>Frete</h3>
                                    <h3 class='txtGotham txtBlack txtUpper txtGotham3 txt100'>Entrega</h3>
                                </div>
                                <div>
                                    <h2 class='txtGotham txtBlack txtUpper txtGotham3 txt100 txtRight'>R$ <?php echo number_format($subTotalSacola, 2, ',', '.'); ?></h2>
                                    <?php
                                        if ($_GET['enderecoEntrega'] <> 'entregaLoja') {
                                            echo "<h2 class='txtGotham txtBlack txtUpper txtGotham txt100 txtRight' style='margin-top:13px;'>R$ " . $frete->valorFrete . "</h2>";
                                            echo "<h2 id='entrega' class='txtGotham txtBlack txtUpper txtGotham txt100 txtRight' style='margin-top:13px;margin-bottom:-5px;'>" . date('d/m/Y', strtotime('+'.$frete->entregaFrete.' days')) . "</h2>";
                                        } else {
                                            echo "<h2 class='txtGotham txtBlack txtUpper txtGotham txt100 txtRight' style='margin-top:13px;'>GRÁTIS</h2>";
                                            echo "<h2 id='entrega' class='txtGotham txtBlack txtUpper txtGotham txt100 txtRight' style='margin-top:13px;margin-bottom:-5px;'>" . date('d/m/Y', strtotime('+7 days')) . "</h2>";
                                        }
                                    ?>
                                </div>
                            </div>
                            <div style='bottom:0;'>
                                <div>
                                    <h3 class='txtGotham txtBlack txtUpper txtGotham3 txt100'>TOTAL</h3>
                                </div>
                                <div>
                                    <h1 class='txtGotham txtBlack txtUpper txtGotham3 txt100 txtRight'>R$ <?php echo number_format($totalSacola, 2, ',', '.'); ?></h1>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- <form method="POST" style='display:flex;justify-content:center;'>
                        <script src="https://www.mercadopago.com.br/integrations/v1/web-payment-checkout.js"
                        data-preference-id="<?php echo $preference->id; ?>" data-button-label="Pagar" data-elements-color='#EB3237' data-header-color="#EB3237"></script>
                    </form> -->
                    </br>
                    <center><a class='mercadopago-button' href="<?php echo $preference->init_point; ?>">Pagar</a></center>
                </div>
            </div>
        </main>
        <?php include('PHP/footer.php'); ?>
    </body>
</html>