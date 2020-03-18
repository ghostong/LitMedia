<?php

namespace Lit\Media;

class BlurOutBase{
    /**
     * 获取比例值最大可裁切图片大小
     * @param $width int 原图片宽度
     * @param $height int 原图片高
     * @param $ratioWidth int 容器比例宽
     * @param $ratioHeight int 容器比例高
     * @return array 宽高数组 width,height
     */
    protected function getCropSize( $width, $height, $ratioWidth, $ratioHeight ) {
        if ( $ratioWidth == $ratioHeight ) { //任意形状裁切为正方形
            $cropSize = min($width,$height);
            return ["width" => $cropSize,"height"=>$cropSize ];

        } elseif ( $height/$width > $ratioHeight/$ratioWidth ) { //以宽为基准
            $tmpHeight = floor($ratioHeight*$width/$ratioWidth);
            return ["width" => $width,"height"=>$tmpHeight ];

        } elseif ( $height/$width < $ratioHeight/$ratioWidth) { //以高为基础
            $tmpWidth = floor($height*$ratioWidth/$ratioHeight);
            return ["width" => $tmpWidth,"height"=>$height];

        } else { //裁切比例相同
            return ["width" => $width,"height"=>$height ];

        }
    }

    /**
     * 获取比例值最小容纳图片的尺寸
     * @param $width int 原图片宽度
     * @param $height int 原图片高
     * @param $ratioWidth int 容器比例宽
     * @param $ratioHeight int 容器比例高
     * @return array 宽高数组 width,height
     */
    protected function getHoldSize ( $width, $height, $ratioWidth, $ratioHeight ){
        if ( $ratioWidth == $ratioHeight ) { //任意形状裁切为正方形
            $cropSize = max($width,$height);
            return ["width" => $cropSize,"height"=>$cropSize ];

        } elseif ( $height/$width > $ratioHeight/$ratioWidth ) { //以高为基准
            $tmpWidth = floor($height*$ratioWidth/$ratioHeight);
            return ["width" => $tmpWidth,"height"=>$height];

        } elseif ( $height/$width < $ratioHeight/$ratioWidth) { //以宽为基础
            $tmpHeight = floor($ratioHeight*$width/$ratioWidth);
            return ["width" => $width,"height"=>$tmpHeight ];

        } else { //裁切比例相同
            return ["width" => $width,"height"=>$height ];

        }
    }

    /**
     * 获取裁切位置
     * @param $oriWidth int 原图片的宽
     * @param $oriHeight int 原图片的高
     * @param $cutWidth int 裁切图片宽
     * @param $cutHeight int 裁切图片高
     * @return array 坐标点 x,y
     */
    protected function getCropPosition( $oriWidth, $oriHeight, $cutWidth, $cutHeight ){
        if ( $oriWidth > $cutWidth ) {
            $x = floor(($oriWidth-$cutWidth) / 2);
            $y = 0;
        } elseif ( $oriHeight > $cutHeight){
            $x = 0;
            $y = floor(($oriHeight - $cutHeight) / 2 );
        }else{
            $x = 0;
            $y = 0;
        }
        return [ "x" => $x , "y" => $y ];
    }

    /**
     * 获取拼贴位置
     * @param $oriWidth int 原图片的宽
     * @param $oriHeight int 原图片的高
     * @param $holdWidth int 容器图片宽
     * @param $holdHeight int 溶剂图片高
     * @return array 坐标点 x,y
     */
    protected function getCompositePosition( $oriWidth, $oriHeight, $holdWidth, $holdHeight ){
        if ( $holdWidth > $oriWidth ) {
            $x = floor(($holdWidth-$oriWidth) / 2);
            $y = 0;
        } elseif ( $holdHeight > $oriHeight){
            $x = 0;
            $y = floor(($holdHeight - $oriHeight) / 2 );
        }else{
            $x = 0;
            $y = 0;
        }
        return [ "x" => $x , "y" => $y ];
    }

    protected function checkRatio ( $width, $height, $ratioWidth, $ratioHeight ) {
        if ( !( $height > 0 && $ratioHeight > 0) ) {
            return false;
        }
        if ( ($width / $height) == ($ratioWidth / $ratioHeight) ) {
            return false;
        }
        return true;
    }
}