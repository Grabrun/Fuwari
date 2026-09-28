# Fuwari（浮絮）深度安全与架构审计报告

- **审计对象**：Fuwari WordPress 主题（独立分支，源自 Boxmoe LoliMeow 13.12）
- **审计版本**：0.8.1（commit `d662038`）
- **审计日期**：2026-09-28
- **审计方式**：静态源码审查 + 语法冒烟 + 生产站只读行为验证
- **审计结论**：**通过**（无高危遗留风险；1 项中危已修复并随 0.8.1 发版）

---

## 一、审计范围

| 项目 | 结果 |
|---|---|
| PHP 源文件 | 73 个，语法冒烟 **73/73 通过**（`php -l`，PHP 8.3.33） |
| JavaScript 资源 | `fuwari.js`（与生产部署 MD5 一致）、`theme.min.js`、`lib.min.js` 均无 location 覆写 / 导航拦截逻辑 |
| 后台选项接口 | `core/panel/` 全量审查（注册 / 渲染 / 保存 / 消毒） |
| AJAX 端点 | 17 个端点逐一核对 nonce 与权限 |
| 生产行为 | 只读验证（分类重定向链、占位图配置、前台资源引用），未做任何写入 |

## 二、已修复问题（0.8.1）

| 编号 | 风险 | 位置 | 修复 |
|---|---|---|---|
| A-001 | **反射型 XSS**（中危） | `page/p-user_center.php:39` | `$items` 参数非白名单取值时未转义直接回显，登录用户可构造恶意参数触发。已改为白名单映射 + `esc_html()` 强制转义，commit `d662038`，随 **0.8.1 正式版**发布 |

## 三、安全项逐项核查

### 3.1 注入类

| 检查项 | 结果 | 说明 |
|---|---|---|
| SQL 注入 | ✅ 未发现 | 全部数据库操作走 WP 转义（`$wpdb->prepare` / `absint` / 白名单），`widget-comments` 的 `$outer`/`$limit` 已强制整数化 |
| 反射型 XSS | ✅ 已修复 | 见 A-001；其余输出点均经 `esc_html` / `esc_url` / `esc_attr` |
| 存储型 XSS | ✅ 未发现 | 评论区、用户资料等输入点均有转义与 `kses` 处理 |
| 文件包含 | ✅ 安全 | 模板引入均为 `get_template_directory()` + 白名单文件名拼接，无用户输入 |

### 3.2 认证与会话

| 检查项 | 结果 | 说明 |
|---|---|---|
| AJAX nonce | ✅ 全覆盖 | 12 处核心验证点：评论、登录、注册、重置密码、文章点赞/收藏、用户中心 3 端点、SEO metabox；17 个 AJAX 端点全带 nonce |
| 暴力破解限流 | ✅ 60 秒 | 登录 / 注册 / 验证码 / 重置密码统一 60 秒防刷 |
| 登录态校验 | ✅ | 用户中心、个人资料修改均校验 `is_user_logged_in` + 当前用户 ID |

### 3.3 文件与数据

| 检查项 | 结果 | 说明 |
|---|---|---|
| 头像上传 | ✅ 安全 | `finfo` 真实 MIME 内容检测 + 扩展名白名单 + 1MB 上限 + 随机文件名（防路径穿越 / 木马上传） |
| SMTP 密码存储 | ✅ 加密 | AES-256-CBC 加密落库，密钥由 `wp_salt` 派生；兼容旧 `boxmoe_enc:` 与新 `fuwari_enc:` 前缀，升级不断档 |
| 选项消毒 | ✅ | `class-options-sanitization.php` 统一 sanitize（URL、文件类型、数字等） |

### 3.4 重定向与外部请求

| 检查项 | 结果 | 说明 |
|---|---|---|
| 分类去前缀 301 | ✅ | `fun-no-category.php` 的 301 已带 `Cache-Control: must-revalidate, no-cache, no-store`，不会污染浏览器缓存 |
| 后台权限重定向 | ✅ | `wp_safe_redirect( home_url() )` 仅作用于非管理员访问后台，白名单目标 |
| 外联请求 | ✅ | 仅"用户可选推送"场景发起外联，且 SSL 证书校验已恢复；SEO 远程请求带超时（5s）+ 重定向上限 |
| 生产 404 行为 | ⚠️ 服务器配置 | 生产服务器（宝塔/EdgeOne）对不存在路径返回 **无缓存控制头的 301 → 首页**（如 `/feed/`、`/category/`），这是此前"分类跳首页"缓存污染的来源。**建议**在服务器规则上补 `Cache-Control: no-store`（主题代码无此问题，WordPress 自身 301 均已带 no-store） |

## 四、架构审计

### 4.1 硬耦合移除（erphpdown）

- 会员 / VIP / 充值 / 订单 / 下载权限等 erphpdown 付费体系相关代码**已全部移除**，未保留后台入口与前端残留。
- 移除后无残留函数调用（73 个 PHP 文件语法冒烟通过即为佐证）。

### 4.2 命名与品牌

| 项目 | 结果 |
|---|---|
| 函数前缀 | `boxmoe_*` → `fuwari_*` 全量改名完成（含选项 id、AJAX action、query var） |
| 选项 id | 全部迁移为 `fuwari_*`（如 `fuwari_no_categoty`、`fuwari_lazy_load_images`） |
| 作者信息 | 作者改为 拿完西瓜跑（Grabrun）；前台页尾保留 `Theme by Fuwari・基于 Boxmoe 的 LoliMeow 项目` + boxmoe.com 链接 |
| 版权合规 | LICENSE 保留原作者 GPLv3 声明 + 本项目版权声明；页尾 / 版权注释合规 |

### 4.3 目录与版本

- 主题目录统一为 `fuwari`；打包前缀 `fuwari/`。
- 语义化版本 2.0.0（独立分支从 0.0.0 起），每版本 beta 标 prerelease、结束发正式版；打包名 `Fuwari-v<版本>-<日期>-<时间>.zip`。

## 五、性能审计

| 项目 | 结果 |
|---|---|
| jQuery 移除 | 前台不加载 WP 自带 jQuery（`wp_deregister_script('jquery')`），静态资源均不依赖 |
| 冗余 CSS 剔除 | 精准 dequeue `global-styles`、`wp-block-library` 等（保留主题自身样式输出，无整站 CSS 失效风险——已修正上游 13.12 的误删行为） |
| 无用 WP 头部输出 | 可选开关移除 generator / feed 链接 / RSD / 短链等 |
| 图片懒加载 | 原生 IntersectionObserver 实现，占位图路径已修正为 `fuwari/assets/images/loading.gif`（生产后台已配置） |
| 缓存兼容 | 资源版本号统一 `ver=0.8.1`，与 WP Fastest Cache 兼容 |

## 六、遗留风险与建议

| 优先级 | 事项 |
|---|---|
| 中 | 服务器「404 → 301 首页」规则补 `Cache-Control: no-store`（见 3.4），防止缓存污染复发 |
| 低 | GitHub token 已多次出现在会话中，**建议轮换**（`github_pat_...`） |
| 低 | 上游 `13.12` tag 未建 Release（用户未要求，仅记录） |
| 低 | GitHub Releases 列表按 tag 名字典序排序（平台行为），版本顺序以 README「版本历史」表为准 |

## 七、验证方式与覆盖

1. **语法层**：便携 PHP 8.3.33 `php -l` 全量 73 文件。
2. **源码层**：Grep 定位注入 / 非转义输出 / nonce / 重定向 / 上传 / 加密等模式，逐一人工复核上下文。
3. **行为层**：生产站只读验证（HTTP/1.1 + HTTP/2、多 UA/Cookie 头组合）确认分类页 200、301 链带 no-store、占位图引用 fuwari 路径；未做任何生产写入。

---

*本报告结论基于审计时点的源码与生产可观测行为；代码后续变更需重新评估。*
