<?php


function bss_update_available(): bool
{
    return false;
}

// TODO: Possible cleanup of id_to_alpha

function id_to_alpha($id)
{
    $alpha = "";

    $alphabet = range('A', 'Z');
    $rounds =  (int) ceil($id / 26);

    for ($i = 0; $i < $rounds; $i++) {
        if ($id >= 26) {
            $char = 'Z';
            $id = $id - 26;
        } else {
            $char = $alphabet[($id - 1)];
        }

        $alpha .= $char;
    }

    return $alpha;
}
