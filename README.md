媒体处理相关
==============
媒体处理

### blurOut 不足比例的视频/图片虚化
使用此功能处理图片需要安装 php-imagick 扩展
````bash
apt install php-imagick -y
````
使用此功能处理视频需要安装 ffmpeg
````bash
apt install ffmpeg -y
````
````php
//处理为 1:1 的虚化图片
(new Lit\Media\BlurOut("in.jpg"))->exec(1,1,"./out.jpg");

//处理为 4:5 的虚化视频
(new Lit\Media\BlurOut("in.mp4"))->exec(4,5,"./out.mp4");
````