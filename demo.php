<?php

include("./vendor/autoload.php");

//处理为 1:1 的虚化图片
(new Lit\Media\BlurOut("in.jpg"))->exec(1,1,"./out.jpg");

//处理为 4:5 的虚化视频
(new Lit\Media\BlurOut("in.mp4"))->exec(4,5,"./out.mp4");