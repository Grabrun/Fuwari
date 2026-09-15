# -*- coding: utf-8 -*-
"""LoliMeow PHP 静态冒烟检查 v3（无 PHP CLI 时的降级验证）

策略（对 PHP 模板启发式检查误报率高，仅采用可靠规则）：
1. 文件不得含 UTF-8 BOM
2. 剥离 PHP 注释与字符串后统计标签：?> 数量不得多于 <?php 数量
   （正则表达式字符串内的 ?> 属合法内容，剥离后不计）
3. 纯 PHP 文件（剥离字符串后无 ?> 标签）：对全文剥注释/字符串后做括号平衡检查
   （模块、面板类文件均为纯 PHP，整体检查可靠；含 HTML 的模板文件跳过括号检查）
"""
import os, re, sys

ROOT = r"E:\Projects\DouBao\WordPressTheme\lolimeow-master"
ISSUES = []
SKIPPED_BRACE = []

def strip_php(code):
    """剥离 PHP 注释与字符串字面量（含转义），返回纯代码。"""
    out = []
    i, n = 0, len(code)
    while i < n:
        c = code[i]
        if c == '/' and i + 1 < n and code[i+1] == '/':
            j = code.find('\n', i)
            i = n if j == -1 else j + 1
            continue
        if c == '/' and i + 1 < n and code[i+1] == '*':
            j = code.find('*/', i + 2)
            i = n if j == -1 else j + 2
            continue
        if c in ('"', "'"):
            quote = c
            i += 1
            while i < n:
                if code[i] == '\\':
                    i += 2
                    continue
                if code[i] == quote:
                    i += 1
                    break
                i += 1
            continue
        out.append(c)
        i += 1
    return ''.join(out)

def check_file(path):
    rel = os.path.relpath(path, ROOT)
    with open(path, 'rb') as f:
        raw = f.read()
    if raw.startswith(b'\xef\xbb\xbf'):
        ISSUES.append(f"[BOM] {rel}: 文件含 UTF-8 BOM")
    try:
        code = raw.decode('utf-8')
    except UnicodeDecodeError:
        code = raw.decode('gbk', errors='replace')

    stripped = strip_php(code)
    opens = len(re.findall(r'<\?php', stripped))
    closes = len(re.findall(r'\?>', stripped))
    if closes > opens:
        ISSUES.append(f"[TAG] {rel}: ?> x{closes} 多于 <?php x{opens}")
    if opens == 0:
        ISSUES.append(f"[NO-PHP] {rel}: 未发现 PHP 代码块")

    # 纯 PHP 文件：无 ?> 标签 → 全文括号平衡检查
    if closes == 0 and opens > 0:
        for ch_o, ch_c, name in (('{', '}', '{}'), ('(', ')', '()'), ('[', ']', '[]')):
            if stripped.count(ch_o) != stripped.count(ch_c):
                ISSUES.append(f"[BRACE] {rel}: {name} 不平衡 open={stripped.count(ch_o)} close={stripped.count(ch_c)}")
    else:
        SKIPPED_BRACE.append(rel)

def main():
    php_files = []
    for dirpath, _, files in os.walk(ROOT):
        if '.git' in dirpath or '.verify' in dirpath:
            continue
        for fn in files:
            if fn.endswith('.php'):
                php_files.append(os.path.join(dirpath, fn))
    for p in sorted(php_files):
        check_file(p)
    print(f"检查 {len(php_files)} 个 PHP 文件；括号平衡覆盖纯 PHP 文件 {len(php_files) - len(SKIPPED_BRACE)} 个，跳过混排模板 {len(SKIPPED_BRACE)} 个")
    if ISSUES:
        print(f"发现 {len(ISSUES)} 个问题：")
        for it in ISSUES:
            print("  " + it)
        sys.exit(1)
    print("全部通过：无 BOM、PHP 标签规范、纯 PHP 文件括号平衡")

if __name__ == '__main__':
    main()
