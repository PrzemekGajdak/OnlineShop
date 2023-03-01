<?php
session_start();
if(!isset($_SESSION['cart'])){
    $_SESSION['cart'] = array();
}
$parts = array(
    "Procesory" => array(
        array("name" => "Intel Core i5-11600K", "price" => 1359.00),
        array("name" => "AMD Ryzen 7 5800X", "price" => 1799.00)
    ),
    "Pamięci" => array(
        array("name" => "Crucial Ballistix RGB 16GB DDR4 3200MHz", "price" => 349.00),
        array("name" => "Kingston HyperX Fury 16GB DDR4 3200MHz", "price" => 299.00)
    ),
    "Płyty główne" => array(
        array("name" => "ASUS TUF GAMING B560M-PLUS WIFI", "price" => 729.00),
        array("name" => "Gigabyte AORUS B550 ELITE V2", "price" => 719.00)
    ),
    "Dyski" => array(
        array("name" => "Samsung 970 EVO Plus 1TB", "price" => 1399.00),
        array("name" => "Seagate BarraCuda 2TB", "price" => 359.00)
    ),
    "Obudowy" => array(
        array("name" => "Fractal Design Meshify C", "price" => 529.00),
        array("name" => "NZXT H510i", "price" => 599.00)
    )
);

if(isset($_POST['submit'])){
    $part_id = $_POST['part'];
    $part_name = $parts[$_POST['category']][$part_id]['name'];
    $part_price = $parts[$_POST['category']][$part_id]['price'];
    $cart_item = array("name" => $part_name, "price" => $part_price);
    array_push($_SESSION['cart'], $cart_item);
    header("Location: index.php");
    exit();
}

if(isset($_GET['action'])){
    if($_GET['action'] == "clear"){
        $_SESSION['cart'] = array();
    }
}

$total_price = 0;
foreach($_SESSION['cart'] as $item){
    $total_price += $item['price'];
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Sklep Komputerowy</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <header>
        <h1>Witamy w sklepie komputerowym. Stwórz swój własny zestaw!</h1>
    </header>
    <nav>
        <ul>
            <li><a href="index.php?category=Procesory">Procesory</a></li>
            <li><a href="index.php?category=Pamięci">Pamięci</a></li>
            <li><a href="index.php?category=Płyty główne">Płyty główne</a></li>
            <li><a href="index.php?category=Dyski">Dyski</a></li>
            <li><a href="index.php?category=Obudowy">Obudowy</a></li>
            <li><a href="cart.php">Koszyk (<?php echo count($_SESSION['cart']); ?>)</a></li>
            </ul>
    </nav>
    <div id="content">
        <?php
        if(isset($_GET['category'])){
            $category = $_GET['category'];
            if(array_key_exists($category, $parts)){
                echo "<h2>$category</h2>";
                echo "<ul>";
                foreach($parts[$category] as $key => $part){
                    echo "<li>";
                    echo "<form method='post' action='index.php'>";
                    echo "<input type='hidden' name='category' value='$category'>";
                    echo "<input type='hidden' name='part' value='$key'>";
                    echo "<span class='part-name'>$part[name]</span>";
                    echo "<span class='part-price'>$part[price] zł</span>";
                    echo "<input type='submit' name='submit' value='Dodaj do koszyka'>";
                    echo "</form>";
                    echo "</li>";
                }
                echo "</ul>";
            }
            else{
                echo "<p>Nieprawidłowa kategoria</p>";
            }
        }
        else{
            echo "<p>Wybierz kategorię z menu powyżej</p>";
        }
        ?>
    </div>
    <footer>
        <p>Łączna wartość zamówienia: <?php echo $total_price; ?> zł</p>
    </footer>
</body>
</html>
