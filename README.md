# Fuwari · 浮絮

> 一款基于 LoliMeow 深度重构的 WordPress 博客主题 —— 更安全、更快、更现代。

Fuwari（浮絮）源自 [LoliMeow](https://www.boxmoe.com)（Boxmoe 系列）主题，在保留其简洁优雅设计语言的基础上，完成了**架构级重构与安全加固**：

- 剥离第三方付费插件（erphpdown）强耦合，会员中心完全内置
- 统一内部标识、规范模块架构，代码可维护性大幅提升
- 全链路安全审计加固（XSS / CSRF / 文件上传 / 邮件滥用等）
- 加载性能专项优化（渲染阻塞消除、字体子集化、死资源清理）

**当前版本**：`0.8.0-beta.13`（独立分支项目 · 遵循语义化版本 SemVer 2.0.0 · 版本自 0.0.0 起算）

---

## ✨ 功能特性

- **三模式切换**：亮色 / 暗色 / 跟随系统，暗色 UI 全站适配
- **自研会员中心**：个人资料、头像、密码、收藏、评论 —— 完全内置，**不依赖任何付费插件**
- **SEO 优化面板**：标题 / 描述 / 关键词、站点验证、百度 / Google / 360 推送、sitemap
- **经典编辑器增强**：表情包、评论表情、短代码（登录可见、密码保护等）
- **文章形式支持**：多文章形式、缩略图随机 API、图片懒加载
- **社交与通知**：QQ 社交登录、评论/注册机器人通知（企业微信、QQ 机器人等）

## 🛡️ 安全加固

- 全仓代码审计：修复存储型 XSS、邮件滥用端点（nonce + 限流）、SQL 拼接隐患、外链中转输出转义等
- **头像上传**：登录校验 + nonce + 文件真实内容 MIME 白名单（杜绝上传可执行文件）
- **SMTP 密码**：AES-256-CBC 加密存储（密钥派生自 wp_salt，不落库明文）
- **AJAX 端点**：全覆盖 nonce 校验；密码比较使用 `hash_equals` 常量时间比较
- **客户端 IP**：不再无条件信任代理头，`REMOTE_ADDR` 优先

## ⚡ 性能优化

- **消除 CSS @import 渲染阻塞**：fancybox / font-awesome 改并行加载
- **字体子集化**：Alimama 圆体 2352KB → 1292KB（生僻字回退系统字体）
- **删除未引用死资源**：如 dance.gif（1109KB）
- **jQuery 默认不加载**：主题脚本零 jQuery 依赖（保留后台开关，兼容第三方插件）
- **按需加载**：评论脚本仅文章 / 页面加载；第三方域名 preconnect 预连接
- **后台选项缓存**：静态缓存 + transient 跨请求缓存，设置页显著提速

## 🚀 快速开始

### 方式一：Releases 下载（推荐）

1. 前往 [Releases](https://github.com/Grabrun/Fuwari/releases) 下载最新安装包 `Fuwari-vX.X.X-beta.x-YYYYMMDD-HHMMSS.zip`
2. WordPress 后台 → 外观 → 主题 → 安装主题 → 上传主题，选择该 zip 包
3. 启用主题，进入「外观 → Fuwari 主题设置」完成站点配置

### 方式二：源码部署

```bash
git clone https://github.com/Grabrun/Fuwari.git
# 将 fuwari 目录上传至 wp-content/themes/fuwari
```

## 📦 版本历史

独立分支项目，版本自 `0.0.0` 起算，遵循[语义化版本 2.0.0](https://semver.org/lang/zh-CN/)。`beta` 为预发布标识，不代表最终 API 稳定。

| 版本 | 里程碑 |
| --- | --- |
| 0.8.0-beta.x | 全项目代码审计（安全 / 兼容 / 回归）+ 后台设置搜索、暗色 UI 系列修复 |
| 0.7.0-beta.1 | 后台设置架构重构：定义 Schema 化 + 自研注册制框架（观感不变） |
| 0.6.0-beta.1 | 后台设置体验重构：设置项搜索、键盘导航、分组折叠、暗色适配、保存 Toast |
| 0.5.0-beta.1 | 后台设置性能优化：选项定义缓存、group 状态机防御、PHP 8 复查 |
| 0.4.0-beta.x | 加载速度优化 + 版权合规 + 后台界面修复 + 深度回归审计 |
| 0.3.0-beta.1 | 内部标识统一重构（`boxmoe_*` → `fuwari_*`），含选项迁移与旧键回退 |
| 0.2.0-beta.x | 移除 erphpdown 付费插件强耦合（会员 / VIP / 充值 / 订单体系） |
| 0.1.0-beta.1 | 架构与安全深度优化首版 |
| 13.12 | 上游基线（LoliMeow 原版，git tag `13.12`） |

完整变更记录见 [CHANGELOG.md](CHANGELOG.md)。

## 📄 版权与许可

- **主题名称**：Fuwari（浮絮）
- **作者**：拿完西瓜跑（Grabrun）
- **许可协议**：[GPLv3 or later](LICENSE)（GPL-3.0 全文见仓库根目录）
- **衍生声明**：本主题源自 [LoliMeow](https://www.boxmoe.com) 项目（Boxmoe），各源文件保留原项目版权说明（`@link https://www.boxmoe.com`）
- **内置组件**：Options Framework（Devin Price / WP Theming，GPL-2.0+），版权归其原作者

## 🤝 参与贡献

欢迎提交 Issue 反馈问题与建议，也欢迎 Fork + Pull Request：

1. Fork 本仓库
2. 创建特性分支：`git checkout -b feature/xxx`
3. 提交变更（遵循语义化版本规范）
4. 发起 Pull Request

## ⚠️ 免责声明

本项目为开源学习交流项目，请在遵循 GPLv3 协议的前提下使用。使用过程中如遇问题，欢迎通过 Issue 反馈。
