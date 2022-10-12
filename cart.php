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

    $dataReceived = json_decode($localStorage, true);

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $idToFind = $_POST["orderId"];
        echo $idToFind;
        $comanda = $conn->prepare("INSERT INTO comanda (livrare_contact, livrare_adresa, livrare_telefon, livrare_email, total, transport)
                                         VALUES (:contact, :adresa, :telefon, :email, :total, :transport)");

        $comanda->bindParam(':contact', $contact);
        $comanda->bindParam(':adresa', $adresa);
        $comanda->bindParam(':telefon', $telefon);
        $comanda->bindParam(':email', $email);
        $comanda->bindParam(':total', $total);
        $comanda->bindParam(':transport', $transport);

        $contact = $dataReceived["facturare"]["numeCumparator"];
        $adresa = $dataReceived["livrare"]["adresa"]["strada"]; $adresa .= ", ";
        $adresa .= $dataReceived["livrare"]["adresa"]["oras"]; $adresa .= ", ";
        $adresa .= $dataReceived["livrare"]["adresa"]["judet"];
        $telefon = $dataReceived["facturare"]["phoneNumber"];
        $email = $dataReceived["facturare"]["email"];
        $transport = 25;
        $total = $_GET["totalPriceInput"] + $transport;

        $comanda->execute();

        //Caz ipotetic: ce se întampla în cazul in care 2 oameni dau comanda in acelasi timp?

        $orderId = $conn->lastInsertId();

        foreach ($dataReceived["productList"]["value"] as $k) {
            $produse = $conn->prepare("INSERT INTO produse (nume_produs, cantitate_produs, id_comanda)
                                             VALUES (:numeProdus, :cantitateProdus, :idComanda)");

            $produse->bindParam(':numeProdus', $k[0]);
            $produse->bindParam(':cantitateProdus', $k[1]);
            $produse->bindParam(':idComanda', $orderId);
            $produse->execute();
        }

        $produse = $conn->prepare("SELECT nume_produs, cantitate_produs, total FROM produse WHERE id_comanda=$idToFind;");
        $comanda = $conn->prepare("SELECT * FROM comanda WHERE id=$idToFind");
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