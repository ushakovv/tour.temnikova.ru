<?php

namespace core\media;

/**
 * SiteIndex image processor GD implementation.
 *
 * @author Dmitry Latikov <dlatikov@promo.ru>
 * @package core.components.utils.images
 * @link http://php.net/manual/ru/book.image.php
 */
class ImageProcessorGd extends ImageProcessor implements IImageProcessor
{
	/*public function load($file)
	{
		$path = $this->transport->getFullPath($file);
		$this->_imageHander = imagecreatefrompng($path);
		return $this;
	}*/

	public function create($content)
	{
		$this->_imageHander = @imagecreatefromstring($content);
		if(!$this->_imageHander){
			throw new ImageProcessorException("Image open error");
		}
		return $this;
	}

	public function getContent(){
		ob_start();
		if($this->_extension == 'png'){
			imagepng($this->_imageHander);
		} else {
			imagejpeg($this->_imageHander, null, $this->_quality);
		}
		$content = ob_get_contents();
		ob_end_clean();
		return $content;
	}

	public function destroy(){
		imagedestroy($this->_imageHander);
	}

	/**
	 * Crop image.
	 * 
	 * @param $width
	 * @param $height
	 * @param $x
	 * @param $y
	 * @return ImageProcessorImagick
	 */
	public function crop($width, $height, $x, $y)
	{
		$origSize = $this->getImageGeometry();
		$newImage = imagecreatetruecolor($width, $height);

		imagealphablending( $newImage, false );
		imagesavealpha( $newImage, true );

		imagecopy ($newImage, $this->_imageHander, 0, 0, $x, $y, $width, $height);

		imagedestroy($this->_imageHander);
		$this->_imageHander = $newImage;
		return $this;
	}

	/**
	 * Scale image to fit dimensions, if image sizes are over them. 
	 * 
	 * @param $width
	 * @param $height
	 * @return ImageProcessorImagick
	 */
	public function scaleDown($width, $height)
	{
		$srcSize = $this->getImageGeometry();
		
		if ($width < $srcSize['width'] || $height < $srcSize['height']) {
			if ($width / $srcSize['width'] > $height / $srcSize['height']) {
				$this->resize(null, $height);
			} else {
				$this->resize($width, null);
			}
		}

		return $this;
	}

	public function append($width, $height)
	{
		$srcSize = $this->getImageGeometry();

		if ($width < $srcSize['width'] || $height < $srcSize['height']) {
			if ($width / $srcSize['width'] > $height / $srcSize['height']) {
				$this->resize(null, $height);
			} else {
				$this->resize($width, null);
			}
		}

		$srcSize = $this->getImageGeometry();

		$newImage = imagecreatetruecolor($width, $height);

		if($this->_extension == 'png'){
			imagealphablending( $newImage, false );
			imagesavealpha( $newImage, true );
		} else {
			$bg = imagecolorallocate($newImage, $this->bgColor[0], $this->bgColor[1], $this->bgColor[2]);
			imagefill($newImage, 0, 0, $bg);
		}
		$destX = 0;
		$destY = 0;
		if($width > $srcSize['width']){
			$destX = round(($width - $srcSize['width'])/2);
		}
		if($height > $srcSize['height']){
			$destY = round(($height - $srcSize['height'])/2);
		}

		imagecopyresampled($newImage, $this->_imageHander, $destX, $destY, 0, 0, $srcSize['width'], $srcSize['height'], $srcSize['width'], $srcSize['height']);

		imagedestroy($this->_imageHander);
		$this->_imageHander = $newImage;
		return $this;
	}

	/**
	 * Get image dimensions.
	 * 
	 * @return array Returns array with width and height keys.
	 */
	public function getImageGeometry()
	{
		return array(
			'width' => imagesx($this->_imageHander),
			'height' => imagesy($this->_imageHander)
		);
	}

	/**
	 * Resize image to given dimensions exactly. This method may break aspect ratio of source image.
	 * 
	 * @param null $width
	 * @param null $height
	 * @return ImageProcessorImagick
	 */
	public function resize($width = null, $height = null)
	{
		$srcSize = $this->getImageGeometry();
		$srcSizeRatio = $srcSize['width'] / $srcSize['height'];
		
		if (!$width && !$height) {
			throw new \yii\base\Exception(\Yii::t('app', 'You must specify at least width or height for resize.'));
		} elseif (!$width) {
			$width = $height * $srcSizeRatio;
		} elseif (!$height) {
			$height = $width / $srcSizeRatio;
		}

		$newImage = imagecreatetruecolor($width, $height);

		if($this->_extension == 'png'){
			imagealphablending( $newImage, false );
			imagesavealpha( $newImage, true );
		} else {
			$bg = imagecolorallocate($newImage, $this->bgColor[0], $this->bgColor[1], $this->bgColor[2]);
			imagefill($newImage, 0, 0, $bg);
		}

		imagecopyresampled($newImage, $this->_imageHander, 0, 0, 0, 0, $width, $height, $srcSize['width'], $srcSize['height']);

		imagedestroy($this->_imageHander);
		$this->_imageHander = $newImage;
		return $this;
	}

	public function overlay($overlayPath, $dstX = 0, $dstY = 0, $srcX = 0, $srcY = 0, $srcW = null, $srcH = null)
	{
		$content = $this->transport->load($overlayPath);

		$srcHandler = @imagecreatefromstring( $content );

		if($srcHandler){
			if(!$srcW || !$srcH){
				$srcW = imagesx($srcHandler);
				$srcH = imagesy($srcHandler);
			}
			imagecopy($this->_imageHander, $srcHandler, $dstX, $dstY, $srcX, $srcY, $srcW, $srcH);
		} else {
			throw new ImageProcessorException("Overlay load error: " . $overlayPath);
		}
	}

	public function rotate($angle)
	{
		$this->_imageHander = imagerotate($this->_imageHander, $angle, 0);
	}

	public function text($x, $y, $text, $size, $color = 0x000000, $align = "center", $angle = 0, $fontfile = '@yii/captcha/SpicyRice.ttf', $debugMode)
	{
		$text = iconv("UTF-8", "Windows-1251", $text);

		$text = $this->win2uni($text);

		imagealphablending( $this->_imageHander, true );
		imagesavealpha( $this->_imageHander, true );

		/*$geometry = $this->getImageGeometry();

		$newImage = imagecreatetruecolor($geometry['width'], $geometry['height']);*/

		$color = imagecolorallocate(
			$this->_imageHander,
			(int) ($color % 0x1000000 / 0x10000),
			(int) ($color % 0x10000 / 0x100),
			$color % 0x100
		);
		$fontfile = \Yii::getAlias($fontfile);
		if($align == "center"){
			$box = imagettfbbox($size, $angle, $fontfile, $text);
			list($blx, $bly, $brx, $bry, $trx, $try, $tlx, $tly) = $box;
			$width = max($trx, $brx) - min($blx, $tlx);
			$height = max($bly, $bry) - min($tly, $try);
			$offsetY =  ($bry > $bly) ? ($bry - $bly) : 0;
			$offsetX = ($blx > $tlx) ? ($blx -  $tlx) : 0;
			$x = $x - $width / 2 + $offsetX;
			$y = $y + $height / 2 - $offsetY;
			if($debugMode){
				$dx = $x - $blx;
				$dy = $y - $bly;
				$this->line($dx + $tlx, $dy + $tly, $dx + $trx, $dy + $try, 0xFFFFFF);
				$this->line($dx + $trx, $dy + $try, $dx + $brx, $dy + $bry, 0xFFFFFF);
				$this->line($dx + $brx, $dy + $bry, $dx + $blx, $dy + $bly, 0xFFFFFF);
				$this->line($dx + $blx, $dy + $bly, $dx + $tlx, $dy + $tly, 0xFFFFFF);
			}
		}
		/*imagealphablending( $this->_imageHander, false );
		imagesavealpha( $this->_imageHander, true );*/

		imagettftext($this->_imageHander, $size, $angle, $x, $y, $color, $fontfile, $text);

		//imagecopy($this->_imageHander, $newImage, 0, 0, 0, 0, $geometry['width'], $geometry['height']);
	}

	public function imagettfbbox($size, $angle, $fontfile, $text)
	{
		$text = iconv("UTF-8", "Windows-1251", $text);
		$text = $this->win2uni($text);
		return imagettfbbox($size, $angle, $fontfile, $text);
	}

	public function line($x1, $y1, $x2, $y2, $color)
	{
		imageline ( $this->_imageHander ,$x1 , $y1 , $x2 , $y2 , $color );
	}

	private function win2uni ($winstr){
		$isoline = convert_cyr_string($winstr, "w", "i");
		$uniline = "";

		for ($i=0; $i < strlen($isoline); $i++){
			$thischar=substr($isoline,$i,1);
			$charcode=ord($thischar);
			$uniline.=($charcode>175) ? "&#" . (1040+($charcode-176)). ";" : $thischar;
		}
		return $uniline;
	}

}

?>