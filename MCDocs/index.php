<?php
/**
 * MCDocs 主题，一款文档风格的 Typecho 主题。
 *
 * @package MCDocs
 * @author GARFIELDTOM
 * @version 1.0
 * @link http://www.gfieldtom.cool
 */

if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$this->need('header.php');
?>

<main class="flex-grow">
    <!-- Hero 首屏区域 -->
    <section class="hero-section">
        <div class="hero-container">
            <!-- 左侧：文字内容 -->
            <div class="hero-content">
                <div class="version-badge">
                    <?php echo htmlspecialchars($this->options->versionBadge); ?>
                </div>
                
                <h1 class="hero-title">
                    <?php echo htmlspecialchars($this->options->heroTitle); ?>
                </h1>
                
                <p class="hero-description">
                    <?php $this->options->description(); ?>
                </p>
                
                <!-- 按钮组 -->
                <div class="hero-buttons">
                    <a href="#latest-posts" class="btn-primary">
                        <span>开始使用</span>
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="stroke-width: 3; stroke-linecap: square;">
                            <path d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                    <a href="#features" class="btn-secondary">
                        浏览组件库
                    </a>
                </div>
            </div>

            <!-- 右侧：装饰性方块阵列 -->
            <div class="hero-decoration">
                <div class="relative w-80 h-80">
                    <!-- 蓝色配置卡片 -->
                    <div class="decoration-card-blue">
                        <div class="p-4 font-mono font-bold text-white">
                            # config.yml
                        </div>
                        <div class="px-4 text-white/80 font-mono text-sm">
                            name: "<?php echo htmlspecialchars($this->options->title()); ?>"<br>
                            version: 2.0
                        </div>
                    </div>
                    
                    <!-- 黄色闪电图标 -->
                    <div class="decoration-card-yellow">
                        <i class="fa-solid fa-bolt text-3xl"></i>
                    </div>
                    
                    <!-- 绿色背景方块 -->
                    <div class="decoration-card-green"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- 分类网格区 -->
    <section class="features-section" id="features">
        <div style="max-width: 1280px; margin: 0 auto; padding: 0 1rem;">
            <h2 class="text-center font-black mb-12 text-3xl md:text-4xl uppercase tracking-wide">
                文档分类
            </h2>
            
            <?php
            \Widget\Metas\Category\Rows::alloc()->to($indexCategories);
            
            // 预加载每个分类的第一篇文章链接
            $db = \Typecho\Db::get();
            $firstPosts = [];
            if ($indexCategories->have()):
                $indexCategories->rewind();
                while ($indexCategories->next()) {
                    $mid = $indexCategories->mid;
                    $firstPost = $db->fetchRow(
                        $db->select('c.cid', 'c.slug', 'c.created')
                            ->from('table.contents AS c')
                            ->join('table.relationships AS r', 'c.cid = r.cid')
                            ->where('r.mid = ?', $mid)
                            ->where('c.type = ?', 'post')
                            ->where('c.status = ?', 'publish')
                            ->order('c.created', \Typecho\Db::SORT_DESC)
                            ->limit(1)
                    );
                    if ($firstPost) {
                        $firstPosts[$mid] = \Typecho\Router::url('post', [
                            'cid' => $firstPost['cid'],
                            'slug' => urlencode($firstPost['slug']),
                            'directory' => '', 'category' => '',
                            'year' => date('Y', $firstPost['created']),
                            'month' => date('m', $firstPost['created']),
                            'day' => date('d', $firstPost['created'])
                        ], $this->options->index);
                    }
                }
                
                // 图标和背景色轮换
                $cardStyles = [
                    ['icon' => 'fa-solid fa-book-open',   'bg' => '#eff6ff', 'accent' => '#2563eb'],
                    ['icon' => 'fa-solid fa-folder-tree','bg' => '#f0fdf4', 'accent' => '#059669'],
                    ['icon' => 'fa-solid fa-code',       'bg' => '#faf5ff', 'accent' => '#7c3aed'],
                    ['icon' => 'fa-solid fa-wrench',     'bg' => '#fef3c7', 'accent' => '#d97706'],
                    ['icon' => 'fa-solid fa-rocket',     'bg' => '#fce7f3', 'accent' => '#db2777'],
                    ['icon' => 'fa-solid fa-shield-halved', 'bg' => '#f3e8ff', 'accent' => '#9333ea'],
                ];
                $styleIndex = 0;
            ?>
            
            <div class="features-grid">
                <?php $indexCategories->rewind(); ?>
                <?php while ($indexCategories->next()): 
                    $catMid = $indexCategories->mid;
                    $style = $cardStyles[$styleIndex % count($cardStyles)];
                    $firstLink = isset($firstPosts[$catMid]) ? $firstPosts[$catMid] : '#';
                    
                    // 获取该分类的文章数
                    $catPostCount = $db->fetchObject(
                        $db->select('COUNT(c.cid) AS cnt')
                            ->from('table.contents AS c')
                            ->join('table.relationships AS r', 'c.cid = r.cid')
                            ->where('r.mid = ?', $catMid)
                            ->where('c.type = ?', 'post')
                            ->where('c.status = ?', 'publish')
                    );
                    $count = $catPostCount ? (int)$catPostCount->cnt : 0;
                ?>
                <a href="<?php echo $firstLink; ?>" class="feature-card block-hover" style="
                    background-color: <?php echo htmlspecialchars($style['bg']); ?>;
                    text-decoration: none;
                    color: inherit;
                    display: block;
                ">
                    <div class="feature-icon" style="background-color: <?php echo htmlspecialchars($style['accent']); ?>;">
                        <i class="<?php echo htmlspecialchars($style['icon']); ?>"></i>
                    </div>
                    <h3 class="feature-title"><?php $indexCategories->name(); ?></h3>
                    <p class="feature-description" style="
                        display: -webkit-box;
                        -webkit-line-clamp: 2;
                        -webkit-box-orient: vertical;
                        overflow: hidden;
                    ">
                        <?php
                        $desc = $indexCategories->description();
                        echo !empty($desc) ? htmlspecialchars($desc) : '浏览此分类下的文档内容';
                        ?>
                    </p>
                    <div style="
                        display: inline-flex;
                        align-items: center;
                        gap: 0.5rem;
                        margin-top: auto;
                        padding-top: 0.75rem;
                        border-top: 1px solid rgba(0,0,0,0.08);
                        font-size: 0.8125rem;
                        font-weight: 700;
                        font-family: 'JetBrains Mono', monospace;
                        color: <?php echo htmlspecialchars($style['accent']); ?>;
                    ">
                        <span><?php echo $count; ?></span> 篇文章
                        <i class="fa-solid fa-arrow-right" style="font-size: 0.6875rem;"></i>
                    </div>
                </a>
                <?php 
                    $styleIndex++;
                endwhile; ?>
            </div>
            
            <?php endif; ?>
        </div>
    </section>

    <!-- 最新文章列表区域 -->
    <section id="latest-posts" style="padding: 5rem 0; max-width: 1280px; margin: 0 auto; width: 100%;">
        <div style="padding: 0 1rem;">
            <h2 class="font-black mb-8 text-3xl uppercase tracking-wide border-b-4 border-black pb-4">
                最新文章
            </h2>

            <?php if ($this->have()): ?>
            <div style="display: grid; gap: 2rem;">
                <?php while ($this->next()): ?>
                <article class="bg-white border-2 border-black p-6 block-shadow hover:-translate-y-1 hover:block-shadow-sm transition-all duration-300" 
                         style="display: flex; flex-direction: column; gap: 1rem;"
                         itemscope itemtype="https://schema.org/Article">
                    <!-- 文章标题 -->
                    <h3 class="text-xl md:text-2xl font-bold">
                        <a href="<?php $this->permalink(); ?>" 
                           itemprop="url"
                           rel="bookmark"
                           style="color: inherit; text-decoration: none;"
                           onmouseover="this.style.color='#059669'"
                           onmouseout="this.style.color='inherit'">
                            <span itemprop="name"><?php $this->title(); ?></span>
                        </a>
                    </h3>

                    <!-- 文章摘要 -->
                    <p class="text-gray-600 font-medium line-clamp-3" 
                       style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;"
                       itemprop="description">
                        <?php $this->excerpt(150, '...'); ?>
                    </p>

                    <!-- 元信息栏 -->
                    <div class="article-meta" style="margin-bottom: 0;">
                        <span class="meta-tag">
                            <i class="fa-regular fa-calendar"></i> <?php $this->date('Y-m-d'); ?>
                        </span>
                        <span class="meta-tag">
                            <i class="fa-solid fa-pen-nib"></i> <?php $this->author(); ?>
                        </span>
                        <?php if ($this->category): ?>
                        <span class="meta-tag">
                            <i class="fa-solid fa-folder"></i> <?php $this->category(','); ?>
                        </span>
                        <?php endif; ?>
                        <span class="meta-tag">
                            <i class="fa-regular fa-comments"></i> <?php $this->commentsNum('%d 条评论'); ?>
                        </span>
                    </div>

                    <!-- 阅读更多按钮 -->
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
            <?php $this->pageNav('&laquo;', '&raquo;'); ?>

            <?php else: ?>
            <!-- 无文章提示 -->
            <div style="text-align: center; padding: 4rem 2rem; background-color: white; border: 4px solid black; box-shadow: 6px 6px 0 0 black;">
                <p style="font-size: 3rem; margin-bottom: 1rem;"><i class="fa-regular fa-envelope-open text-4xl"></i></p>
                <h3 class="font-black text-2xl mb-4">暂无文章</h3>
                <p class="text-gray-600 font-medium">还没有发布任何文章，快来写第一篇吧！</p>
            </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php $this->need('footer.php'); ?>