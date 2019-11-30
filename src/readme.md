# Utvecklartest - PHP - Black Box

Klassen `BlackBox` har en metod som heter `url()`. Målet med detta test är att; dokumentera, refaktorera och testa den metoden.

## Dokumentera

Skriv **PHPDoc** för metoden `url()`. Vad gör funktionen? Vilka är parametrarna? Vad returneras?

## Refaktorera

Skriv om metoden `url()` så som du anser den bör se ut och fungera. Hur ser kodstrukturen ut? Namngivningar? Är där fel som behöver rättas till?

Det finns ett script som heter validateBlackBox.php som ska fungera att köra. Output från scriptet ska vara '`6 av 6 valideringar OK`' både innan och efter refaktorering.

Denna validering testar bara känd och korrekt input och är till för att du ska känna dig trygg med din refaktorering.

Arrayn `$checks` i det scriptet får du inte ändra på. Andra ändringar är okej, även om de inte ingår direkt i testet.

## Testa

Använd exempelvis **PHPUnit** och skriv tester för metoden `url()`.

Det är fritt spelrum här i vad du testar.

Du får lov att skriva om `url()` så att dina tester går igenom (så länge validateBlackBox.php fortfarande ger korrekt output) och du får även lov att skriva test som failar, men som borde fungera, även om du inte rättar till dessa fel i `url()`.

---

Vi tror att ett bra sätt att se vilken nivå du som utvecklare ligger på är att se hur du hanterar och förbättrar gammal kod (i det här fallet medvetet mindre bra skriven). Vi vill heller inte styra dig för hårt i vad du ska göra hur du ska göra det; mycket kan rymmas inom områdena dokumentation, refaktorering och test. Lägg det på den nivå _du_ känner är rimlig. Lycka till med testet och glöm inte ha kul när du gör det.
