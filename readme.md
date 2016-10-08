# zhihu-daily-lumen

[zhihu-daily-vue](https://github.com/poplary/zhihu-daily-vue) 的后台

使用知乎日报 API 将数据存储到本地数据库(2015-01-01开始)。因为知乎的图片做了防盗链处理，获取图片文件存到本地提供访问。

配置完成后通过以下命令获取知乎日报的数据：

``` bash
# 获取知乎日报的数据并存储图片到本地
php artisan zhihu:crawl

# 使用 crontab 每半小时获取数据
crontab -e
30 * * * *   /usr/bin/php /www/zhihu-daily-lumen/artisan zhihu:crawl

```
