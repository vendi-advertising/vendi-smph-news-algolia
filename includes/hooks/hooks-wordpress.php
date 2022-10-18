<?php

const VENDI_SMPH_NEWS_ALGOLIA_QUERY_STRING = 'smph-algolia';
const VENDI_SMPH_NEWS_ALGOLIA_PAGE_URL = 'algolia-json';

add_action(
    'init',
    static function () {
        // The =true is needed because get_query_var() returns an empty string for missing parameters, too.
        // The actual value itself doesn't matter, anything will work.
        add_rewrite_rule('^'.VENDI_SMPH_NEWS_ALGOLIA_PAGE_URL.'[/]?$', 'index.php?'.VENDI_SMPH_NEWS_ALGOLIA_QUERY_STRING.'=true', 'top');
    }
);

add_filter(
    'query_vars',
    static function ($query_vars) {
        $query_vars[] = VENDI_SMPH_NEWS_ALGOLIA_QUERY_STRING;

        return $query_vars;
    }
);

add_filter(
    'template_include',
    static function ($template) {
        // NOTE: This is truthy so the QS must include something, not just an empty string
        if (get_query_var(VENDI_SMPH_NEWS_ALGOLIA_QUERY_STRING)) {
            // The path must be absolute
            return VENDI_SMPH_NEWS_ALGOLIA_PATH.'/templates/algolia.php';
        }

        return $template;
    }
);