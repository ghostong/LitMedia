<?php

namespace Lit\Media;

interface BlurOutInterface {

    function blurOut ( $ratioWidth, $ratioHeight, $saveFile );

    function isAllowExtensions ( $ext ) ;

}