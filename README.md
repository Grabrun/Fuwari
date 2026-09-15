

**欢迎使用 Lolimeow Wordpress主题**
<br>当前分支：**0.1.0**（正式版）（独立分支项目 · 语义化版本 SemVer 2.0.0 · 版本从 0.0.0 起算）

**版本说明**
<br>`13.12-master` 为上游源码基线（git 分支 `13.12-master`，tag `13.12`）。本分支为独立项目，从 0.0.0 起算，首版 `0.1.0-beta.1` 在保留全部功能与 UI 的前提下：
- 重构模块加载机制（集中清单 + filter 扩展点）
- 解耦评论→通知的跨模块直接调用（事件化）
- 修复头像上传任意扩展名高危漏洞、补齐资金类 AJAX nonce、SMTP 密码加密存储等安全项（详见 CHANGELOG.md）

**历史版本（13.12）说明**
<br>版本已于2025年2月20日进行迭代发布 V13.Beta版本

**版本进行了重构：**
<br>前端UI 和 后代代码都进行了优化，特别的后端的代码进行了标准化迭代，这是为了以后BOXMOE的主题都可以无缝切换

**主题官网链接：**
<br>[点击查看](https://www.boxmoe.com/468.html "点击查看")

<br>本次迭代的内容比较多，具体在博客体验前端，第一次增加了暗黑模式 并3模切换；

<br>会员中心UI也进行了迭代；

<br>前端图片也添加了懒加载；
<br>
<br>
**2/21更新**
<br>
<br>1.V13.01 添加登录可见短代码 \core\module\fun-shortcode.php 可单独替换文件
<br>2.V13.01 优化注册邮件通知 \core\module\fun-msg.php 、\core\module\fun-user.php可单独替换文件
<br>3.V13.01 删除多余无用文件 \page\page-erphpdown-user.php 可单独删除文件
<br>
**V13.02**
<br>添加侧栏搜索框 \core\widgets\widget-set.php  \core\widgets\widget-search.php
**V13.03-V13.1**
<br>修复文章形式开关问题、错别字、优化短代码、添加经典编辑器表情包、添加仪表盘评论回复表情包、添加评论内容安全防护输出、优化UI、优化注册/登录页面体验，其他bug；
