<?php
    session_start();

    include("PHP/conn.php");
    include("PHP/frete.php");

    if (!isset($_SESSION['BAG']))
        header("location: index.php");

    if (isset($_POST['adicionarEndereco'])) {
        try {
            $SQLEndereco = $conn->prepare('INSERT INTO clientes_endereco (IDCliente, Endereco, Numero, Bairro, Complemento, Municipio, Pais, UF, CEP)
                                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
            $SQLEndereco->execute([$_SESSION['LOGIN']['ID'], $_POST['endereco'], $_POST['numero'], $_POST['bairro'], $_POST['complemento'],
                                   $_POST['municipio'], 'Brasil', $_POST['uf'], $_POST['cep']]);
            header("location: entrega.php");
        } catch (PDOException $e) {
            echo "<script>alert($e)</script>";
        }
    }

    if (isset($_POST['finalizarEntrega'])) {
        try {
            header("location: pagamento.php?enderecoCobranca=".$_POST['enderecoCobranca'].'&enderecoEntrega='.$_POST['enderecoEntrega']);
        } catch (PDOException $e) {
            echo "<script>alert($e)</script>";
        }
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <title>Entrega | Sex Lingerie</title>
        <?php include("PHP/meta.php"); ?>
        <link rel='stylesheet' type='text/css' href='CSS/entrega.css'>
        <script src='JS/entrega.js'></script>
    </head>
    <body>
        <section class='preview small red'>
            <?php include("PHP/header.php"); ?>
            <div class='centralize'>
                <span>
                    <div class='row small white'><h1 class='txtBlack txt500'>E</h1></div>
                    <h1 class='txtBlack txt500'>Entrega</h1>
                </span>
            </div>
        </section>
        <main>
            <div class='container'>
                <div class='entrega'>
                    <a href="bag.php" class='button btBlack btBigger btRounded txtGotham2 txtWhite txt900 txtUpper' style='margin-right:15px;'>sacola</a>
                    <a href="perfil-enderecos.php" class='button btBlack btBigger btRounded txtGotham2 txtWhite txt900 txtUpper'>Ir para seus endereços</a>
                    <form method='post'>
                    <h1 class='txtCenter txtBlack txt100' style='margin-top:30px;margin-bottom:20px'>Endereço de Cobrança</h1>
                    <section>
                        <?php
                            $SQLCobranca = $conn->prepare('SELECT * FROM clientes_endereco WHERE IDCliente=? LIMIT 2');
                            $SQLCobranca->execute([$_SESSION['LOGIN']['ID']]);
                            $rowCount = $SQLCobranca->rowCount();

                            $selected = '';

                            if ($rowCount < 2) {
                                echo "<div class='endereco add'>";
                                    echo "<div>";
                                        echo "<h1 class='txtCenter txtBlack txtGotham txt600'>Adicionar</h1>";
                                        echo "<h1 class='txtCenter txtBlack txtGotham3 txt600'>endereço</h1>";
                                        echo "<center><input type='button' onclick=" . '"' . "showModal('modalEndereco')" . '"' ." class='button btRounded btBorder btNormal' value='Adicionar'></center>";
                                    echo "</div>";
                                echo "</div>";
                            }

                            $I = 1;
                            $SQLCobranca = $SQLCobranca->fetchAll();
                            foreach ($SQLCobranca as $value) {
                                if ($I == 1) {
                                    echo "<input type='hidden' name='enderecoCobranca' id='enderecoCobranca' value='" . $value['IDEndereco'] . "'>";
                                    $selected = 'selected';
                                }
                                    
                                echo "<div class='enderecoCobranca endereco $selected' id='cobranca" . $value['IDEndereco'] . "'>";
                                    echo "<div>";
                                        echo "<h1 class='txtCenter txtBlack txtGotham3 txt100 txtUpper'>" . $value['Endereco'] . "</h1>";
                                        echo "<h2 class='txtCenter txtBlack txtGotham'>" . $value['Bairro'] . "</h2>";
                                        echo "<h2 class='txtCenter txtBlack txtGotham'>" . $value['Municipio'] . '-' . $value['UF'] . "</h2>";
                                        echo "<h3 class='txtCenter txtBlack txtGotham3 txt600'>" . $value['CEP'] . "</h3>";
                                        echo "<center><input type='button' onclick=" . '"' . "selectCobranca('cobranca" . $value['IDEndereco'] . "')" . '"' ." class='button btRounded btBorder btNormal' value='Selecionar'></center>";
                                    echo "</div>";
                                echo "</div>";

                                $selected = '';
                                $I++;
                            }
                        ?>
                    </section>
                    </br>
                    <h1 class='txtCenter txtBlack txt100' style='margin-top:30px;margin-bottom:20px'>Como gostaria de receber seu Pedido?</h1>
                    <section>
                        <?php
                            $SQLEntrega = $conn->prepare('SELECT * FROM clientes_endereco WHERE IDCliente=? LIMIT 2');
                            $SQLEntrega->execute([$_SESSION['LOGIN']['ID']]);
                            $rowCount = $SQLEntrega->rowCount();

                            $selected = '';

                            if ($rowCount < 2) {
                                echo "<div class='endereco add'>";
                                    echo "<div>";
                                        echo "<h1 class='txtCenter txtBlack txtGotham txt600'>Adicionar</h1>";
                                        echo "<h1 class='txtCenter txtBlack txtGotham3 txt600'>endereço</h1>";
                                        echo "<center><input type='button' onclick=" . '"' . "showModal('modalEndereco')" . '"' ." class='button btRounded btBorder btNormal' value='Adicionar'></center>";
                                    echo "</div>";
                                echo "</div>";
                            }

                            $I = 1;
                            $SQLEntrega = $SQLEntrega->fetchAll();
                            foreach ($SQLEntrega as $value) {
                                if ($I == 1) {
                                    echo "<input type='hidden' name='enderecoEntrega' id='enderecoEntrega' value='" . $value['IDEndereco'] . "'>";
                                    $selected = 'selected';
                                }

                                $frete = new frete;
                                $frete->calcularfrete($value['CEP']);
                                $data = date('d/m/Y', strtotime('+'.$frete->entregaFrete.' days'));
                                    
                                echo "<div class='enderecoEntrega endereco $selected' id='entrega" . $value['IDEndereco'] . "'>";
                                    echo "<div>";
                                        echo "<h1 class='txtCenter txtBlack txtGotham3 txt100 txtUpper'>" . $value['Endereco'] . "</h1>";
                                        // echo "<h2 class='txtCenter txtBlack txtGotham'>" . $value['Bairro'] . "</h2>";
                                        // echo "<h2 class='txtCenter txtBlack txtGotham'>" . $value['Municipio'] . '-' . $value['UF'] . "</h2>";
                                        echo "<h2 class='txtCenter txtBlack txtGotham' style='font-size:27px;margin-top:10px;'>Valor do Frete: <b class='txtRed txtGotham3 txt600'>R$ " . $frete->valorFrete . "</b></h2>";
                                        echo "<h2 class='txtCenter txtBlack txtGotham' style='font-size:27px;margin-bottom:10px;'>Entrega: <b class='txtRed txtGotham3 txt600'>" . $data . "</b></h2>";
                                        echo "<h3 class='txtCenter txtBlack txtGotham3 txt600'>" . $value['CEP'] . "</h3>";
                                        echo "<center><input type='button' onclick=" . '"' . "selectEntrega('entrega" . $value['IDEndereco'] . "')" . '"' ." class='button btRounded btBorder btNormal' value='Selecionar'></center>";
                                    echo "</div>";
                                echo "</div>";

                                $selected = '';
                                $I++;
                            }
                        ?>
                    </section></br>
                    <section>
                        <div class='endereco retireLoja' id='entregaLoja'>
                            <div>
                                <h1 class='txtCenter txtBlack txtGotham3 txt600'>Retire <b class='txtRed txtGotham3 txt600'>grátis</b> na Loja</h1>
                                <h2 class='txtCenter txtBlack txtGotham txt300' style='margin-top: 25px;margin-bottom:10px;'>A partir de <b class='txtRed txtGotham'>7 dias</b> úteis</h2>
                                <center><input type='button' onclick="selectEntrega('entregaLoja')" class='button btRounded btBorder btNormal' value='Selecionar' style='width:250px;'></center>
                            </div>
                        </div>
                    </section></br>
                    <center><input type="submit" name='finalizarEntrega' class='button btRed btRounded btBigger txtUpper txtWhite txtGotham3 txt600' value='Ir para pagamento' style='width:350px;letter-spacing:3px'></center>
                    </form>
                </div>
            </div>
            <div id='modalEndereco' class='modalContainer closeModal'>
                <div class="modal">
                    <h1 class='txtBlack txtCenter txt100 title'>Adicionar Endereço</h1>
                    <div class='mainModal'>
                        <form method='post'>
                            <div>
                                <label>
                                    <h3 class='txtGotham'>CEP</h3>
                                    <input type="text" name='cep' class='cep input ipBorder ipRounded ipGrey ipBig txtGotham' placeholder='Seu CEP' required>
                                </label>
                            </div>
                            <div>
                                <label>
                                    <h3 class='txtGotham'>Endereço</h3>
                                    <input type="text" name='endereco' class='input ipBorder ipRounded ipGrey ipBig txtGotham' placeholder='Seu endereço' required>
                                </label>
                                <label>
                                    <h3 class='txtGotham'>Número</h3>
                                    <input type="text" name='numero' class='input ipBorder ipRounded ipGrey ipBig txtGotham' placeholder='Número do seu endereço' required>
                                </label>
                            </div>
                            <div>
                                <label>
                                    <h3 class='txtGotham'>Bairro</h3>
                                    <input type="text" name='bairro' class='input ipBorder ipRounded ipGrey ipBig txtGotham' placeholder='Seu bairro' required>
                                </label>
                                <label>
                                    <h3 class='txtGotham'>Complemento</h3>
                                    <input type="text" name='complemento' class='input ipBorder ipRounded ipGrey ipBig txtGotham' placeholder='Complemento do seu endereço'>
                                </label>
                            </div>
                            <div>
                                <label>
                                    <h3 class='txtGotham'>Estado</h3>
                                    <select name="uf" class="input ipBorder ipRounded ipGrey ipBig txtGotham" style='width:300px' required>
                                        <?php
                                            $SQLUF = $conn->prepare('SELECT * FROM uf');
                                            $SQLUF->execute();
                                            $SQLUF = $SQLUF->fetchAll();

                                            foreach ($SQLUF as $value) {
                                                $selected = '';
                                                if ($value['UF'] == 'DF')
                                                    $selected = 'selected';
                                                echo "<option value='".$value['UF']."' $selected>" . $value['Estado'] . "</option>";
                                            }
                                        ?>
                                    </select>
                                </label>
                                <label>
                                    <h3 class='txtGotham'>Município</h3>
                                    <input type="text" name='municipio' class='input ipBorder ipRounded ipGrey ipBig txtGotham' placeholder='Seu município' required>
                                </label>
                            </div>
                            <center><input type="submit" name='adicionarEndereco' class='button btRed btRounded btBigger txtGotham txtWhite txt600 txtUpper' value='SALVAR' style='letter-spacing:2px;font-size:17px;width:300px;margin: 10px 0;'></center>
                        </form>
                    </div>
                </div>
            </div>
        </main>
        <?php include('PHP/footer.php'); ?>
    </body>
</html>