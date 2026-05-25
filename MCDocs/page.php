<?php
/**
 * MCDocs Theme - 独立页面模板
 * 全宽布局，无侧边栏，用于关于/联系/赞助等页面
 */

if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$this->need('header.php');
?>

<!-- 独立页 Hero 区域 (黄黑警告风格) -->
<header class="page-hero">
    <!-- 装饰性警告条（顶部） -->
    <div class="hero-stripes"></div>
    
    <div class="page-hero-content">
        <div class="page-hero-badge">
            STANDALONE PAGE
        </div>
        <h1 class="page-hero-title"><?php $this->title(); ?></h1>
        <?php if ($this->fields->subtitle): ?>
        <p class="page-hero-description" style="font-size: 1.125rem;">
            <?php $this->fields->subtitle(); ?>
        </p>
        <?php else: ?>
        <p class="page-hero-description" style="font-size: 1.125rem;">
            <?php echo mb_substr($this->description, 0, 20, 'UTF-8') . (mb_strlen($this->description, 'UTF-8') > 20 ? '...' : ''); ?>
        </p>
        <?php endif; ?>
    </div>
    
    <!-- 装饰性警告条（底部） -->
    <div class="hero-stripes bottom"></div>
</header>

<!-- 独立页内容区 (全宽布局) -->
<main class="flex-grow" style="max-width: 1280px; margin: 0 auto; padding: 4rem 1rem; width: 100%;">
    
    <article itemscope itemtype="https://schema.org/WebPage">
        
        <!-- 页面正文内容 -->
        <div itemprop="mainContentOfPage" style="max-width: 56rem; margin: 0 auto;" 
             class="prose-content page-content">
            
            <!-- 如果页面有自定义内容类型标记，使用特殊布局 -->
            <?php if ($this->fields->template === 'team'): ?>
            
            <!-- 团队成员区域 -->
            <?php elseif ($this->fields->template === 'pricing'): ?>
            
            <!-- 赞助方案区域 -->
            <?php elseif ($this->fields->template === 'faq'): ?>
            
            <!-- FAQ 区域 -->
            <?php else: ?>
            
            <!-- 默认：直接输出页面内容 -->
            <?php $this->content(); ?>
            
            <?php endif; ?>

        </div>
    </article>

</main>

<?php $this->need('footer.php'); ?>