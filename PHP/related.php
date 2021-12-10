</br>
<h1 class='txtBlack txt500 title'>Relacionados</h1>
</br>
<div class='listingProduct'>
    <?php
    $SQLRelated = $conn->prepare("SELECT * FROM produtos WHERE IDCategoria=? AND Situacao=? AND IDProduto<>? ORDER BY RAND() LIMIT 6");
    $SQLRelated->execute([$SQL['IDCategoria'], '1', $SQL['IDProduto']]);
    $SQLRelated = $SQLRelated->fetchAll();
    $I = 1;
    foreach ($SQLRelated as $value) {
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