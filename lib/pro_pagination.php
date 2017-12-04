<?php

class pro_pagination
{
    function pagination_pro_content()
    {
        $html = '
                <div class="pro-pagination-custom col-md-12">
                    <span onclick="page_forward(5);" class="page-back glyphicon glyphicon-backward"></span>
                    <span class="pagination-pages">1 / 14</span>
                    <span onclick="page_backward($(this));" class="page-forward glyphicon glyphicon-forward"></span>
                </div>';
        
        echo $html;
    }
    
    
}