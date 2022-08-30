Det finns fyra filer. (db.json, index.php, state.log,with_test_cases.php)
Från början tänkte jag fel, tänkte skapa en UI för API tjänsten med sessions variabler, en kundvagn att kunden kan lägga till sina varor i kundvagn.
Men sedan märkte jag att det behövs inte. Testet som jag uppfattat nu är att skapa en API tjänst för att fylla på json filen med antal ett antal varor, det beroende på varan och antal varor som kunderna handlar.
index filen, den innehåller funktionerna, metoderna som används för API-tjänsten.
När det gäller db.json, jag tänker en mysql data tabell som innehåller olik artiklar och antalet tillgängliga produkter, har försökt att inte ändra mycket i filen.
Filen state.log, i den lagras data beroende på den API request som skickar.
Filen with_test_cases.php, den finns för att kunna testa! Det går att testa på följande sätt. URL: with_test_cases.php?test Det är viktigt att titta på filen state.log, för att success eller inte kommer det att lagras i state.log filen
index.php?route=data
index.php?route=purchase
Handla med metoden POST
[[2,2]]
RestMan is good for testing...
with_test_cases.php?test
