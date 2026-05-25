<?php
/**
 * MCDocs Theme - 404 错误页面
 */

if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$this->need('header.php');
?>

<main class="flex-grow" style="display: flex; align-items: center; justify-content: center; min-height: 60vh; padding: 2rem;">
    <div style="
        text-align: center;
        max-width: 40rem;
        padding: 4rem 3rem;
        background-color: white;
        border: 6px solid black;
        box-shadow: 12px 12px 0 0 black;
    ">
        <!-- 404 数字 -->
        <h1 style="
            font-size: 10rem;
            font-weight: 900;
            line-height: 1;
            margin-bottom: 1rem;
            color: transparent;
            -webkit-text-stroke: 4px black;
            letter-spacing: -0.05em;
        ">
            404
        </h1>
        
        <!-- 错误信息 -->
        <h2 style="
            font-size: 2rem;
            font-weight: 900;
            margin-bottom: 1rem;
            text-transform: uppercase;
        ">
            页面未找到 🚫
        </h2>
        
        <p style="
            font-size: 1.25rem;
            color: #6b7280;
            margin-bottom: 2rem;
            line-height: 1.6;
        ">
            抱歉，您访问的页面不存在或已被删除。<br>
            可能是输入了错误的地址，或者该页面已经迁移到其他位置。
        </p>

        <!-- 建议操作 -->
        <div style="
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
            margin-bottom: 2rem;
        ">
            <a href="<?php $this->options->siteUrl(); ?>" 
               class="btn-primary"
               style="display: inline-flex;">
                ← 返回首页
            </a>
            
            <button onclick="history.back()" 
                    class="btn-secondary">
                返回上页
            </button>
        </div>

        <!-- 快速链接 -->
        <div style="
            border-top: 3px dashed #e5e7eb;
            padding-top: 2rem;
            margin-top: 2rem;
        ">
            <p style="font-weight: 700; margin-bottom: 1rem; color: #374151;">
                或者尝试以下操作：
            </p>
            <ul style="
                list-style: none;
                display: inline-block;
                text-align: left;
            ">
                <li style="margin-bottom: 0.5rem;">
                        <i class="fa-solid fa-check"></i> 检查网址拼写是否正确
                    </li>
                    <li style="margin-bottom: 0.5rem;">
                        <i class="fa-solid fa-magnifying-glass"></i> 使用搜索功能查找内容
                    </li>
                    <li style="margin-bottom: 0.5rem;">
                        <i class="fa-solid fa-sitemap"></i> 浏览网站地图或归档页面
                    </li>
                    <li>
                        <i class="fa-solid fa-envelope"></i> 联系站长报告此问题
                    </li>
            </ul>
        </div>
    </div>
</main>

<style>
/* 404 页面特殊动画效果 */
@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
}

main > div {
    animation: float 3s ease-in-out infinite;
}
</style>

<?php $this->need('footer.php'); ?>