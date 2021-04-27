#!/usr/bin/php
<?php

/** post-INSTALLATION (command line)

composer require akeneo/api-php-client-ee \
php-http/guzzle6-adapter:^2.0 http-interop/http-factory-guzzle:^1.0 symfony/dotenv

 */

require_once __DIR__ . '/vendor/autoload.php';
include 'barcode.php';
use \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder;
use \Akeneo\Pim\ApiClient\Search\SearchBuilder;
use Symfony\Component\Dotenv\Dotenv;

/** Global Barcode Settings */
$format     =   "png";
$options    =   "";
$symbology  =   "ean-13-pad"; //Choose the type of barcode from the supported options listed below:
/**
    Possible values for $symbology
    upc-a*         code-39         qr     dmtx
    upc-e          code-39-ascii   qr-l   dmtx-s
    ean-8          code-93         qr-m   dmtx-r
    ean-13*        code-93-ascii   qr-q   gs1-dmtx
    ean-13-pad*    code-128        qr-h   gs1-dmtx-s
    ean-13-nopad*  codabar                gs1-dmtx-r
    ean-128        itf
    * Supported in this version.
**/


/** Configure the Barcode based on the settings **/
switch ($symbology) {
        case 'upc-a':
            $standard = "upc";
        break;    
        case 'ean-13':
            $standard = "ean";
        case 'ean-13-pad':
            $standard = "ean";
        case 'ean-13-nopad':
            $standard = "ean";
        break;
    }

/** API VARS are in the .env file */
$dotenv = new Dotenv();
$dotenv->load(__DIR__.'/.env');
if(file_exists(__DIR__ . '/.env.local')) {
    $dotenv->load(__DIR__ . '/.env.local');
}

/** SET THE API CLIENT */
$clientBuilder = new AkeneoPimClientBuilder($_ENV['API_URL']);
$client = $clientBuilder->buildAuthenticatedByPassword(
    $_ENV['API_CLIENT'], $_ENV['API_SECRET'], $_ENV['API_USERNAME'], $_ENV['API_PASSWORD']);

/** Build the search */
$searchBuilder = new SearchBuilder();
$searchBuilder->addFilter($standard, 'NOT EMPTY');
$searchBuilder->addFilter('barcode', 'EMPTY');
$searchFilters = $searchBuilder->getFilters();

/** Generate and add the barcodes to products that do not have one */
$products = $client->getProductApi()->all(100, ['search' => $searchFilters]);
foreach ($products as $product) {
    $productsku     =   $product['identifier'];
    $barcodedata    =   $product['values'][$standard][0]['data'];
    $generator = new barcode_generator();
    $generator->output_image($format, $symbology, $barcodedata, $options, $productsku);
    $generatedbarcode   =   $productsku.'.'.$format;
        $client->getProductMediaFileApi()->create($generatedbarcode, [
            'identifier' => $productsku,
            'attribute'  => 'barcode',
            'scope'      => null,
            'locale'     => null,
        ]);
    unlink($generatedbarcode);
}
