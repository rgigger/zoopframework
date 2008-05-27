<?php
die('this example does not work.  Graphic support is currently experimental');
include('config.php');
include(zoop_dir . '/Zoop.php');

Zoop::loadLib('zend');
Zoop::loadLib('graphic');
$parser = new GraphicParser('ZendPdf');

$xml = file_get_contents('content.html');

/*
$xml = <<<EOT
<meta name="pagesize" content="300:600"/>
Lorem ipsum dolor <b>sit amet</b>, consectetuer adipiscing elit. Donec quis eros non neque porta molestie. Duis hendrerit. Nullam vitae elit. 
Donec id odio ut erat tristique condimentum. Cras quis magna. Mauris condimentum porttitor velit. Mauris scelerisque mollis metus. Fusce 
aliquam, augue eu molestie ultricies, libero odio vehicula pede, nec sollicitudin tortor ante ac mi. Ut eleifend porta nulla. Nulla elit
EOT;
*/

/*
	  
	nisl, lacinia et, mollis non, lacinia in, nisl.
*/

// echo "string = " . htmlentities($xml) . '<br>';
$document = $parser->parseText($xml);

// echo "<br>object tree:<br>";
// $document->getObjectTree();
// 
// echo "<br>render tree:<br>";
// $document->drawRenderTree();
// die();

$document->draw();
$context = $document->getContext();

// $len = $context->stringWidth("Lorem ipsum dolor sit amet, consectetuer adipiscing");
// var_dump($len);
// die();

$context->display();

/*
echo_r($parser);
*/

/*
// Create new PDF 
$pdf = new Zend_Pdf(); 

// Add new page to the document 
$page = $pdf->newPage(Zend_Pdf_Page::SIZE_A4); 
$pdf->pages[] = $page; 

// Set font 
$page->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA), 20); 

// Draw text 
$page->drawText('Hello world!', 100, 510);

// Get PDF document as a string 
$pdfData = $pdf->render(); 

//We send to a browser
Header('Content-Type: application/pdf');
echo $pdfData;
*/