<?php

class Control_Pagination extends QUI_Control_Abstract
{
    function render()
    {
        $pagination = $this->pagination;
        $udi        = $this->get('udi', $this->_context->requestUDI());
        $length     = $this->get('length', 9);
        $slider     = $this->get('slider', 2);
        $prev_label = $this->get('prev_label', '上一页');
        $next_label = $this->get('prev_label', '下一页');
        $url_args   = $this->get('url_args');
        $js_func    = $this->get('js_func');
        $js_limit   = $this->get('js_limit');

        //$context = QContext::instance();
        //$uri = preg_replace('/\/page\/\d+/', '', $context->requestURI());
        $qs = $_SERVER['QUERY_STRING'];

        //$out = "<div class=\"talk_page\"><ul>\n";
        $out = "";
        /*if ($this->get('show_count'))
        {
            $out .= "<p>共 {$pagination['record_count']} 个条目</p>\n";
        }*/
        $limit = empty($_GET['limit']) ? null : $_GET['limit'];
        /*$out .= "<p>共 {$pagination['record_count']} 个条目</p>";
        $out .= "<p><span style='float:left'>每页显示</span><select id=\"\" onchange=\"show_num(this.value);\" style='float:left;margin-top:-2px'>
                <option".($limit==15?" selected":"")." value=\"15\">15</option>
                <option".($limit==30?" selected":"")." value=\"30\">30</option>
                <option".($limit==50?" selected":"")." value=\"50\">50</option>
                <option".($limit==100?" selected":"")." value=\"100\">100</option>
                </select><span style='float:left'>条</span></p>\n";*/
        //$out .= '<ul id="' . h($this->id()) . "\">\n";

        $url_args = (array)$url_args;

        

        $base = $pagination['first'];
        $current = $pagination['current'];

        $mid = intval($length / 2);
        if ($current < $pagination['first'])
        {
            $current = $pagination['first'];
        }
        if ($current > $pagination['last'])
        {
            $current = $pagination['last'];
        }

        $begin = $current - $mid;
        if ($begin < $pagination['first'])
        {
            $begin = $pagination['first'];
        }
        $end = $begin + $length - 1;
        if ($end >= $pagination['last'])
        {
            $end = $pagination['last'];
            $begin = $end - $length + 1;
            if ($begin < $pagination['first'])
            {
                $begin = $pagination['first'];
            }
        }

        if ($begin > $pagination['first'])
        {
            for ($i =$pagination['first'] + $slider-1; $i > ($pagination['first']-1); $i --)
            {
                $url_args['page'] = $i;
                $in = $i + 1 - $base;
                if (!$js_func){
                    $url = url($udi, $url_args)."?$qs";
                } 
                else{
                    $url = "javascript:$js_func($in)";
                } 
                if($i == $pagination['first']){
                        $out .= "<li class='page_first_normal'><a href=\"{$url}\">{$in}</a></li>\n";
                }else{
                    $out .= "<li class='page_normal'><a href=\"{$url}\">{$in}</a></li>\n";
                }
                
            }

            if ($i < $begin)
            {
                $out .= "<li class=\"page_normal\">...</li>\n";
            }
        }

        for ($i =$end ; $i >=$begin ; $i --)
        {
            $url_args['page'] = $i;
            $in = $i + 1 - $base;
            if ($i == $pagination['current'])
            {
                if($i == $begin){
                    $out .= "<li class='  page_first_normal page_over'><a href=\"javascript:;\">{$in}</a></li>\n";
                }else{
                    $out .= "<li class='page_over'><a href=\"javascript:;\">{$in}</a></li>\n";
                }
            }
            else
            {
                if (!$js_func) $url = url($udi, $url_args)."?$qs";
                else $url = "javascript:$js_func($in)";

                if($i == $begin){
                     $out .= "<li class='page_first_normal'><a href=\"{$url}\">{$in}</a></li>\n";
                }else{
                     $out .= "<li class='page_normal'><a href=\"{$url}\">{$in}</a></li>\n";
                }
               
            }
        }

        if ($pagination['last'] - $end > $slider)
        {
            $out = "<li class=\"page_normal\">...</li>\n".$out;
            $end = $pagination['last'] - $slider;
        }

        for ($i = $end + 1; $i <= $pagination['last']; $i ++)
        {
            $url_args['page'] = $i;
            $in = $i + 1 - $base;
            if (!$js_func) $url = url($udi, $url_args)."?$qs";
            else $url = "javascript:$js_func($in)";
            $out = "<li  class=\"page_normal\"><a href=\"{$url}\">{$in}</a></li>\n".$out;
        }

        


        if ($pagination['current'] == $pagination['first'])
        {
            $out = "<li class=\"disabled page_up\"><a href='javascript:;'>{$prev_label}</li>\n".$out;
        }
        else
        {
            $url_args['page'] = $pagination['prev'];
            if (!$js_func) $url = url($udi, $url_args)."?$qs";
            else $url = "javascript:$js_func({$pagination['prev']})";
            $out = "<li class=\"page_up\"><a href=\"{$url}\"> {$prev_label}</a></li>\n".$out;
        }
        if ($pagination['current'] == $pagination['last'])
        {
            $out = "<li class=\"disabled page_next page_last\">{$next_label}</li>\n".$out;
        }
        else
        {
            $url_args['page'] = $pagination['next'];
            if (!$js_func) $url = url($udi, $url_args)."?$qs";
            else $url = "javascript:$js_func({$pagination['next']})";
            $out = "<li class='page_next page_last'><a href=\"{$url}\">{$next_label}</a></li>\n".$out;
        }
        $out = "<div class=\"talk_page\"><ul>\n".$out."</ul>\n";

        $url_args['page'] = 1;
        //$url = url($udi, $url_args);
        $qs1 = preg_replace('/&?limit=\d+/', '', $qs);
        $url = preg_replace('/\/page\/\d+/', '', QContext::instance()->requestURI());
        if(empty($js_limit)){
        $out .= "<script>
        function show_num(param){
            location=\"$url?$qs1\"+\"&limit=\"+param;
        }
        </script>";
        }else{
        $out .= "<script>
        function show_num(param){
            {$js_limit}(param);
        }
        </script>";
        }
        return $out;
    }
}
