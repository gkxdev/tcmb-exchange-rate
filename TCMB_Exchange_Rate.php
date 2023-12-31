<?php
/**
 * TCMB Exchange Rate
 * @author Gökhan Kaya <gkxdev@gmail.com>
 */

class TCMB_Exchange_Rate {
    private $date;
    private $currencies;

    public function __construct($date = 'now') {
        $this->initExchangeRates($date);
    }

    public function getCurrency($currency) {
        if (!isset($this->currencies[$currency])) {
            throw new Exception('Currency code not found! ' . $currency);
        }

        return $this->currencies[$currency];
    }

    public function getAllCurrencies() {
        return $this->currencies;
    }

    public function getDate() {
        return $this->date;
    }

    private function initExchangeRates($date) {
        $this->date = date_create($date);

        if ($this->getDate()->getTimestamp() > time()) {
            throw new Exception('Date cannot be in the future!');
        }

        if ($this->getDate()->format('N') >= 6) {
            $this->getDate()->modify('previous friday');
        }

        $this->currencies = $this->tcmbParseXml(
            $this->tcmbHttpRequest()
        );
    }

    private function tcmbHttpRequest() {
        while (true) {
            $url = sprintf('https://www.tcmb.gov.tr/kurlar/%s/%s.xml',
                $this->getDate()->format('Ym'),
                $this->getDate()->format('dmY')
            );

            $response = @file_get_contents($url);

            if (empty($http_response_header)) {
                throw new Exception('Could not connect to TCMB server!');
            }

            if ($response) break;

            $this->getDate()->modify('-1 day');
        }

        return $response;
    }

    private function tcmbParseXml($xml) {
        $xml = simplexml_load_string($xml);

        $data = array();

        foreach ($xml->Currency as $currency) {
            $currencyCode = (string) $currency['CurrencyCode'];

            $buying  = $currency->BanknoteBuying  ?: $currency->ForexBuying;
            $selling = $currency->BanknoteSelling ?: $currency->ForexSelling;

            $data[$currencyCode] = (object) array(
                'currencyCode'   => $currencyCode,
                'currencyName'   => (string) $currency->CurrencyName,
                'unit'           => (string) $currency->Unit,
                'buying'         => (double) $buying,
                'selling'        => (double) $selling,
                'crossRateUsd'   => (double) $currency->CrossRateUSD   ?: null,
                'crossRateOther' => (double) $currency->CrossRateOther ?: null
            );
        }

        return $data;
    }
}