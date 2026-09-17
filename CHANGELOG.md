# Changelog

本主题遵循[语义化版本 2.0.0](https://semver.org/lang/zh-CN/)：
`主版本号.次版本号.修订号[-预发布版本]`。预发布版本（beta/rc）不代表最终 API 稳定。

## [0.8.0] - 2026-09-17

> **正式版发布（stable）**：0.8.0 系列（beta.1 ~ beta.13）完成全部开发与修复后稳定，正式发布。自 0.8.0 起为正式版本，后续新功能开发进入 0.9.0 系列。

### 正式版说明

- 基于 0.8.0-beta.13 代码状态发布，无代码变更，仅移除 `-beta.13` 预发布标识。
- 0.8.0 系列累计：全项目代码审计（安全/兼容/回归）、后台设置搜索与暗色 UI 系列修复、后台设置优化（选项缓存/设置搜索/键盘导航/分组折叠/暗色适配/定义 Schema 化）。
- 安装方式不变：下载 `Fuwari-v0.8.0-*.zip` 上传至 WordPress 后台启用。

## [0.8.0-beta.13] - 2026-09-17

> 修复暗色下**内容区顶部 tab 标题完全看不见**（用户红笔圈出"基础设置"、其他页也一样）：每个 tab 内容区顶部的标题（基础设置/Banner设置/SEO优化…）是 `.group` 容器内的 `.fuwari_tab_header`，颜色写死深色 `#101620`，而暗色规则此前只覆盖 `.section` 内的同类标题——`.group` 顶部的这个漏掉了，暗色下文字融入背景不可见。纯样式，无选项 ID / 数据结构变更。

### 修复

- **内容区顶部 tab 标题暗色不可见**：新增全局暗色规则 `#optionsframework-wrap.fuwari-dark .fuwari_tab_header {color:#e8edf4;}`（覆盖 `.group` 顶部标题；`.section` 内分组标题保持原规则）。

### 验证

- 本地渲染 HTML 确认标题元素为 `.group > .fuwari_tab_header`（`#options-group-N` 首元素）。
- CSS 括号平衡、搜索功能 jsdom 回归 21/21 通过。

## [0.8.0-beta.12] - 2026-09-17

> 继续修复侧栏当前选中菜单项：用户反馈"基础设置文字根本看不出来"——active 项白字落在亮蓝渐变背景上对比度不足。纯样式，无选项 ID / 数据结构变更。

### 修复

- **active 菜单项文字对比度**：白字在旧渐变（#4c8bff 亮蓝）上对比度不足 → 背景已改半透明暗蓝 `rgba(69,122,206,.28)`（beta.11），本次再**加粗文字**（`font-weight:600`）+ **hover/焦点保持白字**（原 hover 规则会把 active 文字变深蓝 #4371c5，在半透明蓝底上更看不清）。
- 非 active 菜单项文字 `#fff` 正常可见（生产裁剪确认）。

### 验证

- CSS 括号平衡、搜索功能 jsdom 回归 21/21 通过。

## [0.8.0-beta.11] - 2026-09-17

> 修复侧栏当前选中菜单项的视觉（用户反馈"视觉效果一坨"：active 项原为半透明蓝色渐变横条 + 左侧竖条双重效果，在暗色深蓝背景上像一条蓝色进度条）。纯样式，无选项 ID / 数据结构变更。

### 修复

- **侧栏 active 菜单项**：去掉 `linear-gradient(to right,#4c8bff,#4371c54d)` 半透明渐变横条，改为**半透明蓝底 `rgba(69,122,206,.28)` + 左侧 4px 亮蓝竖条 `#4c8bff` + 白色文字**——简洁、与暗色深蓝背景协调。

### 验证

- CSS 括号平衡、搜索功能 jsdom 回归 21/21 通过。

## [0.8.0-beta.10] - 2026-09-17

> 全站后台设置页（11 个 tab）暗色文字可读性审计：浏览器逐页抽查 + 本地渲染结构审计，确认其他页文字均可见（浅色），补全覆盖 radio 标签、上传控件提示、说明文字对比度。纯样式，无选项 ID / 数据结构变更。

### 修复

- **radio 选项标签（无 class label）暗色下无规则**：`#optionsframework label` 强制 `#d0d7de`（影响全部 radio 选项：布局/边框/一言类型/社交平台等）。
- **上传控件内提示文字（p/i）**：`.controls p/.controls i` 强制 `#d0d7de`（如"Upgrade your version of WordPress..."）。
- **说明文字（explain/description/note）对比度提亮**：`#9aa7b8 → #aab6c6`（各 tab 的"开启后填写…""建议开启…"等辅助说明更清晰）。
- 浏览器逐页抽查 Banner/SEO/文章/评论/用户/社交/静态/系统/通知 9 页：文字均可见，无"看不见"项；暗色覆盖已闭环。

### 验证

- CSS 括号平衡、搜索功能 jsdom 回归 21/21 通过。

## [0.8.0-beta.9] - 2026-09-17

> 修复"关于主题"页暗色下标题与条款文字不可见（用户截图反馈：`.section-info` 区块 `<p>` 条款文字无暗色规则，默认深灰文字落在深色卡片上几乎不可见）。纯样式，无选项 ID / 数据结构变更。

### 修复

- **关于主题（info 区块）条款文字不可见**：`#optionsframework .section-info p` 无暗色规则（默认深灰 #3c434a），强制 `color:#d0d7de`。
- **关于主题标题对比度不足**：`.section-info .heading` 提亮 `#e8edf4`；页头标题 `.header-set-title .themes-name` 提亮 `#e8edf4`。
- **条款中的链接**：`.section-info a` 强制 `#6ea8fe`（版本信息 / 更新日志 / 群链接）。

### 验证

- CSS 括号平衡、搜索功能 jsdom 回归 21/21 通过。

## [0.8.0-beta.8] - 2026-09-17

> 暗色 UI 对比度增强（生产实测：未选中态 checkbox/radio 边框深色几乎不可见、输入框边界模糊、占位符过暗）。纯样式，无选项 ID / 数据结构变更。

### 修复

- **未选中态 checkbox/radio 显示不明显**：`accent-color` 只影响勾选态，未选中边框仍为深色——补 `border:1px solid #6ea8fe` + 亮色光晕，勾选态变亮蓝 `#4c8bff`。
- **输入框/select/textarea 边界模糊**：暗色边框 `#2a3550` 提亮为 `#3e4f6e`。
- **占位符文字过暗**：`#5c6a80` → `#7b8aa0`（搜索框 / 输入框 / 文本域）。

### 验证

- CSS 括号平衡、搜索功能 jsdom 回归 21/21 通过。

## [0.8.0-beta.7] - 2026-09-17

> 后台设置页暗色 UI 视觉修复（生产浏览器实测，暗色模式截图核对）。纯样式/文案，无选项 ID / 数据结构变更。

### 修复

- **K1 侧栏主题名仍为原项目名"盒子萌主题"**：`core/panel/includes/class-options-framework-admin.php` — 暗色侧栏顶部品牌名改为「Fuwari · 浮絮」（与主题名/页尾版权一致）。
- **K2 暗色下头部"在线文档"按钮为白色（刺眼）**：`core/panel/css/optionsframework.css` — `.el-button` 原白底未做暗色适配，改为深底浅字、hover 高亮。
- **K3 暗色下滚动条为 WP 默认浅灰**：左侧菜单/内容区/菜单列表滚动条改深色（`#2a3550` thumb、hover 提亮）。
- **K4 checkbox/radio 未选中态对比度不足**：暗色下加 `accent-color:#457ace`。
- **K5 左侧菜单顶部与列表区背景色差**：`.set-main-menu`（黑）与 `.nav-tab-wrapper`（#0b121b）统一为 #0b121b，浅灰边线（border-right/bottom #e6e6e6）改深色；菜单项文字/active 项在暗色下微调。
- **K6 WP 默认上传等按钮在暗色下为白底**：`.button/.button-secondary` 暗色化。

### 验证

- 生产浏览器实测（只读，已还原）：暗色模式正常激活（body 暗色信号），截图核对侧栏/内容区/按钮/卡片配色。
- CSS 括号平衡、`php -l` 通过；搜索功能 jsdom 回归 21/21 通过。

## [0.8.0-beta.6] - 2026-09-17

> 后台设置页搜索状态残留修复：搜索过滤后手动切换 tab（搜索框已清空），切回原 tab 仍显示过滤后内容。纯修复，无选项 ID / 数据结构变更。

### 修复（`core/panel/js/options-custom.js`）

- **J1 清空搜索只恢复当前可见 group**：恢复分支原为 `$('.group:visible .section').show()`——手动切走 tab 时清空搜索只恢复了新 tab（当时可见），原 tab 的分组/叶子项残留搜索过滤状态（display:none），切回时仍显示过滤后内容。改为恢复**全部** group 的 `.section` 与 `.fuwari_group_opened`（`$('.group ...')`），再按折叠记忆处理——切回任意 tab 均完整显示。

### 验证

- jsdom + jQuery 3.7 + 真实渲染 HTML，21/21 断言通过。新增场景：搜索"边框"（基础设置 21 个组内项隐藏）→ 点击 Banner tab（搜索框清空、Banner 完整显示）→ 点击回基础设置（22/22 组内项与 logo 完整恢复、计数隐藏）。
- `node --check`、`php_smoke.py` 通过。

## [0.8.0-beta.5] - 2026-09-17

> 后台设置页搜索功能深度修复（jsdom + jQuery 3.7 真实渲染验证，15/15 用例通过）。beta.4 解决了"浏览器用旧资源"，本版解决"新版资源下搜索仍不工作"的 JS 层根因。纯修复，无选项 ID / 数据结构变更。

### 修复（`core/panel/js/options-custom.js`）

- **I1 分组内设置项不参与搜索（搜索不到组内项/分组容器被强制隐藏）**：分组内项渲染为 `.fuwari_group_opened`（无 `.section` class），原逻辑只过滤 `.section` 导致组内项既不被搜索、又在第二遍 `$g.find('.section')` 查空后被全部隐藏。现 `.fuwari_group_opened` 纳入过滤/计数/跨 tab 匹配，第二遍可见性检查含组内项，清空搜索时一并恢复。
- **I2 `$.trim` 在 jQuery 3.5+ 已移除导致搜索首行崩溃**：WordPress 自带 jQuery 3.7.x（未启用主题内置 jQuery 时），`$.trim(...)` 直接 TypeError，搜索完全失效。改为原生 `String.prototype.trim`（3 处）。
- **I3 跨 tab 自动切换 ReferenceError**：`fuwari_activate_tab` 原嵌套在 `options_framework_tabs()` 局部作用域，搜索跨 tab 调用时未定义崩溃。提升到 ready 顶层，`$group` 局部依赖改为 `$('.group')`。
- **I4 跨 tab 切换成功后计数被覆盖为"无匹配"**：切换后递归过滤已更新计数，外层循环结束仍执行空态提示覆盖。现仅在 `switched=false` 时提示无匹配。
- **I5 防递归**：跨 tab 切换后若目标 tab 仍无匹配（理论不出现），`tried` 集合防止重复切回同一 tab 造成死循环。

### 验证

- `node --check` 通过；`php_smoke.py` 通过。
- jsdom + jQuery 3.7（与 WP 内置一致）加载真实渲染 HTML（11 group / 39 section / 98 组内项）逐项断言 15/15 通过：叶子命中（LOGO）、组内项命中（边框）、跨 tab 自动切换（一言，计数不被覆盖）、清空恢复当前 tab 完整显示、分组容器显隐正确。

## [0.8.0-beta.4] - 2026-09-17

> 生产环境实测修复：后台设置页 JS/CSS 版本参数固定 `Options_Framework::VERSION`（1.9.0）且服务器 `Cache-Control: max-age=43200`，浏览器 12 小时长缓存旧资源，导致"主题文件已更新（含 beta.3 吸顶/搜索修复）但页面仍用旧版"——清服务器缓存（WP Fastest Cache）无效。纯修复，无选项 ID / 数据结构变更。

### 修复

- **H1 后台资源版本参数固定导致浏览器缓存旧版（根因）**：`core/panel/includes/class-options-framework-admin.php` — `optionsframework.css` / `options-custom.js` 的 enqueue 版本参数由固定 `Options_Framework::VERSION`（1.9.0）改为 `THEME_VERSION`（读取自 style.css）。主题升级后 `?ver` 自动变化，浏览器强制拉取最新资源，不再吃 12 小时长缓存旧版。
- **H2 quicktags.js 版本参数统一**：`core/module/fun-shortcode.php` — `html_code_button()` 的 `'1.0.0'` 固定版本改为 `THEME_VERSION`。

### 验证

- 生产实测（只读，已还原）：服务器 options-custom.js / optionsframework.css 与本地 0.8.0-beta.3 内容一致（仅换行符差异），均含吸顶（`position:sticky;top:32px`）与搜索修复逻辑；页面直接加载原始资源（后台不合并）；根因确认为浏览器长缓存旧版。本版起版本参数跟随主题版本，升级自动破缓存。
- `php_smoke.py` 静态检查通过；PHP 8.3 本地模拟渲染 11 个 group / 108 项选项完整。

## [0.8.0-beta.3] - 2026-09-17

> 后台设置页交互回归修复：左侧菜单吸顶后内容区与菜单的视觉叠加、搜索状态下切换设置项空白/弹回、小屏下搜索框随菜单整体滚走。纯修复，无选项 ID / 数据结构变更。

### 修复

- **G1 内容区与左侧菜单叠加（布局）**：`core/panel/css/optionsframework.css` — 内容区 `.metabox-holder` 原有 `margin-left:-2rem` 负边距使白底内容区左侵 32px 盖住吸顶菜单右缘（搜索框区域被覆盖）。已归零，菜单与内容区边界干净。
- **G2 搜索状态下点设置项不显示 / 被弹回（交互）**：`core/panel/js/options-custom.js` — 搜索激活后手动切换 tab 会立即按关键词过滤新 tab，无匹配时触发跨 tab 自动跳回原 tab，导致"点了没反应/内容不显示"。改为：**手动点击 / 键盘切换 tab 时清空搜索并恢复完整显示**；仅搜索内部的跨 tab 自动跳转保留关键词继续过滤（`fuwari_activate_tab(href, keepSearch)`）。
- **G3 小屏下搜索框随菜单整体滚出（布局）**：`core/panel/css/optionsframework.css` — 菜单吸顶高度内整体滚动时，搜索框/计数会一起滚出视口。改为站点名、搜索框、计数固定（`flex-shrink:0`），仅菜单项列表 `overflow-y:auto` 滚动，搜索框始终可见。

### 验证

- `node --check`：options-custom.js 通过。
- 行为回归：菜单吸顶、搜索过滤（含分组展开/跨 tab 自动跳转）、键盘导航、分组折叠记忆均保持兼容；内容区观感仅左缘归位 32px，无其他布局变化。

## [0.8.0-beta.2] - 2026-09-17

> 用户反馈修复：后台设置页左侧菜单/搜索框滚动时不固定、设置项搜索分组内结果不可见、前台搜索结果页搜索词不显示。纯修复，无选项 ID / 数据结构变更，无需数据迁移。

### 修复

- **F1 后台左侧菜单吸顶（布局）**：`core/panel/css/optionsframework.css` — `.set-main-menu` 增加 `position:sticky; top:32px; height:calc(100vh - 32px); overflow-y:auto`（避开 WP 顶栏），`.set-main-plane` 改 `align-items:flex-start`。滚动设置页时左侧菜单与搜索框保持在视口内，可随时切换设置项 / 使用搜索，无需滚回顶部。移动端（≤550px 弹层布局）不受影响。
- **F2 后台设置项搜索（逻辑）**：`core/panel/js/options-custom.js` — 分组容器（group start）自身也带 `.section` 类，原过滤逻辑将未命中的分组容器隐藏，导致**组内命中项被父容器吞掉、搜索结果不可见**。改为：仅过滤叶子设置项，命中项展开所在分组容器，无命中的分组容器隐藏；跨 tab 自动切换逻辑同步修正。
- **F3 前台搜索词显示（PHP 8 兼容）**：`search.php` — 搜索关键词由 `htmlspecialchars($s)`（模板作用域未声明全局，PHP 8 下 `Undefined variable $s`，关键词显示为空）改为 `esc_html(get_search_query())`。
- **F4 前台初始化链隔离（防御）**：`assets/js/fuwari.js` — DOMContentLoaded 初始化链改为逐项独立 try/catch，任一初始化异常不再中断后续步骤（避免出现"搜索框不可用 + 吸顶菜单失效"同时发生）。

### 验证

- `node --check`：fuwari.js / options-custom.js 通过。
- PHP 静态冒烟：73 文件全部通过。
- 行为回归：均为局部修复，后台观感除"菜单吸顶"外不变；搜索过滤与分组折叠/记忆逻辑保持兼容；前台搜索词正常回显。

## [0.8.0-beta.1] - 2026-09-17

> 全项目代码审计（安全 / 兼容 / 回归）。对 73 个 PHP、11 个 JS 文件完成盘点与安全扫描，逐文件精读审计；修复 1 处存储型 XSS、2 处邮件滥用端点、2 处 SQL 拼接隐患与 1 处双重编码乱码。**不改变任何设置项结构、选项 ID 与前台观感，无需数据迁移。**

### 安全修复

- **S1 存储型 XSS（中高危）**：`fun-seo.php` 的文章自定义 keywords/description 输出到 `<meta name="keywords/description" content="...">` 时未转义（作者可控 meta 值可注入属性）；同时 meta box 表单回显未转义、保存未清理。已修复：输出侧 `esc_attr()`、表单回显 `esc_attr()/esc_textarea()`、保存侧按字段类型 `sanitize_text_field()/sanitize_textarea_field()`。
- **S2 邮件滥用（中危）**：`fun-user.php` 注册验证码发送端点（`send_verification_code`，匿名可调）**无 nonce、无频率限制**，可被脚本化循环调用轰炸任意邮箱；重置密码端点（`reset_password_action`）无限流。已修复：验证码端点增加 `user_signup` nonce 校验（前端注册页同步携带 `signup_nonce`），两个端点均增加同邮箱/同 IP 60 秒 transient 限流。
- **S3 SQL 拼接加固（低危防御）**：`widget-comments.php` 最新评论 SQL 中 `$limit`/`$outer` 来自 widget 实例且未 int 化直入 SQL。已修复：`absint()`/`(int)` 强制整数化，`LIMIT` 上限 50（`$outer` 保留负数语义以 `(int)` 处理，不用 `absint`）。
- **S4 输出转义（低危防御）**：`widget-comments.php` 评论链接 `title` 属性中 `post_title` 未转义；`widget-tags.php` 标签云 `title` 属性与正文中 `$tag->name` 未转义。已修复：`esc_attr()/esc_html()/esc_url()`，标签数量 `absint()`。
- **S5 双重编码乱码（显示 bug）**：`page/p-goto.php` 外链提醒页 13 处中文（模板名、提示文案、按钮、注释）为双重编码乱码且含私用区字符（U+E044 等）。已按基线 `13.12` 权威文案修复，正文提示语、标题变量、跳转逻辑、加固转义（`esc_attr/esc_html/esc_js`）均保留。

### 审计确认（无需改动）

- `fun-smtp.php`：`manage_options` 菜单 + `check_admin_referer` + 全字段 sanitize + SMTP 密码 AES-256-CBC（密钥 wp_salt('auth') 派生不落库，`fuwari_enc:`/`boxmoe_enc:` 双前缀兼容）。
- `fun-user-center.php`：头像上传登录 + nonce + MIME 白名单 + finfo 真实内容检测 + 1MB 上限 + 随机文件名；资料/密码更新 nonce + sanitize + `wp_check_password` 验证旧密码。
- `fun-user.php`：登录 nonce + `wp_signon`；注册 nonce + 验证码 + 中文用户名强正则 + 固定 `subscriber` 角色；客户端 IP 优先 `REMOTE_ADDR`，代理头逐段 `filter_var` 校验。
- `fun-comments.php`：AJAX 评论 `check_ajax_referer('comment_nonce', 'security')` + 20 秒 IP 间隔 + 重复评论检查 + `wp_allow_comment` 审核；表单回填 `esc_attr`。
- `fun-article.php`：点赞/收藏 nonce + `absint` + 文章存在性 + IP transient 每日防刷。
- `fun-shortcode.php`：`extract(shortcode_atts(...))` 受白名单约束；密码保护 `hash_equals` 常量时间比较。
- `page/p-go.php`、`page/p-goto.php`：跳转 scheme 白名单（http/https/thunder 等）+ `esc_attr/esc_html/esc_js` 输出 + 414 长度防护；`fun-msg.php` curl 通知（超时 + SSL 默认校验）；`fun-optimize.php` OPTIMIZE 表名取自 `$wpdb` 白名单且仅定时任务触发。
- 登记不改（设计取舍，非缺陷）：评论内容 `esc_attr` 全袋转义存储（防 XSS 的显示副作用）、widget 标题按 WP 官方惯例不转义、登录失败无限流（记录为可选改进）。

### 验证

- PHP 静态冒烟：73 文件无 BOM、标签规范、括号平衡全部通过。
- JS `node --check`：11 个 JS 全部通过。
- 全仓双重编码乱码 / 私用区字符扫描：仅剩 CHANGELOG.md 中 0.4.0-beta.10 的乱码引用示例（预期内）。
- 行为回归：修复均为局部加固，无选项 ID / 数据结构 / 存储变更；后台设置观感与前台渲染不受影响。

## [0.7.0-beta.1] - 2026-09-16

> 后台主题设置架构重构（方案 C：定义 Schema 化 + 自研注册制框架）。**观感与 0.6.0 完全一致**（不做全屏面板式布局）：渲染层（class-options-interface.php 等）、选项存储（options-framework-theme）、HTML/CSS/JS 输出均未改动；仅"定义管理"层重构。

### 变更

- **C1 定义 Schema 化 / 注册制**：11 个 `set-*.php` 中 124 项定义由 `$options[] = array(...)` 改为 `Fuwari_Options_Registry::register( array(...) )` 声明式注册；定义内容（键/值/顺序/缩进）与迁移前**逐字节一致**（脚本比对 124/124 全等）。
- **C2 自研注册器**（新增 `core/panel/includes/class-fuwari-options-registry.php`）：
  - 集中加载定义文件（顺序可控、上下文统一构建：分类/标签/页面/图片路径/版本号）；
  - Schema 校验：type 白名单、id 唯一性、group start/end 配对预检与终检——**容错设计**：仅记录（WP_DEBUG 时 error_log），绝不阻断渲染，设置页不白屏；
  - `optionsframework_options()` 改为从注册器取数（对外接口、返回结构不变），A1/A2 双重缓存（静态 + transient）原样保留。
- 渲染层 / 存储层 / 前台完全不动：设置页观感、字段行为、保存验证、数据格式均与 0.6.0 一致。

### 验证

- 迁移脚本：改前/改后 124 项定义（缩进 + 内容）全等比对通过。
- Schema 校验模拟：124 项 type 全部合法、group 终检 depth=0（无孤儿 end、无未闭合 start）；唯一提示"重复 id banquan"为**迁移前已存在**的现状（两个 info 项，渲染层容忍），注册器仅警告不阻断。
- div 配对模拟：124 项 / 24 组 / 11 tab 最终 depth=0。
- PHP 静态冒烟通过（无 BOM、标签规范、括号平衡）。
- 目标环境：进入「外观-主题设置」观感应与 0.6.0 完全一致；后台若开启 WP_DEBUG，日志中可能出现 `[Fuwari Options] 重复 id「banquan」`（预期内，不影响使用）。

## [0.6.0-beta.1] - 2026-09-16

> 后台主题设置体验重构（方案 B）。纯前端增强（PHP 仅增加 DOM 标识与搜索框），无选项 ID / 数据结构 / 存储变更，无需迁移。样式与代码风格对齐主题主视觉（深色侧栏 `#0b121b`、主蓝 `#457ace`）。

### 变更

- **B1 设置项搜索**（`class-options-framework-admin.php` + `options-custom.js`）：
  - 左侧菜单区新增搜索框，输入即过滤当前 tab 内设置项（按名称/描述/标签/ID/占位符匹配）；
  - 实时显示"匹配 X / Y 项"；当前 tab 无匹配时自动切换到第一个有匹配的 tab；全部无匹配显示空态；清空恢复原状（含折叠记忆）。
- **B2 Tab 键盘导航**：聚焦左侧 tab 后可用 `↑ ↓ ← →` 切换并激活，首尾循环；Tab 激活统一入口（点击/键盘/搜索共用）。
- **B3 分组折叠**（`class-options-interface.php`）：group 分组标题新增折叠按钮（箭头旋转、`aria-expanded` 语义），点击折叠/展开该组，折叠状态记忆到 localStorage，默认展开；为组内选项输出 `data-fuwari-group` 标识。
- **B4 后台暗色适配**（`optionsframework.css` + JS 检测）：检测 WP 后台暗色（body 类 `wp-dark-mode`/`is-dark-theme`/`wp-admin-dark`/`dark-theme` + 系统 `prefers-color-scheme: dark` 双信号）后，设置页内容区自动切换深色配色；侧栏与主视觉不变。
- **B5 保存/重置 Toast**：点击保存/重置显示"正在保存…"；页面加载时检测保存结果（WP notice 类）显示成功/失败 Toast，右下角深色卡片风格，2.6 秒自动消失。
- 样式与代码对齐：新增 CSS/JS 沿用主题深色侧栏与主蓝风格；JS 保持 tab 缩进与 jQuery 风格。

### 验证

- div 配对模拟：124 项 / 24 组 / 11 tab 最终 depth=0、最小 depth=0（data 属性不改变结构）。
- JS `node --check` 通过；PHP 静态冒烟通过（无 BOM、标签规范、括号平衡）。
- 专项检查：搜索框/计数、折叠按钮 + `aria-expanded`、`data-fuwari-group` 注入（start 项/普通项/heading 重置/group end 清除）、暗色双信号、Toast 提交与回读逻辑均在包内核对通过。
- 目标环境：进入「外观-主题设置」验证搜索、键盘切换、分组折叠、暗色模式、保存 Toast；需用户在实际站点走查确认视觉效果。

## [0.5.0-beta.1] - 2026-09-16

> 后台主题设置优化（方案 A：轻量加固）。本版本聚焦设置页性能与稳定性，不改变任何选项 ID、数据结构与存储，无需迁移。

### 变更

- **A1+A2 选项定义缓存**（`options.php`）：
  - `optionsframework_options()` 增加**静态缓存**——同一请求内 tabs/fields/validate 多次调用只构建一次，消除 `get_categories()/get_tags()/get_pages()` 的重复数据库查询与 11 个 `set-*.php` 重复加载；
  - 增加 **transient 跨请求缓存**（键含主题版本号，1 小时）：进入设置页不再全量重建 124 项定义；
  - 缓存失效：设置保存（`optionsframework_after_validate`）、分类/标签/文章变更（created/edited/deleted 钩子）自动清理；主题升级（版本号变化）自动失效。
- **A3 group 状态机防御**（`class-options-interface.php`）：group 渲染增加三层防御——嵌套 `start` 先闭合旧分组、孤儿 `end` 忽略、循环结束泄漏补闭合。四种场景（正常/孤儿 end/嵌套 start/末尾未闭合）经 div 配对模拟全部通过。
- **A4 PHP 8 复查**：panel 全文件扫描无废弃函数（`each(` 为 `foreach(` 误报）；未定义数组键访问均已有防护。
- **A5 仅后台加载确认**：panel CSS/JS 的 `options_screen` 页面条件已有，确认仅设置页加载，无需改动。

### 验证

- div 配对模拟（124 项/24 组/11 tab）最终 depth=0；三种异常数据防御生效。
- 静态冒烟通过。
- 目标环境：进入「外观-主题设置」应明显提速；设置保存后下拉选项即时刷新（缓存已按变更钩子清理）。

## [0.4.0-beta.10] - 2026-09-16

> 编码修复：0.4.0-beta.9 加入版权声明时，批量脚本中的中文作者名经 `\xNN` 转义被 Python 按 Latin-1 码位解释，写入时二次 UTF-8 编码 → 版权行中文变成 `æ‹¿å®Œè¥¿ç“œè·‘` 乱码（双重编码）。已全部还原。

### 修复

- **66 个 PHP 文件**版权行中文 `æ‹¿å®Œè¥¿ç“œè·‘ (Grabrun)` → 还原为 `拿完西瓜跑 (Grabrun)`（latin-1 → utf-8 还原，逐文件校验）。
- **全仓终检**：所有 PHP/CSS/JS/MD/TXT/JSON/XML/SVG 文件 UTF-8 可解码、无双重编码残留；`LICENSE`/`README.md`/两个 `style.css` 的中文作者名经核查本就正确（直接中文字符写入路径），未受影响。
- 静态冒烟通过。

### 经验记录

- 批量写文件头时中文一律直接写字符（源脚本保持 UTF-8），**禁止**用 `\xNN` 字节转义表达中文——Python 的 `\xNN` 是 Latin-1 码位而非原始字节。

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
