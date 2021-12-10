<?php
    if (isset($_POST['salvarNewsLetter'])) {
        try {
            $SQL = $conn->prepare('SELECT * FROM newsletter WHERE IDFilial=? AND Email=? LIMIT 1');
            $SQL->execute(['1', $_POST['emailNewsLetter']]);

            if ($SQL->rowCount() == 0) {
                $SQL = $conn->prepare('INSERT INTO newsletter (IDFilial, Email) VALUES (?, ?)');
                $SQL->execute(['1', $_POST['emailNewsLetter']]);
            }
        } catch (PDOException $e) {
            echo "<script> alert('Erro ao registrar-se na nossa NewsLetter! Tente novamente mais tarde.'); </script>";
        }
    }
?>
<footer>
    <div id='novidades'>
        <section>
            <h1 class='txtBlack txtCenter txt500'>Receba nossas novidades</h1>
            <form method='post'>
                <input type="email" name='emailNewsLetter' placeholder='Seu e-mail' class='input ipBorder ipRounded ipGrey ipBig txtGotham txt600' required></br>
                <input type="submit" name='salvarNewsLetter' class='button btBlack btBig btRounded txtWhite txtFutura' style='font-size:17px;letter-spacing:2px' value='ENVIAR'>
            </form>
        </section>
    </div>
    <div id='instagram'>
        <section>
            <div class='row' style='width: 272px; float:right'></div>
            <h1 class='txtBlack txt500'>@sex_lingerie_multimarcas</h1>
            <div class='row' style='width: 117px;'></div>
        </section>
    </div>
    <div id='copy'>
        <section>
            <center>
            <img src="IMG/Logo/Logo.png" alt="Logo Footer">
            </br>
                <a href="#"><img src="IMG/Custom/whatsapp.png" alt="WhatsApp Footer"></a>
                <a href="#"><img src="IMG/Custom/facebook.png" alt="Facebook Footer"></a>
                <a href="#"><img src="IMG/Custom/instagram.png" alt="Instagram Footer"></a>
            </center>
            </br>
            <h3 class='txtBlack txtGotham txtCenter'>Copyright © 2020 Sex Lingerie</br>
                                                     By <a href="http://cupomautomacao.com/" target='_blank'>Cupom</a></h3>
        </section>
    </div>
</footer>