# Changelog

本主题遵循[语义化版本 2.0.0](https://semver.org/lang/zh-CN/)：
`主版本号.次版本号.修订号[-预发布版本]`。预发布版本（beta/rc）不代表最终 API 稳定。

## [0.4.0-beta.9] - 2026-09-16

> 本项目版权声明加入：作者 **拿完西瓜跑（Grabrun）**，许可 GPL-3.0-or-later；全仓 PHP 文件头统一标注本项目版权，原项目与第三方版权声明保持原样。

### 变更

- **全仓 72 个 PHP 文件版权声明核查与补齐**：
  - 66 个主题原创文件头统一追加 `@copyright 2026 拿完西瓜跑 (Grabrun)` 与 `@license GPL-3.0-or-later`（原 `@link https://www.boxmoe.com` 原项目声明保留）；补齐 8 个此前无标准头的文件（6 个 core/module + page/p-go、page/p-goto，后者于模板头内追加，不破坏 Template Name）。
  - 6 个第三方 Options Framework 文件保留原作者版权（Devin Price / WP Theming / GPL-2.0+），未添加本项目署名。
- **主题头 Author 更新**：`Author:拿完西瓜跑 (Grabrun)`（根 style.css 与 assets/css/style.css）。
- **LICENSE**：顶部新增本项目版权声明段（Copyright (C) 2026 拿完西瓜跑 (Grabrun) + GPLv3 说明 + LoliMeow 衍生声明），GPL-3.0 全文保留完整。
- **README**：新增「版权与许可」章节。
- 顺带修复 0.4.0-beta.8 引入的头部损坏：`assets/css/style.css` 的 Author 值被 License 行插入吞掉（Author 变空、License URI 尾部被拼接），本次已还原。

### 验证

- 两个 style.css 主题头字段完整（Theme Name/URI/Description/Author/Version/License）。
- 66 原创文件含 Grabrun 版权、6 第三方保留原版权；冒烟通过。

## [0.4.0-beta.8] - 2026-09-16

> 版权合规与页尾文案：项目明确采用 **GPLv3**；原项目（LoliMeow / Boxmoe）版权声明全量保留核查通过；前台页尾更新为 "Theme by Fuwari・基于 Boxmoe 的 LoliMeow 项目"。

### 变更

- **新增 `LICENSE`（GPL-3.0 全文，35KB，取自 gnu.org）**；根目录与 `assets/css/style.css` 主题头补充 `License: GPLv3 or later` + `License URI`。
- **版权声明全量核查（72 个 PHP 文件）**：
  - 66 个 LoliMeow 原创文件头 `@link https://www.boxmoe.com` 全部保留；补齐遗漏的 `core/panel/options-framework-js.php`。
  - 6 个第三方 Options Framework 文件保留原作者版权（Devin Price / WP Theming / GPL-2.0+ / wptheming.com），未改动。
- **页尾文案**：`Theme by Fuwari・基于 Boxmoe 的 LoliMeow 项目`（"・"为日文中点；Boxmoe 与 LoliMeow 链接指向原项目 boxmoe.com）。

### 说明

- 主题以 GPLv3 发布；其中内置的 Options Framework（GPL-2.0+）为兼容许可，二者可共存分发。
- 原项目版权归属文字（文件头 @link boxmoe.com）属"保留声明"，与主题品牌名（Fuwari）并行，不冲突。

## [0.4.0-beta.7] - 2026-09-16

> 后台主题设置回归修复：0.4.0-beta.6 将缺 `type` 项默认设为 `'info'` 后，**group 结束标记（`group => 'end'`）因 `type='info'` 跳过了收尾逻辑，group 容器未闭合、`group_opened` 状态泄漏**——导致"用户设置" tab 之后的所有 tab（社交图标/静态加速/系统优化/通知设置/关于主题）HTML 结构错乱，点击后右侧内容不显示。

### 根因链

- 原始设计：group end 项**不带 `type`**，遍历时 `$value['type']` 为 null → 进入收尾分支（`type != heading && != info`）→ 关闭 group 容器并复位 `group_opened`。
- 0.4.0-beta.6 为消除 PHP 8 `Undefined array key "type"` 警告，把缺 type 项默认成 `'info'` → group end 项走 `info` 分支，**跳过收尾分支** → group 不关闭。
- 该回归由 div 开闭配对模拟确认：旧逻辑最终 depth=1（多一个未闭合 div）、`group_opened=True`（泄漏）；新逻辑 depth=0、状态正常。

### 修复

- `class-options-interface.php`：group 结束标记的闭合逻辑**独立于 type 条件**单独执行——无论 group end 项 type 为 `info`/缺失/其他，均关闭 group 容器并复位 `group_opened`；普通项的 div 收尾行为完全不变（已逐场景核对）。

### 验证

- div 开闭配对模拟：124 个选项项、24 组 group start/end、11 个 tab 全部配对，最终 depth=0。
- 静态冒烟通过。
- 目标环境：更新后后台主题设置各 tab 右侧内容恢复正常；`Undefined array key "type"` 警告仍由 0.4.0-beta.6 的防御保持消除。

## [0.4.0-beta.6] - 2026-09-16

> 后台主题设置界面修复：`Warning: Undefined array key "type"`（`class-options-interface.php`）——用户设置分组的 `group => 'end'` 闭合标记项缺 `type` 键（上游遗留，PHP 8 严格报错）。

### 修复

- **数据根治**：`core/panel/settings/set-user.php` 的 group 结束项补 `'type' => 'info'`（全仓 124 个选项项精确扫描，仅此 1 处缺 type）。
- **代码防御**：`class-options-interface.php`
  - `optionsframework_tabs()`：无 `type` 的项直接跳过（tab 只关心 heading）；
  - `optionsframework_fields()`：无 `type` 的项默认 `'info'`（不可 continue——需保留 group 结束闭合逻辑）。
- admin 侧（`class-options-framework-admin.php`）经核查本就有 `isset` 保护，不受影响。

### 验证

- 全仓选项项扫描确认无其他缺 type 项；静态冒烟通过。
- 目标环境：后台主题设置页警告应消失；若仍出现，请提供完整行号（当前修复覆盖全部无保护读取点）。

## [0.4.0-beta.5] - 2026-09-16

> 页尾文案调整：前台页尾版权行 "Theme by Fuwari（浮絮） · 源自 LoliMeow 项目" 中移除中文名"（浮絮）"，仅保留英文 "Theme by Fuwari · 源自 LoliMeow 项目"（原项目说明链接保留）。登录/注册页脚与主题头品牌全称不受影响。

## [0.4.0-beta.4] - 2026-09-16

> 侧栏兼容修复：0.3.0 改名时误改 search/archive 两个 widget 的 id_base（WP 数据库存储键），升级用户旧侧栏配置失效、对应 widget 从前台消失（其余 6 个 widget 未变故仍显示，表现为"侧栏显示了一部分 / 搜索不见了"）。

### 根因

- 0.3.0 内部标识改名把 `widget-search` 的 id_base 从 `boxmoe_widget_search` 改为 `fuwari_widget_search`、`widget-archive` 从 `boxmoe_widget_archive` 改为 `fuwari_widget_archive`。
- WP_Widget 的 id_base 同时是 `widget_{id_base}` 选项与 `sidebars_widgets` 引用的存储键：id_base 改变后，数据库中既有实例（`boxmoe_widget_search-1` 等）找不到对应 widget 类，前台 `dynamic_sidebar()` 渲染时被跳过——**旧配置数据仍在数据库，只是不再被识别**。

### 修复

- 恢复两个 widget 的 id_base 为 0.2.0 时代值：`boxmoe_widget_search` / `boxmoe_widget_archive`（classname 不变，前台样式不受影响）。
- 新增一次性迁移 `fuwari_migrate_widget_storage()`（挂 `init`，早于 `widgets_init`）：把 0.3.0+ 期间产生的新键配置（`fuwari_widget_*` 选项与侧栏引用）合并迁移回旧键，幂等、新旧配置均不丢失。

### 验证

- 静态冒烟通过；id_base 恢复确认；迁移函数对旧键数据/新键数据/混合情况均安全（重复执行无副作用）。
- 目标环境更新后：既有侧栏配置自动恢复显示；若仍不显示，检查「外观-小工具」中 widget 是否被手动删除。

## [0.4.0-beta.3] - 2026-09-16

> 首页致命修复：侧栏组件 `widget_ui_loader` 因变量作用域问题在 `widgets_init` 时取到 null，导致 foreach 警告且侧栏 8 个组件全部未注册。

### 根因

0.1.0 架构重构将模块改为在 `fuwari_load_modules()` 函数内 `require_once` 加载：`widget-set.php` 顶层定义的 `$widgets` 因此成为**函数局部变量**（非全局）；`widget_ui_loader()` 内的 `global $widgets` 在 `widgets_init` 钩子触发时取到 null → `foreach()` 报错 + 所有侧栏 widget 未注册。

### 修复

- `widget_ui_loader()` 不再依赖全局变量：widget 列表内联于函数内，并加 `class_exists()` 防御后再 `register_widget()`（8 个 widget 类文件存在性已验证）。
- 全仓精确扫描确认：除本处外无其他"模块顶层变量 + 函数内 `global` 引用"同类模式。

### 验证

- 静态冒烟 `.verify/php_smoke.py` 通过；修复后 `widget-set.php` 无 `global` 语句（仅注释说明）；`add_action('widgets_init', 'widget_ui_loader')` 钩子保留。
- 目标环境需刷新页面确认：警告消失、双栏布局侧栏组件（广告/文章列表/评论/分类/归档/标签/用户信息/搜索）正常注册。

## [0.4.0-beta.2] - 2026-09-16

> 深度审计修复版：全项目审计（函数/钩子/资源/安全/回归五维）发现并修复 2 处缺陷，其余全部验证通过。

### 修复

- **安全（CSRF）**：`fuwari_delete_favorite`（用户中心删除收藏）后端补充 `fuwari_ajax_nonce` 校验——前端 `user_center.js` 本已随请求发送 nonce，后端此前未验证；与 `post_favorite`/`post_like`/用户中心其他端点对齐。
- **功能（移动端 Banner 高度设置失效）**：`fun-basis.php` 调用键 `fuwari_banner_height_mobile` 与设置面板键 `fuwari_banner_height_m` 不一致（上游遗留，改名保持了两者不一致），移动端高度设置一直回退默认 480。调用处改为 `fuwari_banner_height_m`，后台设置即生效。

### 审计通过项（0.1.0 → 0.4.0 全轮回归）

- **函数完整性**：108 个 PHP 函数定义与 154 处调用 100% 一致；所有 `add_action`/`add_filter` 字符串引用函数均有定义（0.3.0 改名无任何悬挂引用）。
- **资源完整性**：模板引用图片 0 缺失；enqueue 资源全存在；fontawesome 字体 5 格式齐全；0.4.0 性能优化（fancybox/font-awesome CSS enqueue、comments 条件加载、jquery 开关、子集字体）功能确认正常。
- **安全链**：头像上传（登录+nonce+finfo 真实 MIME+扩展名映射+1MB）；post_like/post_favorite/用户中心/SMTP/评论/登录/注册/重置密码全部有 nonce；SQL 无拼接（absint/sanitize）；输出无直接回显用户输入；无 eval（p-go/p-goto 的 "eval(" 为攻击特征拦截防护）；无 unserialize。
- **0.2.0 回归**：erphpdown/vip/充值/订单零残留。
- **版本一致性**：style.css / README / CHANGELOG 全部对齐。

### 已知遗留（非阻断，记录不修复）

- `number.svg`（widget 序号背景）、`pattern/*.svg`（纹理装饰，模板零使用）为上游缺失的视觉级资源，无功能影响。
- 自定义 AJAX 登录/注册端点无独立速率限制（依赖 WP 核心登录保护或第三方插件）。

### 验证

- 静态冒烟 `.verify/php_smoke.py` 通过；两处修复均经引用一致性复查。

## [0.4.0-beta.1] - 2026-09-16

> 加载速度优化版（非破坏性）：消除渲染阻塞、削减首屏体积与请求数，不改变任何功能与安全行为。

### 性能优化

- **CSS `@import` 消除（渲染阻塞修复）**：`style.css` 内 `@import url(fancybox.min.css)` 与 `@import url(font-awesome.min.css)`（浏览器串行下载、阻塞渲染）移除，改为 `wp_enqueue_style` 并行加载（顺序 font-awesome → fancybox → theme.min → style 保持不变）。
- **字体子集化（最大体积收益）**：主题字体 `alimama.woff2`（2352KB，GB2312 全字符集）子集化为 `alimama-common.woff2`（1292KB，GB2312 一级 3755 常用字 + ASCII + 常用标点，经 fonttools 校验字形齐全）。**生僻字/二级汉字自动回退到系统字体**（Microsoft JhengHei/雅黑，font-family 备选已存在），不影响功能。
- **删除未引用死资源**：`assets/images/top/dance.gif`（1109KB）无任何代码引用，删除。
- **jQuery 默认不加载**：审计确认主题全部脚本（fuwari.js / comments.js / theme.min.js / lib.min.js / sakura.js / user_center.js）均为原生 JS，`jQuery(` 调用 0 次（fancybox 为无 jQuery 版）；`fuwari_jquery_switch` 默认值由开启改为关闭（新装站点每页省约 85KB）。**保留后台开关**，第三方插件/子主题需要时可开启。已有站点已保存的配置不受影响。
- **comments.js 按需加载**：仅在 `is_singular()`（文章/页面，存在评论表单）时加载，减少首页/列表/归档页 1 个 JS 请求。脚本本身带 `#commentform` 判空保护，功能不变。
- **第三方域名预连接**：`wp_resource_hints` 对 `gravatar.com`（头像）、`boxmoe.com`（页尾链接）及配置的 `wpa.qq.com`（QQ 社交）添加 `preconnect`，提前建连降低首屏延迟。

### 验证

- 静态冒烟 `.verify/php_smoke.py`：72 个 PHP 文件通过；CSS `@import` 残留 0；旧字体/死资源引用残留 0。
- 子集字体经 fonttools 校验：3984 字形，常用字/ASCII/标点全部命中 cmap。
- 本机无 PHP CLI 与浏览器，真实站点走查（前台样式与图标、文章页评论、暗黑/亮色切换、图片灯箱 fancybox、登录注册）需在目标环境执行。

## [0.3.0-beta.1] - 2026-09-16

> 破坏性变更版：内部标识统一重构（`boxmoe_*` → `fuwari_*`），作者更新，移除对原作者服务器的依赖。

### 破坏性变更（Breaking Changes）

- **函数/常量/事件钩子改名**：全仓 `boxmoe_*` → `fuwari_*`（函数名、`get_fuwari`、`FUWARI_THEME_VERSION`、`fuwari_modules` filter、`fuwari_comment_notify`/`fuwari_user_register_notify` 钩子、AJAX nonce `fuwari_ajax_nonce`、翻译域 `'fuwari'`、`ui_fuwari_com`）。
- **选项 id 改名与迁移**：设置项 id `boxmoe_*` → `fuwari_*`；`get_fuwari` 读取时回退旧键（`fuwari_x` 未命中时查 `boxmoe_x`），主题激活（`after_switch_theme`）时一次性复制旧键为新键——升级站点设置不丢失。
- **CSS 类/ID 与前端选择器**：`.boxmoe_*`/`.boxmoe-*` → `.fuwari_*`/`.fuwari-*`，模板、CSS、JS 选择器同步；`assets/js/boxmoe.js` → `fuwari.js`（enqueue 同步）。
- **作者更新**：`Author: 拿完西瓜跑`（移除 Author URI）；后台页脚 "Theme by 拿完西瓜跑"；登录/注册页脚改为 "Fuwari（浮絮） powered by WordPress"；广告 widget 默认内容清空；SEO 默认关键词移除 boxmoe。
- **移除原作者版本更新检查**：后台不再请求 `doc.boxmoe.com/wp-json/themes/v1/version/lolimeow`，改为显示本分支版本（`FUWARI_THEME_VERSION`）。
- **仅前台页尾保留原项目说明**：页尾版权行输出 "Theme by Fuwari（浮絮） · 源自 LoliMeow 项目"（链接 boxmoe.com）。

### 兼容与安全

- SMTP 加密前缀由 `boxmoe_enc:`（11 字符）改为 `fuwari_enc:`（10 字符）；解密同时识别新旧前缀（修复前缀长度变化导致的 `substr` 截取错位隐患）。
- 保留：`boxmoe.com` 域名仅出现于代码注释、跳转白名单（p-go/p-goto 功能数据）、登录页背景图 API 默认值与页尾说明；历史 CHANGELOG/README 中原名作为版本溯源。

### 验证

- 静态冒烟 `.verify/php_smoke.py`：72 个 PHP 文件通过；`node --check` 通过 fuwari.js / user_center.js / comments.js / quicktags.js / tinymce-emoji.js。
- 非 URL `boxmoe` 残留复查：仅兼容层 6 处（选项迁移、SMTP 旧前缀识别、旧键回退），全部为有意保留。
- 本机无 PHP CLI，`php -l` 与真实站点走查（设置面板保存、旧站点升级选项保留、SMTP 旧密码解密、用户中心、前台页尾）需在目标环境执行。

## [0.2.0-beta.2] - 2026-09-16

> Bug 修复版：修复文章缩略图随机 API 配置后前台显示失败的 URL 拼接缺陷。

### 修复

- **修复**：文章缩略图随机 API（如 `https://images.grabrun.top/api/v1/random?category=acg&type=redirect`）配置后前台显示失败——原实现在 `boxmoe_article_thumbnail_src()` 返回值后无条件以 `?` 追加防缓存参数，而随机 API URL 本身已含查询参数，导致 URL 损坏（`...?category=acg&type=redirect?id1`）。
  - `boxmoe_article_thumbnail_src()` 新增可选参数 `$cache_buster`，在函数内统一追加：URL 已含 `?` 时用 `&` 连接，否则用 `?`。
  - `page/template/blog-list.php`、`core/widgets/widget-postlist.php` 改为通过函数参数传入防缓存串，移除调用处的裸 `?` 拼接。
  - 覆盖场景：随机图 API（含查询参数）、自定义 `_thumbnail` 外链、文章内容首图、本地随机图、默认图。
- 验证：URL 拼接逻辑模拟通过（API 含参 → `&id{ID}`；本地图 → `?id{ID}`）；全仓复查无同类裸 `?` 拼接残留。
- **工具修复**：`.verify/php_smoke.py` 仓库根路径由硬编码 `lolimeow-master` 改为相对脚本位置解析，目录更名 `fuwari` 后冒烟脚本恢复真实扫描（此前因路径失效出现"检查 0 个文件"的假通过，已修复并复跑 72 文件通过）。

## [0.2.0-beta.1] - 2026-09-15

> 破坏性变更版：移除对 erphpdown 付费插件的全部硬耦合（会员/VIP/充值/订单体系），主题不再需要该插件即可运行用户中心。`0.x` 阶段破坏性变更递增次版本（0.1.0 → 0.2.0），仍为预发布版。

### 破坏性变更（Breaking Changes）

- **移除会员中心插件依赖**：`page/p-user_center.php` 不再判断/调用 `mobantu_erphp_menu()`；删除插件未启用时的提示页与"破解版插件购买"广告链接（原 else 分支整体移除）。
- **移除会员/VIP 体系**：删除 VIP 订阅页 `user-vip.php`、`fun-user-center.php` 中的 `handle_vip_upgrade()`（`upgrade_vip` AJAX，含余额扣减 `erphpSetUserMoneyXiaoFei`、会员写入 `userPayMemberSetData`、分销 `EPD::doAff`、优惠码 `$_SESSION['erphp_promo_code']`）；用户中心不再展示会员等级/到期时间。
- **移除充值体系**：删除卡密充值 `boxmoe_form_money_card()`（`checkDoCardResult`）、在线充值 `boxmoe_form_money_online()`（`constant("erphpdown")` 拼 20+ 支付渠道 URL、`ERPHPDOWN_ECPAY_URL`/`ERPHPDOWN_NEWEBPAY_URL`、`plugin_check_ecpay/newebpay`、微信 OAuth）；删除充值页 `user-money.php`、充值记录页 `user-recharge.php`；删除主题设置项 `boxmoe_czcard_src` 及 `fun-user.php` 中 `boxmoe_czcard_src()`。
- **移除订单体系**：删除订单管理页 `user-order.php`（直查 `$wpdb->icealipay` 表、`constant("erphpdown").'download.php'` 下载链接）；删除消费记录页 `user-consumption.php`（空壳文件）。
- **用户中心路由精简**：`?items=` 仅保留 `home/collect/comment/password` 四项；`user-nav.php` 移除订单/会员/资产/充值 4 个入口；删除依赖插件的 `mobantu_paging` 分页函数与首页"积分充值"入口、`ice_ali_money_checkin` 签到（`erphpdown_check_checkin`）。
- **前端 JS 精简**：`assets/js/user_center.js` 删除卡密充值、在线充值、每日签到、VIP 升级模态框四段逻辑，保留资料/密码/头像/收藏。
- **残留清理**：删除 `fun-article.php` 中已注释的 `erphpdownbuy_replace` 残骸；删除 `assets/css/style.css` 中 `.single-content .erphpdown` 样式；清理 `options-framework-js.php` 中对已删选项 `czcard_src`/`user_banner_src` 的死引用。

### 保留（未受影响）

- 用户中心自有功能完整保留：个人资料编辑、密码修改、头像上传、我的收藏（`user_favorites`）、我的评论。
- 评论 session（`init_comment_session`）保留——它服务评论者信息记忆，与 VIP 优惠码无关。
- 0.1.0 建立的架构与安全加固全部保留：模块加载集中化、通知事件化、头像 MIME 白名单、AJAX nonce、SMTP 密码加密、VIP 重放锁（其唯一消费方已随 `handle_vip_upgrade` 删除）、`get_boxmoe` 静态缓存。

### 主题品牌更名（2026-09-16）

- 主题名由 **LoliMeow（洛丽喵）** 更名为 **Fuwari（浮絮）**，全称 **Fuwari · 浮絮**。
- 更新：`style.css` Theme Name/Description（根目录与 assets/css）、README 标题、后台页脚 "Theme by" 文案、全仓文件头 `@package` 标识、SEO 默认关键词。
- 保留：历史 CHANGELOG/README 中原名作为版本溯源记录；作者信息与 boxmoe.com 版权链接不变；作者端主题更新检查 URL 不变；主题目录名 `lolimeow-master` 不变（避免影响既有部署路径）。
- 追加（2026-09-16）：主题目录与 zip 安装前缀统一为 `fuwari`（仓库目录 `lolimeow-master` → `fuwari`，发布包内目录结构为 `fuwari/`）。

### 回归审计修复（2026-09-15，随本版本一并交付）

深度回归审计发现并修复两处会导致用户中心不可用的悬空引用：

- **修复**：`page/template/user-home.php` 仍调用已删除的 `boxmoe_user_money()` / `boxmoe_user_moneyto()`（未定义函数 → PHP fatal）。已删除"积分余额/累计消耗"两个 erphp 卡片及其充值入口链接，保留收藏/评论卡片与头像、资料表单。
- **修复**：删除卡片时误删了 `<div class="row gx-4">` 容器起始标签（导致网格布局失效），已补回。
- **审计覆盖**：全仓复查无 `items=order/vip/money/recharge/consumption` 残留链接、无 `get_option('erphp/ice_')` 调用、无已删函数调用方；模板 div 闭合平衡；7 个 AJAX 端点（点赞/收藏/取消收藏/头像/资料/密码）与 `ajax_object.nonce`、`showToast`（boxmoe.js）依赖链完整；`fun-user-center.php` 删除边界精确（0.1.0 保留区完整）；设置面板 group 闭合、`options-framework-js.php` 死引用清理完成。

### 验证

- 静态冒烟 `.verify/php_smoke.py` 全仓通过；`user_center.js` 通过 `node --check`。
- 本机无 PHP CLI，`php -l` 与真实站点走查（用户中心 4 个页面、注册/登录/评论/收藏）仍需在目标环境执行。

## [0.1.0-beta.1] - 2026-09-15

> 独立分支项目首版：版本从 0.0.0 起算（基线为 LoliMeow 13.12 源码），本版本为架构与安全深度优化的第一个测试版。主版本为 0 表示初始开发阶段，`0.1.0` 为第一个功能版本，`beta.1` 为预发布。

### 安全（Security）

- **高危**：修复头像上传任意扩展名漏洞（`fun-user-center.php`）——文件扩展名不再取自用户文件名，改为基于文件真实内容（finfo / getimagesize）的 MIME 白名单映射（jpeg/png/gif → .jpg/.png/.gif），杜绝上传可执行文件。
- **中危**：为涉及资金的 AJAX 端点补齐 nonce 校验：卡密充值（`boxmoe_form_money_card`）、在线充值（`boxmoe_form_money_online`），前端 `user_center.js` 同步携带 nonce。
- **低危**：为点赞（`post_like`）、收藏（`post_favorite`）AJAX 补齐 nonce 校验，前端 `boxmoe.js` 同步携带 nonce。
- **中危**：SMTP 密码不再明文存储（`fun-smtp.php`），改用 `wp_salt()` 派生密钥 + AES-256-CBC 加密（openssl 可用时）；设置页不再回显密码，留空表示保持原密码；同时为 SMTP 设置保存与测试邮件表单补齐 CSRF nonce。
- **中危**：机器人 webhook 请求移除 `CURLOPT_SSL_VERIFYHOST/PEER = 0`（`fun-msg.php`），恢复默认证书校验。
- **修复**：`get_client_ip()` 不再无条件信任 `X-Forwarded-For`，改为 REMOTE_ADDR 优先、代理头仅作参考且校验 IP 格式，防伪造污染登录记录。
- **修复**：移除 `fun-optimize.php` 中危险的 `remove_action('wp_head', 'wp_print_styles')`（会连带压制整站样式输出）；保留其余按 handle 精准 dequeue。
- **修复**：中文用户名处理不再在 AJAX 流程中临时 `remove/add_filter('sanitize_user')`，改为主题初始化时一次性替换默认清理回调（`fun-user.php`），消除竞态。
- **加固**：短代码 `pwd_protected_post` 密码比较改用 `hash_equals()` 常量时间比较（`fun-shortcode.php`）。
- **加固**：VIP 升级（`upgrade_vip`）增加 5 秒 transient 短锁，防重复提交重复扣款。
- **修复**：Google 推送改为正确的 sitemap ping 语义（GET `google.com/ping?sitemap=`），移除原错误的 POST 调用；360 推送升级为 HTTPS 传输（`fun-seo.php`）。
- **修复**：外链中转页输出转义——`p-go.php` meta refresh 改用 `esc_attr`；`p-goto.php` JS 上下文改用 `esc_js`、属性改用 `esc_attr(esc_url())`、文本改用 `esc_html`（修复 HTML 实体在 script 内被解码还原的 XSS 隐患）。
- **修复**：`boxmoe_disable_autosave` 钩子从 `wp_enqueue_scripts` 修正为 `admin_enqueue_scripts`（原钩子在前台不生效）。

### 架构（Architecture）

- **模块加载集中化**（`functions.php`）：14 个模块改为 `BOXMOE_MODULES` 清单 + 统一循环加载，暴露 `boxmoe_modules` filter 供子主题/扩展增删模块；新增 `BOXMOE_THEME_VERSION` 常量（取自 style.css）。
- **通知事件解耦**（`fun-msg.php` / `fun-comments.php` / `fun-user.php`）：评论模块与用户模块不再直接调用消息模块函数，改为触发 `boxmoe_comment_notify` / `boxmoe_user_register_notify` 动作，由消息模块集中分发（含开关判断）。
- **修复 13.12 缺陷**：评论通知曾调用不存在的函数 `boxmoe_new_comment_notice_email`（开关开启时触发 fatal）；AJAX 与 wp_insert_comment 双路径可能重复发邮件/机器人通知——统一收敛为单一事件入口。
- **选项读取静态缓存**（`get_boxmoe`）：单次请求内选项只读一次数据库，重复读取走静态缓存。
- **死代码清理**：`p-links.php` 重复 `get_footer()`；`fun-msg.php` 不可达分支（未定义变量 $context/$opts/$msgqq）；`fun-user-center.php` 未定义变量 `$start_down2`（显式初始化，行为不变）。

### 已知限制（本版本不处理）

- 与 erphpdown 插件的强耦合保持不变（会员/支付为插件职责），主题侧仅补齐鉴权与幂等保护。
- 主题设置中的 HTML 型字段（统计代码、自定义 CSS/JS、页脚信息）按设计允许原始 HTML，使用者需自行确认内容可信。
- Google sitemap ping 接口已被 Google 官方停用，实时收录需自行接入 Google Indexing API（需 OAuth2 凭据）。
- 本机未安装 PHP CLI，未执行 `php -l` 全量语法验证（已做静态冒烟与逐文件复查）。
