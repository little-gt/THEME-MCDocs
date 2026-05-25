<?php
/**
 * MCDocs Theme - 评论组件
 * 显示评论列表和评论表单
 */

if (!defined('__TYPECHO_ROOT_DIR__')) exit;

// 判断是否允许评论
if ($this->allow('comment')):
    // 获取当前评论关闭状态
    $closeComment = false;
    
    // 检查是否在文章页面且设置了关闭评论
    if ($this->is('post') && isset($this->fields->closeComment)) {
        $closeComment = (bool)$this->fields->closeComment;
    }
    
    // 【关键修复】必须在 if/else 之前初始化 $comments，否则无评论时 cancelReply() 会报致命错误
    $this->comments()->to($comments);
?>

<div id="comments" style="margin-top: 4rem; padding-top: 3rem; border-top: 4px solid #000;">
    
    <?php if ($this->commentsNum > 0): ?>
    <!-- 评论列表标题 -->
    <h3 style="font-weight: 900; font-size: 2rem; margin-bottom: 2rem; text-transform: uppercase;">
        <i class="fa-regular fa-comments"></i> 评论 (<?php $this->commentsNum(); ?>)
    </h3>
    
    <!-- 评论列表（使用 Typecho 标准 listComments 支持嵌套回复） -->
    <div id="comment-list">
        <?php $comments->listComments([
            'before'        => '',
            'after'         => '',
            'beforeAuthor'  => '',
            'afterAuthor'   => '',
            'beforeDate'    => '',
            'afterDate'     => '',
            'dateFormat'    => 'Y-m-d H:i',
            'replyWord'     => _t('回复'),
            'commentStatus' => _t('待审核'),
            'avatarSize'    => 48,
            'defaultAvatar' => null,
        ]); ?>
    </div>

    <!-- 分页 -->
    <nav style="margin-top: 2rem;">
        <?php $comments->pageNav('&laquo;', '&raquo;'); ?>
    </nav>

    <?php else: ?>
    <!-- 无评论提示 -->
    <div style="
        text-align: center;
        padding: 3rem 2rem;
        background-color: #f0fdf4;
        border: 4px solid #000;
        box-shadow: 6px 6px 0 0 #000;
        margin-bottom: 2rem;
    ">
        <p style="font-size: 2rem; margin-bottom: 1rem;"><i class="fa-regular fa-comments text-3xl"></i></p>
        <h3 style="font-weight: 900; font-size: 1.25rem; margin-bottom: 0.5rem;">暂无评论</h3>
        <p style="color: #6b7280; font-weight: 500;">快来发表第一条评论吧！</p>
    </div>
    <?php endif; ?>

    <?php if (!$closeComment): ?>
    <!-- 评论表单容器（TypechoComment JS 通过 #respond 定位此元素） -->
    <div id="<?php $this->respondId(); ?>" class="respond">
        
        <!-- 取消回复链接（TypechoComment.cancelReply() 控制显隐） -->
        <div class="cancel-comment-reply" style="margin-bottom: 1rem; display: none;">
            <?php $comments->cancelReply(); ?>
        </div>

        <div id="comment-form-wrapper" style="
            background-color: white;
            border: 4px solid #000;
            box-shadow: 8px 8px 0 0 #000;
            padding: 2rem;
        ">
            <h3 id="comment-form-title" style="
                font-weight: 900;
                font-size: 1.5rem;
                margin-bottom: 1.5rem;
                text-transform: uppercase;
                border-bottom: 2px solid #e5e7eb;
                padding-bottom: 0.75rem;
            ">
                <i class="fa-solid fa-pen-nib"></i> 发表评论
            </h3>

            <form method="post" action="<?php $this->commentUrl() ?>" id="comment-form">
                <!-- 反垃圾评论 Token（PHP 兜底，确保提交时 token 已存在） -->
                <input type="hidden" name="_" value="<?php echo $this->security->getToken($this->request->getRequestUrl()); ?>">
                
                <!-- 用户信息输入（未登录时显示） -->
                <?php if (!$this->user->hasLogin()): ?>
                <div style="display: grid; gap: 1rem; margin-bottom: 1.5rem;">
                    <input type="text" name="author" 
                           placeholder="昵称 *" 
                           required
                           value="<?php $this->remember('author'); ?>"
                           style="
                               width: 100%;
                               padding: 0.75rem 1rem;
                               border: 2px solid #000;
                               font-size: 1rem;
                               outline: none;
                               font-family: inherit;
                           "
                           onfocus="this.style.boxShadow='4px 4px 0 0 #10b981'"
                           onblur="this.style.boxShadow='none'" />
                           
                    <input type="email" name="mail" 
                           placeholder="邮箱 *" 
                           required
                           value="<?php $this->remember('mail'); ?>"
                           style="
                               width: 100%;
                               padding: 0.75rem 1rem;
                               border: 2px solid #000;
                               font-size: 1rem;
                               outline: none;
                               font-family: inherit;
                           "
                           onfocus="this.style.boxShadow='4px 4px 0 0 #10b981'"
                           onblur="this.style.boxShadow='none'" />
                           
                    <input type="url" name="url" 
                           placeholder="网站（选填）"
                           value="<?php $this->remember('url'); ?>"
                           style="
                               width: 100%;
                               padding: 0.75rem 1rem;
                               border: 2px solid #000;
                               font-size: 1rem;
                               outline: none;
                               font-family: inherit;
                           "
                           onfocus="this.style.boxShadow='4px 4px 0 0 #10b981'"
                           onblur="this.style.boxShadow='none'" />
                </div>
                <?php else: ?>
                <!-- 已登录用户信息 -->
                <div style="
                    display: flex;
                    align-items: center;
                    gap: 0.75rem;
                    margin-bottom: 1.5rem;
                    padding: 0.75rem 1rem;
                    background-color: #f3f4f6;
                    border: 2px solid #000;
                    font-size: 0.9rem;
                ">
                    <i class="fa-solid fa-user-check" style="color: #10b981;"></i>
                    <span>登录身份：</span>
                    <strong><?php $this->user->screenName(); ?></strong>
                    <a href="<?php $this->options->logoutUrl(); ?>" style="
                        margin-left: auto;
                        color: #dc2626;
                        font-weight: 700;
                        text-decoration: none;
                    ">退出 &raquo;</a>
                </div>
                <?php endif; ?>

                <!-- 评论内容 -->
                <textarea name="text" 
                          id="textarea"
                          rows="6" 
                          required
                          placeholder="写下你的想法..."
                          style="
                              width: 100%;
                              padding: 1rem;
                              border: 2px solid #000;
                              font-size: 1rem;
                              resize: vertical;
                              outline: none;
                              font-family: inherit;
                              line-height: 1.6;
                              min-height: 150px;
                          "
                          onfocus="this.style.boxShadow='4px 4px 0 0 #10b981'"
                          onblur="this.style.boxShadow='none'"><?php $this->remember('text'); ?></textarea>

                <!-- 提交按钮 -->
                <div style="margin-top: 1.5rem; text-align: right;">
                    <button type="submit" 
                            style="
                                background-color: #10b981;
                                color: #000;
                                border: 2px solid #000;
                                padding: 0.875rem 2rem;
                                font-weight: 900;
                                font-size: 1rem;
                                cursor: pointer;
                                box-shadow: 4px 4px 0 0 #000;
                                transition: all 200ms ease;
                                text-transform: uppercase;
                            "
                            onmouseover="this.style.transform='translate(-2px,-2px)'; this.style.boxShadow='6px 6px 0 0 #000'"
                            onmouseout="this.style.transform='none'; this.style.boxShadow='4px 4px 0 0 #000'"
                            onmousedown="this.style.transform='translate(2px,2px)'; this.style.boxShadow='none'">
                        提交评论 <i class="fa-solid fa-paper-plane"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
    <?php else: ?>
    <!-- 评论已关闭提示 -->
    <div style="
        text-align: center;
        padding: 2rem;
        background-color: #fef3c7;
        border: 2px dashed #000;
        margin-top: 2rem;
    ">
        <p style="font-weight: 700; color: #92400e;"><i class="fa-solid fa-lock"></i> 评论已关闭</p>
    </div>
    <?php endif; ?>

</div>

<?php else: ?>
<!-- 不允许评论的提示 -->
<div style="
    text-align: center;
    padding: 2rem;
    background-color: #fee2e2;
    border: 2px solid #dc2626;
    margin-top: 2rem;
">
    <p style="font-weight: 700; color: #991b1b;"><i class="fa-solid fa-triangle-exclamation"></i> 当前页面不允许评论</p>
</div>
<?php endif; ?>

<?php
/**
 * 自定义评论渲染函数（MCDocs 粗野主义风格）
 * 被 $comments->listComments() 内部的 threadedCommentsCallback() 调用
 */
function threadedComments($comments, $options) {
    $commentClass = '';
    if ($comments->authorId && $comments->authorId == $comments->ownerId) {
        $commentClass .= ' comment-by-author';
    } elseif ($comments->authorId) {
        $commentClass .= ' comment-by-user';
    }
?>
<div id="<?php $comments->theId(); ?>" class="mc-comment<?php echo $commentClass; ?><?php if ($comments->levels > 0) { echo ' mc-comment-child'; } ?>" style="
    background-color: white;
    border: 2px solid #000;
    padding: 1.5rem;
    margin-bottom: 1rem;
    box-shadow: 4px 4px 0 0 #000;
    transition: all 200ms ease;
    <?php if ($comments->levels > 0): ?>
    margin-left: 2rem;
    margin-top: 0.75rem;
    <?php endif; ?>
"
onmouseover="this.style.transform='translateY(-2px)'"
onmouseout="this.style.transform='none'">
    
    <!-- 评论者信息 -->
    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
        <!-- 头像占位符 -->
        <div style="
            width: 3rem;
            height: 3rem;
            background-color: #10b981;
            border: 2px solid #000;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            color: white;
            flex-shrink: 0;
        ">
            <?php echo mb_substr($comments->author, 0, 1, 'UTF-8'); ?>
        </div>
        
        <div style="flex: 1;">
            <div style="display: flex; align-items: baseline; gap: 0.75rem; flex-wrap: wrap;">
                <strong style="font-size: 1.125rem;"><?php $comments->author(); ?></strong>
                <time datetime="<?php $comments->date('c'); ?>" 
                      style="font-family: 'JetBrains Mono', monospace; font-size: 0.875rem; color: #6b7280;">
                    <?php $comments->date('Y-m-d H:i'); ?>
                </time>
                <?php if ($comments->status != 'approved'): ?>
                <span style="
                    background-color: #fde047;
                    border: 2px solid #000;
                    padding: 0.125rem 0.5rem;
                    font-size: 0.75rem;
                    font-weight: 700;
                ">
                    待审核
                </span>
                <?php endif; ?>
            </div>
            
            <?php if ($comments->replyTo): ?>
            <span style="font-size: 0.875rem; color: #6b7280;">
                回复 @<?php $comments->replyTo->author(); ?>
            </span>
            <?php endif; ?>
        </div>
    </div>

    <!-- 评论内容 -->
    <div style="color: #374151; line-height: 1.7; font-weight: 500;">
        <?php $comments->content(); ?>
    </div>

    <!-- 操作按钮 -->
    <div style="margin-top: 1rem; text-align: right;">
        <?php $comments->reply($options->replyWord); ?>
    </div>
</div>
<?php
    // 递归渲染子评论（回复）
    $comments->threadedComments();
}
?>
