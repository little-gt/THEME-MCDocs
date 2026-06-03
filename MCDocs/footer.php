<?php
/**
 * MCDocs Theme - 公共底部组件
 * 包含页脚信息、链接分组、版权声明、备案信息
 */

if (!defined('__TYPECHO_ROOT_DIR__')) exit;
?>

    <!-- 页脚 -->
    <footer class="footer-main">
        <div class="footer-container">
            <div class="footer-content">
                <!-- 左侧：Logo 和简介 -->
                <div class="footer-brand">
                    <div class="footer-logo">
                        <div class="footer-logo-icon">MC</div>
                        <span class="footer-logo-text"><?php $this->options->title(); ?></span>
                    </div>
                    <p class="footer-description">
                        <?php echo !empty($this->options->footerDesc) ? htmlspecialchars((string)$this->options->footerDesc) : $this->options->description(); ?>
                    </p>
                </div>

                <!-- 右侧：链接群 -->
                <div class="footer-links">
                    <!-- 资源链接（自动读取独立页面） -->
                    <div class="footer-link-group">
                        <h4 class="footer-link-title">资源</h4>
                        <?php \Widget\Contents\Page\Rows::alloc()->to($pages); ?>
                        <?php if ($pages->have()): ?>
                            <?php while($pages->next()): ?>
                            <a href="<?php $pages->permalink(); ?>" class="footer-link"><?php $pages->title(); ?></a>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <span class="footer-link" style="color: #9ca3af;">暂无页面</span>
                        <?php endif; ?>
                    </div>

                    <!-- 社区/外部链接（可配置） -->
                    <div class="footer-link-group">
                        <h4 class="footer-link-title">社区</h4>
                        
                        <?php if (!empty($this->options->discordUrl)): ?>
                        <a href="<?php echo htmlspecialchars((string)$this->options->discordUrl); ?>" class="footer-link" target="_blank" rel="noopener noreferrer">
                            <i class="fa-brands fa-discord" style="margin-right: 0.25rem;"></i>Discord
                        </a>
                        <?php endif; ?>
                        
                        <?php if (!empty($this->options->twitterUrl)): ?>
                        <a href="<?php echo htmlspecialchars((string)$this->options->twitterUrl); ?>" class="footer-link" target="_blank" rel="noopener noreferrer">
                            <i class="fa-brands fa-x-twitter" style="margin-right: 0.25rem;"></i>Twitter / X
                        </a>
                        <?php endif; ?>
                        
                        <?php if (!empty($this->options->githubUrl)): ?>
                        <a href="<?php echo htmlspecialchars((string)$this->options->githubUrl); ?>" class="footer-link" target="_blank" rel="noopener noreferrer">
                            <i class="fa-brands fa-github" style="margin-right: 0.25rem;"></i>GitHub
                        </a>
                        <?php endif; ?>

                        <a href="<?php $this->options->feedUrl(); ?>" class="footer-link">
                            <i class="fa-solid fa-rss" style="margin-right: 0.25rem;"></i>RSS 订阅
                        </a>
                    </div>
                </div>
            </div>

            <!-- 底部：版权信息 + 备案号 -->
            <div class="footer-bottom">
                <div class="footer-copyright">
                    &copy; <?php echo date('Y'); ?> <?php $this->options->title(); ?>. All rights reserved.
                </div>
                
                <div class="footer-filing">
                    <?php if (!empty($this->options->icpCode)): ?>
                    <a href="https://beian.miit.gov.cn/" target="_blank" rel="noopener noreferrer" class="filing-link">
                        <?php echo htmlspecialchars((string)$this->options->icpCode); ?>
                    </a>
                    <?php endif; ?>
                    
                    <?php if (!empty($this->options->policeCode)): ?>
                    <a href="https://beian.mps.gov.cn/#/query/webSearch" target="_blank" rel="noopener noreferrer" class="filing-link police-link">
                        <?php echo htmlspecialchars((string)$this->options->policeCode); ?>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </footer>

    <!-- 输出用户自定义尾部内容（如统计代码等） -->
    <?php $this->footer(); ?>

    <!-- Google Analytics -->
    <?php if (!empty($this->options->gaId)): ?>
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo htmlspecialchars((string)$this->options->gaId); ?>"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '<?php echo htmlspecialchars((string)$this->options->gaId); ?>');
    </script>
    <?php endif; ?>

    <!-- 自定义 CSS -->
    <?php if (!empty($this->options->customCss)): ?>
    <style><?php echo $this->options->customCss; ?></style>
    <?php endif; ?>

</body>
</html>
