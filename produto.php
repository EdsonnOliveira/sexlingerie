<?php
    include("PHP/conn.php");
    if (!isset($_GET['ID']))
        header("location:index.php");

    $SQL = $conn->prepare("SELECT * FROM produtos WHERE IDProduto=? LIMIT 1");
    $SQL->execute([$_GET['ID']]);
    $SQL = $SQL->fetch();

    if (isset($_POST['adicionar'])) {
        $SQLColor = $conn->prepare("SELECT * FROM produtos_cor_tamanho WHERE IDProd=? LIMIT 1");
        $SQLColor->execute([$_POST['color']]);
        $SQLColor = $SQLColor->fetch();
        $IDCor = $SQLColor['IDCor'];

        $SQLSize = $conn->prepare("SELECT * FROM produtos_cor_tamanho WHERE IDProd=? LIMIT 1");
        $SQLSize->execute([$_POST['size']]);
        $SQLSize = $SQLSize->fetch();
        $IDTamanho = $SQLSize['IDTamanho'];

        header("location: bag.php?ID=" . $_GET['ID'] . "&&color=" . $IDCor . '&&size=' . $IDTamanho);
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <title><?php echo $SQL['Nome'] ?> | Sex Lingerie</title>
        <?php include("PHP/meta.php"); ?>
    </head>
    <body>
        <section class='preview none'>
            <?php include("PHP/header.php"); ?>
        </section>
        <main>
            <div class='container'>
                <form method='post'>
                    <div class='productDetails'>
                        <div class='panelImage'>
                            <?php
                                if ($SQL['Imagem2'] != '') {
                            ?>
                            <div class='carousel'>
                                <div class='product'>
                                    <img src="<?php echo IMAGE . $SQL['Imagem2']; ?>" alt="">
                                </div>
                                <div class='product'>
                                    <img src="<?php echo IMAGE . $SQL['Imagem3']; ?>" alt="">
                                </div>
                                <div class='product'>
                                    <img src="<?php echo IMAGE . $SQL['Imagem4']; ?>" alt="">
                                </div>
                            </div>
                                <?php } ?>
                            <div class='imageMain'>
                                <img src="<?php echo IMAGE . $SQL['Imagem']; ?>" alt="">
                            </div>
                        </div>
                        <div class='panelInfo'>
                            <h1 class='txtBlack txtGotham txt600 txtUpper'><?php echo $SQL['Nome']; ?></h1>
                            <h3 class='txtGrey txtGotham txt100 txtUpper'>REF: <?php echo $SQL['Codigo']; ?></h3>
                            <div class='option'>
                                <h4 class='txtBlack txtGotham3 txt100 txtUpper'>Cor: <b class='txtBlack txtGotham txt100 txtUpper' id='nameColor'>
                                    <?php
                                        $SQLStock = $conn->prepare('SELECT * FROM produtos_cor_tamanho WHERE IDProduto=? AND Estoque>0 ORDER BY IDCor LIMIT 1');
                                        $SQLStock->execute([$_GET['ID']]);
                                        $SQLStock = $SQLStock->fetch();

                                        $SQLColor = $conn->prepare('SELECT * FROM produtos_cor WHERE IDCor=? LIMIT 1');
                                        $SQLColor->execute([$SQLStock['IDCor']]);
                                        $SQLColor = $SQLColor->fetch();

                                        echo $SQLColor['Nome'];
                                    ?>
                                </b></h4>
                                <input type="hidden" name='color' id='ipColor' value='<?php echo $SQLStock['IDProd']; ?>'>
                                <div class='options'>
                                    <?php
                                        $SQLStock = $conn->prepare('SELECT * FROM produtos_cor_tamanho WHERE IDProduto=? AND Estoque>0 GROUP BY IDCor ORDER BY IDCor');
                                        $SQLStock->execute([$_GET['ID']]);
                                        $SQLStock = $SQLStock->fetchAll();
                                        $I = 1;
                                        foreach ($SQLStock as $value) {
                                            $SQLColor = $conn->prepare('SELECT * FROM produtos_cor WHERE IDCor=? LIMIT 1');
                                            $SQLColor->execute([$value['IDCor']]);
                                            $SQLColor = $SQLColor->fetch();

                                            if ($I == 1) 
                                                echo "<div alt='" . $SQLColor['Nome'] . "' class='select selected' id='colorSelect" . $value['IDProd'] . "' onclick=" . '"colorSelect(' . "'colorSelect" . $value['IDProd'] . "')" . '"' . ">";
                                            else
                                                echo "<div alt='" . $SQLColor['Nome'] . "' class='select' id='colorSelect" . $value['IDProd'] . "' onclick=" . '"colorSelect(' . "'colorSelect" . $value['IDProd'] . "')" . '"' . ">";
                                            
                                                echo "<div style='background-color:" . $SQLColor['Cor'] . "'></div>";
                                            echo "</div>";
                                            $I = 0;
                                        }
                                    ?>
                                </div>
                            </div>
                            <div class='option'>
                                <h4 class='txtBlack txtGotham3 txt100 txtUpper'>Tamanho: <b class='txtBlack txtGotham txt100 txtUpper' id='nameSize'></b></h4>
                                <input type="hidden" name='size' id='ipSize' value=''>
                                <div class='options'>
                                    <?php
                                        $SQLStock = $conn->prepare('SELECT * FROM produtos_cor_tamanho WHERE IDProduto=? AND Estoque>0 ORDER BY IDCor');
                                        $SQLStock->execute([$_GET['ID']]);
                                        $SQLStock = $SQLStock->fetchAll();
                                        $I = 1;

                                        foreach ($SQLStock as $value) {
                                            if ($I == 1) {
                                                echo "<div id='opt" . $value['IDProd'] . "' class='sizes visible'>";
                                                $IDCor2 = $value['IDCor'];
                                            }

                                            $SQLSize = $conn->prepare('SELECT * FROM produtos_tamanho WHERE IDTamanho=? LIMIT 1');
                                            $SQLSize->execute([$value['IDTamanho']]);
                                            $SQLSize = $SQLSize->fetch();

                                            $IDCor = $value['IDCor'];

                                            if ($IDCor <> $IDCor2) {
                                                echo "</div>";
                                                if ($I == 1)
                                                    echo "<div id='opt" . $value['IDProd'] . "' class='sizes visible'>";
                                                else
                                                    echo "<div id='opt" . $value['IDProd'] . "' class='sizes'>";
                                            }

                                                echo "<div alt='" . $SQLSize['Nome'] . "' class='select grey' id='sizeSelect" . $value['IDProd'] . "' onclick=" . '"sizeSelect(' . "'sizeSelect" . $value['IDProd'] . "')" . '"' . ">";
                                                    echo "<h5 class='txtBlack txtGotham'>" . $SQLSize['Tamanho'] . "</h5>";
                                                echo "</div>";

                                            $I = 0;
                                            $IDCor2 = $value['IDCor'];
                                        }
                                        if ($IDCor == $IDCor2)
                                            echo "</div>";
                                    ?>
                                </div>
                            </div>
                            <div class='price'>
                                <h1 class='txtBlack txtGotham3 txtBlack txtCenter txt100'>R$ <?php echo str_replace('.', ',', $SQL['ValorVenda']); ?></h1>
                                <h2 class='txtBlack txtGotham txtBlack txtCenter txt100'>ou <?php echo $SQL['Parcelas'] . 'x de R$ ' . str_replace('.', ',', number_format($SQL['ValorVenda'] / $SQL['Parcelas'], 2)); ?></h2>
                                <center><input type='submit' name='adicionar' id='btAdicionar' class='button btRed btBigger btRounded txtWhite txtGotham2 txt700' style='font-size:15px' value='ADICIONAR A SACOLA' disabled></center>
                            </div>
                            <?php
                                if ($SQL['Descricao'] != '') {
                            ?>
                            <div class='details'>
                                <h4 class='txtBlack txtGotham3 txt100 txtUpper'>Detalhes:</h4>
                                <textarea class='txtBlack txt600 txtGotham txtUpper' disabled><?php echo $SQL['Descricao']; ?></textarea>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </form>
                <?php include('PHP/related.php'); ?>
            </div>
        </main>
        <?php include('PHP/footer.php'); ?>
    </body>
</html>