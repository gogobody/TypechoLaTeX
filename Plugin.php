<?php
/**
 * Typecho 自动渲染 LaTeX 公式
 * 
 * @package TypechoLaTeX
 * @author gogobody
 * @version 1.0.0
 * @link https://www.ijkxs.com
 */
class TypechoLaTeX_Plugin implements Typecho_Plugin_Interface {
     /**
     * 激活插件方法,如果激活失败,直接抛出异常
     * 
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function activate() {
        Typecho_Plugin::factory('Widget_Archive')->header = array(__CLASS__, 'header');
        Typecho_Plugin::factory('Widget_Archive')->footer = array(__CLASS__, 'footer');
        Typecho_Plugin::factory('admin/write-post.php')->content = array(__CLASS__, 'header');
        Typecho_Plugin::factory('admin/write-post.php')->bottom = array(__CLASS__, 'footer');
    }

    /**
     * 禁用插件方法,如果禁用失败,直接抛出异常
     * 
     * @static
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function deactivate() {}

    /**
     * 获取插件配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form 配置面板
     * @return void
     */
    public static function config(Typecho_Widget_Helper_Form $form) {
        ?>
        <div>
            <h4>一款 typecho 数学公式插件 by gogobody</h4>
            <a href="https://www.ijkxs.com">欢迎访问=>即刻学术</a><br><br>
            <a href="https://github.com/gogobody/TypechoLaTeX">github地址</a>
            <br><br>
        </div>
        <?php
        $renderingList = array(
            'KaTeX' => 'KaTeX',
            'MathJax' => 'MathJax',
        );
        $name = new Typecho_Widget_Helper_Form_Element_Select('rendering', $renderingList, 'MathJax', _t('选择 LaTeX 渲染方式'));
        $form->addInput($name->addRule('enum', _t('请选择 LaTeX 渲染方式'), $renderingList));
    }

    /**
     * 个人用户的配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form
     * @return void
     */
    public static function personalConfig(Typecho_Widget_Helper_Form $form) {}

    /**
     * 插件实现方法
     * 
     * @access public
     * @return void
     */
    public static function render() {}

    /**
     * 添加额外输出到 Header
     * 
     * @access public
     * @return void
     */
    public static function header() {
        $rendering = Helper::options()->plugin('TypechoLaTeX')->rendering;
        switch($rendering) {
            case 'MathJax':
                break;
            case 'KaTeX':
                echo <<<HTML
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.13.0/dist/katex.min.css" integrity="sha384-t5CR+zwDAROtph0PXGte6ia8heboACF9R5l/DiY+WZ3P2lxNgvJkQk5n7GPvLMYw" crossorigin="anonymous">
<script defer src="https://cdn.jsdelivr.net/npm/katex@0.13.0/dist/katex.min.js" integrity="sha384-FaFLTlohFghEIZkw6VGwmf9ISTubWAVYW8tG8+w2LAIftJEULZABrF9PPFv+tVkH" crossorigin="anonymous"></script>
<script defer src="https://cdn.jsdelivr.net/npm/katex@0.13.0/dist/contrib/auto-render.min.js" integrity="sha384-bHBqxz8fokvgoJ/sc17HODNxa42TlaEhB+w8ZJXTc2nZf1VgEaFZeZvT4Mznfz0v" crossorigin="anonymous"></script>
HTML;
                break;
        }
    }

    /**
     * 添加额外输出到 Footer
     * 
     * @access public
     * @return void
     */
    public static function footer() {

        $rendering = Helper::options()->plugin('TypechoLaTeX')->rendering;
        switch($rendering) {
            case 'MathJax':
                echo <<<HTML
<script>
MathJax={tex:{inlineMath:[["$","$"],["\\(","\\)"]]},svg:{fontCache:"global"}};
function triggerRenderingLaTeX(element){MathJax.typeset()};
</script>
<script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-chtml.js">
</script>
HTML;
                break;
            case 'KaTeX':
                echo <<<HTML
<script>
function triggerRenderingLaTeX(element) {renderMathInElement(element,{delimiters:[{left:"$$",right:"$$",display:true},{left:"$",right:"$",display:false},{left:"\\(",right:"\\)",display:false},{left:"\\begin{equation}",right:"\\end{equation}",display:true},{left:"\\begin{align}",right:"\\end{align}",display:true},{left:"\\begin{alignat}",right:"\\end{alignat}",display:true},{left:"\\begin{gather}",right:"\\end{gather}",display:true},{left:"\\begin{CD}",right:"\\end{CD}",display:true},{left:"\\[",right:"\\]",display:true}],macros:{"\\ge":"\\geqslant","\\le":"\\leqslant","\\geq":"\\geqslant","\\leq":"\\leqslant"}})}
document.addEventListener("DOMContentLoaded",function(){triggerRenderingLaTeX(document.body)});
</script>
HTML;
                break;
        }
        echo <<<HTML
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    var wmdPreviewLink = document.querySelector("a[href='#wmd-preview']");
                    var wmdPreviewContainer = document.querySelector("#wmd-preview");
                    if(wmdPreviewLink && wmdPreviewContainer) {
                        wmdPreviewLink.onclick = function() {
                            triggerRenderingLaTeX(wmdPreviewContainer);
                        };
                    }
                });
            </script>
HTML;
    }
}
