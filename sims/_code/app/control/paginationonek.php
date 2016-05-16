<?php

class Control_Paginationonek extends QUI_Control_Abstract
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
		$js_func	= $this->get('js_func');

        //$context = QContext::instance();
        //$uri = preg_replace('/\/page\/\d+/', '', $context->requestURI());
        $qs = $_SERVER['QUERY_STRING'];

        $out = "<div class=\"pagination\">\n";

        /*if ($this->get('show_count'))
        {
            $out .= "<p>共 {$pagination['record_count']} 个条目</p>\n";
        }*/
        $limit = empty($_GET['limit']) ? null : $_GET['limit'];
        $out .= "<p>共 {$pagination['record_count']} 个条目</p>";
        $out .= "<p><span style='float:left'>每页显示</span><select id=\"\" onchange=\"show_num(this.value);\" style='float:left;margin-top:-2px'>
        		<option".($limit==15?" selected":"")." value=\"15\">15</option>
        		<option".($limit==30?" selected":"")." value=\"30\">30</option>
        		<option".($limit==50?" selected":"")." value=\"50\">50</option>
				<option".($limit==100?" selected":"")." value=\"100\">100</option>
				<option".($limit==200?" selected":"")." value=\"200\">200</option>
				<option".($limit==300?" selected":"")." value=\"300\">300</option>
				<option".($limit==400?" selected":"")." value=\"400\">400</option>
				<option".($limit==500?" selected":"")." value=\"500\">500</option>
				<option".($limit==600?" selected":"")." value=\"600\">600</option>
				<option".($limit==700?" selected":"")." value=\"700\">700</option>
				<option".($limit==800?" selected":"")." value=\"800\">800</option>
				<option".($limit==900?" selected":"")." value=\"900\">900</option>
				<option".($limit==1000?" selected":"")." value=\"1000\">1000</option>
        		</select><span style='float:left'>条</span></p>\n";
        $out .= '<ul id="' . h($this->id()) . "\">\n";

        $url_args = (array)$url_args;
        if ($pagination['current'] == $pagination['first'])
        {
            $out .= "<li class=\"disabled\">&#171; {$prev_label}</li>\n";
        }
        else
        {
            $url_args['page'] = $pagination['prev'];
            if (!$js_func) $url = url($udi, $url_args)."?$qs";
			else $url = "javascript:$js_func({$pagination['prev']})";
            $out .= "<li><a href=\"{$url}\">&#171; {$prev_label}</a></li>\n";
        }

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
            for ($i = $pagination['first']; $i < $pagination['first'] + $slider && $i < $begin; $i ++)
            {
                $url_args['page'] = $i;
                $in = $i + 1 - $base;
                if (!$js_func) $url = url($udi, $url_args)."?$qs";
				else $url = "javascript:$js_func($in)";
                $out .= "<li><a href=\"{$url}\">{$in}</a></li>\n";
            }

            if ($i < $begin)
            {
                $out .= "<li class=\"none\">...</li>\n";
            }
        }

        for ($i = $begin; $i <= $end; $i ++)
        {
            $url_args['page'] = $i;
            $in = $i + 1 - $base;
            if ($i == $pagination['current'])
            {
                $out .= "<li class=\"current\">{$in}</li>\n";
            }
            else
            {
                if (!$js_func) $url = url($udi, $url_args)."?$qs";
				else $url = "javascript:$js_func($in)";
                $out .= "<li><a href=\"{$url}\">{$in}</a></li>\n";
            }
        }

        if ($pagination['last'] - $end > $slider)
        {
            $out .= "<li class=\"none\">...</li>\n";
            $end = $pagination['last'] - $slider;
        }

        for ($i = $end + 1; $i <= $pagination['last']; $i ++)
        {
            $url_args['page'] = $i;
            $in = $i + 1 - $base;
            if (!$js_func) $url = url($udi, $url_args)."?$qs";
			else $url = "javascript:$js_func($in)";
            $out .= "<li><a href=\"{$url}\">{$in}</a></li>\n";
        }

        if ($pagination['current'] == $pagination['last'])
        {
            $out .= "<li class=\"disabled\">{$next_label} &#187;</li>\n";
        }
        else
        {
            $url_args['page'] = $pagination['next'];
            if (!$js_func) $url = url($udi, $url_args)."?$qs";
			else $url = "javascript:$js_func({$pagination['next']})";
            $out .= "<li><a href=\"{$url}\">{$next_label} &#187;</a></li>\n";
        }

        $out .= "</ul></div>\n";

        $url_args['page'] = 1;
        //$url = url($udi, $url_args);
        $qs1 = preg_replace('/&?limit=\d+/', '', $qs);
		$url = preg_replace('/\/page\/\d+/', '', QContext::instance()->requestURI());
        $out .= "<script>
        function show_num(param){
    		location=\"$url?$qs1\"+\"&limit=\"+param;
        }
        </script>";

        return $out;
    }
}
