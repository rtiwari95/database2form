<?php

Class error404Controller Extends baseController {

    public function index() 
    {
        $this->registry->template->blog_heading = 'This is the 404';
        $this->registry->template->show('header');
        $this->registry->template->show('error404');
        $this->registry->template->show('footer');
    }


}
?>
