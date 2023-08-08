<?

/**
 * @package bilibili_data
 * @version 1.0
 */
/*
Plugin Name: bilibili数据面板v1.0
Plugin URI: http://jiuchiyl.xyz
Description: 这是一个用于获取哔哩哔哩空间数据的插件，请按方法填写需要的数据
Author: 久池鸢里
Version: 1.0
Author URI: http://jiuchiyl.xyz
*/
// 爬取用户哔哩哔哩的数据
define('BILIBILIDATA_PATH', dirname( __FILE__ ));
add_action('admin_menu','add_settings_menu');
function add_settings_menu() {
    add_menu_page(__('bilibili'), __('bilibili数据面板'), 'administrator',  __FILE__, 'my_function_menu', false, 100);
}
function my_function_menu() {
    require_once BILIBILIDATA_PATH . "./index.html";
}