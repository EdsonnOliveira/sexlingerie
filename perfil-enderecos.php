<?php 
    session_start();

    include("PHP/conn.php"); 

    if (!isset($_SESSION['LOGIN']))
        header("location: login.php");

    if (isset($_GET['sair'])) {
        unset($_SESSION['LOGIN']);
        header("location: login.php");
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <title>Meu Endereços | Sex Lingerie</title>
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
                        <h2 class='txtBlack txt500'>Meus Endereços</h2>
                        <table class='txtBlack'>
                            <tr>
                                <th class='txtGotham3 borderRight'>CEP</th>
                                <th class='txtGotham3 borderRight'>ENDERECO</th>
                                <th class='txtGotham3 borderRight'>BAIRRO</th>
                                <th class='txtGotham3 borderRight'>MUNICIPIO</th>
                                <th class='txtGotham3'>UF</th>
                            </tr>
                            <?php
                                $SQLEnderecos = $conn->prepare("SELECT * FROM clientes_endereco WHERE IDCliente=?");
                                $SQLEnderecos->execute([$_SESSION['LOGIN']['ID']]);
                                $SQLEnderecos = $SQLEnderecos->fetchAll();
                                foreach ($SQLEnderecos as $value) {
                                    echo "<tr>";
                                        echo "<td class='txtCenter borderRight txt600'><a href='perfil-endereco.php?ID=".$value['IDEndereco']."' class='txtGotham2 txtBlack txtNoDecoration'>" . $value['CEP'] . "</a></td>";
                                        echo "<td class='txt100 borderRight'><a href='perfil-endereco.php?ID=".$value['IDEndereco']."' class='txtGotham txtBlack txtNoDecoration'>" . $value['Endereco'] . "</a></td>";
                                        echo "<td class='txt100 txtCenter borderRight'><a href='perfil-endereco.php?ID=".$value['IDEndereco']."' class='txtGotham txtBlack txtNoDecoration'>" . $value['Bairro'] . "</a></td>";
                                        echo "<td class='txtCenter borderRight'><a href='perfil-endereco.php?ID=".$value['IDEndereco']."' class='txtGotham2 txtBlack txtNoDecoration'>" . $value['Municipio'] . "</a></td>";
                                        echo "<td class='txtCenter'><a href='perfil-endereco.php?ID=".$value['IDEndereco']."' class='txtGotham2 txtBlack txtNoDecoration'>" . $value['UF'] . "</a></td>";
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