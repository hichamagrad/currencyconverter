<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "convertisseur_devises";

// Create a new connection
$conn = new mysqli($servername, $username, $password, $dbname, 3307);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve the exchange rates from the database
$sql = "SELECT * FROM currency";
$result = $conn->query($sql);

// Initialize variables for exchange rates
$usd_to_eur = 0;
$usd_to_gbp = 0;
$usd_to_mad = 0;

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        if ($row["name"] === "EUR") {
            $usd_to_eur = $row["taux"];
        } elseif ($row["name"] === "GBP") {
            $usd_to_gbp = $row["taux"];
        } elseif ($row["name"] === "MAD") {
            $usd_to_mad = $row["taux"];
        }
    }
}

// Close the database connection
$conn->close();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $amount = $_POST["amount"];
    $from_currency = $_POST["from_currency"];
    $to_currency = $_POST["to_currency"];

    // Convert the amount to the desired currency
    $converted_amount = 0;

    if ($from_currency === "USD") {
        if ($to_currency === "EUR") {
            $converted_amount = $amount * $usd_to_eur;
        } elseif ($to_currency === "GBP") {
            $converted_amount = $amount * $usd_to_gbp;
        } elseif ($to_currency === "MAD") {
            $converted_amount = $amount * $usd_to_mad;
        }
    } elseif ($from_currency === "EUR") {
        // Conversion rates for EUR to other currencies (not provided in this example)
        // Add the conversion logic here
    } elseif ($from_currency === "GBP") {
        // Conversion rates for GBP to other currencies (not provided in this example)
        // Add the conversion logic here
    } elseif ($from_currency === "MAD") {
        if ($to_currency === "USD") {
            $converted_amount = $amount / $usd_to_mad;
        } elseif ($to_currency === "EUR") {
            // Conversion rate for MAD to EUR (not provided in this example)
            // Add the conversion logic here
        } elseif ($to_currency === "GBP") {
            // Conversion rate for MAD to GBP (not provided in this example)
            // Add the conversion logic here
        }
    }

    // Return the converted amount as JSON
    echo json_encode(array("converted_amount" => $converted_amount, "to_currency" => $to_currency));
}
?>
