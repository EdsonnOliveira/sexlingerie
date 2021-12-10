<?php 
    session_start();
    include("PHP/conn.php");

    if (!isset($_SESSION['LOGIN']))
        header("location: login.php");

    $SQL = $conn->prepare("SELECT * FROM clientes WHERE IDCliente=? LIMIT 1");;
    $SQL->execute([$_SESSION['LOGIN']['ID']]);
    $SQL = $SQL->fetch();

    $erro = '';

    if (isset($_POST['salvar'])) {
        try {
            $SQLVerify = $conn->prepare('SELECT * FROM clientes WHERE Email=? AND IDFilial=? AND Situacao=? AND IDCliente<>? LIMIT 1');
            $SQLVerify->execute([$_POST['email'], '1', '1', $_SESSION['LOGIN']['ID']]);
            if ($SQLVerify->rowCount() > 0)
                $erro = 'Email informado já está em uso!';

            $SQLVerify = $conn->prepare('SELECT * FROM clientes WHERE CPF=? AND IDFilial=? AND Situacao=? AND IDCliente<>? LIMIT 1');
            $SQLVerify->execute([$_POST['cpf'], '1', '1', $_SESSION['LOGIN']['ID']]);
            if ($SQLVerify->rowCount() > 0) 
                $erro = 'CPF informado já está em uso!';

            if ($_POST['senha'] <> $_POST['confirmarSenha'])
                $erro = 'As senhas não conferem!';

            if ($erro == '') {
                $SQL = $conn->prepare("UPDATE clientes SET Nome=?, CPF=?, Email=?, DataNascimento=?, Senha=? WHERE IDCliente=? LIMIT 1");
                $SQL->execute([$_POST['nome'], $_POST['cpf'], $_POST['email'], $_POST['dataNascimento'], $_POST['senha'], $_SESSION['LOGIN']['ID']]);
                header("location: perfil-info.php");
            }
        } catch (PDOException $e) {
            echo "<script>alert($e)</script>";
        }
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <title>Minhas Informações | Sex Lingerie</title>
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
                        <h2 class='txtBlack txt500'>Minhas Informações</h2>
                        <form method='post'>
                            <?php
                                echo "<h1 class='txtRed txtCenter'>" . $erro . "</h1>";
                            ?>
                            <div>
                                <label style='margin-right:30px'>
                                    <h3 class='txtGotham txtBlack'>Nome</h3>
                                    <input type="text" name='nome' class='input ipBorder ipRounded ipGrey ipBig txtGotham' placeholder='Seu nome completo' value='<?php echo $SQL['Nome'] ?>'>
                                </label>
                                <label>
                                    <h3 class='txtGotham txtBlack'>CPF</h3>
                                    <input type="text" name='cpf' class='input ipBorder ipRounded ipGrey ipBig txtGotham' placeholder='Seu CPF' value='<?php echo $SQL['CPF'] ?>'>
                                </label>
                            </div>
                            <div>
                                <label style='margin-right:30px'>
                                    <h3 class='txtGotham txtBlack'>E-Mail</h3>
                                    <input type="email" name='email' class='input ipBorder ipRounded ipGrey ipBig txtGotham' placeholder='seu@email.com' value='<?php echo $SQL['Email'] ?>'>
                                </label>
                                <label>
                                    <h3 class='txtGotham txtBlack'>Data de Nascimento</h3>
                                    <input type="date" name='dataNascimento' class='input ipBorder ipRounded ipGrey ipBig txtGotham' value='<?php echo $SQL['DataNascimento'] ?>'>
                                </label>
                            </div>
                            <div>
                                <label style='margin-right:30px'>
                                    <h3 class='txtGotham txtBlack'>Senha</h3>
                                    <input type="password" name='senha' class='input ipBorder ipRounded ipGrey ipBig txtGotham' value=''>
                                </label>
                                <label>
                                    <h3 class='txtGotham txtBlack'>Confirmar Senha</h3>
                                    <input type="password" name='confirmarSenha' class='input ipBorder ipRounded ipGrey ipBig txtGotham' value=''>
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