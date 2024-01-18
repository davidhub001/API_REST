# exemple utilisation api rest

curl -X POST -H "Content-Type: application/json" -d '{"name":"david", "phone":"123456789"}' http://myhost/API/api.php

curl http://myhost/API/api.php

curl -X PUT -H "Content-Type: application/x-www-form-urlencoded" -d "id=0&name=david&phone=987654321" http://myhost/API/api.php

curl -X DELETE -H "Content-Type: application/x-www-form-urlencoded" -d "id=0" http://myhost/API/api.php
