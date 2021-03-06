<?php

/**
 * http://tool.oschina.net/commons?type=3
 *
 * http://php.net/manual/zh/function.imagecreatetruecolor.php
 *
 * Class Verification
 *
 */
class Verification
{
    private $model  = '09';//纯数字09|纯字母aZ|混合使用a9|算术0+

    private $length = 6;//非算术模式使用

    private $result = null;//验证结果

    private $disturb_wire = 0;//干扰线

    private $disturb_spot = 0;//干扰点

    private $backgroundColor = ['255 255 0','255 0 255','255 0 255','0 191 255','255 250 250','255 255 255','0 255 0'];


    public function __construct($width = 120,$height = 50)
    {
        $this->width  = $width;
        $this->height = $height;
        $this->imgresource = null;
    }

    public function __destruct()
    {
        imagedestroy($this->imgresource);
    }

    public function showImage()
    {
        $this->genImage();
        $this->setChars();
        $this->setDisturb();
        $this->outputImage();
    }

    public function getResult()
    {
        return $this->result;
    }

    /**
     * Generate the image
     */
    private function genImage()
    {

        $this->imgresource = imagecreatetruecolor($this->width,$this->height);

        list($r,$g,$b) = explode(' ',$this->backgroundColor[mt_rand(0,6)]);

        $bgColor = imagecolorallocate($this->imgresource,$r,$g,$b);

        imagefill($this->imgresource,0,0,$bgColor);

        $borderColor=imagecolorallocate($this->imgresource,0,0,0);

        imagerectangle($this->imgresource,0,0,$this->width-1,$this->height-1,$borderColor);
    }

    /**
     * Draw a character horizontally
     */
    private function setChars()
    {
        $code = $this->genCode();
        $len = strlen($code);

        for ($i = 0; $i < $len; $i++) {
            $color = imagecolorallocate($this->imgresource, rand(0, 128), rand(0, 128), rand(0, 128));
            $x = 10 + ($this->width / $len) * $i; //水平位置
            $y = rand($this->height/6, $this->height/3);
            imagechar($this->imgresource, 5, $x, $y, $code{$i}, $color);
        }
    }

    /**
     * Set the interference
     */
    private function setDisturb()
    {

        for ($i=0;$i<$this->disturb_spot;$i++) {

            $randColor=imagecolorallocate($this->imgresource,rand(0,255),rand(0,255),rand(0,255));

            imagesetpixel($this->imgresource,rand(1,$this->width-2),rand(1,$this->height-2),$randColor);
        }

        for ($i=0;$i<$this->disturb_wire;$i++) {

            $randColor=imagecolorallocate($this->imgresource,rand(0,200),rand(0,200),rand(0,200));

            imageline($this->imgresource,rand(1,$this->width-2),rand(1,$this->height-2),rand(1,$this->height-2),rand(1,$this->width-2),$randColor);
        }
    }

    /**
     * Output image verification code
     */
    private function outputImage()
    {
        header("Content-Type:image/png");
        imagepng($this->imgresource);
    }

    public function genCode()
    {
        switch ($this->model)
        {
            case '09':
                $origin = '0123456789';
                $code = $this->stochastic($origin);
                break;
            case 'aZ':
                $origin = 'ZAQWSXCDERFVBGTYHNMJUIKLOPzaqwsxcderfvbgtyhnmjuiklop';
                $code = $this->stochastic($origin);
                break;
            case 'a9':
                $origin = 'ZAQWSXCDERFVBGTYHNMJUIKLOPzaqwsxcderfvbgtyhnmjuiklop0123456789';
                $code = $this->stochastic($origin);
                break;
            case '0+':
            default:
                $origin = '0123456789';
                $code = $this->mathematics($origin);
                break;
        }
        return $code;
    }

    private function stochastic($origin = null)
    {
        $i = $code = null;
        $origin_length = strlen($origin) - 1;
        do {
            $i++;
            $code .= $origin{mt_rand(0,$origin_length)};
        } while ( $i < $this->length );
        $this->result = $code;
        return $code;
    }

    private function mathematics($origin)
    {
        $origin_length = strlen($origin);
        $start = $origin{mt_rand(0,$origin_length-1)};
        $end = $origin{mt_rand(0,$origin_length-1)};
        $symbol = '+-*';

        switch ($symbol{mt_rand(0,2)}) {
            case '+':
                $code = $start.'+'.$end.'=';
                $result = $start+$end;
                break;
            case '-':
                $code = $start.'-'.$end.'=';
                $result = $start-$end;
                break;
            case '*':
            default:
                $code = $start.'*'.$end.'=';
                $result = $start*$end;
                break;
        }
        $this->result = $result;
        return $code;
    }
}