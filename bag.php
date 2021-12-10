<?php
    include("PHP/conn.php");

    session_start();
    if (!isset($_SESSION['BAG']))
        $_SESSION['BAG'] = array();

    $subTotalSacola = 0;
    $frete = 0;
    $totalSacola = 0;

    if (isset($_GET['ID'])) {
        if (isset($_SESSION['QNTD_BAG']))
            $_SESSION['QNTD_BAG'] += 1;
        else
            $_SESSION['QNTD_BAG'] = 1;

        $SEQ = $_SESSION['QNTD_BAG'];

        $_SESSION['BAG'][$SEQ]['IDProduto']  = $_GET['ID'];
        $_SESSION['BAG'][$SEQ]['Quantidade'] = 1;
        $_SESSION['BAG'][$SEQ]['Cor']        = $_GET['color'];
        $_SESSION['BAG'][$SEQ]['Tamanho']    = $_GET['size'];
        header('location: bag.php');
    }

    if (isset($_GET['IDClose'])) {
        unset($_SESSION['BAG'][$_GET['IDClose']]);
        if (count($_SESSION['BAG']) == 0)
            unset($_SESSION['BAG']);
        header('location: bag.php');
    }

    if (isset($_POST['frete'])) {
        include("PHP/frete.php");

        $frete = new Frete();
        $frete->calcularFrete($_POST['cepDestino']);
    }

    if (isset($_POST['finalizar'])) {
        if (isset($_SESSION['LOGIN']))
            header("location: entrega.php");
        else
            header("location: login.php?finalizar");
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <title>Sacola | Sex Lingerie</title>
        <?php include("PHP/meta.php"); ?>
        <link rel='stylesheet' type='text/css' href='CSS/bag.css'>
    </head>
    <body>
        <section class='preview small red'>
            <?php include("PHP/header.php"); ?>
            <div class='centralize'>
                <span>
                    <div class='row small white'><h1 class='txtBlack txt500'>M</h1></div>
                    <h1 class='txtBlack txt500'>Minha Sacola</h1>
                </span>
            </div>
        </section>
        <main>
            <div class='container'>
                <div class='bag'>
                    <div class='list'>
                        <div class='top'>
                            <a href="lancamentos.php" class='button btBlack btBigger btRounded txtGotham2 txtWhite txt900 txtUpper'>Continuar Comprando</a>
                            <h1 class='txtBlack txtGotham2 txtUpper txt100'>
                            <?php
                                $Item = '0 Itens';
                                if (isset($_SESSION['BAG'])) {
                                    if (count($_SESSION['BAG']) <> 0) {
                                        if (count($_SESSION['BAG']) > 1)
                                            $Item = count($_SESSION['BAG']) . ' Itens';
                                        else
                                            $Item = '1 Item';
                                    }
                                }

                                echo $Item;
                            ?></h1>
                        </div>
                            <?php
                                if (isset($_SESSION['BAG']) and (count($_SESSION['BAG']) > 0)) {
                                    echo "<table>";
                                        echo "<thead>";
                                            echo "<tr>";
                                                echo "<th class='txtBlack txtGotham2 txtUpper txtLeft borderRight' style='width:500px' >Produto</th>";
                                                echo "<th class='txtBlack txtGotham2 txtUpper borderRight' style='width:150px'>Valor</th>";
                                                echo "<th class='txtBlack txtGotham2 txtUpper borderRight' style='width:100px'>Qntd.</th>";
                                                echo "<th class='txtBlack txtGotham2 txtUpper' style='width:200px'>Total</th>";
                                                echo "<th></th>";
                                            echo "</tr>";
                                        echo "</thead>";

                                        echo "<tbody>";
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

                                        $totalProduto = $_SESSION['BAG'][$IDProduto]['Quantidade'] * $SQLProd['ValorVenda'];

                                        echo "<tr>";
                                            echo "<td class='tdImg'>";
                                                echo "<img src='" . IMAGE . $SQLProd['Imagem'] . "' alt='" . $SQLProd['Nome'] . "'>";
                                                echo "<div>";
                                                    echo "<h2 class='txtBlack txtUpper txtGotham3'>" . $SQLProd['Nome'] . "</h2>";
                                                    echo "<h3 class='txtUpper txtGrey txtGotham' style='margin-bottom: 10px'>REF: " . $SQLProd['Codigo'] . "</h3>";
                                                    echo "<h3 class='txtBlack txtGotham txtUpper' style='margin-bottom: 5px'><b class='txtGotham3 txt900'>Cor: </b>" . $SQLColor['Nome'] . "</h3>";
                                                    echo "<h3 class='txtBlack txtGotham txtUpper'><b class='txtGotham3 txt900'>Tamanho: </b>" . $SQLSize['Nome'] . "</h3>";
                                                echo "</div>";
                                            echo "</td>";
                                            echo "<td class='tdValorVenda txtBlack txtGotham txtUpper txtCenter txt900' style='font-size: 20px'>R$ " . str_replace('.', ',', $SQLProd['ValorVenda']) . "</td>";
                                            echo "<td class='tdQuantidade txtBlack txtGotham2 txtUpper txtCenter txt100'>" . $_SESSION['BAG'][$IDProduto]['Quantidade'] . "</td>";
                                            echo "<td class='tdValorTotal txtBlack txtGotham2 txtUpper txtCenter txt100'>R$ " . number_format($totalProduto, 2, ',', '.') . "</td>";
                                            echo "<td><a href='?IDClose=$IDProduto'><img src='IMG/Custom/trash.png'></a></td>";
                                        echo "</tr>";

                                        $subTotalSacola = $subTotalSacola + $totalProduto;
                                    }
                                    $totalSacola = $subTotalSacola;
                                    if (isset($frete->valorFrete))
                                        $totalSacola = $frete->valorFrete + $subTotalSacola;
                                    echo "</tbody>";
                                    echo "</table>";
                                } else
                                    echo "<h1 class='txtUpper txtBlack txtGotham3 txtCenter txt100' style='margin-top:100px;margin-bottom:100px;'>Sua sacola está vazia!</h1>";
                            ?>
                    </div>
                    <?php
                        if ($totalSacola <> 0) {
                    ?>
                    <div class='total'>
                        <form method='post'>
                            <div class='main' style='height:85px'>
                                <div>
                                    <h3 class='txtGotham txtBlack txtUpper txtGotham3 txt100'>Frete</h3>
                                </div>
                                <div>    
                                    <input type="text" name='cepDestino' id='cepDestino' class='cep input ipMedium ipBorder ipRounded txtGotham3 txtBlack txt900 txtCenter' placeholder='CEP' maxlength='10'>
                                </div>
                                <input type="submit" name='frete' class='button btBorder btRounded txtGotham3 txtBlack' value='CALCULAR' style='width:150px;height:40px;position:absolute;margin-left:75px;margin-top:55px'>        
                            </div>
                        </form>
                        <div class='main'>
                            <div>
                                <h3 class='txtGotham txtBlack txtUpper txtGotham3 txt100'>SubTotal</h3>
                                <h3 class='txtGotham txtBlack txtUpper txtGotham3 txt100'>Frete</h3>
                                <h3 class='txtGotham txtBlack txtUpper txtGotham3 txt100'>Entrega</h3>
                                <div></div>
                                <h1 class='txtGotham txtBlack txtUpper txtGotham3 txt100'>Total</h1>
                            </div>
                            <div style='position:relative;'>
                                <h2 class='txtGotham txtBlack txtUpper txtGotham3 txt100 txtRight'>R$ <?php echo number_format($subTotalSacola, 2, ',', '.'); ?></h2>
                                <h2 class='txtGotham txtBlack txtUpper txtGotham txt100 txtRight' style='margin-top:13px;'>R$ <?php if (isset($frete->valorFrete)) echo $frete->valorFrete; ?></h2>
                                <h2 id='entrega' class='txtGotham txtBlack txtUpper txtGotham txt100 txtRight' style='margin-top:13px;margin-bottom:-5px;'><?php if (isset($frete->valorFrete)) echo $frete->entregaFrete . ' dias';?></h2>
                                <div></div>
                                <h1 class='txtGotham txtBlack txtUpper txtGotham3 txt100 txtRight' style='position:absolute;right:0;bottom:0;'>R$ <?php echo number_format($totalSacola, 2, ',', '.'); ?></h1>
                            </div>
                        </div>
                        <form method='post'>
                            <input type="submit" name='finalizar' class='button btRed btRounded btBigger txtWhite txtGotham3 txtUpper txt900' value='Finalizar Compra'>
                        </form>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </main>
        <?php include('PHP/footer.php'); ?>
    </body>
</html>