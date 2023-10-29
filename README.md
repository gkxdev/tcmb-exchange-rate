# TCMB Exchange Rate

TCMB (Türkiye Cumhuriyeti Merkez Bankası) üzerinden bugün veya geçmiş tarihli döviz kurlarını almaya yarar.

> Döviz kurlarında alış ve satış verisi; varsa efektif, yoksa döviz olarak alınır.

## Kullanımı

```php
<?php
require_once 'TCMB_Exchange_Rate.php';

// Bugün
$tcmb = new TCMB_Exchange_Rate;

// USD Alış
echo $tcmb->getCurrency('USD')->buying;

// USD Satış
echo $tcmb->getCurrency('USD')->selling;

// Tüm Döviz Kurları
print_r($tcmb->getAllCurrencies());
```

Bugün veya geçmiş tarihli döviz kurları istenirken; tarih hafta sonuna denk geliyorsa, bir önceki cuma günü alınır. Eğer ilgili tarihte döviz kuru bilgisi yoksa (gün içerisinde açıklanmamış, resmi tatil, bayram vb. durumlar), birer gün geriye giderek açıklanan son kur bilgisi bulunur. Bu durumda kullanıcıyı bilgilendirmek isteyebilirsiniz.

```php
<?php
require_once 'TCMB_Exchange_Rate.php';

// Geçmiş Tarihli
// 01 Ocak 2023, Pazar
$tcmb = new TCMB_Exchange_Rate('2023-01-01');

$eur = $tcmb->getCurrency('EUR');

// 30 Aralık 2022, Cuma
printf('%s tarihli %s alış kuru %.4f - satış kuru %.4f',
    $tcmb->getDate()->format('d/m/Y'),
    $eur->currencyCode,
    $eur->buying, // Alış
    $eur->selling // Satış
);
```

### getCurrency(string $currency)

Parametre olarak verilen para biriminin döviz kuru objesini getirir.

```
stdClass Object
(
    [currencyCode]   => Döviz Kodu
    [currencyName]   => Döviz Adı
    [unit]           => Birim
    [buying]         => Efektif / Döviz Alış
    [selling]        => Efektif / Döviz Satış
    [crossRateUsd]   => Çapraz Kur USD
    [crossRateOther] => Çapraz Kur Diğer
)
```

### getAllCurrencies()

Tüm para birimlerinin döviz kuru objelerini dizi olarak getirir.

### getDate()

Alınan döviz kurlarının tarih bilgisini DateTime objesi olarak döner.

## Hata Yakalama

```php
<?php
require_once 'TCMB_Exchange_Rate.php';

try {

    // Gelecek Tarihli
    $tcmb = new TCMB_Exchange_Rate(
        date('Y-m-d', strtotime('+1 day'))
    );

    print_r($tcmb->getCurrency('USD'));

} catch (Exception $e) {
    exit('Error: ' . $e->getMessage());
}
```