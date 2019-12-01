#### Borttagning av rekursiv `urldecode`
Lösningen baseras på förväntad input och funktion av superglobala variabler som `$_GET` och `$_SERVER`

Eftersom `$_GET` variabeln redan är decodad är det riskabelt att uföra detta explicit för varje värde.
Därför togs den rekursiva loopen bort helt i commit `8ff487b001d6d3217b24d52e0be405673f066a0f`.

https://www.php.net/manual/en/function.urldecode.php

 