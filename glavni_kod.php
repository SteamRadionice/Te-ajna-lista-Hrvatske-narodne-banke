<!DOCTYPE html>
<html>

    <head>
        <title>Tečajna lista</title>
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>

    <body>
        <h1>Tečajna lista</h1>

        <h4>Svi tečajevi su iskazani za 1 EUR</h4>

        <?php
        // ovaj dio koda provjerava postoji li varijabla option u GET parametrima (tj. je li korisnik odabrao neku opciju u formi)
        // ako je korisnik odabrao opciju "date" u formi, tada će $option dobiti vrijednost "date"
        // ako je odabrao opciju "timeframe", tada će $option dobiti vrijednost "timeframe"
        if (isset($_GET['option'])) {
            $option = $_GET['option'];

            // odabir tečajne liste na određeni dan
            // provjerava je li varijabla $option jednaka stringu "date", te je li istovremeno postavljena varijabla $_GET['date']
            // odabrani datum iz kalendara forme se dodjeljuje varijabli $selectedDate
            // URL adresa koristi se za dohvaćanje podataka o tečajnoj listi za odabrani datum putem API zahtjeva
            if ($option === "date" && isset($_GET['date'])) {
                $selectedDate = $_GET['date'];
                $url = "https://api.hnb.hr/tecajn-eur/v3?datum-primjene={$selectedDate}";

                // API zahtjev
                $response = file_get_contents($url);

                // da li je JSON odgovor uspješno primljen
                // funkcija json_decode služi da bi se dekodirao JSON odgovor
                if ($response !== false) {
                    $data = json_decode($response, true);

                    // ako je $data različita od null, to znači da su podaci uspješno dekodirani iz JSON formata
                    // u polju "data" se pohranjuju dekodirani podaci ($data) u JSON formatu putem json_encode funkcije
                    if ($data !== null) {
                        echo '<div class="export-button">';
                        echo '<form method="POST">';
                        echo '<input type="hidden" name="data" value="' . htmlspecialchars(json_encode($data)) . '">';
                        echo '<button type="submit">Eksportiraj tablicu u XML datoteku</button>';
                        echo '</form>';
                        echo '</div>';

                        echo '<table>';
                        echo '<tr><th>Datum</th><th>Valuta</th><th>Tecaj</th></tr>';

                        // petlja foreach služi za prolazak kroz svaki element u $data polju
                        // za svaki element se generira HTML redak tablice te se ispisuju vrijednosti
                        foreach ($data as $item) {
                            echo '<tr>';
                            echo '<td>' . $item['datum_primjene'] . '</td>';
                            echo '<td>' . $item['valuta'] . '</td>';
                            echo '<td>' . $item['kupovni_tecaj'] . '</td>';
                            echo '</tr>';
                        }

                        echo '</table>';

                        // nakon ispisivanja tablice, provjerava se je li polje "data" postavljeno u POST zahtjevu
                        // ako jest, to znači da je korisnik kliknuo na gumb za eksportiranje tablice
                        // tada se generira XML datoteka koristeći podatke iz $data polja
                        // XML datoteka se sprema na serveru
                        if (isset($_POST['data'])) {
                            $xml = '<?xml version="1.0" encoding="UTF-8"?>';
                            $xml .= '<exchange_rates>';

                            foreach ($data as $item) {
                                $xml .= '<rate>';
                                $xml .= '<date>' . htmlspecialchars($item['datum_primjene']) . '</date>';
                                $xml .= '<currency>' . htmlspecialchars($item['valuta']) . '</currency>';
                                $xml .= '<exchange_rate>' . htmlspecialchars($item['kupovni_tecaj']) . '</exchange_rate>';
                                $xml .= '</rate>';
                            }

                            $xml .= '</exchange_rates>';

                            // Export u XML datoteku
                            $exchangeDate = $data[0]['datum_primjene'];
                            $formattedDate = date('Ymd', strtotime($exchangeDate));
                            $filename = 'tečajna_lista_' . $formattedDate . '.xml';
                            file_put_contents($filename, $xml);

                            echo '<p>XML datoteka izvezena i spremljena kao: <a href="' . $filename . '">' . $filename . '</a></p>';

                            // Vrati se na početnu formu
                            echo '<script>window.location.href = "index.php";</script>';
                            exit;
                        }
                    } else {
                        echo "Greška prilikom dekodiranja API odgovora.";
                    }
                } else {
                    echo "Greška prilikom dohvaćanja API podataka.";
                }
            // odabir tečajne liste na određeni vremenski raspon
            // ovaj dio koda se izvršava kada korisnik odabere opciju "timeframe" i dostavi vrijednost parametra "timeframe" putem GET zahtjeva  
            // varijable $startDate i $finishDate se koriste za određivanje datuma početka i datuma kraja raspona 
            // $finishDate sadrži trenutni datum, dok se $startDate određuje oduzimanjem broja dana (iz $timeframe) od trenutnog datuma
            } elseif ($option === "timeframe" && isset($_GET['timeframe'])) {
                $timeframe = $_GET['timeframe'];
                $finishDate = date('Y-m-d');
                $startDate = date('Y-m-d', strtotime("-{$timeframe} days"));
                $url = "https://api.hnb.hr/tecajn-eur/v3?datum-primjene-od={$startDate}&datum-primjene-do={$finishDate}";

                // API zahtjev
                $response = file_get_contents($url);

                // da li je JSON odgovor uspješno primljen
                // funkcija json_decode služi da bi se dekodirao JSON odgovor
                if ($response !== false) {
                    $data = json_decode($response, true);

                    // ako je $data različita od null, to znači da su podaci uspješno dekodirani iz JSON formata
                    // u polju "data" se pohranjuju dekodirani podaci ($data) u JSON formatu putem json_encode funkcije
                    if ($data !== null) {
                        echo '<div class="export-button">';
                        echo '<form method="POST">';
                        echo '<input type="hidden" name="data" value="' . htmlspecialchars(json_encode($data)) . '">';
                        echo '<button type="submit">Eksportiraj tablicu u XML datoteku</button>';
                        echo '</form>';
                        echo '</div>';

                        echo '<table>';
                        echo '<tr><th>Datum</th><th>Valuta</th><th>Tecaj</th></tr>';

                        // petlja foreach služi za prolazak kroz svaki element u $data polju
                        // za svaki element se generira HTML redak tablice te se ispisuju vrijednosti
                        foreach ($data as $item) {
                            echo '<tr>';
                            echo '<td>' . $item['datum_primjene'] . '</td>';
                            echo '<td>' . $item['valuta'] . '</td>';
                            echo '<td>' . $item['kupovni_tecaj'] . '</td>';
                            echo '</tr>';
                        }

                        echo '</table>';

                        // nakon ispisivanja tablice, provjerava se je li polje "data" postavljeno u POST zahtjevu
                        // ako jest, to znači da je korisnik kliknuo na gumb za eksportiranje tablice
                        // tada se generira XML datoteka koristeći podatke iz $data polja
                        // XML datoteka se sprema na serveru
                        if (isset($_POST['data'])) {
                            $xml = '<?xml version="1.0" encoding="UTF-8"?>';
                            $xml .= '<exchange_rates>';

                            foreach ($data as $item) {
                                $xml .= '<rate>';
                                $xml .= '<date>' . htmlspecialchars($item['datum_primjene']) . '</date>';
                                $xml .= '<currency>' . htmlspecialchars($item['valuta']) . '</currency>';
                                $xml .= '<exchange_rate>' . htmlspecialchars($item['kupovni_tecaj']) . '</exchange_rate>';
                                $xml .= '</rate>';
                            }

                            $xml .= '</exchange_rates>';

                            // Export u XML datoteku
                            $formattedStartDate = date('Ymd', strtotime($startDate));
                            $formattedFinishDate = date('Ymd', strtotime($finishDate));
                            $filename = 'tečajna_lista_' . $formattedStartDate . '-' . $formattedFinishDate . '.xml';
                            file_put_contents($filename, $xml);

                            echo '<p>XML datoteka izvezena i spremljena kao: <a href="' . $filename . '">' . $filename . '</a></p>';

                            // Vrati se na početnu formu
                            echo '<script>window.location.href = "index.php";</script>';
                            exit;
                        }
                    } else {
                        echo "Greška prilikom dekodiranja API odgovora.";
                    }
                } else {
                    echo "Greška prilikom dohvaćanja API podataka.";
                }
            }
        }
        ?>
    </body>

</html>