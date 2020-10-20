<?php

function permission_check($module, $menu = NULL, $access = NULL)
{
    $widget = session('widget');

    
    // if check three of menu access 3 param
    if (!empty($module) && !empty($menu) && !empty($access)) {
        return isset($widget[$module][$menu]) ? in_array($access, $widget[$module][$menu]) : FALSE;
    }

    // if check menu access 2 param
    if (!empty($module) && !empty($menu) && empty($access)) {
        return isset($widget[$module]) ? in_array($menu, $widget[$module]) : FALSE;
    }

    // if check menu access 1 param
    if (!empty($module) && empty($menu) && empty($access)) {
        return isset($widget[$module]) ? TRUE : FALSE;
    }
}
