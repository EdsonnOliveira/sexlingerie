<?php
    include("PHP/conn.php");

    $qntd  = 21;
    $page  = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;
    $start = ($qntd * $page) - $qntd;
    
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <title>Moda Íntima | Sex Lingerie</title> 
        <?php include("PHP/meta.php"); ?>
    </head>
    <body>
        <section class='preview small red'>
            <?php include("PHP/header.php"); ?>
            <div class='centralize'>
                <span>
                    <h1 class='txtBlack txt500'>Moda Íntima</h1>
                </span>
            </div>
        </section>
        <main>
            <div class='container'>
                <div class='listingProduct'>
                    <?php
                        $SQL = $conn->prepare("SELECT * FROM produtos ORDER BY IDProduto ASC LIMIT $start, $qntd");
                        $SQL->execute();
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
                <div class="pagination">
                <?php
                    $SQL = $conn->prepare("SELECT * FROM produtos");
                    $SQL->execute();

                    $total = $SQL->rowCount();
                    $total = ceil($total / $qntd);
                    $back = (($page - 1) == 0) ? 1 : $page - 1;
                    $skip = (($page+1) >= $total) ? $total : $page+1;
                    $qntdPages = 5;

                    echo "<a href='?page=$back' class='button btBlack btNormal btRounded txtWhite txtFutura'>ANTERIOR</a>";

                    for($i = $page-$qntdPages; $i <= $page-1; $i++){
                        if($i > 0)
                            echo "<a href='?page=$i' class='button btSmall btRounded btRed txtWhite txtFutura'>$i</a>";
                    }

                    echo "<a href='?page=$page' class='button btSmall btRounded btBlack txtWhite txtFutura'>$page</a>";

                    for($i = $page+1; $i < $page+$qntdPages; $i++){
                        if($i <= $total)
                            echo "<a href='?page=$i' class='button btSmall btRounded btRed txtWhite txtFutura'>$i</a>";
                   }
                    
                    echo "<a href='?page=$skip' class='button btRed btNormal btRounded txtWhite txtFutura'>PRÓXIMO</a>";
                ?>
                </div>
            </div>
        </main>
        <?php include('PHP/footer.php'); ?>
    </body>
</html>