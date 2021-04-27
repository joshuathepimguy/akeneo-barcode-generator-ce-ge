# Akeneo Barcode Generator
Automatically generates barcodes based on the UPC or EAN and places the barcode image into Akeneo.

This free and open-source project is not supported.

## Requirements
Either:
+ Akeneo Growth Edition
+ Akeneo Community Edition >=4.0


## The Basics
The application checks to see if your products have a UPC but no barcode. It will then generate the scannable barcodes and place them into the products.

## Setting it up
#### Your PIM must have the following *non-scopable and non-localizable* attributes:
+ Barcode image attribute (code: barcode)
+ If your are generating a UPC-based barcode:
  + UPC text attribute (code: upc)
+ If your are generating an EAN-based barcode:
  + EAN text attribute (code: ean)
#### Add your connection details to the .env file.
#### Choose the type of barcode you would like by changing the `$symbology` variable in the `generate.php` file to one of the supported formats.

| Current supported formats  |
|:--------------------------:|
| upc-a |
| ean-13 |
| ean-13-pad |
| ean-13-nopad |

## Instructions
Execute the script by running `php generate.php` from your bash terminal.

## Notes
This currently supports UPC and EAN barcodes but it can be easily adapted for other uses such as UPC-E, QR Codes, and many others.

This application utilizes the Open Source library found [here](https://github.com/kreativekorp/barcode "here") and the Akeneo PHP API Client.
