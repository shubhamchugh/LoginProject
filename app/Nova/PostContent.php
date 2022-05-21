<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use App\Models\JsonPostContent;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Http\Requests\NovaRequest;

class PostContent extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\JsonPostContent::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),
            Trix::make('post_description'),
            Text::make('post_thumbnail')->hideFromIndex(),
            Trix::make('google_rich_snippet'),

            Trix::make('bing_rich_snippet_text'),
            Text::make('bing_rich_snippet_link')->hideFromIndex(),

            Trix::make('post_content_above'),
            Trix::make('post_content_middle'),
            Trix::make('post_content_after'),

            KeyValue::make('bing_related_keywords')->rules('json'),
            KeyValue::make('google_related_keywords')->rules('json'),

            KeyValue::make('bing_news_title')->rules('json'),
            KeyValue::make('bing_news_description')->rules('json'),

            KeyValue::make('bing_search_result_title')->rules('json'),
            KeyValue::make('bing_search_result_description')->rules('json'),
            KeyValue::make('bing_search_result_url')->rules('json'),

            KeyValue::make('google_search_result_title')->rules('json'),
            KeyValue::make('google_search_result_description')->rules('json'),
            KeyValue::make('google_search_result_url')->rules('json'),

            KeyValue::make('bing_paa_questions')->rules('json'),
            KeyValue::make('bing_paa_answers')->rules('json'),

            KeyValue::make('bing_pop_faq_questions')->rules('json'),
            KeyValue::make('bing_pop_faq_answers')->rules('json'),

            KeyValue::make('bing_tab_faq_questions')->rules('json'),
            KeyValue::make('bing_tab_faq_answers')->rules('json'),

            KeyValue::make('bing_slider_faq_questions')->rules('json'),
            KeyValue::make('bing_slider_faq_answers')->rules('json'),

            KeyValue::make('bing_videos')->rules('json'),
            KeyValue::make('bing_images')->rules('json'),
            // KeyValue::make('bing_search_result')->rules('json'),

        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }
}
