<?php
/**
 * MCDocs Theme - 搜索结果页面模板
 */

if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$this->need('header.php');

// 获取搜索关键词：优先使用 Typecho 标准方法，兼容伪静态和普通参数两种模式
$searchKeyword = $this->getKeywords() ?: $this->request->get('s') ?: '';
?>

<main class="flex-grow">
    <div style="max-width: 1280px; margin: 0 auto; padding: 4rem 1rem; width: 100%;">
        <!-- 搜索标题区域 -->
        <div style="margin-bottom: 3rem;">
            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                <span style="font-size: 2.5rem;"><i class="fa-solid fa-magnifying-glass text-4xl"></i></span>
                <h1 class="font-black text-3xl uppercase tracking-wide">搜索结果</h1>
            </div>
            
            <div style="background-color: #f0fdf4; border: 2px solid #000; padding: 1.25rem 1.5rem; box-shadow: 4px 4px 0 0 #000;">
                <p style="font-weight: 700; margin-bottom: 0.5rem;">搜索关键词：<span style="color: #059669; font-family: 'JetBrains Mono', monospace; font-size: 1.125rem;">&ldquo;<?php echo htmlspecialchars((string)$searchKeyword); ?>&rdquo;</span></p>
                <p style="font-size: 0.875rem; color: #6b7280;">共找到 <strong><?php echo $this->getTotal(); ?></strong> 篇相关文章</p>
            </div>
        </div>

        <!-- 搜索结果列表 -->
        <?php if ($this->have()): ?>
        <div style="display: grid; gap: 1.5rem;">
            <?php while ($this->next()): ?>
            <article class="bg-white border-2 border-black p-5 block-shadow hover:-translate-y-1 hover:block-shadow-sm transition-all duration-300" 
                     itemscope itemtype="https://schema.org/Article"
                     style="display: flex; flex-direction: column;">
                <!-- 文章标题 -->
                <h3 class="text-xl md:text-2xl font-bold mb-2">
                    <a href="<?php $this->permalink(); ?>" 
                       itemprop="url"
                       rel="bookmark"
                       style="color: inherit; text-decoration: none;">
                        <span itemprop="name"><?php $this->title(); ?></span>
                    </a>
                </h3>

                <!-- 文章摘要（高亮匹配内容） -->
                <p class="text-gray-600 font-medium line-clamp-3" 
                   style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;"
                   itemprop="description">
                    <?php 
                        $excerpt = $this->excerpt(200, '...');
                        if (!empty($searchKeyword)) {
                            $excerpt = str_ireplace(
                                $searchKeyword, 
                                '<mark style="background-color: #fef08a; padding: 0 2px; color: #000; font-weight: 600;">' . htmlspecialchars((string)$searchKeyword) . '</mark>', 
                                $excerpt
                            );
                        }
                        echo $excerpt;
                    ?>
                </p>

                <!-- 元信息栏 -->
                <div class="article-meta" style="margin-top: 1rem;">
                    <span class="meta-tag"><i class="fa-regular fa-calendar"></i> <?php $this->date('Y-m-d'); ?></span>
                    <span class="meta-tag"><i class="fa-solid fa-pen-nib"></i> <?php $this->author(); ?></span>
                    <?php if ($this->category): ?>
                    <span class="meta-tag"><i class="fa-solid fa-folder"></i> <?php $this->category(','); ?></span>
                    <?php endif; ?>
                    <span class="meta-tag"><i class="fa-regular fa-comments"></i> <?php $this->commentsNum('%d 条评论'); ?></span>
                </div>

                <div style="margin-top: 1.5rem; padding-top: 1rem; border-top: 2px solid #e5e7eb; display: flex; align-items: center; justify-content: space-between;">
                    <a href="<?php $this->permalink(); ?>" 
                       class="btn-primary"
                       style="
                           display: inline-flex;
                           align-items: center;
                           gap: 0.5rem;
                           font-size: 0.9rem;
                           padding: 0.625rem 1.5rem;
                       ">
                        查看详情
                        <i class="fa-solid fa-arrow-right" style="font-size: 0.75rem;"></i>
                    </a>
                    <span style="font-family: 'JetBrains Mono', monospace; font-size: 0.75rem; color: #9ca3af;">
                        #<?php echo $this->cid; ?>
                    </span>
                </div>
            </article>
            <?php endwhile; ?>
        </div>

        <!-- 分页导航 -->
        <nav style="margin-top: 3rem; text-align: center;">
            <?php $this->pageNav('&laquo;', '&raquo;'); ?>
        </nav>

        <?php else: ?>
        <!-- 无搜索结果提示 -->
        <div style="text-align: center; padding: 6rem 3rem; background-color: white; border: 4px solid black; box-shadow: 8px 8px 0 0 black;">
            <p style="font-size: 4rem; margin-bottom: 1.5rem;"><i class="fa-regular fa-folder-open text-5xl"></i></p>
            <h3 class="font-black text-2xl mb-3">未找到相关内容</h3>
            <p class="text-gray-600 font-medium mb-4">尝试使用其他关键词搜索，或浏览文章归档</p>
        </div>
        <?php endif; ?>
    </div>
</main>

<?php $this->need('footer.php'); ?>
