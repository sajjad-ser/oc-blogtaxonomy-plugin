<?php

namespace GinoPane\BlogTaxonomy\Components;

use RainLab\Blog\Models\Post;
use GinoPane\BlogTaxonomy\Plugin;
use GinoPane\BlogTaxonomy\Models\Series;

/**
 * Class SeriesPosts
 *
 * @package GinoPane\BlogTaxonomy\Components
 */
class SeriesPosts extends PostListAbstract
{
    const NAME = 'postsInSeries';

    /**
     * @var Series
     */
    public $series;

    /**
     * @return array
     */
    public function componentDetails()
    {
        return [
            'name'        => Plugin::LOCALIZATION_KEY . 'components.series_posts.name',
            'description' => Plugin::LOCALIZATION_KEY . 'components.series_posts.description'
        ];
    }

    /**
     * @return array
     */
    public function defineProperties()
    {
        $properties = [
                'series' => [
                    'title'       => Plugin::LOCALIZATION_KEY . 'components.series_posts.series_title',
                    'description' => Plugin::LOCALIZATION_KEY . 'components.series_posts.series_description',
                    'type'        => 'string',
                    'default'     => '{{ :series }}',
                ],
            ] + parent::defineProperties();

        return $properties;
    }

    /**
     * Prepare variables
     */
    protected function prepareContextItem()
    {
        // load series
        $this->series = Series::where('slug', $this->property('series'))->first();

        return $this->series;
    }

    /**
     * @return mixed
     */
    protected function getPostsQuery()
    {
        $query = Post::whereHas('series', function ($query) {
            $query->where('slug', $this->series->slug);
        })->isPublished();

        return $query;
    }
}
