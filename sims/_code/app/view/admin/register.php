<?php $this->_extends('_layouts/login_layout'); ?>

<?php $this->_block('contents'); ?>

<?php $this->_element('formview_login', array('form' => $form)); ?>

<?php $this->_endblock(); ?>