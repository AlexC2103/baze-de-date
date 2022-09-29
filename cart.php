<?php

    $data = [
        "livrare_contact" => "Alex",
        "livrare_adresa" => "A i cuza",
        "livrare_telefon" => "997",
        "livrare_email" => "adf@ggg.com",
        "total" => 750,
        "transport" => 25
    ];

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "magazinonline";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $idComanda = $_GET["orderId"];
        echo $idComanda;



        // prepare sql and bind parameters
        $produse = $conn->prepare("SELECT nume_produs, cantitate_produs, total FROM produse WHERE id_comanda=$idComanda;");
        $comanda = $conn->prepare("SELECT * FROM comanda WHERE id=$idComanda;");

        $produse->execute();
        $comanda->execute();

        $resultProduse = $produse->fetch(PDO::FETCH_ASSOC);
        $resultComanda = $comanda->fetch(PDO::FETCH_ASSOC);

    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    $conn = null;

    include ("cart.html");
    include ("form.html");