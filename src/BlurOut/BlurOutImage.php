<?php

namespace Lit\Media;

require_once (__DIR__.DIRECTORY_SEPARATOR."BlurOutBase.php");
require_once (__DIR__.DIRECTORY_SEPARATOR."BlurOutInterface.php");

class BlurOutImage extends BlurOutBase implements BlurOutInterface {

    private $image = null;

    function __construct( $imageFile ){
        $this->image = new \Imagick($imageFile);
    }

    /**
     * 虚化超出范围
     * @param $ratioWidth int 裁切比例宽
     * @param $ratioHeight int 裁切比例高
     * @param $saveFile string 保存位置
     */
    public function blurOut( $ratioWidth, $ratioHeight, $saveFile ) {
        $imageSize = $this->imageSize(); //图片尺寸

        if ( $this->checkRatio( $imageSize["width"], $imageSize["height"], $ratioWidth, $ratioHeight ) ) {
            $compositeImage = clone $this->image;

            $cropSize = $this->getCropSize($imageSize["width"], $imageSize["height"], $ratioWidth, $ratioHeight); //最大裁切尺寸
            $cutPosition = $this->getCropPosition($imageSize["width"], $imageSize["height"], $cropSize["width"], $cropSize["height"]); //获取裁切位置
            $this->cropImage($cropSize["width"], $cropSize["height"], $cutPosition["x"], $cutPosition["y"]);//裁切

            $holdSize = $this->getHoldSize($imageSize["width"], $imageSize["height"], $cropSize["width"], $cropSize["height"]); //获取可以包住视频的最小尺寸
            $this->thumbnailImage($holdSize["width"], $holdSize["height"]); //制作缩略图

            $this->blurImage(50, 30); //虚化

            $compositePosition = $this->getCompositePosition($imageSize["width"], $imageSize["height"], $holdSize["width"], $holdSize["height"]);
            $this->compositeImage($compositeImage, $compositePosition["x"], $compositePosition["y"]); //粘贴原图

        }

        $this->writeImage($saveFile);
    }

    /**
     * 图片尺寸
     */
    private function imageSize() {
        $page = $this->image->getImagePage();
        return [ "width" => $page["width"], "height" => $page["height"] ];
    }

    /**
     * 模糊图片
     * @param $radius int 模糊半径
     * @param $sigma int 偏差
     */
    private function blurImage( $radius, $sigma ) {
        $this->image->blurImage( $radius, $sigma);
    }

    /**
     * 图片裁剪
     * @param $width int 要裁切的宽
     * @param $height int 要裁切的高
     * @param $x int 裁切坐标点X轴
     * @param $y int 裁切坐标点Y轴
     */
    private function cropImage ( $width, $height, $x, $y ) {
        $this->image->cropImage( $width, $height, $x, $y );
    }

    /**
     * 保存 imagick 对象至文件
     * @param $filePath string 保存路径
     */
    private function writeImage ( $filePath ) {
        $this->image->writeImage( $filePath );
    }


    /**
     * 粘贴图片
     * @param $compositeImage \Imagick 要粘贴的图片 imagick 对象
     * @param $x int 粘贴坐标点X轴
     * @param $y int 粘贴坐标点Y轴
     */
    private function compositeImage ( $compositeImage, $x, $y ) {
        $this->image->compositeImage ($compositeImage, \Imagick::COMPOSITE_DEFAULT, $x, $y );
    }

    /**
     * 图片缩放
     * @param $width int 宽
     * @param $height int 高
     */
    private function thumbnailImage( $width, $height ){
        $this->image->thumbnailImage( $width, $height );
    }

}