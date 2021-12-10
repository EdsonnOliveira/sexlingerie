<?php 
    session_start();

    include("PHP/conn.php"); 

    if (!isset($_SESSION['LOGIN']))
        header("location: login.php");

    if (isset($_GET['sair'])) {
        unset($_SESSION['LOGIN']);
        header("location: login.php");
    }

    if (isset($_GET['collection_status'])) {
        try {
            $SQLCliente = $conn->prepare("SELECT * FROM clientes WHERE IDCliente=? LIMIT 1");
            $SQLCliente->execute([$_SESSION['LOGIN']['ID']]);
            $SQLCliente = $SQLCliente->fetch();

            $situacao = '1';
            if ($_GET['payment_type'] == 'ticket')
                $situacao = '2';

            $SQL = $conn->prepare("INSERT INTO pedido (IDFilial, IDCliente, NomeCliente, ValorFrete, DataCriacao, HoraCriacao,
                                                    DiasFrete, Situacao) VALUES (?, ?, ?, ?, ?, ?,
                                                                                    ?, ?)");
            $SQL->execute(['1', $_SESSION['LOGIN']['ID'], $SQLCliente['Nome'], str_replace(',', '.', $_GET['valorFrete']), date('Y/m/d'), date('H:i:s'),
                            $_GET['diasFrete'], $situacao]);

            $SQL = $conn->prepare('SELECT * FROM pedido WHERE IDCliente=? ORDER BY IDPedido DESC LIMIT 1');
            $SQL->execute([$_SESSION['LOGIN']['ID']]);
            $SQL = $SQL->fetch();
            $IDPedido = $SQL['IDPedido'];

            $valorBruto = 0;
            $valorLiquido = 0;
            $volume = 0;
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

                $SQLItem = $conn->prepare("INSERT INTO pedido_item (IDPedido, IDProduto, Codigo, Nome, UM, Quantidade, ValorBruto, ValorLiquido, Tamanho, Cor)
                                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $SQLItem->execute([$IDPedido, $SQLProd['IDProduto'], $SQLProd['Codigo'], $SQLProd['Nome'], $SQLProd['UM'],
                                   $_SESSION['BAG'][$IDProduto]['Quantidade'], $SQLProd['ValorVenda'],
                                   $_SESSION['BAG'][$IDProduto]['Quantidade'] * $SQLProd['ValorVenda'], $SQLSize['Tamanho'], $SQLColor['Cor']]);

                $valorBruto = $valorBruto + $SQLProd['ValorVenda'];
                $valorLiquido = $valorLiquido + $SQLProd['ValorVenda'];
                $volume++;
            }

            $SQLCobranca = $conn->prepare("SELECT * FROM clientes_endereco WHERE IDEndereco=? LIMIT 1");
            $SQLCobranca->execute([$_GET['enderecoCobranca']]);
            $SQLCobranca = $SQLCobranca->fetch();

            $SQLEndereco = $conn->prepare('INSERT INTO pedido_cobranca (IDPedido, Forma, Endereco, Bairro, CEP, Municipio, UF, Pais) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
            $SQLEndereco->execute([$IDPedido, $_GET['payment_type'], $SQLCobranca['Endereco'], $SQLCobranca['Bairro'], $SQLCobranca['CEP'], $SQLCobranca['Municipio'], $SQLCobranca['UF'], $SQLCobranca['Pais']]);

            if ($_GET['enderecoEntrega'] != 'entregaLoja') {
                $SQLEntrega = $conn->prepare("SELECT * FROM clientes_endereco WHERE IDEndereco=? LIMIT 1");
                $SQLEntrega->execute([$_GET['enderecoEntrega']]);
                $SQLEntrega = $SQLEntrega->fetch();

                $SQLEndereco = $conn->prepare('INSERT INTO pedido_entrega (IDPedido, Forma, Endereco, Bairro, CEP, Municipio, UF, Pais) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
                $SQLEndereco->execute([$IDPedido, 'Frete', $SQLEntrega['Endereco'], $SQLEntrega['Bairro'], $SQLEntrega['CEP'], $SQLEntrega['Municipio'], $SQLEntrega['UF'], $SQLEntrega['Pais']]);
            } else {
                $SQLEndereco = $conn->prepare('INSERT INTO pedido_entrega (IDPedido, Forma) VALUES (?, ?)');
                $SQLEndereco->execute([$IDPedido, 'Retirada na Loja']);
            }

            $SQL = $conn->prepare('UPDATE pedido SET ValorBruto=?, ValorLiquido=?, Volume=? WHERE IDPedido=? LIMIT 1');
            $SQL->execute([$valorBruto, $valorLiquido, $volume, $IDPedido]);

            unset($_SESSION['BAG']);
            header("location: perfil.php");
        } catch (PDOException $e) {
            echo "<script>alert($e)</script>";
        }
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <title>Meu Pedidos | Sex Lingerie</title>
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
                        <h2 class='txtBlack txt500'>Meus Pedidos</h2>
                        <table class='txtBlack'>
                            <tr>
                                <th class='txtGotham3 borderRight'>#</th>
                                <th class='txtGotham3 borderRight'>ITENS</th>
                                <th class='txtGotham3 borderRight'>DATA</th>
                                <th class='txtGotham3 borderRight'>TOTAL</th>
                                <th class='txtGotham3'>STATUS</th>
                            </tr>
                            <?php
                                $SQLPedidos = $conn->prepare("SELECT * FROM pedido WHERE IDCliente=? ORDER BY IDPedido DESC");
                                $SQLPedidos->execute([$_SESSION['LOGIN']['ID']]);
                                $SQLPedidos = $SQLPedidos->fetchAll();
                                foreach ($SQLPedidos as $value) {
                                    echo "<tr>";
                                        echo "<td class='txtCenter borderRight txt600'><a href='perfil-pedido.php?ID=".$value['IDPedido']."' class='txtGotham2 txtBlack txtNoDecoration'>" . $value['IDPedido'] . "</a></td>";

                                    $SQLItens = $conn->prepare('SELECT * FROM pedido_item WHERE IDPedido=?');
                                    $SQLItens->execute([$value['IDPedido']]);
                                    $SQLItens = $SQLItens->fetchAll();

                                        echo "<td class='txt100 borderRight'><a href='perfil-pedido.php?ID=".$value['IDPedido']."' class='txtGotham txtBlack txtNoDecoration'>";
                                            foreach ($SQLItens as $item) 
                                                echo $item['Nome'] . "</br>";
                                        echo "</a></td>";
                                        echo "<td class='txt100 txtCenter borderRight'><a href='perfil-pedido.php?ID=".$value['IDPedido']."' class='txtGotham txtBlack txtNoDecoration'>" . $value['DataCriacao'] . "</a></td>";
                                        echo "<td class='txtCenter borderRight'><a href='perfil-pedido.php?ID=".$value['IDPedido']."' class='txtGotham2 txtBlack txtNoDecoration'>R$&nbsp;" . $value['ValorLiquido'] . "</a></td>";
                                        switch ($value['Situacao']) {
                                            case 1:
                                                echo "<td class='txtCenter'><a href='perfil-pedido.php?ID=".$value['IDPedido']."' class='txtGotham2 txtBlack txtNoDecoration'>Aguardando Envio</a></td>";
                                                break;
                                            case 2:
                                                echo "<td class='txtCenter'><a href='perfil-pedido.php?ID=".$value['IDPedido']."' class='txtGotham2 txtBlack txtNoDecoration'>Pendente</a></td>";
                                                break;
                                            case 3:
                                                echo "<td class='txtCenter'><a href='perfil-pedido.php?ID=".$value['IDPedido']."' class='txtGotham2 txtBlack txtNoDecoration'>Enviado</a></td>";
                                                break;
                                            case 4:
                                                echo "<td class='txtCenter'><a href='perfil-pedido.php?ID=".$value['IDPedido']."' class='txtGotham2 txtBlack txtNoDecoration'>Cancelado</a></td>";
                                                break;
                                            case 5:
                                                echo "<td class='txtCenter'><a href='perfil-pedido.php?ID=".$value['IDPedido']."' class='txtGotham2 txtBlack txtNoDecoration'>Entregue</a></td>";
                                                break;
                                        }
                                    echo "</tr>";
                                }
                            ?>
                        </table>
                    </div>
                </div>
            </div>
        </main>
        <?php include('PHP/footer.php'); ?>
    </body>
</html>