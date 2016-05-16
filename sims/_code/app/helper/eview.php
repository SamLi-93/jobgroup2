<?php

class Helper_EView {
    /**
     * form 读取form yaml
     *
     * @var array
     */
    public $form;
    /**
     * 标题
     *
     * @var array
     */
    public $subject;

    /**
     * 属性值
     *
     * @var array/object
     */
    public $attrs;

    public function __construct($model, $attrs, $subject='') {
        $filename = Q::ini('app_config/APP_DIR') . '/form/'.$model.'_form.yaml';
        $this->form = Helper_YAML::loadCached($filename);
        unset($this->form['~form']);
        $this->attrs = $attrs;
        $this->subject = $subject;
    }
}