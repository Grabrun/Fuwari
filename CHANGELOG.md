# Changelog

本主题遵循[语义化版本 2.0.0](https://semver.org/lang/zh-CN/)：
`主版本号.次版本号.修订号[-预发布版本]`。预发布版本（beta/rc）不代表最终 API 稳定。

## [0.2.0-beta.2] - 2026-09-16

> Bug 修复版：修复文章缩略图随机 API 配置后前台显示失败的 URL 拼接缺陷。

### 修复

- **修复**：文章缩略图随机 API（如 `https://images.grabrun.top/api/v1/random?category=acg&type=redirect`）配置后前台显示失败——原实现在 `boxmoe_article_thumbnail_src()` 返回值后无条件以 `?` 追加防缓存参数，而随机 API URL 本身已含查询参数，导致 URL 损坏（`...?category=acg&type=redirect?id1`）。
  - `boxmoe_article_thumbnail_src()` 新增可选参数 `$cache_buster`，在函数内统一追加：URL 已含 `?` 时用 `&` 连接，否则用 `?`。
  - `page/template/blog-list.php`、`core/widgets/widget-postlist.php` 改为通过函数参数传入防缓存串，移除调用处的裸 `?` 拼接。
  - 覆盖场景：随机图 API（含查询参数）、自定义 `_thumbnail` 外链、文章内容首图、本地随机图、默认图。
- 验证：URL 拼接逻辑模拟通过（API 含参 → `&id{ID}`；本地图 → `?id{ID}`）；全仓复查无同类裸 `?` 拼接残留。

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
