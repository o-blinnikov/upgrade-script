<?php

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

/************ Create CMS Page ***********/

$id = 'cms-page-id';
$model = Mage::getModel('cms/page');

$content = <<<HTML
    <div>
        HTML CONTENT HERE
    </div>
HTML;

$layout = <<<HTML
<reference name="head">
        <!-- Custom styles for this template -->
  <action method="addItem"><type>skin_css</type><name>css/custom.css</name><params/></action>
</reference>
HTML;

$cmsPage = Array (
    'identifier' => $id,
    'title' => 'CMS Page Title',
    'stores' => array(0), //store ids here, 0 for all store
    'is_active' => 1,
    'under_version_control' => 0,
    'content_heading' => '',
    'content' => $content,
    'root_template' => 'one_column',
    'layout_update_xml' => $layout,
    'sort_order' => 0
);

$model->setData($cmsPage)->save();

/************ Update Existing CMS Page ***********/

$id = 'existing-cms-page-id';
$pageId = $model->getCollection()->addFieldToFilter('identifier', $id)->getFirstItem()->getId();
$page = $model->load($pageId);

$content = <<<HTML
    <div>
        HTML CONTENT HERE
    </div>
HTML;

$layout = <<<HTML
<reference name="head">
        <!-- Custom styles for this template -->
  <action method="addItem"><type>skin_css</type><name>css/custom.css</name><params/></action>
</reference>
HTML;

$cmsPage = Array (
    'page_id' => $pageId,
    'identifier' => $id,
    'title' => 'CMS Page Title',
    'stores' => array(0), //store ids here, 0 for all store
    'is_active' => 1,
    'under_version_control' => 0,
    'content_heading' => '',
    'content' => $content,
    'root_template' => 'one_column',
    'layout_update_xml' => $layout,
    'sort_order' => 0
);

$page->setData($cmsPage)->save();

/************ Create CMS Static Block ***********/

$blockModel = Mage::getModel('cms/block');
$identifier = 'cms-block-identifier';
$title = 'CMS block title';
$content = <<<HTML
    <div>
        HTML CONTENT HERE
    </div>
HTML;

$stores = array(0);

$cmsBlock = Array (
    "identifier" => $identifier,
    "title"      => $title,
    "content"    => $content,
    "is_active"  => 1,
    "stores"     => $stores
);

$blockModel->setData($cmsBlock)->save();

/************ Set Config Data ***********/

$xmlPathFlat = 'storelocator/global/pagetitle';
$value       = 'Store locator';

$installer->setConfigData($xmlPathFlat, $value);

Mage::getConfig()->saveConfig('catalog/frontend/default_sort_by', 'producer');

$installer->endSetup();

/************ Update Attribute ***********/

const USED_FOR_SORT_BY = "used_for_sort_by";
$attributesToRemove = array(
    'name',                     // attribute_id=71
    'wine_spectator_score',     // attribute_id=453
);
$attributesToAdd = array(
    'producer'                  // attribute_id=365
);

foreach ($attributesToRemove as $attribute) {
    $this->updateAttribute(
        Mage_Catalog_Model_Product::ENTITY, $attribute, USED_FOR_SORT_BY, 0
    );
}
foreach ($attributesToAdd as $attribute) {
    $this->updateAttribute(
        Mage_Catalog_Model_Product::ENTITY, 
        $attribute, 
        USED_FOR_SORT_BY, 
        1
    );
}
