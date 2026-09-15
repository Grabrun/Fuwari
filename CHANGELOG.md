# Changelog

本主题遵循[语义化版本 2.0.0](https://semver.org/lang/zh-CN/)：
`主版本号.次版本号.修订号[-预发布版本]`。预发布版本（beta/rc）不代表最终 API 稳定。

## [0.1.0] - 2026-09-17

> **正式版发布（stable）**：基于 0.1.0-beta.1 代码状态发布，无代码变更，仅移除预发布标识。

### 正式版说明

- 基于 0.1.0-beta.1 状态发布，移除 `beta.1` 预发布标识。
- 安装方式不变：下载 `Fuwari-v0.1.0-*.zip` 上传至 WordPress 后台启用。

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
