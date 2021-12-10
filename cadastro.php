<?php
    include("PHP/conn.php");

    session_start();

    $erro = '';

    if (isset($_POST['cadastro'])) {
        try {
            $SQL = $conn->prepare('SELECT * FROM clientes WHERE Email=? AND IDFilial=? AND Situacao=? LIMIT 1');
            $SQL->execute([$_POST['email'], '1', '1']);
            if ($SQL->rowCount() > 0)
                $erro = 'Email informado já está em uso!';

            $SQL = $conn->prepare('SELECT * FROM clientes WHERE CPF=? AND IDFilial=? AND Situacao=? LIMIT 1');
            $SQL->execute([$_POST['cpf'], '1', '1']);
            if ($SQL->rowCount() > 0)
                $erro = 'CPF informado já está em uso!';

            if ($_POST['senha'] <> $_POST['confirmarSenha'])
                $erro = 'As senhas não conferem!';
                
            if ($erro == '') {
                $SQL = $conn->prepare('INSERT INTO clientes (IDFilial, Nome, Email, Sexo, CPF, DataNascimento, Celular, DataCadastro, Senha, Situacao) 
                                       VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
                $SQL->execute(['1', $_POST['nome'], $_POST['email'], $_POST['sexo'], $_POST['cpf'], $_POST['dataNascimento'], $_POST['celular'],
                                date('Y/m/d'), $_POST['senha'], '1']);
                
                $SQL = $conn->prepare('SELECT * FROM clientes WHERE IDFilial=? ORDER BY IDCliente LIMIT 1');
                $SQL->execute(['1']);
                $SQL = $SQL->fetch();

                $SQLEndereco = $conn->prepare('INSERT INTO clientes_endereco (IDCliente, Endereco, Numero, Bairro, Complemento, Municipio, Pais, UF, CEP)
                                               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
                $SQLEndereco->execute([$SQL['IDCliente'], $_POST['endereco'], $_POST['numero'], $_POST['bairro'], $_POST['complemento'],
                                       $_POST['municipio'], 'Brasil', $_POST['uf'], $_POST['cep']]);

                $_SESSION['LOGIN']['ID'] = $SQL['IDCliente'];
                if (isset($_GET['finalizar']))
                    header("location: entrega.php");
                else
                    header("location: perfil.php");
            }
        } catch(PDOException $e) {
            $erro = $e;
        }
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <title>Login | Sex Lingerie</title> 
        <?php include("PHP/meta.php"); ?>
        <link rel='stylesheet' type='text/css' href='CSS/cadastro.css'>
    </head>
    <body>
        <section class='preview small red'>
            <?php include("PHP/header.php"); ?>
            <div class='centralize'>
                <span>
                    <div class='row small white'><h1 class='txtBlack txt500'>C</h1></div>
                    <h1 class='txtBlack txt500' style='margin-left:20px'>Cadastro</h1>
                </span>
            </div>
        </section>
        <main>
            <div class='container' style='display:flex;'>
                <form method='post' style='margin: auto;'>
                    <?php
                        echo "<h1 class='txtRed txtCenter'>" . $erro . "</h1>";
                    ?>
                    <h1 class='txtBlack'>Dados Pessoais</h1>
                    <div>
                        <label>
                            <h3 class='txtGotham'>Nome</h3>
                            <input type="text" name='nome' class='input ipBorder ipRounded ipGrey ipBig txtGotham' placeholder='Seu nome completo' autofocus required>
                        </label>
                        <label>
                            <h3 class='txtGotham'>CPF</h3>
                            <input type="text" name='cpf' class='cpf input ipBorder ipRounded ipGrey ipBig txtGotham' placeholder='Seu CPF' required>
                        </label>
                    </div>
                    <div>
                        <label>
                            <h3 class='txtGotham'>E-mail</h3>
                            <input type="email" name='email' class='input ipBorder ipRounded ipGrey ipBig txtGotham' placeholder='seu@email.com' required>
                        </label>
                        <label>
                            <h3 class='txtGotham'>Data de Nascimento</h3>
                            <input type="date" name='dataNascimento' class='input ipBorder ipRounded ipGrey ipBig txtGotham' style='height:20px' required>
                        </label>
                    </div>
                    <div>
                        <label>
                            <h3 class='txtGotham'>Celular</h3>
                            <input type="text" name='celular' class='celular input ipBorder ipRounded ipGrey ipBig txtGotham' placeholder='(00) 0000-0000' required>
                        </label>
                        <label>
                            <h3 class='txtGotham'>Sexo</h3>
                            <select name="sexo" class="input ipBorder ipRounded ipGrey ipBig txtGotham" style='width:300px' required>
                                <option value="F">Feminino</option>
                                <option value="M">Masculino</option>
                                <option value="O">Outro</option>
                            </select>
                        </label>
                    </div>
                    <div>
                        <label>
                            <h3 class='txtGotham'>Senha</h3>
                            <input type="password" name='senha' class='input ipBorder ipRounded ipGrey ipBig txtGotham' required>
                        </label>
                        <label>
                            <h3 class='txtGotham'>Confirmar Senha</h3>
                            <input type="password" name='confirmarSenha' class='input ipBorder ipRounded ipGrey ipBig txtGotham' required>
                        </label>
                    </div></br>
                    <h1 class='txtBlack'>Dados de Entrega</h1>
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
                    </br>
                    <center><input type="submit" name='cadastro' class='button btRed btBigger btRounded txtGotham2 txtWhite' value='CADASTRAR' style='font-size:15px;letter-spacing:2px'></center>
                </form>
            </div>
        </main>
        <?php include('PHP/footer.php'); ?>
    </body>
</html>