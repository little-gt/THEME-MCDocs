<?php
/**
 * MCDocs Theme - 公共头部组件
 * 包含导航栏、Logo、搜索框和菜单
 */

if (!defined('__TYPECHO_ROOT_DIR__')) exit;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="<?php $this->options->charset(); ?>">
    <meta name="renderer" content="webkit">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title><?php $this->archiveTitle([
            'category' => _t('分类 %s 下的文章'),
            'search'   => _t('包含关键字 %s 的文章'),
            'tag'      => _t('标签 %s 下的文章'),
            'author'   => _t('%s 发布的文章')
        ], '', ' - '); ?><?php $this->options->title(); ?></title>

    <!-- 加载样式表 -->
    <link rel="stylesheet" href="<?php $this->options->themeUrl('style.css'); ?>">
    
    <!-- Favicon -->
    <?php if (!empty($this->options->faviconUrl)): ?>
    <link rel="icon" href="<?php echo htmlspecialchars((string)$this->options->faviconUrl); ?>">
    <?php endif; ?>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.garfieldtom.cool/resource/libs/tailwind/3.4.17/tailwindcss.js"></script>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;700;800&family=Noto+Sans+SC:wght@400;700;900&display=swap" type="text/css" rel="stylesheet">
    
    <!-- FontAwesome -->
    <link href="https://cdn.garfieldtom.cool/resource/libs/fontawesome/7.2.0/css/all.min.css" type="text/css" rel="stylesheet">
    
    <!-- 输出用户自定义头部内容 -->
    <?php $this->header(); ?>
</head>

<body class="text-gray-900 min-h-screen flex flex-col">
    
    <!-- 顶部导航栏 -->
    <nav class="header-nav">
        <div class="header-container">
            <!-- Logo 区域 -->
            <a href="<?php $this->options->siteUrl(); ?>" class="logo-group block-hover">
                <div class="logo-icon">
                    <span class="text-white font-black font-mono text-xl">MC</span>
                </div>
                <span class="logo-text"><?php $this->options->title(); ?></span>
            </a>

            <!-- 搜索框 (桌面端显示) -->
            <?php if ($this->options->enableSearch == '1'): ?>
            <div class="search-box hidden md:block">
                <form action="<?php $this->options->siteUrl(); ?>" method="get" role="search">
                    <input type="text" name="s" 
                           id="searchInput"
                           <?php if($this->is('search')): ?>value="<?php echo htmlspecialchars((string)$this->request->get('s')); ?>"<?php endif; ?>
                           placeholder="搜索文档 (Ctrl+K)" 
                           class="search-input">
                </form>
            </div>
            <?php endif; ?>

            <!-- 右侧链接区域 -->
            <div class="nav-links hidden md:flex items-center space-x-6 font-bold">
                <?php \Widget\Contents\Page\Rows::alloc()->to($pages); ?>
                <?php while($pages->next()): ?>
                <a<?php if ($this->is('page', $pages->slug)): ?> class="nav-link current"<?php else: ?> class="nav-link hover:text-green-600"<?php endif; ?>
                   href="<?php $pages->permalink(); ?>"><?php $pages->title(); ?></a>
                <?php endwhile; ?>
                
                <a href="<?php echo htmlspecialchars((string)$this->options->githubUrl); ?>" class="btn-ghost btn-hover" target="_blank" rel="noopener noreferrer">GitHub</a>
            </div>

            <!-- 移动端菜单按钮 -->
            <button class="mobile-menu-btn" onclick="toggleMobileMenu()" aria-label="打开菜单">
                ☰
            </button>
        </div>
        
        <!-- 移动端下拉菜单 -->
        <div id="mobileMenu" style="display: none;" class="mobile-menu-container">
            <div style="padding: 1rem; border-top: 2px solid #000; background-color: white;">
                <?php if ($this->options->enableSearch == '1'): ?>
                <form action="<?php $this->options->siteUrl(); ?>" method="get" style="margin-bottom: 1rem;">
                    <input type="text" name="s" 
                           <?php if($this->is('search')): ?>value="<?php echo htmlspecialchars((string)$this->request->get('s')); ?>"<?php endif; ?>
                           placeholder="搜索..." 
                           class="search-input" style="width: 100%;">
                </form>
                <?php endif; ?>
                
                <?php \Widget\Contents\Page\Rows::alloc()->to($pages); ?>
                <?php while($pages->next()): ?>
                <a href="<?php $pages->permalink(); ?>" class="nav-link" style="display: block; padding: 0.5rem 0;"><?php $pages->title(); ?></a>
                <?php endwhile; ?>
                
                <a href="<?php echo htmlspecialchars((string)$this->options->githubUrl); ?>" class="btn-ghost" style="margin-top: 1rem;" target="_blank" rel="noopener noreferrer">GitHub</a>
            </div>
        </div>
    </nav>

<style>
/* 移动端菜单：仅在小于 768px 时通过 JS 显示 */
.mobile-menu-container {
    display: none !important;
}

@media (max-width: 767px) {
    .mobile-menu-container.active {
        display: block !important;
    }
}
</style>

<script>
// 移动端菜单切换
function toggleMobileMenu() {
    const menu = document.getElementById('mobileMenu');
    if (menu) {
        if (menu.classList.contains('active')) {
            menu.classList.remove('active');
            menu.style.display = 'none';
        } else {
            menu.classList.add('active');
            menu.style.display = 'block';
        }
    }
}

// Ctrl+K 快捷键聚焦搜索框
document.addEventListener('keydown', function(e) {
    if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
        e.preventDefault();
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.focus();
        }
    }
});
</script>