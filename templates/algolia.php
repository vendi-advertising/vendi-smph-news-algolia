<?php

$newsItems = get_posts(
    [
        'post_type' => 'news',
        'numberposts' => -1,
        'post_status' => ['publish'],
    ]
);

$jsonArray = [];

foreach ($newsItems as $newsItem) {
    $obj = [
        'postType' => $newsItem->post_type,
        'title' => $newsItem->post_title,
        'publishedDate' => (new DateTimeImmutable($newsItem->post_date))->getTimestamp(),
        'objectId' => hash('sha256', $newsItem->ID),
        'shortTitle' => get_field('short_title', $newsItem),
        'excerpt' => get_field('excerpt', $newsItem),
    ];

    if ($heroImage = get_field('hero_image', $newsItem)) {
        $obj['heroImageUrl'] = $heroImage['url'];
    }

    if ($mediaContacts = get_field('media_contacts', $newsItem)) {
        /** @var WP_Post $mediaContact */
        foreach ($mediaContacts as $mediaContact) {
            $obj['mediaContacts'][] = $mediaContact->post_title;
        }
    }

    if ($components = get_field('content_components', $newsItem)) {
        $obj['content'] = '';
        foreach ($components as $component) {
            $html = match ($component['acf_fc_layout']) {
                'lead', 'basic_copy' => $component['copy'],
                'figure' => $component['caption'],
                default => null,
            };

            if ('figure' === $component['acf_fc_layout'] && !isset($obj['heroImageUrl'])) {
                $obj['heroImageUrl'] = $component['image']['url'];
            }

            if ($html) {
                $obj['content'] .= strip_tags($html)."\n\n";
            }
        }

        $obj['content'] = trim($obj['content']);
    }

    foreach (['news-category' => 'Tag', 'news-topic' => 'Topic', 'news-subject' => 'Subject'] as $taxonomy => $name) {
        if ($terms = wp_get_post_terms($newsItem->ID, $taxonomy)) {
            /** @var WP_Term $term */
            foreach ($terms as $term) {
                $obj[$name][] = $term->name;
            }
        }
    }

    $obj = array_filter($obj);

    $jsonArray[] = $obj;
}

echo json_encode($jsonArray, JSON_THROW_ON_ERROR);
exit;