<?php

namespace Lit\Media;

class BlurOut {

    private $resource = null;

    function __construct( $resourcePath ) {
        $this->factory( $resourcePath );
    }

    public function exec ( $ratioWidth, $ratioHeight, $saveFile ) {
        $this->resource->blurOut( $ratioWidth, $ratioHeight, $saveFile );
    }

    /**
     * @param $resourcePath
     * @throws \Exception
     */
    private function factory ( $resourcePath ) {
        $ext = $this->getFormat( $resourcePath );
        switch ($ext) {
            case "jpg" :
            case "jpeg":
            case "png" :
                require_once (__DIR__.DIRECTORY_SEPARATOR."BlurOut".DIRECTORY_SEPARATOR."BlurOutImage.php");
                $this->resource = new BlurOutImage( $resourcePath );
                break;
            case "mp4" :
                require_once (__DIR__.DIRECTORY_SEPARATOR."BlurOut".DIRECTORY_SEPARATOR."BlurOutVideo.php");
                $this->resource = new BlurOutVideo( $resourcePath );
                break;
            default:
                $this->resource = null;
                break;
        }
        if (null == $this->resource) {
            throw new \Exception("Unsupported file extensions!");
        }
    }

    /**
     * 获取图片格式
     * @param $resourcePath 图片路径/名称
     * @throws \Exception 不支持的文件异常
     * @return string 文件扩展名
     */
    private function getFormat( $resourcePath ) {
        $baseName = basename($resourcePath);
        if(empty($baseName)) {
            throw new \Exception("Unsupported file!");
        }
        $exp = explode(".",$baseName);
        return strtolower( end($exp) );
    }

}