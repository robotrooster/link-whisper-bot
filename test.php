<?php

    echo "Testing...";
    $orphans = str_replace(" AND p.ID IN (", "", Wpil_Query::reportPostIds(true, $hide_noindex));
    $lwbinfo = explode(",",substr($orphans, 0, -1));

    print_r($lwbinfo);

?>