<!DOCTYPE html>
<html>

    <head>
        <title>Tečajna lista Hrvatske narodne banke</title>
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>

    <body>
        <h1>Tečajna lista Hrvatske narodne banke</h1>

        <!-- 
        * GET metoda HTTP zahtjeva koja se koristi prilikom slanja podataka iz forme 
        * podaci će se poslati u "glavni_kod.php" datoteku koja će obraditi podatke
        * rezultat će biti otvoren u novoj kartici preglednika
        -->
        <form method="GET" action="glavni_kod.php" target="_blank">
            
            <label>
                <input type="radio" name="option" value="date" checked>
                Odaberi datum:
            </label>
            <input type="date" id="date" name="date">
            <br>

            <br>
            <h4>ili</h4>
            <br>

            <label>
                <input type="radio" name="option" value="timeframe">
                Odaberi vremensko razdoblje:
            </label>
            <select id="timeframe" name="timeframe">
                <option value="7">posljednih 7 dana</option>
                <option value="30">posljednih 30 dana</option>
            </select>
            <br>

            <button type="submit">Dohvati podatke</button>

        </form>
    </body>

</html>