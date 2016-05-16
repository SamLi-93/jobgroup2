<?php
$this->_view['pager'] = $q->getPagination();
$this->_view['list'] = $q->getAll();
$this->_view['start'] = ($page-1)*$limit;
