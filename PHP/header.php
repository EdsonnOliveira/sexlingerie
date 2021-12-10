<?php 
    if (basename($_SERVER['SCRIPT_NAME']) <> 'index.php') {
?>
<header style='background-color: #292929;'>
<?php
    } else {
?>
<header>
<?php } ?>
    <div id='headerMobile'>
        <img src="IMG/Custom/menu.png" alt="" onclick='showHeader()'>
    </div>
    <div id='headerClick'>
        <nav>
            <ul>
                <li class='txtWhite' onclick='closeHeader()'>Fechar Menu</li>
                <li><a href="index.php">Início</a></li>
                <li><a href="login.php">Minha Conta</a></li>
                <!-- <li><a href="#">Lançamentos</a></li> -->
                <li><a href="moda-intima.php">Moda Íntima</a></li>
                <?php
                    $SQLMenu = $conn->prepare('SELECT * FROM produtos_categoria WHERE IDFilial=? AND Menu=?');
                    $SQLMenu->execute(['1', '1']);
                    $SQLMenu = $SQLMenu->fetchAll();
                    foreach ($SQLMenu as $value) {
                        echo "<li><a href='categoria.php?ID=" . $value['IDCategoria'] . "'>" . $value['Nome'] . "</a></li>";
                    }
                ?>
            </ul>
        </nav>
    </div>
    <a href="index.php"><img src="IMG/Logo/Logo.png" alt="Logo"></a>
    <nav>
        <ul>
            <li><a href="index.php">Início</a></li>
            <!-- <li><a href="#">Lançamentos</a></li> -->
            <li><a href="moda-intima.php">Moda Íntima</a></li>
            <?php
                $SQLMenu = $conn->prepare('SELECT * FROM produtos_categoria WHERE IDFilial=? AND Menu=?');
                $SQLMenu->execute(['1', '1']);
                $SQLMenu = $SQLMenu->fetchAll();
                foreach ($SQLMenu as $value) {
                    echo "<li><a href='categoria.php?ID=" . $value['IDCategoria'] . "'>" . $value['Nome'] . "</a></li>";
                }
            ?>
        </ul>
    </nav>
    <div id='sex'>
        <div>
            <?php 
                if (basename($_SERVER['SCRIPT_NAME']) == 'index.php'){
            ?>
                <!-- <a href="sexshop.php" class='button btWhite btNormal btRounded txtRed txtRoboto txt700 txtUpper'>Sex Shop</a> -->
            <?php
                }
            ?>
        </div>
    </div>
    <div id='loginHeader'>
        <div style='width:250px;'>
            <?php 
                $imgSearch = 'IMG/Custom/search.png';
                $imgLogin = 'IMG/Custom/login.png';
                $imgBag = 'IMG/Custom/bag.png';
                if (basename($_SERVER['SCRIPT_NAME']) <> 'index.php'){
                    $imgSearch = 'IMG/Custom/search2.png';
                    $imgLogin = 'IMG/Custom/login2.png';
                    $imgBag = 'IMG/Custom/bag2.png';
            ?>
                <!-- <a href="sexshop.php" class='button btWhite btNormal btRounded txtRed txtRoboto txt700 txtUpper'>Sex Shop</a> -->
            <?php
                }
            ?>
            <a href='procurar.php'><img src="<?php echo $imgSearch;?>" alt="Search"></a>
            <a href="login.php"><img src="<?php echo $imgLogin;?>" alt="Login"></a>
            <a href="bag.php"><img src="<?php echo $imgBag;?>" alt="Bag"></a>
        </div>
    </div>
    <div id='headerBag'>
        <a href="procurar.php" style='margin-right:5px'><img src="IMG/Custom/search2.png" alt="Bag"></a>
        <a href="bag.php"><img src="IMG/Custom/bag2.png" alt="Bag"></a>
    </div>
</header>