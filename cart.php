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

    $localStorage = $_GET["localStorageInput"];
    echo $localStorage;

    $dataReceived = json_decode($localStorage, true);
    var_dump($dataReceived);

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $idComanda = $_POST["orderId"];

        $comanda = $conn->prepare("INSERT INTO comanda (livrare_contact, livrare_adresa, livrare_telefon, livrare_email, total, transport)
                                         VALUES (:contact, :adresa, :telefon, :email, :total, :transport)");

        $comanda->bindParam(':contact', $contact);
        $comanda->bindParam(':adresa', $adresa);
        $comanda->bindParam(':telefon', $telefon);
        $comanda->bindParam(':email', $email);
        $comanda->bindParam(':total', $total);
        $comanda->bindParam(':transport', $transport);

        $contact = $dataReceived["facturare"]["numeCumparator"];
        $adresa = $dataReceived["livrare"]["adresa"]["strada"];
        $telefon = $dataReceived["facturare"]["phoneNumber"];
        $email = $dataReceived["facturare"]["email"];
        $total = 1500;
        $transport = 25;
        //$comanda->execute();

        $produse = $conn->prepare("SELECT nume_produs, cantitate_produs, total FROM produse WHERE id_comanda=$idComanda;");
        $comanda = $conn->prepare("SELECT * FROM comanda WHERE id=$idComanda");
        $produse->execute();
        $comanda->execute();

        $resultProduse = $produse->fetch(PDO::FETCH_ASSOC);
        $resultComanda = $comanda->fetch(PDO::FETCH_ASSOC);

//print_r($resultComanda);
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    $conn = null;

    include ("cart.html");
    include ("form.html");