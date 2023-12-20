<?php

namespace common\models\telegram;

use common\models\Category;
use common\models\Language;
use common\models\ObjectType;
use common\models\Product;
use common\models\ProductVariation;
use common\models\TgCartProductOptionTmp;
use Yii;
use yii\base\Model;
use yii\helpers\Json;

/**
 * This is the model class for table "category".
 *
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property int|null $parent_id
 * @property string|null $emoji
 * @property string|null $created_at
 */
class TgKeyboard extends Model
{
    function getHomePage(){

    }

    public static function formatKeyboard($items,$per_row = 1){

        $data['resize_keyboard'] = true;
        $curr_row = 0;
        $counter = 0;
        foreach($items AS $item){
            $counter++;
            $data['keyboard'][$curr_row][] = $item;
            if($counter % $per_row == 0){
                $curr_row++;
            }
        }
        return json_encode($data);
    }

    public static function formatInlineKeyboard($items,$per_row = 1){

        $data = [];
        $curr_row = 0;
        $counter = 0;
        foreach($items AS $item){
            $counter++;
            $data['inline_keyboard'][$curr_row][] = ['text' => $item['text'],$item['type'] => $item['key']];
            if($counter % $per_row == 0){
                $curr_row++;
            }
        }
        return json_encode($data);
    }

    public static function formatInlineKeyboardArray($rowsArray){

        $data = [];
        foreach($rowsArray AS $rowNum => $row){
            foreach($row AS $item){
                $data['inline_keyboard'][$rowNum][] = ['text' => $item['text'],$item['type'] => $item['key']];
            }
        }

        return json_encode($data);
    }

    public static function getLanguageList(){
        $inline_buttons = [];
        foreach(Language::find()->all() AS $language){
            $inline_buttons[] = [
                'text' => $language->label,
                'type' => 'callback_data',
                'key' => 'lang_'.$language->code
            ];
        }

        return self::formatInlineKeyboard($inline_buttons);
    }

    public static function getHomePageKeyboard(){
        $buttons = [];

        $buttons[]['text'] = Yii::t('app','catalog');
        $buttons[]['text'] = Yii::t('app','cart');
        $buttons[]['text'] = Yii::t('app','checkout');
        $buttons[]['text'] = Yii::t('app','choose lang');

        return self::formatKeyboard($buttons,2);
    }

    public static function getCategoriesList(){
        $buttons = [];

        foreach(Category::find()->all() AS $category){
            $category->lang = \Yii::$app->language;
            $buttons[] = [
                'text' => ($category->categoryText && $category->categoryText->title) ? $category->icon->icon." ".$category->categoryText->title : '-',
                'type' => 'callback_data',
                'key' => 'cat_'.$category->id
            ];
        }
        $buttons[] = [
            'text' => 'test',
            'type' => 'url',
            'key' => 'https://mail.ru'
        ];
        $buttons[] = [
            'text' => 'test',
            'type' => 'switch_inline_query_current_chat',
            'key' => 'ddasdsaa'
        ];
        return self::formatInlineKeyboard($buttons,2);
    }

    public static function getCategories(){
        $buttons = [];

        foreach(Category::find()->all() AS $category){
            $category->lang = \Yii::$app->language;
            $buttons[] = [
                'text' => ($category->categoryText && $category->categoryText->title) ? $category->icon->icon." ".$category->categoryText->title : '-',
            ];
        }
        $buttons[] = [
            'text' => Yii::t('app','back')
        ];
        return self::formatKeyboard($buttons,2);
    }

    public static function getObjectTypes($str = ''){
        $inline_buttons = [];
        $items = ObjectType::find()->where(['like','title',$str])->limit(15)->all();
        if(!empty($items)){
            foreach($items AS $object_type){
                $inline_buttons[] = [
                    'text' => $object_type->title,
                    'type' => 'callback_data',
                    'key' => 'object_type_'.$object_type->id
                ];
            }

            return self::formatInlineKeyboard($inline_buttons,3);
        }
        else{
            return false;
        }

    }

    public static function getProductButtons($data){
        $rowsArray[] = [
            ['text' => \Yii::t('app','add to cart'),'type' => 'callback_data','key' => 'product_tocart'.$data['id']]
        ];
        return self::formatInlineKeyboardArray($rowsArray);
    }

    public static function getProductOptionButtons($data){
        $fullProduct = $data['full_product'];
        $rowsArray = [];

        $options = Product::getOptionsArray($fullProduct);
        foreach($options AS $optionIndex => $option){
            foreach ($option['values'] AS $option_item){
                $rowsArray[$optionIndex][] = ['text' => $option_item['label'],'type' => 'callback_data','key' => 'product_id_option_'.$fullProduct['id'].'_'.$option['id'].'_'.$option_item['id']];
            }
        }

        /*$rowsArray[] = [
            ['text' => '-','type' => 'callback_data','key' => 'product_minus'.$fullProduct['id']],
            ['text' => '0','type' => 'callback_data','key' => 'product_num'.$fullProduct['id']],
            ['text' => '+','type' => 'callback_data','key' => 'product_plus'.$fullProduct['id']]
        ];*/

        return self::formatInlineKeyboardArray($rowsArray);
    }

    public static function getProductOptionButtonsSelected($data){
        $fullProduct = $data['full_product'];
        $rowsArray = [];

        $options = Product::getOptionsArray($fullProduct);
        $selected_options_count = 0;
        $selected_option_values = [];
        foreach($options AS $optionIndex => $option){
            foreach ($option['values'] AS $option_item){
                $TgCartProductOptionTmp = TgCartProductOptionTmp::findOne([
                    'product_id' => $fullProduct['id'],
                    'product_option_id' => $option['id'],
                    'product_option_item_id' => $option_item['id'],
                    'subscriber_id' => $data['subscriber']->id,
                ]);
                $label = $option_item['label'];
                $key = 'product_id_option_'.$fullProduct['id'].'_'.$option['id'].'_'.$option_item['id'];
                if($TgCartProductOptionTmp){
                    $label = "🟢 ".$option_item['label'];
                    $key = 'remove_product_option_'.$fullProduct['id'].'_'.$option['id'].'_'.$option_item['id'];
                    $selected_options_count++;
                    $selected_option_values[] = ['option_id' => $option['id'],'option_item_id' => $option_item['id']];
                }
                $rowsArray[$optionIndex][] = ['text' => $label,'type' => 'callback_data','key' => $key];
            }
        }

        if($selected_options_count >= count($options)){

            $variation_id = ProductVariation::getVariationByOptions([
                    'selected_option_values' => $selected_option_values,
                    'product_id' => $data['full_product']['id']]);

            if(!empty($variation_id)){
                $rowsArray[][] = ['text' => \Yii::t('app','add to cart'),'type' => 'callback_data','key' => 'product_variation_tocart_'.$variation_id];
            }


        }

        return self::formatInlineKeyboardArray($rowsArray);
    }

    public static function test(){
        $buttons[] = [
            'text' => 'test',
            'type' => 'url',
            'key' => 'https://mail.ru'
        ];
        return self::formatInlineKeyboard($buttons,2);
    }


}
