<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Restauracja</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            padding: 20px;
        }

        h1, h2 {
            text-align: center;
        }

        section {
            background: white;
            padding: 20px;
            margin: 20px auto;
            max-width: 500px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        form {
            display: flex;
            flex-direction: column;
        }

        input, textarea, button {
            margin: 10px 0;
            padding: 10px;
            font-size: 1em;
        }

        button {
            background: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background: #218838;
        }
    </style>
</head>
<body>
    <h1>Restauracja</h1>

    <section>
        <h2>Rezerwacja stolika</h2>
        <form id="rezerwacja" method="POST">
            <input type="text" name="imie" placeholder="Imię i nazwisko" required>
            <input type="phone" name="tel" placeholder="Telefon" required>
            <input type="number" name="goscie" placeholder="Liczba osób" required>
            <input type="date" name="data" required>
            <input type="time" name="czas" required>
            <button type="submit">Zarezerwuj</button>
        </form>
        <div id="zarezerwuj"></div>
    </section>

    <section>
        <h2>Zamów jedzenie na adres</h2>
        <form id="zamówienie" method="POST">
            <input type="text" name="imie" placeholder="Imię i nazwisko" required>
            <input type="phone" name="telefon" placeholder="Telefon" required>
            <textarea name="address" placeholder="Adres dostawy" required></textarea>
            <textarea name="zamowienie" placeholder="Co chcesz zamówić?" required></textarea>
            <button type="submit">Zamów</button>
        </form>
        <div id="zamow"></div>
    </section>

    <script>
        document.getElementById("rezerwacja").addEventListener("submit", function (e){
            e.preventDefault();
            const formData = new FormData(this);
            formData.append("form_type", "rezerwacja");
            fetch("", {
                method: "POST",
                body: formData
            }).then(res => res.text())
            .then(data => {
                document.getElementById("zarezerwuj").textContent = data;
                this.reset();
            });
        });

        document.getElementById("zamówienie").addEventListener("submit", function (e){
            e.preventDefault();
            const formData = new FormData(this);
            formData.append("form_type", "zamowienie");
            fetch("", {
                method: "POST",
                body: formData
            }).then(res => res.text())
            .then(data => {
                document.getElementById("zamow").textContent = data;
                this.reset();
            });
        });
    </script>

    <?php
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $conn = new mysqli("127.0.0.1", "root", "", "restauracja");

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $form_type = $_POST['form_type'] ?? '';

        if ($form_type === "rezerwacja") {
            $name = $_POST['imie'] ?? '';
            $phone = $_POST['tel'] ?? '';
            $guests = (int)($_POST['goscie'] ?? 0);
            $date = $_POST['data'] ?? '';
            $time = $_POST['czas'] ?? '';

            $zapytanie = "INSERT INTO rezerwacje (imie, telefon, goscie, data, czas) 
                        VALUES ('$name', '$phone', $guests, '$date', '$time')";

            if ($conn->query($zapytanie) === TRUE) {
                echo "Rezerwacja została zapisana!";
            } else {
                echo "Błąd: " . $conn->error;
            }
        }

        if ($form_type === "zamowienie") {
            $imie = $_POST['imie'] ?? '';
            $telefon = $_POST['telefon'] ?? '';
            $adres = $_POST['address'] ?? '';
            $zamowienie = $_POST['zamowienie'] ?? '';

            $zapytanie = "INSERT INTO zamowienia (imie, telefon, adres, zamowienie) 
                        VALUES ('$imie', '$telefon', '$adres', '$zamowienie')";

            if ($conn->query($zapytanie) === TRUE) {
                echo "Zamówienie zostało złożone!";
            } else {
                echo "Błąd: " . $conn->error;
            }
        }

        $conn->close();
        exit();
    }
    ?>

</body>
</html>
