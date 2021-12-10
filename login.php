<?php 
    session_start();
    include("PHP/conn.php"); 

    if (isset($_SESSION['LOGIN'])) 
        header("location: perfil.php");

    if (isset($_POST['entrar'])) {
        $campo = 'CPF';
        if (strpos($_POST['emailCPF'], '@'))
            $campo = 'Email';

        $SQL = $conn->prepare('SELECT * FROM clientes WHERE '.$campo.'=? AND Senha=? AND Situacao=? LIMIT 1');
        $SQL->execute([$_POST['emailCPF'], $_POST['senha'], '1']);

        if ($SQL->rowCount() > 0) {
            $SQL = $SQL->fetch();
            $_SESSION['LOGIN']['ID'] = $SQL['IDCliente'];

            if (isset($_GET['finalizar']))
                header("location: entrega.php");
            else
                header("location: perfil.php");
        } else
            echo "<script>alert('$campo ou Senha incorretos!')</script>";
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <title>Login | Sex Lingerie</title>
        <?php include("PHP/meta.php"); ?>
        <link rel='stylesheet' type='text/css' href='CSS/login.css'>
    </head>
    <body>
        <section class='preview small red'>
            <?php include("PHP/header.php"); ?>
            <div class='centralize'>
                <span>
                    <div class='row small white'><h1 class='txtBlack txt500'>L</h1></div>
                    <h1 class='txtBlack txt500' style='margin-left:25px'>Login</h1>
                </span>
            </div>
        </section>
        <main>
            <div class='container'>
                <div id='login'>
                    <div id='entrar'>
                        <h1 class='txtBlack txtGotham2 txtCenter'>Entrar</h1>
                        <form method='post'>
                            <label>
                                <h3 class='txtGotham'>E-Mail ou CPF</h3>
                                <input type="text" name='emailCPF' class='input ipBorder ipRounded ipGrey ipBig txtGotham' placeholder='Seu e-mail ou CPF'>
                            </label></br></br>
                            <label>
                                <h3 class='txtGotham'>Senha</h3>
                                <input type="password" name='senha' class='input ipBorder ipRounded ipGrey ipBig txtGotham txt400' placeholder='Sua senha'>
                            </label></br></br>
                            <center><input type="submit" name='entrar' class='button btBlack btBigger btRounded txtGotham txtWhite' value='ENTRAR'></center>
                        </form>
                    </div>
                    <div id='cadastrar'>
                        <h1 class='txtBlack txtGotham2 txtCenter'>Cadastrar-se</h1>
                        <center>
                            <?php
                                if (isset($_GET['finalizar']))
                                    echo "<a href='cadastro.php?finalizar' id='btCadastrar' class='button btRed btBigger btRounded txtWhite txtGotham txtUpper txt900'>Cadastrar com email e senha</a></br></br></br>";
                                else
                                    echo "<a href='cadastro.php' id='btCadastrar' class='button btRed btBigger btRounded txtWhite txtGotham txtUpper txt900'>Cadastrar com email e senha</a></br></br></br>";
                            ?>
                            <!-- <a href="" id='btGoogle' class='button btGrey btBigger btRounded txtBlack txtGotham txtUpper txt900'><img src='IMG/Custom/Google.png'> &nbsp;&nbsp; Cadastrar com Google</a></br></br></br>
                            <a href="" id='btFacebook' class='button btGrey btBigger btRounded txtBlack txtGotham txtUpper txt900'><img src='IMG/Custom/facebook2.png'> &nbsp;&nbsp;&nbsp;Cadastrar com Facebook</a></br></br></br> -->
                        </center>
                    </div>
                </div>
            </div>
        </main>
        <?php include('PHP/footer.php'); ?>
    </body>
</html>