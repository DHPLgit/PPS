<?php

function GetJson()
{
    $jsonFile = file_get_contents('../public/uploads/dropdown.json');
    $data = json_decode($jsonFile);

    return $data;
}
