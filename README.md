# Akeneo Barcode Generator
Automatically generates barcodes based on the UPC and places the barcode image into Akeneo.

## The Basics
The application checks to see if your products have a UPC but no barcode. It will then generate the scannable barcodes and place them into the products.

## Setting it up
Your PIM must have the following *non-scopable* attributes:
- UPC text attribute (code: upc)
- Barcode image attribute (code: barcode)

Add your connection details to the .env file.

## Instructions
Execute the script by running `php generate.php` from your bash terminal.

## Notes
This currently supports UPC-based barcodes but it can be easily adapted for EAN-based barcodes.

This application utilizes the Open Source library found [here](https://github.com/kreativekorp/barcode "here") and the Akeneo PHP API Client.
