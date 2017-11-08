<?php

/*
 * * VERSION 0.1
 * * FUNCTIONS ** 
 */

class Menu_md {

    //get database values
    private $page_array;

    function __construct() {
        $this->page_array = $_SESSION['page_array'];
    }

    public function getMenu() {
        //hent alle sider for den aktuelle mappe
        $myDb = new db_md();
        $myDb->sql = "SELECT * FROM pages WHERE folder='" . FOLDER . "' ORDER BY ranking ASC";
        $pages = $myDb->makeArray();

        $menu_items = array();
        foreach ($pages as $page) {
            $level = 0;
            $cls = ($page['page_id'] == $this->page_array['page_id']) ? 'active' : NULL;
            $menu_items[] = '
			<ul>
				<li id="menu-item-' . $page['page_id'] . '" data-level="' . $level . '" class="menu-item menu-item-' . $level . ' ' . $cls . '">
					<a href="' . $page['folder'] . '?p=' . $page['page_id'] . '">
						' . $page['menuname'] . '
					</a>
				</li>
			</ul>';
        }

        //finalize
        $html = '<nav>' . implode('', $menu_items) . '</nav>';

        return $html;
    }

}

?>