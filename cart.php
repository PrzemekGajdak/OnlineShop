<?php
session_start();
if(!isset($_SESSION['cart'])){
    $_SESSION['cart'] = array();
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

if(isset($_POST['submit'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $zip = $_POST['zip'];
    $cart = $_SESSION['cart'];
    $order = "Imię i nazwisko: $name\nE-mail: $email\nAdres: $address\nMiasto: $city\nKod pocztowy: $zip\n\nZamówione części:\n";
    foreach($cart as $item){
        $order .= "$item[name] - $item[price] zł\n";
    }
    $order .= "\nŁączna wartość zamówienia: $total_price zł";
    $file_name = "order_" . date("YmdHis") . ".txt";
    file_put_contents($file_name, $order);
    $_SESSION['cart'] = array();
    header("Location: cart.php?status=success");
    exit();
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Koszyk</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <header>
        <h1>Koszyk</h1>
    </header>
    <nav>
        <ul>
            <li><a href="index.php">Sklep</a></li>
            <li><a href="cart.php">Koszyk (<?php echo count($_SESSION['cart']); ?>)</a></li>
            <li><a href="cart.php?action=clear">Wyczyść koszyk</a></li>
        </ul>
    </nav>
    <div id="content">
        <?php
        if(isset($_GET['status'])){
            if($_GET['status'] == "success"){
                echo "<p>Zamówienie zostało złożone. Dziękujemy!</p>";
            }
        }
        else{
            if(count($_SESSION['cart']) > 0){
                echo "<h2>Twój koszyk</h2>";
                echo "<ul>";
                foreach($_SESSION['cart'] as $key => $item){
                    echo "<li>";
                    echo "<span class='part-name'>$item[name]</span>";
                    echo "<span class='part-price'>$item[price] zł</span>";
                    echo "<a class='remove-item' href='cart.php?remove=$key'>Usuń</a>";
                    echo "</li>";
                }
                echo "</ul>";
                echo "<p>Łączna wartość zamówienia: $total_price zł</p>";
                echo "<h2>Podsumowanie</h2>";
                echo "<form method='post' action='cart.php'>";
                echo "<label for='name'>Imię i nazwisko:</label>";
                echo "<input type='text' name='name' id='name'>";
                echo "<label for='email'>E-mail:</label>";
                echo "<input type='email' name='email' id='email'>";
                echo "<label for='address'>Adres:</label>";
                echo "<input type='text' name='address' id='address'>";
                echo "<label for='city'>Miasto:</label>";
                echo "<input type='text' name='city' id='city'>";
                echo "<label for='zip'>Kod pocztowy:</label>";
                echo "<input type='text' name='zip' id='zip'>";
                echo "<input type='submit' name='submit' value='Złóż zamówienie'>";
                echo "</form>";
            }
            else{
                echo "<p>Twój koszyk jest pusty</p>";
            }
        }
        ?>
    </div>
</body>
</html>
<?php
    if(isset($_POST['submit'])){
        $name = $_POST['name'];
        $email = $_POST['email'];
        $address = $_POST['address'];
        $city = $_POST['city'];
        $zip = $_POST['zip'];
        $order_items = $_SESSION['cart'];
        $total_price = calculateTotalPrice($_SESSION['cart']);

        $order_data = "Imię i nazwisko: $name\n";
        $order_data .= "E-mail: $email\n";
        $order_data .= "Adres: $address\n";
        $order_data .= "Miasto: $city\n";
        $order_data .= "Kod pocztowy: $zip\n\n";
        $order_data .= "Zamówione części:\n";
        foreach($order_items as $item){
            $order_data .= "$item[name]: $item[price] zł\n";
        }
        $order_data .= "\nŁączna wartość zamówienia: $total_price zł\n";
        $order_data .= "Data zamówienia: " . date("d-m-Y H:i:s") . "\n";
        $order_data .= "----------------------------------------------\n\n";

        $file = fopen("orders.txt", "a");
        fwrite($file, $order_data);
        fclose($file);

        unset($_SESSION['cart']);
        header("Location: cart.php?status=success");
        exit();
    }
?>
<?php
function calculateTotalPrice($cart){
    $total_price = 0;
    foreach($cart as $item){
        $total_price += $item['price'];
    }
    return $total_price;
}
?>
