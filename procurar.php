<?php
    include("PHP/conn.php");
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <title>Procurar | Sex Lingerie</title> 
        <?php include("PHP/meta.php"); ?>
        <link rel='stylesheet' type='text/css' href='CSS/cadastro.css'>
    </head>
    <body>
        <section class='preview small red'>
            <?php include("PHP/header.php"); ?>
            <div class='centralize'>
                <form method='post'>
                    <input type="text" name='procurar' class='input ipBorder ipRounded ipBig txtCenter' placeholder='Procurar produtos...' autofocus>
                </form>
            </div>
        </section>
        <main>
            <div class='container'>
                <div class='listingProduct'>
                    <?php
                    if (isset($_POST['procurar'])) {
                        $SQL = $conn->prepare("SELECT * FROM produtos WHERE IDFilial=? AND Situacao=? AND Nome LIKE '%". $_POST['procurar'] . "%' LIMIT 60");
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
                    }
                    ?>
                </div>
            </div>
        </main>
        <?php include('PHP/footer.php'); ?>
    </body>
</html>