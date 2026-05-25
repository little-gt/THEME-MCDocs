<?php
/**
 * MCDocs Theme - 分类页面模板
 */

if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$this->need('header.php');
?>

<main class="flex-grow">
    <div style="max-width: 1280px; margin: 0 auto; padding: 4rem 1rem; width: 100%;">
        <!-- 分类标题区域 -->
        <div style="margin-bottom: 3rem;">
            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                <h1 class="font-black text-3xl uppercase tracking-wide"><?php $this->getArchiveTitle(); ?></h1>
            </div>
            
            <div style="background-color: #dbeafe; border: 2px solid #000; padding: 1.25rem 1.5rem; box-shadow: 4px 4px 0 0 #000;">
                <p style="font-weight: 700; margin-bottom: 0.5rem;">分类简介</p>
                <p style="font-size: 0.9rem; color: #1e40af;"><?php echo $this->getArchiveDescription() ? $this->getArchiveDescription() : '该分类暂无描述'; ?></p>
                <p style="font-size: 0.875rem; color: #6b7280; margin-top: 0.5rem;">共 <strong><?php echo $this->getTotal(); ?></strong> 篇文章</p>
            </div>
        </div>

        <!-- 文章列表 -->
        <?php if ($this->have()): ?>
        <div style="display: grid; gap: 1.5rem;">
            <?php while ($this->next()): ?>
            <article class="bg-white border-2 border-black p-5 block-shadow hover:-translate-y-1 hover:block-shadow-sm transition-all duration-300" 
                     itemscope itemtype="https://schema.org/Article"
                     style="display: flex; flex-direction: column;">
                <h3 class="text-xl md:text-2xl font-bold mb-2">
                    <a href="<?php $this->permalink(); ?>" 
                       itemprop="url"
                       rel="bookmark"
                       style="color: inherit; text-decoration: none;">
                        <span itemprop="name"><?php $this->title(); ?></span>
                    </a>
                </h3>

                <p class="text-gray-600 font-medium line-clamp-3" 
                   style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;"
                   itemprop="description">
                    <?php $this->excerpt(150, '...'); ?>
                </p>

                <div class="article-meta" style="margin-top: 1rem;">
                    <span class="meta-tag"><i class="fa-regular fa-calendar"></i> <?php $this->date('Y-m-d'); ?></span>
                    <span class="meta-tag"><i class="fa-solid fa-pen-nib"></i> <?php $this->author(); ?></span>
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
                        阅读全文
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
        <div style="text-align: center; padding: 6rem 3rem; background-color: white; border: 4px solid black; box-shadow: 8px 8px 0 0 black;">
            <p style="font-size: 4rem; margin-bottom: 1.5rem;"><i class="fa-regular fa-envelope-open text-5xl"></i></p>
            <h3 class="font-black text-2xl mb-3">该分类暂无文章</h3>
            <p class="text-gray-600 font-medium mb-4">快来发布第一篇文章吧！</p>
            
            <a href="<?php $this->options->siteUrl(); ?>" class="btn-primary">
                ← 返回首页
            </a>
        </div>
        <?php endif; ?>
    </div>
</main>

<?php $this->need('footer.php'); ?>