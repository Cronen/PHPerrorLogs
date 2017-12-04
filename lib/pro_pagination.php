<?php

class pro_pagination
{
    function pagination_pro_content()
    {
        $html = '
                <div class="pro-pagination-custom col-md-12">
                    <span onclick="pro_sort($(this))class="page-back glyphicon glyphicon-backward"></span>
                    <span onclick="pro_sort($(this))class="pagination-pages">1 / 14</span>
                    <span onclick="pro_sort($(this))class="page-forward glyphicon glyphicon-forward"></span>
                </div>';
    }
}