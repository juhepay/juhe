##安装步骤
1.下载或克隆项目，进入项目根目录执行,等待框架安装

``composer install``

2.将.env.example修改为.env,并进行相关配置,然后在项目根目录执行

``php artisan key:generate``

3.手动创建数据库,执行迁移数据库表结构和数据

``php artisan migrate:refresh --seed``

4.需求php扩展``xlswriter``

win安装： https://pecl.php.net/package/xlswriter，

下载对应版本得的dll复制到目录，修改php配置文件``extension = xlswriter``

linux安装：

``pecl install xlswriter``
