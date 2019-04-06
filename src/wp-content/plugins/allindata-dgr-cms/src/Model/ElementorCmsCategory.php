<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\Dgr\Cms\Model;

use Elementor\Core\Common\Modules\Finder\Base_Category;

/**
 * Class ElementorCmsCategory
 * @package AllInData\Dgr\Cms\Model
 */
class ElementorCmsCategory extends Base_Category
{

    /**
     * @return string|void
     */
    public function get_title()
    {
        return __('DGR CMS', AID_DGR_CMS_TEXTDOMAIN);
    }

    /**
     * @param array $options
     * @return array
     */
    public function get_category_items(array $options = [])
    {
        return [
            'allindata-dgr-cms-dummy01' => [
                'title' => __('Dummy Element Item', AID_DGR_CMS_TEXTDOMAIN),
                'url' => '/dummy-url/',
                'keywords' => ['dummy']
            ]
        ];
//        return [
//            'jet-popup-settings' => [
//                'title' => __('Jet Popup Settings', 'jet-popup'),
//                'url' => jet_popup()->settings->get_settings_page_url(),
//                'keywords' => ['general', 'popup', 'settings', 'jet', 'mailchimp'],
//            ],
//            'jet-popup-library' => [
//                'title' => __('Jet Popup Library', 'jet-popup'),
//                'url' => jet_popup()->post_type->get_library_page_url(),
//                'icon' => 'folder',
//                'keywords' => ['popup', 'library', 'jet', 'create', 'new'],
//            ],
//        ];
    }
}
