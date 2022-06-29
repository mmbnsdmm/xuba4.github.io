# initYii

#### Description
yii2 模板

#### Software Architecture
Software architecture description

#### Installation

1. download app `git clone https://gitee.com/mmbnsdmm/xuba4.git`
2. `cd path/to/dirYii2`
3. `php init`
4. `composer install`
5. set config in `common/config/main-local.php`

#### 伪静态

##### nginx

```allykeynamelanguage
location / {
    try_files $uri $uri/ /index.php$is_args$args;
}
location /app_dir {
    try_files $uri $uri/ /app_dir/index.php$is_args$args;
}

location / 
{
     index  index.html index.htm index.php;
     if (!-e $request_filename) {
           rewrite ^/(.*)$ /index.php?s=$1 last;
           rewrite ^/app_dir(.*)$ /app_dir/index.php?s=$1 last;
           break;
     }
     #autoindex  on;
}

location / {
    try_files $uri $uri/ /index.php$is_args$args;
}
location /api {
    try_files $uri $uri/ /api/index.php$is_args$args;
}
location /admin {
    try_files $uri $uri/ /admin/index.php$is_args$args;
}
```
    
    
##### apache
    
    Options +FollowSymLinks
    IndexIgnore */*
    RewriteEngine on
    
    # if a directory or a file exists, use it directly
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    
    # otherwise forward it to index.php
    RewriteRule . index.php

#### Gitee Feature

1.  You can use Readme\_XXX.md to support different languages, such as Readme\_en.md, Readme\_zh.md
2.  Gitee blog [blog.gitee.com](https://blog.gitee.com)
3.  Explore open source project [https://gitee.com/explore](https://gitee.com/explore)
4.  The most valuable open source project [GVP](https://gitee.com/gvp)
5.  The manual of Gitee [https://gitee.com/help](https://gitee.com/help)
6.  The most popular members  [https://gitee.com/gitee-stars/](https://gitee.com/gitee-stars/)
