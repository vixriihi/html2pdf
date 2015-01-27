# Html to pdf

This is a php wrapper for [wkhtmltopdf](http://wkhtmltopdf.org/).

## Requirements

 * php > 5.4.0
 * [wkhtmltopdf](http://wkhtmltopdf.org/downloads.html)

## Basic usage

```php
$html = '<h1>Working</h1>';

$html2pdf = new Html2Pdf();
$pdf      = $html2pdf->convert($html);

header("Content-type: application/octet-stream");
header("Content-disposition: attachment;filename=hmtl.pdf");

echo $pdf;

```

[All options for wkhtmltopdf](http://wkhtmltopdf.org/usage/wkhtmltopdf.txt) are accessible with method setParameter

```php
$html2pdf = new Html2Pdf();
$html2pdf->setParam('page-size','A3');
$html2pdf->setParam('dpi',96);

```