<?php 
    include("PHP/conn.php");
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <title>Sex Lingerie</title>
        <?php include("PHP/meta.php"); ?>
        <link rel='stylesheet' type='text/css' href='CSS/index.css'>
    </head>
    <body>
        <section class='preview big'>
        <?php include("PHP/header.php"); ?>
            <div id='header'>
                <div id='social'>
                    <a href="#"><img src="IMG/Custom/whatsapp.png" alt="Whatsapp"></a>
                    <a href="#"><img src="IMG/Custom/facebook.png" alt="Facebook"></a>
                    <a href="#"><img src="IMG/Custom/instagram.png" alt="Instagram"></a>
                </div>
                <div class='text medium left'>
                    <span style='margin-left:30px'>
                        <h1 class='txtBlack txt500'>Você merece</br>peças incríveis!</h1>
                        <div id='rowWhite'></div>
                        <!-- <h4 class='txtWhite txt500 txtRight'>lingeries com 30%</br>de desconto!</h4> -->
                    </span>
                </div>
            </div>
            <div id='left'>
                <h4 class='txtWhite txtFutura'>Roupas Fitness</h4>
                <span>
                    <h1 class='txtWhite txtFutura' style='margin-left:-150px;'>ROUPAS</h1>
                    <h1 class='txtWhite txtFutura' style='margin-left: 25px'>FITNESS</h1>
                </span>
                <a href="#" class='button btRounded btWhite btNormal txtBlack txtFutura'>Saiba Mais</a>
            </div>
            <div id='bottom'>
                <span class='txtCenter'>
                    <h2 class='txtRed txtVegan txt500'>O Queridinho</h2>
                    <h2 class='txtRed txtVegan txt500' style='margin-top:-30px'>da Loja</h2>
                    </br></br>
                    <a href="#" class='button btBorder btRounded btNormal txtBlack txt17'>Compre Agora</a>
                </span>
            </div>
            <div id='right'>
                <span>
                    <h5 class='txtWhite txt500 txtCenter' style='padding-top:3px;padding-bottom:3px; background-color: #292929;font-size:22px;'>MODA</h5></br>
                    <h2 class='txtGreen txtUpper txtPalm txt500'>Praia</h2></br>
                    <center><a href="#" class='button btBorder btRounded btNormal txtBlack txt17'>Ver Ofertas</a></center>
                </span>
            </div>
        </section>
        <main>
            <div class='container'>
                <h1 class='txtBlack txt500 title'>Destaques de hoje <a href="#" class='button btBorder btRounded btNormal txtBlack txt500'>Ver Mais</a></h1>
                </br>
                <div class='listingProduct'>
                    <?php
                        $SQL = $conn->prepare("SELECT * FROM produtos WHERE IDFilial=? AND Situacao=? ORDER BY RAND() LIMIT 9");
                        $SQL->execute(['1', '1']);
                        $SQL = $SQL->fetchAll();
                        $I = 1;
                        foreach ($SQL as $value) {
                            if ($I == 1)
                                echo "<div class='row'>";

                            echo "<div class='item'>";
                                echo "<a href='produto.php?ID=" . $value['IDProduto'] . "'>";
                                    echo "<img src='" . IMAGE . $value['Imagem'] . "'>";
                                    echo "<h3 class='txtBlack txtUpper txtCenter txtGotham'>" . $value['Nome'] . "</h3>";
                                    echo "<h2 class='txtBlack txtGotham2 txt700 txtCenter'>R$ " . str_replace('.', ',', $value['ValorVenda']) . ' ';
                                        echo "<b class='txtGotham'>" . $value['Parcelas'] . 'x R$' . str_replace('.', ',', number_format($value['ValorVenda'] / $value['Parcelas'], 2)) . "</b>";
                                    echo "</h2>";
                                echo "</a>";
                            echo "</div>";

                            if ($I ==  3) {
                                $I = 0;
                                echo "</div>";
                            }

                            $I++;
                        }
                        if ($I <> 1) 
                            echo "</div>";
                    ?>
                </div>
            </div>
            <section class='banner bigger black'>
                <div id='img'>
                    <img src="IMG/Galeria/banner1.png" alt="banner1">
                    <img src="IMG/Galeria/banner2.png" alt="banner2">
                </div>
                <div id='txt'>
                    <span id='spnTitle'>
                        <div id='row'></div>
                        <h3 class='txtWhite txtCenter txt400'>Acessórios de</h3>
                        <h1 class='txtWhite txtCenter txt400'>Fetiche</h1>
                    </span>
                    </br></br>
                    <span id='spnDescription' class='txtCenter'>
                        <h3 class='txtWhite txtGotham txt100'>É tempo de reencontrarmos</br>
                            nossas fantasias... Presenteie-se com peças da </br>
                            coleção Fetiche, que prometem apimentar</br>
                            qualquer relação!</h3>
                        </br></br></br>
                        <a href="" class='button btBorder btBorderWhite btRounded btBig txtWhite' style='font-size:19px'>Ver Mais</a>
                    </span>
                </div>
            </section>
        </main>
        <?php include('PHP/footer.php'); ?>
    </body>
</html>