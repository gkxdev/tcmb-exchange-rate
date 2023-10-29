<?php
require_once __DIR__ . '/TCMB_Exchange_Rate.php';

try {

    $tcmb = new TCMB_Exchange_Rate;
    // $tcmb = new TCMB_Exchange_Rate('2023-01-01');

    print_r($tcmb->getCurrency('USD'));

    // USD Alış
    echo $tcmb->getCurrency('USD')->buying;
    // USD Satış
    echo $tcmb->getCurrency('USD')->selling;

    print_r($tcmb->getAllCurrencies());
    print_r($tcmb->getDate());

} catch (Exception $e) {
    exit('Error: ' . $e->getMessage());
}