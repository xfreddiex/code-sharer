<?php
parse_str($argv[1]);
mail($to, $subject, $txt, $headers);
