媒体处理相关
==============
媒体处理

### 环境依赖
使用此功能处理图片需要安装 php-imagick 扩展
````bash
apt install php-imagick -y
````
使用此功能处理视频需要安装 ffmpeg, 视频处理需要更大的内存运行程序.
````bash
apt install ffmpeg -y
````

### Demo
````
https://github.com/ghostong/help/blob/master/LitMedia/demo.php
````

### blurOut 不足比例的视频/图片虚化
````php
//处理为 1:1 的虚化图片
(new Lit\Media\BlurOut("in.jpg"))->exec(1,1,"./out.jpg");
````
<img src="https://raw.githubusercontent.com/ghostong/help/master/LitMedia/image/blurOutImage.jpg" width="300" alt="图片虚化效果">


````php
//处理为 4:5 的虚化视频
(new Lit\Media\BlurOut("in.mp4"))->exec(4,5,"./out.mp4");
````
<img src="https://raw.githubusercontent.com/ghostong/help/master/LitMedia/image/blurOutVideo.jpg" width="300"  alt="视频虚化效果">


### 常见问题
##### The process has been signaled with signal "9"
````
可能是主机内存不足, 进程被杀死.
````
