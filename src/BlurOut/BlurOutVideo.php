<?php

namespace Lit\Media;

require_once (__DIR__.DIRECTORY_SEPARATOR."BlurOutBase.php");
require_once (__DIR__.DIRECTORY_SEPARATOR."BlurOutInterface.php");

class BlurOutVideo extends BlurOutBase implements BlurOutInterface {

    private $video = null;

    protected $customA = array();
    protected $customB = array();
    protected $videoFile = "";

    function __construct( $videoFile ){
        $ffmpeg = \FFMpeg\FFMpeg::create(array(
            'timeout'          => 3600, //线程超时时间
        ));
        $this->videoFile = $videoFile;
        $this->video = $ffmpeg->open( $videoFile );
    }

    /**
     * 虚化超出范围
     * @param $ratioWidth int 裁切比例宽
     * @param $ratioHeight int 裁切比例高
     * @param $saveFile string 保存位置
     */
    function blurOut( $ratioWidth, $ratioHeight, $saveFile ){
        $videoSize = $this->videoSize(); //视频尺寸

        if ( $this->checkRatio( $videoSize["width"], $videoSize["height"], $ratioWidth, $ratioHeight ) ) {

            $cropSize = $this->getCropSize($videoSize["width"], $videoSize["height"], $ratioWidth, $ratioHeight); //最大裁切尺寸
            $cutPosition = $this->getCropPosition($videoSize["width"], $videoSize["height"], $cropSize["width"], $cropSize["height"]); //获取裁切位置
            $this->cropVideo($cropSize["width"], $cropSize["height"], $cutPosition["x"], $cutPosition["y"]);//裁切

            $holdSize = $this->getHoldSize($videoSize["width"], $videoSize["height"], $cropSize["width"], $cropSize["height"]); //获得需要比例最小包容尺寸
            $this->thumbnailVideo($holdSize["width"], $holdSize["height"]); //缩放视频

            $this->blurVideo(10,3); //虚化

            $this->video->filters()->custom(implode(",",$this->customA))->synchronize();

            $compositePosition = $this->getCompositePosition($videoSize["width"], $videoSize["height"], $holdSize["width"], $holdSize["height"]);//获取视频拼贴位置
            $this->compositeVideo( $compositePosition["x"], $compositePosition["y"] ); //粘贴原视频

        }

        $this->writeVideo($saveFile);

    }

    /**
     * 视频尺寸
     */
    private function videoSize () {
        $dimensions = $this->video->getStreams()->videos()->first()->getDimensions();
        return [ "width" => $dimensions->getWidth(), "height" => $dimensions->getHeight() ];
    }

    /**
     * 视频裁剪
     * @param $width int 要裁切的宽
     * @param $height int 要裁切的高
     * @param $x int 裁切坐标点X轴
     * @param $y int 裁切坐标点Y轴
     */
    private function cropVideo ( $width, $height, $x, $y ) {
        $this->customA[] = "crop={$width}:{$height}:{$x}:$y";
    }

    /**
     * 视频保存至文件
     * @param $filePath string 保存路径
     */
    private function writeVideo ( $filePath ) {
        $this->video->save(new \FFMpeg\Format\Video\X264("aac"), $filePath );
    }

    /**
     * 图片视频
     * @param $width int 宽
     * @param $height int 高
     */
    private function thumbnailVideo( $width, $height ){
        $width = intval($width/2) * 2;
        $height = intval($height/2) * 2;
        $this->customA[] = "scale={$width}:{$height}";
    }

    /**
     * 粘贴视频
     * @param $x int 粘贴坐标点X轴
     * @param $y int 粘贴坐标点Y轴
     */
    private function compositeVideo ( $x, $y ) {
        $this->video->filters()->watermark($this->videoFile, array(
                'position' => 'absolute',
                'x' => $x,
                'y' => $y,
            ))->synchronize();
    }

    /**
     * 模糊视频
     * @param $radius int 模糊半径
     * @param $sigma int 偏差
     */
    private function blurVideo( $radius, $sigma ) {
        $this->customA[] = "boxblur={$radius}:{$sigma}";
    }

}