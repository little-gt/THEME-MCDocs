<?php
/**
 * MCDocs Theme - 文章详情页模板
 * 三栏布局：左侧分类导航 + 中间内容区 + 右侧大纲
 */

if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$this->need('header.php');
?>

<!-- 主体布局：左侧导、内容区、右侧大纲 -->
<div class="flex-grow flex max-w-[1600px] w-full mx-auto" style="flex-wrap: wrap;">
    
    <!-- 左侧边栏 (目录树) -->
    <?php $this->need('sidebar.php'); ?>

    <!-- 中心内容区 (Markdown 渲染结果) -->
    <main class="article-container prose-container">
        <div class="prose-content">
            <?php while ($this->next()): ?>
            
            <!-- 标题与 Meta -->
            <article class="post-content" itemscope itemtype="https://schema.org/Article">
                <h1 class="font-black mb-4 uppercase tracking-wide border-b-4 border-black pb-4" 
                    itemprop="headline">
                    <?php $this->title(); ?>
                </h1>
                
                <!-- 文章元信息 -->
                <div class="article-meta">
                    <span class="meta-tag">作者: <?php $this->author(); ?></span>
                    <span class="meta-tag">更新: <?php $this->date('Y-m-d'); ?></span>
                    <?php if ($this->category): ?>
                    <span class="meta-tag">分类: <?php $this->category(','); ?></span>
                    <?php endif; ?>
                </div>

                <!-- 文章摘要（如果有） -->
                <?php if ($this->fields->excerpt): ?>
                <p style="font-size: 1.25rem; font-weight: 500; margin-bottom: 2rem;">
                    <?php $this->fields->excerpt(); ?>
                </p>
                <?php else: ?>
                <p style="font-size: 1.25rem; font-weight: 500; margin-bottom: 2rem;">
                    在这里，我们将展示 <strong><?php $this->options->title(); ?></strong> 如何将枯燥的 Markdown 元素转化为充满力量感的矩形方块风格。
                </p>
                <?php endif; ?>

                <!-- 引用块提示（可选） -->
                <?php if ($this->fields->notice): ?>
                <blockquote>
                    <?php $this->fields->notice(); ?>
                </blockquote>
                <?php endif; ?>

                <!-- 文章正文内容 -->
                <div itemprop="articleBody">
                    <?php $this->content(); ?>
                </div>

                <!-- 底部翻页导航 -->
                <nav class="article-nav" aria-label="文章导航">
                    <span class="article-nav-prev"><?php $this->thePrev('%s', _t('暂无更早文章')); ?></span>
                    <span class="article-nav-next"><?php $this->theNext('%s', _t('暂无较新文章')); ?></span>
                </nav>

                <!-- 标签列表 -->
                <?php if ($this->tags): ?>
                <div style="margin-top: 2rem; padding-top: 2rem; border-top: 2px solid #e5e7eb;">
                    <p style="font-weight: 700; margin-bottom: 0.75rem;"><i class="fa-solid fa-tags"></i> 标签：</p>
                    <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                        <?php $this->tags(',', true, 'none'); ?>
                    </div>
                </div>
                <?php endif; ?>

            </article>

            <!-- 评论区域 -->
            <?php $this->need('comments.php'); ?>

            <?php endwhile; ?>
        </div>
    </main>

    <!-- 右侧边栏 (页面大纲 TOC) -->
    <aside class="sidebar-right hidden xl:block" role="navigation" aria-label="文章目录">
        <h4 class="toc-title">此页内容</h4>
        
        <!-- TOC 导航容器（由 JavaScript 动态生成） -->
        <nav id="toc-navigation" class="toc-nav space-y-3">
            <!-- JavaScript 将在此处生成目录项 -->
        </nav>

        <!-- 广告位 / 自定义区域 -->
        <div class="ad-placeholder">
            <?php if (!empty($this->options->adCode)): ?>
            <div class="ad-box">
                <?php echo $this->options->adCode; ?>
            </div>
            <?php else: ?>
            <span class="ad-label">广告位出租 📢</span>
            <div class="ad-box">
                300x250
            </div>
            <?php endif; ?>
        </div>
    </aside>

</div>

<script>
// ===================================
// TOC 自动生成器（仅从文章正文提取标题）
// ===================================
document.addEventListener('DOMContentLoaded', function() {
    const tocNav = document.getElementById('toc-navigation');
    
    if (!tocNav) return;

    // 只从文章正文区域提取标题，排除评论区
    const articleBody = document.querySelector('article.post-content [itemprop="articleBody"]');
    
    if (!articleBody) {
        tocNav.parentElement.style.display = 'none';
        return;
    }

    // 支持多级标题：h2, h3, h4
    const headings = articleBody.querySelectorAll('h2, h3, h4');
    
    // 如果没有标题，隐藏TOC区域
    if (headings.length === 0) {
        tocNav.parentElement.style.display = 'none';
        return;
    }

    // 构建多级目录结构
    let tocHTML = '<ul class="toc-tree">';
    let currentLevel = 2; // 从 h2 开始

    headings.forEach((heading, index) => {
        // 为没有 ID 的标题自动生成 ID
        if (!heading.id) {
            heading.id = `section-${index}`;
        }
        
        const level = parseInt(heading.tagName.replace('H', ''));
        
        // 处理层级变化
        while (level > currentLevel) {
            tocHTML += '<ul class="toc-child">';
            currentLevel++;
        }
        while (level < currentLevel) {
            tocHTML += '</ul></li>';
            currentLevel--;
        }
        
        // 添加目录项
        tocHTML += `
            <li class="toc-item level-${level}">
                <a href="#${heading.id}" 
                   class="toc-link"
                   data-target="${heading.id}"
                   data-level="${level}">
                    ${heading.textContent}
                </a>
        `;
    });

    // 关闭所有未闭合的标签
    while (currentLevel >= 2) {
        tocHTML += '</li></ul>';
        currentLevel--;
    }

    tocNav.innerHTML = tocHTML;

    // 滚动时高亮当前章节
    const tocLinks = document.querySelectorAll('.toc-link');
    const headingElements = Array.from(headings);

    function updateActiveToc() {
        let currentHeading = null;
        
        for (let i = headingElements.length - 1; i >= 0; i--) {
            const rect = headingElements[i].getBoundingClientRect();
            if (rect.top <= 150) {
                currentHeading = headingElements[i];
                break;
            }
        }

        tocLinks.forEach(link => {
            link.classList.remove('active');
            if (currentHeading && link.dataset.target === currentHeading.id) {
                link.classList.add('active');
            }
        });
    }

    window.addEventListener('scroll', () => {
        requestAnimationFrame(updateActiveToc);
    });

    // 初始化时执行一次
    updateActiveToc();
});

// ===================================
// 平滑滚动增强
// ===================================
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        const href = this.getAttribute('href');
        if (href === '#' || href === '') return;
        
        e.preventDefault();
        const target = document.querySelector(href);
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});
</script>

<?php $this->need('footer.php'); ?>