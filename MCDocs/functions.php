<?php
/**
 * MCDocs Theme - 主题函数库
 * 包含辅助函数、钩子注册和自定义功能
 */

if (!defined('__TYPECHO_ROOT_DIR__')) exit;

/**
 * 注册主题配置面板（Typecho 1.2+）
 */
function themeConfig($form) {
    // 版本号标签
    $versionBadge = new Typecho_Widget_Helper_Form_Element_Text('versionBadge', NULL, 'v2.0 现已发布', _t('版本号标签'), _t('显示在 Hero 区域的版本号文本'));
    $form->addInput($versionBadge);
    
    // Hero 主标题
    $heroTitle = new Typecho_Widget_Helper_Form_Element_Text('heroTitle', NULL, '构建属于你的方块世界文档', _t('Hero 主标题'), _t('首页大标题文字'));
    $form->addInput($heroTitle);
    
    // GitHub 链接
    $githubUrl = new Typecho_Widget_Helper_Form_Element_Text('githubUrl', NULL, 'https://github.com/little-gt/THEME-MCDocs', _t('GitHub 链接'), _t('导航栏 GitHub 按钮链接地址'));
    $form->addInput($githubUrl);
    
    // 是否启用搜索框
    $enableSearch = new Typecho_Widget_Helper_Form_Element_Radio('enableSearch', array(
        '1' => _t('启用'),
        '0' => _t('禁用')
    ), '1', _t('搜索框'), _t('是否在导航栏显示搜索框'));
    $form->addInput($enableSearch);
    
    // 广告位代码
    $adCode = new Typecho_Widget_Helper_Form_Element_Textarea('adCode', NULL, '', _t('广告位 HTML 代码'), _t('将显示在文章页右侧边栏的广告位中，支持 HTML'));
    $form->addInput($adCode);
    
    // Google Analytics ID
    $gaId = new Typecho_Widget_Helper_Form_Element_Text('gaId', NULL, '', _t('Google Analytics ID'), _t('如 UA-XXXXX-Y 或 G-XXXXXXX'));
    $form->addInput($gaId);
    
    // 自定义 CSS
    $customCss = new Typecho_Widget_Helper_Form_Element_Textarea('customCss', NULL, '', _t('自定义 CSS'), _t('添加额外的 CSS 样式代码'));
    $form->addInput($customCss);

    // Favicon URL
    $faviconUrl = new Typecho_Widget_Helper_Form_Element_Text('faviconUrl', NULL, '', _t('Favicon 地址'), _t('网站图标 URL，留空则使用默认图标'));
    $form->addInput($faviconUrl);

    // ICP 备案号
    $icpCode = new Typecho_Widget_Helper_Form_Element_Text('icpCode', NULL, '', _t('ICP 备案号'), _t('如：京ICP备xxxxxxxx号，留空则不显示'));
    $form->addInput($icpCode);
    
    // 网安备案号
    $policeCode = new Typecho_Widget_Helper_Form_Element_Text('policeCode', NULL, '', _t('网安备案号'), _t('如：京公网安备xxxxxxxx号，留空则不显示'));
    $form->addInput($policeCode);

    // Discord 链接
    $discordUrl = new Typecho_Widget_Helper_Form_Element_Text('discordUrl', NULL, '', _t('Discord 链接'), _t('页脚社区区域的 Discord 链接地址，留空则不显示'));
    $form->addInput($discordUrl);
    
    // Twitter/X 链接
    $twitterUrl = new Typecho_Widget_Helper_Form_Element_Text('twitterUrl', NULL, '', _t('Twitter / X 链接'), _t('页脚社区区域的 Twitter 链接地址，留空则不显示'));
    $form->addInput($twitterUrl);

    // 页脚简介
    $footerDesc = new Typecho_Widget_Helper_Form_Element_Textarea('footerDesc', NULL, '', _t('页脚简介'), _t('页脚 Logo 下方的描述文字，留空则使用站点默认描述'));
    $form->addInput($footerDesc);

    // 侧边栏文章排序方式
    $sidebarSort = new Typecho_Widget_Helper_Form_Element_Radio('sidebarSort', array(
        'default' => _t('默认（按发布时间降序）'),
        'cid_asc'  => _t('CID 从小到大'),
        'cid_desc' => _t('CID 从大到小')
    ), 'default', _t('侧边栏排序'), _t('左侧导航栏中文章列表的排序方式'));
    $form->addInput($sidebarSort);
}

/**
 * 注册文章/页面自定义字段（在编辑界面显示）
 */
function themeFields($layout) {
    $excerpt = new Typecho_Widget_Helper_Form_Element_Textarea('excerpt', NULL, '', _t('文章摘要'), _t('显示在文章标题下方的摘要文字，留空则不显示'));
    $layout->addItem($excerpt);

    $notice = new Typecho_Widget_Helper_Form_Element_Textarea('notice', NULL, '', _t('引用块提示'), _t('以引用块样式显示在正文顶部，可用于公告、警告、更新说明等'));
    $layout->addItem($notice);

    $subtitle = new Typecho_Widget_Helper_Form_Element_Text('subtitle', NULL, '', _t('页面副标题'), _t('独立页 Hero 区域的副标题文字，留空则自动截取描述前 20 字'));
    $layout->addItem($subtitle);

    $template = new Typecho_Widget_Helper_Form_Element_Radio('template', array(
        ''       => _t('默认（直接输出内容）'),
        'team'   => _t('团队成员展示'),
        'pricing'=> _t('赞助方案'),
        'faq'    => _t('常见问题')
    ), '', _t('页面模板类型'), _t('选择独立页面的内容布局方式（当前仅默认模式可用）'));
    $layout->addItem($template);
}

/**
 * 文章元信息输出函数
 */
function postMeta(
    \Widget\Archive $archive,
    string $metaType = 'archive'
)
{
    $titleTag = $metaType == 'archive' ? 'h2' : 'h1';
?>
    <<?php echo $titleTag ?> class="post-title" itemprop="name headline">
        <a itemprop="url"
           href="<?php $archive->permalink() ?>"><?php $archive->title() ?></a>
    </<?php echo $titleTag ?>>
    <?php if ($metaType != 'page'): ?>
        <ul class="post-meta">
            <li itemprop="author" itemscope itemtype="http://schema.org/Person">
                <?php _e('作者'); ?>: <a itemprop="name"
                                       href="<?php $archive->author->permalink(); ?>"
                                       rel="author"><?php $archive->author(); ?></a>
            </li>
            <li><?php _e('时间'); ?>:
                <time datetime="<?php $archive->date('c'); ?>" itemprop="datePublished"><?php $archive->date(); ?></time>
            </li>
            <li><?php _e('分类'); ?>: <?php $archive->category(','); ?></li>
            <?php if ($metaType == 'archive'): ?>
                <li itemprop="interactionCount">
                    <a itemprop="discussionUrl"
                       href="<?php $archive->permalink() ?>#comments"><?php $archive->commentsNum(_t('暂无评论'), _t('1 条评论'), _t('%d 条评论')); ?></a>
                </li>
            <?php endif; ?>
        </ul>
    <?php endif; ?>
<?php
}

/**
 * 获取文章缩略图（如果有）
 */
function getPostThumbnail($archive) {
    // 检查是否有自定义字段设置缩略图
    if ($archive->fields->thumbnail) {
        return $archive->fields->thumbnail;
    }
    
    // 尝试从内容中提取第一张图片
    preg_match('/<img[^>]+src=[\'"]([^\'"]+)[\'"][^>]*>/i', $archive->content, $matches);
    
    if (!empty($matches[1])) {
        return $matches[1];
    }
    
    // 返回默认占位图
    return false;
}

/**
 * 输出面包屑导航
 * 
 * @param \Widget\Archive $archive 当前归档对象
 */
function breadcrumb($archive) {
    $siteUrl = $archive->options->siteUrl;
    
    echo '<nav aria-label="面包屑导航" style="margin-bottom: 2rem; font-size: 0.875rem;">';
    echo '<a href="' . $siteUrl . '" style="color: inherit; text-decoration: none;">首页</a>';
    
    if ($archive->is('category')) {
        echo ' > <span>' . htmlspecialchars((string)$archive->title) . '</span>';
    } elseif ($archive->is('post')) {
        if (!empty($archive->category)) {
            echo ' > <a href="' . htmlspecialchars((string)$archive->category['permalink']) . '" style="color: inherit; text-decoration: none;">' . htmlspecialchars((string)$archive->category['name']) . '</a>';
        }
        echo ' > <span>' . htmlspecialchars((string)$archive->title) . '</span>';
    } elseif ($archive->is('page')) {
        echo ' > <span>' . htmlspecialchars((string)$archive->title) . '</span>';
    } elseif ($archive->is('search')) {
        echo ' > <span>搜索：' . htmlspecialchars($archive->request->get('s') ?: '') . '</span>';
    }
    
    echo '</nav>';
}