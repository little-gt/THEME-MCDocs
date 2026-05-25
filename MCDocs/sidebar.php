<?php
/**
 * MCDocs Theme - 左侧文档导航组件
 * 文档系统风格的分类和文章导航
 */

if (!defined('__TYPECHO_ROOT_DIR__')) exit;
?>

<aside class="sidebar-left" role="navigation" aria-label="文档导航">
    <?php
    \Widget\Metas\Category\Rows::alloc()->to($categories);
    
    if ($categories->have()):
        
        $currentCategoryId = null;
        $currentPostId = null;
        
        if ($this->is('post')) {
            $currentCategoryId = !empty($this->categories) ? $this->categories[0]['mid'] : null;
            $currentPostId = $this->cid;
        } elseif ($this->is('category')) {
            $currentCategoryId = $this->mid;
        } else {
            $categories->rewind();
            if ($categories->have()) {
                $currentCategoryId = $categories->mid;
            }
        }

        $db = \Typecho\Db::get();
        $allPostsRaw = $db->fetchAll(
            $db->select('c.cid', 'c.title', 'c.slug', 'c.created', 'r.mid')
                ->from('table.contents AS c')
                ->join('table.relationships AS r', 'c.cid = r.cid')
                ->where('c.type = ?', 'post')
                ->where('c.status = ?', 'publish')
                ->order('c.created', \Typecho\Db::SORT_DESC)
        );

        $groupedPosts = [];
        foreach ($allPostsRaw as $row) {
            $mid = $row['mid'];
            if (!isset($groupedPosts[$mid])) {
                $groupedPosts[$mid] = [];
            }
            $groupedPosts[$mid][] = $row;
        }
    ?>
    
    <!-- 文档树 -->
    <nav class="docs-tree">
        <?php while ($categories->next()): 
            $catId = $categories->mid;
            $isActive = ($currentCategoryId && $catId == $currentCategoryId);
            
            $catPosts = isset($groupedPosts[$catId]) ? $groupedPosts[$catId] : [];
            $hasPosts = !empty($catPosts);
            
            $iconClass = 'fa-solid fa-chevron-right';
            if (!$hasPosts) {
                $iconClass = 'fa-regular fa-file-lines';
            } elseif ($isActive) {
                $iconClass = 'fa-solid fa-chevron-down';
            }
        ?>
        <div class="docs-section">
            <button 
                class="section-header<?php echo $isActive ? ' expanded' : ''; ?>"
                aria-expanded="<?php echo $isActive ? 'true' : 'false'; ?>"
            >
                <span class="section-icon">
                    <i class="<?php echo $iconClass; ?>"></i>
                </span>
                <span class="section-title">
                    <?php $categories->name(); ?>
                    <?php if ($hasPosts): ?>
                    <span class="section-count"><?php echo count($catPosts); ?></span>
                    <?php endif; ?>
                </span>
            </button>
            
            <?php if ($hasPosts): ?>
            <ul class="section-items<?php echo $isActive ? ' show' : ''; ?>">
                <?php foreach ($catPosts as $post): 
                    $postId = $post['cid'];
                    $postTitle = $post['title'];
                    
                    $permalink = \Typecho\Router::url('post', [
                        'cid' => $postId,
                        'slug' => urlencode($post['slug']),
                        'directory' => '',
                        'category' => '',
                        'year' => date('Y', $post['created']),
                        'month' => date('m', $post['created']),
                        'day' => date('d', $post['created'])
                    ], $this->options->index);
                    
                    $isPostActive = ($currentPostId && $postId == $currentPostId);
                ?>
                <li class="section-item">
                    <a href="<?php echo $permalink; ?>" 
                       class="item-link<?php echo $isPostActive ? ' active' : ''; ?>">
                        <span class="item-bullet">•</span>
                        <span class="item-text"><?php echo htmlspecialchars($postTitle); ?></span>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>
        </div>
        <?php endwhile; ?>
    </nav>
    
    <?php else: ?>
    
    <div class="empty-state">
        <div class="empty-icon"><i class="fa-solid fa-folder-open text-4xl"></i></div>
        <p class="empty-title">暂无分类</p>
        <p class="empty-desc">请在后台创建文章分类</p>
    </div>
    
    <?php endif; ?>
</aside>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sectionButtons = document.querySelectorAll('.section-header');
    sectionButtons.forEach(button => {
        button.addEventListener('click', function() {
            const iconEl = this.querySelector('.section-icon i');
            const itemsList = this.nextElementSibling;
            
            if (this.classList.contains('expanded')) {
                this.classList.remove('expanded');
                this.setAttribute('aria-expanded', 'false');
                if (!iconEl.classList.contains('fa-file-lines')) {
                    iconEl.className = 'fa-solid fa-chevron-right';
                }
                if (itemsList) {
                    itemsList.classList.remove('show');
                }
            } else {
                this.classList.add('expanded');
                this.setAttribute('aria-expanded', 'true');
                if (!iconEl.classList.contains('fa-file-lines')) {
                    iconEl.className = 'fa-solid fa-chevron-down';
                }
                if (itemsList) {
                    itemsList.classList.add('show');
                }
            }
        });
    });
});
</script>
