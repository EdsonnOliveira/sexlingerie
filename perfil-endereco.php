<?php 
    session_start();
    include("PHP/conn.php");

    if (!isset($_SESSION['LOGIN']))
        header("location: login.php");

    $SQL = $conn->prepare("SELECT * FROM clientes_endereco WHERE IDEndereco=? LIMIT 1");;
    $SQL->execute([$_GET['ID']]);
    $SQL = $SQL->fetch();

    if (isset($_POST['salvar'])) {
        try {
            $SQLEndereco = $conn->prepare('UPDATE clientes_endereco SET Endereco=?, Numero=?, Bairro=?, Complemento=?, Municipio=?, Pais=?, UF=?, CEP=?
                                           WHERE IDEndereco=?');
            $SQLEndereco->execute([$_POST['endereco'], $_POST['numero'], $_POST['bairro'], $_POST['complemento'],
                                   $_POST['municipio'], 'Brasil', $_POST['uf'], $_POST['cep'], $_GET['ID']]);
            header("location: perfil-enderecos.php");
        } catch (PDOException $e) {
            echo "<script>alert($e)</script>";
        }
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <title>Meus Endereços | Sex Lingerie</title>
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
                        <h2 class='txtBlack txt500'>Informações do Endereço</h2>
                        <form method='post'>
                            <div>
                        <label>
                            <h3 class='txtGotham'>CEP</h3>
                            <input type="text" name='cep' class='cep input ipBorder ipRounded ipGrey ipBig txtGotham' placeholder='Seu CEP' required value='<?php echo $SQL['CEP'] ?>'>
                        </label>
                    </div>
                    <div>
                        <label style='margin-right:30px'>
                            <h3 class='txtGotham'>Endereço</h3>
                            <input type="text" name='endereco' class='input ipBorder ipRounded ipGrey ipBig txtGotham' placeholder='Seu endereço' required value='<?php echo $SQL['Endereco'] ?>'>
                        </label>
                        <label>
                            <h3 class='txtGotham'>Número</h3>
                            <input type="text" name='numero' class='input ipBorder ipRounded ipGrey ipBig txtGotham' placeholder='Número do seu endereço' required value='<?php echo $SQL['Numero'] ?>'>
                        </label>
                    </div>
                    <div>
                        <label style='margin-right:30px'>
                            <h3 class='txtGotham'>Bairro</h3>
                            <input type="text" name='bairro' class='input ipBorder ipRounded ipGrey ipBig txtGotham' placeholder='Seu bairro' required value='<?php echo $SQL['Bairro'] ?>'>
                        </label>
                        <label>
                            <h3 class='txtGotham'>Complemento</h3>
                            <input type="text" name='complemento' class='input ipBorder ipRounded ipGrey ipBig txtGotham' placeholder='Complemento do seu endereço' value='<?php echo $SQL['Complemento'] ?>'>
                        </label>
                    </div>
                    <div>
                        <label style='margin-right:30px'>
                            <h3 class='txtGotham'>Estado</h3>
                            <select name="uf" class="input ipBorder ipRounded ipGrey ipBig txtGotham" style='width:300px' required>
                                <?php
                                    $SQLUF = $conn->prepare('SELECT * FROM uf');
                                    $SQLUF->execute();
                                    $SQLUF = $SQLUF->fetchAll();

                                    foreach ($SQLUF as $value) {
                                        $selected = '';
                                        if ($value['UF'] == $SQL['UF'])
                                            $selected = 'selected';
                                        echo "<option value='".$value['UF']."' $selected>" . $value['Estado'] . "</option>";
                                    }
                                ?>
                            </select>
                        </label>
                        <label>
                            <h3 class='txtGotham'>Município</h3>
                            <input type="text" name='municipio' class='input ipBorder ipRounded ipGrey ipBig txtGotham' placeholder='Seu município' required value='<?php echo $SQL['Municipio'] ?>'>
                        </label>
                    </div>
                            <input type="submit" name='salvar' class='button btRed btRounded btBigger txtWhite txtGotham2 txt100' value='SALVAR'>
                        </form>
                    </div>
                </div>
            </div>
        </main>
        <?php include('PHP/footer.php'); ?>
    </body>
</html>