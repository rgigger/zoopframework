<?php
include('config.php');
include(zoop_dir . '/Zoop.php');

Zoop::loadLib('Zend');

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