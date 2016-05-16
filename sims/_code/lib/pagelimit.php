<?php 
$page = (int)$this->_context->page;
if ($page==0) $page++;
$limit = $this->_context->limit ? $this->_context->limit : 15;

