

**欢迎使用 Fuwari（浮絮）WordPress 主题**
<br>当前分支：**0.8.0-beta.6**（独立分支项目 · 语义化版本 SemVer 2.0.0 · 版本从 0.0.0 起算）

**版本说明**
<br>`13.12-master` 为上游源码基线（git 分支 `13.12-master`，tag `13.12`）。本分支为独立项目，从 0.0.0 起算：`0.1.0-beta.1` 为架构与安全深度优化首版；`0.2.0-beta.1` 为破坏性变更版，移除对 erphpdown 付费插件的硬耦合：
- 移除会员/VIP 订阅、余额/充值（卡密/在线支付）、订单/充值/消费记录等全部依赖 erphpdown 的功能
- 用户中心仅保留主题自有功能：个人资料、头像、密码、收藏、评论
- 删除插件未安装时的"会员中心必须配套 Erphpdown 插件/破解版购买"提示
- 保留 0.1.0 的模块加载集中化、通知事件化与全部安全加固（详见 CHANGELOG.md）

`0.3.0-beta.1` 为内部标识统一重构（破坏性变更）：
- 函数/常量/选项 id/AJAX nonce/事件钩子/翻译域/CSS 类等内部标识由 `boxmoe_*` 全部更名为 `fuwari_*`
- 选项 id 提供旧键读取回退与激活迁移，升级站点设置不丢失
- 作者更新为**拿完西瓜跑**；仅前台页尾保留对原项目（LoliMeow/boxmoe.com）的说明
- 移除对原作者服务器的版本更新检查（独立分支不再提示原版更新）

`0.4.0-beta.1` 为加载速度优化版（非破坏性）：
- 消除 2 处 CSS `@import` 渲染阻塞（fancybox/font-awesome 改并行加载）
- 字体子集化：alimama 圆体 2352KB → 1292KB（常用字保留，生僻字回退系统字体）
- 删除未引用死资源 dance.gif（1109KB）；jQuery 默认不加载（主题脚本零 jQuery 依赖，保留开关）
- comments.js 仅文章/页面加载；第三方域名预连接 preconnect

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


---

## 版权与许可

- **主题**：Fuwari（浮絮）WordPress Theme
- **作者**：拿完西瓜跑（Grabrun）
- **许可**：GPLv3 or later（详见仓库根目录 LICENSE，GPL-3.0 全文）
- **衍生声明**：本主题源自 [LoliMeow](https://www.boxmoe.com) 项目（Boxmoe），各源文件保留原项目版权说明（`@link https://www.boxmoe.com`）；内置 Options Framework 组件版权归其原作者（Devin Price / WP Theming，GPL-2.0+）。
