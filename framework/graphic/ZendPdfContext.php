<?php
class ZendPdfContext extends PdfContext
{
	private $zpdf;
	private $curPage;
	private $curFontName;
	private $curFontSize;
    
	public function init($size = PdfContext::sizeA4, $fontName = PdfContext::fontHelvetica, $fontSize = 12)
	{
		//	extract the dimentions
		$this->assignSize($size);
		
		//	create the pdf object
		$this->zpdf = new Zend_Pdf();
		
		// create the first page
		$this->newPage();
		
		// Set font 
		$this->setFont($fontName, $fontSize);
	}
	
	public function assignSize($size)
	{
		parent::assignSize($size);
	}
	
	private function fixy(&$x, &$y)
	{
		$y = $this->height - $y;
	}
	
	public function newPage($size = NULL)
	{
		if($size)
			$this->assignSize($size);
		
		$sizeString = $this->width . ':' . $this->height . ':';
		
		$this->curPage = $this->zpdf->newPage($sizeString); 
		$this->zpdf->pages[] = $this->curPage;
	}
	
	public function setFont($fontName, $fontSize)
	{
		$this->curFontName = $fontName;
		$this->curFontSize = $fontSize;
		$this->curPage->setFont(Zend_Pdf_Font::fontWithName($fontName), $fontSize);
	}
	
	public function addText($x, $y, $text)
	{
		$this->fixy($x, $y);
		$this->curPage->drawText($text, $x, $y);
	}
	
	public function getCharWidths($string)
	{
		//	I don't know how  intensive creating these font objects is but this is probably something that we should cache
		//	in a static class variable
		// var_dump($this->curFontName);
		$font = Zend_Pdf_Font::fontWithName($this->curFontName);
		
		$drawingString = iconv('', 'UTF-16BE', $string);
	    $characters = array();
	    for ($i = 0; $i < strlen($drawingString); $i++) {
	        $characters[] = (ord($drawingString[$i++]) << 8) | ord($drawingString[$i]);
	    }
		
		$glyphs = $font->cmap->glyphNumbersForCharacters($characters);
		// echo_r($glyphs);
		$widths = $font->widthsForGlyphs($glyphs);
		foreach($widths as $index => $width)
			$widths[$index] = ($width / $font->getUnitsPerEm()) * $this->curFontSize;
		
		return $widths;
	}
	
	function stringWidth($string, $fontName = PdfContext::fontHelvetica, $fontSize = 12)
	{
		$font = Zend_Pdf_Font::fontWithName($fontName);
	    $drawingString = iconv('', 'UTF-16BE', $string);
	    $characters = array();
	    for ($i = 0; $i < strlen($drawingString); $i++) {
	        $characters[] = (ord($drawingString[$i++]) << 8) | ord($drawingString[$i]);
	    }
	    $glyphs = $font->cmap->glyphNumbersForCharacters($characters);
	    $widths = $font->widthsForGlyphs($glyphs);
	    $stringWidth = (array_sum($widths) / $font->getUnitsPerEm()) * $fontSize;
	    return $stringWidth;
	}
	
	public function display()
	{
		// Get PDF document as a string 
		$pdfData = $this->zpdf->render(); 

		// send header for the browser
		Header('Content-Type: application/pdf');
		echo $pdfData;
	}
	
	/*
	function getPageWidth()
	{
		return $this->width;
	}
	
	function getPageHeight()
	{
		return $this->height;
	}
	
	function addColor($name, $r, $g, $b)
	{
		$this->colors[$name] = array($r, $g, $b);
	}
	
	
	function setCurLineColor($colorName)
	{
		parent::setCurLineColor($colorName);
		$color = $this->_getCurLineColor();
		$this->fpdf->setDrawColor($color[0], $color[1], $color[2]);
	}
	
	function setCurFillColor($colorName)
	{
		parent::setCurFillColor($colorName);
		$color = $this->_getCurFillColor();
		$this->fpdf->setFillColor($color[0], $color[1], $color[2]);
	}
	
	function setCurTextColor($colorName)
	{
		parent::setCurTextColor($colorName);
		$color = $this->_getCurTextColor();
		$this->fpdf->setTextColor($color[0], $color[1], $color[2]);
	}
	
	function breakPage()
	{
		$this->fpdf->AddPage();
	}
	
	function getStringWidth($string)
	{
		return $this->fpdf->GetStringWidth($string);
	}
	
	function addLine($x1, $y1, $x2, $y2, $lineWidth = 0.57)
	{
		$this->fpdf->SetLineWidth($lineWidth);
		$this->fpdf->Line($x1, $y1, $x2, $y2);
		//$this->fpdf->SetLineWidth(0.57);
	}
	
	function addHorizLine($left, $right, $top, $lineWidth = 0.57)
	{
		$halfLineWidth = $lineWidth / 2;
		
		$x1 = $left + $halfLineWidth;
		$x2 = $right - $halfLineWidth;
		$y1 = $y2 = $top + $halfLineWidth;
		$this->fpdf->SetLineWidth($lineWidth);
		$this->fpdf->Line($x1, $y1, $x2, $y2);
		//$this->fpdf->SetLineWidth(0.57);
	}
	
	function addVertLine($top, $bottom, $left, $lineWidth = 0.57)
	{
		
		$halfLineWidth = $lineWidth / 2;
		
		$x1 = $x2 = $left + $halfLineWidth;
		$y1 = $top + $halfLineWidth;
		$y2 = $bottom - $halfLineWidth;
		$this->fpdf->SetLineWidth($lineWidth);
		$this->fpdf->Line($x1, $y1, $x2, $y2);
		//$this->fpdf->SetLineWidth(0.57);
	}
	
	function addRect($x, $y, $w, $h, $style = 'D')
	{
		$this->fpdf->Rect($x, $y, $w, $h, $style);
	}
	
	function addImage($file, $x, $y, $w=0, $h=0)
	{
		$this->fpdf->Image($file, $x, $y, $w, $h);
	}
	
	function addPolygon($points, $style = 'D')
	{
		$this->fpdf->Polygon($points, $style);
	}
	
	function addCircle($x, $y, $r, $style='D')
	{
		$this->fpdf->Circle($x, $y, $r, $style );
	}
	
	function addEllipse($x, $y, $rx, $ry, $style='D')
	{
		$this->fpdf->Ellipse($x, $y, $rx, $ry, $style);
	}
	
	//	This uses the tweaked logic of imagearc and imagefilledarc below for evaluating angles
	//		not logic that would actually draw the angles passed in.  This may need to be tweaked
	//		for compatibility with other context modules.
	
	function addArc($x, $y, $w, $h, $startAngle, $endAngle, $style='D')
	{
		$this->fpdf->Arc($x, $y, $w / 2, $h / 2, $startAngle, $endAngle, $style);
	}
	
	//	This uses the tweaked logic of imagearc and imagefilledarc for evaluating angles
	//		not logic that would actually draw the angles passed in.  This may need to be tweaked
	//		for compatibility with other context modules.

	function addCylinderSlice($cx, $cy, $w, $h, $sTheta, $eTheta, $depth, $style='D')
	{
		$this->fpdf->CylinderSlice($cx, $cy, $w / 2, $h / 2, $sTheta, $eTheta, $depth, $style);
	}
	
	function setTextFont($newFontStyle)
	{
		assert( is_a($newFontStyle, 'GraphicTextStyle') );
		
		$style = '';
		if($newFontStyle->getUnderline())
			$style .= 'U';
		if($newFontStyle->getBold())
			$style .= 'B';
		if($newFontStyle->getItalics())
			$style .= 'I';
		
		$this->fpdf->SetFont($newFontStyle->getFont(), $style, $newFontStyle->getTextSize());
	}
	
	function setTextSize($size)
	{
		parent::setTextSize($size);
		$this->fpdf->SetFontSize($size);
	}
	
	function setTextStyle($style)
	{
		parent::setTextStyle($style);
		$this->setCurFont();
	}
	
	function setTextFontName($fontName)
	{
		parent::setTextFontName($fontname);
		$this->setCurFont();
	}
	
	function setCurFont()
	{
		$this->fpdf->SetFont($this->getTextFontName(), $this->getTextStyle(), $this->getTextSize());
	}
	
	function setTextColor($r, $g, $b)
	{
		$this->fpdf->SetTextColor($r, $g, $b);
	}
	
	function setLineWidth($lineWidth)
	{
		$this->fpdf->SetLineWidth($lineWidth);
	}
	
	function addRaw($rawData)
	{
		$this->fpdf->Raw($rawData);
	}
	
	function save($filename)
	{
		//	we should all them to set the name here
		$this->fpdf->Output($filename, 'F');
	}
	
	function display()
	{
		//	we should all them to set the name here
		$this->fpdf->Output('', 'I');
	}
	*/
}
