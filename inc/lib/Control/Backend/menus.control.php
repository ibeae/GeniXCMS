<?php if(!defined('GX_LIB')) die("Direct Access Not Allowed!");
/*
*    GeniXCMS - Content Management System
*    ============================================================
*    Build          : 20140925
*    Version        : 0.0.1 pre
*    Developed By   : Puguh Wijayanto (www.metalgenix.com)
*    License        : Private
*    ------------------------------------------------------------
* filename : menus.control.php
* version : 0.0.1 pre
* build : 20141007
*/
if(isset($_GET['act'])) { $act = $_GET['act'];}else{$act = '';}
switch ($act) {
    case 'add':
        # code...
        if (isset($_POST['submit'])) {
            # code...
            $submit = true;
        }else{
            $submit = false;
        }
        switch ($submit) {
            case true:
                # code...
                $menus = Options::get('menus');
                $menus = json_decode(Options::get('menus'), true);
                echo "<pre>"; print_r($menus); echo "</pre>";
                // $menu = array(
                //                 $_POST['id']  =>  array(
                //                             'name' => $menus[$_POST['id']]['name'],
                //                             'class' => $menus[$_POST['id']]['class'],
                //                             'menu' => array(
                //                                     'parent' => $_POST['parent'],
                //                                     'menuid' => $_POST['id'],
                //                                     'type' => $_POST['type'],
                //                                     'value' => $_POST[$_POST['type']]
                //                                 )
                //                         )
                //                 );
                
                // if(is_array($menus)){
                //     $menu = array_merge($menus, $menu);
                // }
                // echo "<pre>"; print_r($menu); echo "</pre>";
                //$menu = $menus;
                $menu[$_POST['id']]['menu'] = $menus[$_POST['id']]['menu'];
                $menu[$_POST['id']]['menu'][] = array(
                                                    'parent' => $_POST['parent'],
                                                    'menuid' => $_POST['id'],
                                                    'name' => $_POST['name'],
                                                    'type' => $_POST['type'],
                                                    'value' => $_POST[$_POST['type']],
                                                    'sub' => ''
                                                );
                 $menu = array(
                                $_POST['id']  =>  array(
                                            'name' => $menus[$_POST['id']]['name'],
                                            'class' => $menus[$_POST['id']]['class'],
                                            'menu' => $menu[$_POST['id']]['menu']    
                                        )
                                );
                if(is_array($menus)){
                    $menu = array_merge($menus, $menu);
                }
                 echo "<pre>"; print_r($menu); echo "</pre>";
                $menu = json_encode($menu);
                echo "<pre>"; print_r($menu); echo "</pre>";
                //Options::update('menus', $menu);

                $vars = array(
                            'parent' => $_POST['parent'],
                            'menuid' => $_POST['id'],
                            'name' => $_POST['name'],
                            'class' => $_POST['class'],
                            'type' => $_POST['type'],
                            'value' => $_POST[$_POST['type']]
                        );
                Menus::insert($vars);
                break;
            
            default:
                # code...

                break;
        }
        //$data['abc'] = "abc";
        if(isset($_GET['id'])){
            $menuid = $_GET['id'];
        }else{
            $menuid = '';
        }
        $data['parent'] = Menus::getParent('', $menuid);
        //echo "<pre>"; print_r($data); echo "</pre>";
        System::inc('menus_form', $data);
        break;

    case 'edit':
        # code...
        if (isset($_POST['edititem'])) {
            # code...
            $submit = true;
        }else{
            $submit = false;
        }
        switch ($submit) {
            case true:
                

                $vars = array(
                            // 'parent' => $_POST['parent'],
                            'menuid' => $_POST['id'],
                            'name' => $_POST['name'],
                            'class' => $_POST['class'],
                            'type' => $_POST['type'],
                            'value' => $_POST[$_POST['type']]
                        );
                $vars = array(
                            'id' => $_GET['itemid'],
                            'key' => $vars
                        );
                Menus::update($vars);
                break;
            
            default:
                # code...

                break;
        }

        if(isset($_GET['id'])){
            $menuid = $_GET['id'];
        }else{
            $menuid = '';
        }
        $data['menus'] = Menus::getId($_GET['itemid']);
        $data['parent'] = Menus::getParent('', $menuid);
        System::inc('menus_form_edit', $data);
        break;
    case 'del':
        if(isset($_GET['itemid'])){
            Menus::delete($_GET['itemid']);
            $data['alertgreen'][] = 'Menu Deleted';
        }else{
            $data['alertred'][] = 'No ID Selected.';
        }
        break;
    default:
        # code...
        if (isset($_POST['submit'])) {
            # code...
            $submit = true;
        }else{
            $submit = false;
        }
        switch ($submit) {
            case true:
                # code...
                $menu = array(
                                $_POST['id']  =>  array(
                                            'name' => $_POST['name'],
                                            'class' => $_POST['class'],
                                            'menu' => array()
                                        )
                                );
                $menus = json_decode(Options::get('menus'), true);
                if(is_array($menus)){
                    $menu = array_merge($menus, $menu);
                }
                
                $menu = json_encode($menu);
                Options::update('menus', $menu);
                break;
            
            default:
                # code...
                
                break;
        }


        // ADD MENU ITEM START
        
        if (isset($_POST['additem'])) {
            # code...
            $submit = true;
        }else{
            $submit = false;
        }
        switch ($submit) {
            case true:
                

                $vars = array(
                            'parent' => $_POST['parent'],
                            'menuid' => $_POST['id'],
                            'name' => $_POST['name'],
                            'class' => $_POST['class'],
                            'type' => $_POST['type'],
                            'value' => $_POST[$_POST['type']]
                        );
                Menus::insert($vars);
                break;
            
            default:
                # code...

                break;
        }

        // ADD MENU ITEM END


        // CHANGE ORDER START
        if(isset($_POST['changeorder'])){
            $submit = true;
        }else{
            $submit = false;
        }
        switch ($submit) {
            case true:
                # code...
                // echo "<pre>";
                // print_r($_POST['order']);
                // echo "</pre>";

                Menus::updateMenuOrder($_POST['order']);
                break;
            
            default:
                # code...
                break;
        }

        // CHANGE ORDER END

        $data['menus'] = Options::get('menus');
        System::inc('menus', $data);
        break;
}
    


/* End of file menus.control.php */
/* Location: ./inc/lib/Control/Backend/menus.control.php */