<?php  
    session_start();

    if (!isset($_SESSION['LOGIN']))
        header("location: login.php");

    include("PHP/conn.php"); 

    if (!isset($_GET['ID']))
        header("location: login.php");

    $SQL = $conn->prepare("SELECT * FROM pedido WHERE IDPedido=? LIMIT 1");
    $SQL->execute([$_GET['ID']]);
    $SQL = $SQL->fetch();

    $SQLEntrega = $conn->prepare("SELECT * FROM pedido_entrega WHERE IDPedido=? LIMIT 1");
    $SQLEntrega->execute([$_GET['ID']]);
    $SQLEntrega = $SQLEntrega->fetch();

    $SQLCobranca = $conn->prepare("SELECT * FROM pedido_cobranca WHERE IDPedido=? LIMIT 1");
    $SQLCobranca->execute([$_GET['ID']]);
    $SQLCobranca = $SQLCobranca->fetch();
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <title>Dados do Pedido | Sex Lingerie</title>
        <?php include("PHP/meta.php"); ?>
        <link rel='stylesheet' type='text/css' href='CSS/perfil.css'>
    </head>
    <body>
        <section class='preview small red'>
            <?php include("PHP/header.php"); ?>
            <div class='centralize'>
                <span>
                    <div class='row small white'><h1 class='txtBlack txt500'>M</h1></div>
                    <h1 class='txtBlack txt500'>Minha Conta</h1>
                </span>
            </div>
        </section>
        <main>
            <div class='container'>
                <div id='perfil'>
                    <div id='opcoes'>
                        <a href="perfil.php" class='button btBlack btBig btRounded txtGotham2 txtWhite txt600 txtNoDecoration'>Pedidos</a></br></br>
                        <a href="perfil-info.php" class='button btBlack btBig btRounded txtGotham2 txtWhite txt600 txtNoDecoration'>Informações</a></br></br>
                        <a href="perfil-enderecos.php" class='button btBlack btBig btRounded txtGotham2 txtWhite txt600 txtNoDecoration'>Endereços</a></br></br>
                        <a href="perfil.php?sair" class='button btBlack btBig btRounded txtGotham2 txtWhite txt600 txtNoDecoration'>Sair</a>
                    </div>
                    <div id='info'>
                        <h2 class='txtBlack txt500'>Pedido #
                        <?php
                            echo $SQL['IDPedido'] . ' - ';
                            switch ($SQL['Situacao']) {
                                case 1:
                                    echo "Aberta";
                                    break;
                                case 2:
                                    echo "Pendente";
                                    break;
                                case 3:
                                    echo "Enviado";
                                    break;
                                case 4:
                                    echo "Cancelado";
                                    break;
                                case 5:
                                    echo "Entregue";
                                    break;
                            }
                        ?></h2>
                        <div id='forma'>
                            <div>
                                <h2 class='txtBlack txtGotham3 txt100'>Forma de Entrega</h2>
                                <h3 class='txtBlack txtGotham'><?php echo $SQLEntrega['Forma']; ?></h3>
                                </br>
                                <h2 class='txtBlack txtGotham3 txt100'>Endereço de Entrega</h2>
                                <h3 class='txtBlack txtGotham'><b class='txtGotham3 txt100'>Endereço: </b><?php echo $SQLEntrega['Endereco']; ?></h3>
                                <h3 class='txtBlack txtGotham'><b class='txtGotham3 txt100'>Bairro: </b><?php echo $SQLEntrega['Bairro']; ?></h3>
                                <h3 class='txtBlack txtGotham'><b class='txtGotham3 txt100'>CEP: </b><?php echo $SQLEntrega['CEP']; ?></h3>
                                <h3 class='txtBlack txtGotham'><?php echo $SQLEntrega['Municipio'] . ', ' . $SQLEntrega['UF'] . ', ' . $SQLEntrega['Pais']; ?></h3>
                            </div>
                            <div>
                                <h2 class='txtBlack txtGotham3 txt100'>Forma de Pagamento</h2>
                                <h3 class='txtBlack txtGotham'><?php echo $SQLCobranca['Forma']; ?></h3>
                                </br>
                                <h2 class='txtBlack txtGotham3 txt100'>Endereço de Pagamento</h2>
                                <h3 class='txtBlack txtGotham'><b class='txtGotham3 txt100'>Endereço: </b><?php echo $SQLCobranca['Endereco']; ?></h3>
                                <h3 class='txtBlack txtGotham'><b class='txtGotham3 txt100'>Bairro: </b><?php echo $SQLCobranca['Bairro']; ?></h3>
                                <h3 class='txtBlack txtGotham'><b class='txtGotham3 txt100'>CEP: </b><?php echo $SQLCobranca['CEP']; ?></h3>
                                <h3 class='txtBlack txtGotham'><?php echo $SQLCobranca['Municipio'] . ', ' . $SQLCobranca['UF'] . ', ' . $SQLCobranca['Pais']; ?></h3>
                            </div>
                        </div></br>
                        <h2 class='txtBlack txt500'>Itens do Pedido</h2>
                        <table class='txtBlack'>
                            <tr>
                                <th class='txtGotham3 borderRight'>#</th>
                                <th class='txtGotham3 borderRight'>NOME</th>
                                <th class='txtGotham3 borderRight'>QNTD.</th>
                                <th class='txtGotham3 borderRight'>VALOR</th>
                                <th class='txtGotham3'>TOTAL</th>
                            </tr>
                            <?php
                                $SQLItem = $conn->prepare('SELECT * FROM pedido_item WHERE IDPedido=?');
                                $SQLItem->execute([$_GET['ID']]);
                                $SQLItem = $SQLItem->fetchAll();
                                foreach ($SQLItem as $value) {
                                    echo "<tr>";
                                        echo "<td class='txtGotham3 txtCenter borderRight txt600'>" . $value['Codigo'] ."</td>";
                                        echo "<td class='txtGotham txt100 borderRight'>" . $value['Nome'] ."</td>";
                                        echo "<td class='txtGotham txt100 txtCenter borderRight'>" . $value['Quantidade'] . "</td>";
                                        echo "<td class='txtGotham txtCenter borderRight'>R$&nbsp;" . number_format($value['ValorBruto'], 2, ',', '.') . "</td>";
                                        echo "<td class='txtGotham3 txtCenter'>R$&nbsp;" . number_format($value['ValorBruto'], 2, ',', '.') . "</td>";
                                    echo "</tr>";
                                }
                            ?>
                        </table></br>
                        <h1 class='txtGotham txtBlack' style='margin-bottom:5px;'>Frete: R$ <?php echo number_format($SQL['ValorFrete'], 2, ',', '.') ?></h1>
                        <h1 class='txtGotham txtBlack'>Valor Total: R$ <?php echo number_format($SQL['ValorLiquido'], 2, ',', '.') ?></h1>
                    </div>
                </div>
            </div>
        </main>
        <?php include('PHP/footer.php'); ?>
    </body>
</html>