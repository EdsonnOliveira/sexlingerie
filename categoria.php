<?php
    include("PHP/conn.php");

    $SQLCategoria = $conn->prepare('SELECT * FROM produtos_categoria WHERE IDCategoria=? LIMIT 1');
    $SQLCategoria->execute([$_GET['ID']]);
    $SQLCategoria = $SQLCategoria->fetch();
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <title><?php echo $SQLCategoria['Nome']; ?> | Sex Lingerie</title> 
        <?php include("PHP/meta.php"); ?>
    </head>
    <body>
        <section class='preview small red'>
            <?php include("PHP/header.php"); ?>
            <div class='centralize'>
                <span>
                    <h1 class='txtBlack txt500'><?php echo $SQLCategoria['Nome']?></h1>
                </span>
            </div>
        </section>
        <main>
            <div class='container'>
                <div class='listingProduct'>
                    <?php
                        $SQL = $conn->prepare("SELECT * FROM produtos WHERE IDCategoria=?");
                        $SQL->execute([$_GET['ID']]);
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
        </main>
        <?php include('PHP/footer.php'); ?>
    </body>
</html>