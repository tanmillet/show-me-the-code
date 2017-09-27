<?php
echo $_GET['callback']."(".json_encode(Sdk_Sso::login()).");";