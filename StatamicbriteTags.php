<?php

namespace Statamic\Addons\Statamicbrite;

use Statamic\Extend\Tags;

class StatamicbriteTags extends Tags
{

    public function init()
    {

        // load OAuth token this from config yaml
        $this->oauth_token = $this->getConfig('oauth_token', null);

        // load caching details
        $this->cache_results = $this->getConfigBool('cache_results', true);
        $this->cache_length = $this->getConfigInt('cache_length', true);

        if( ! $this->oauth_token || trim( $this->oauth_token ) === '' ) {
            throw new \Exception("OAuth token has not been set");
            return;
        }

        // create Eventbrite client - https://github.com/ryanjarvinen/eventbrite.php/blob/master/Eventbrite.php
        $this->eb_client = new Eventbrite(
            [ 'access_token' => $this->oauth_token ]
        );

    }

    /**
    * The {{ statamicbrite }} tag
    *
    * @return string|array
    */
    public function index()
    {

        // list of possible parameters, these should be set in the tag i.e. sort_by="date"
        // see readme.md or here for reference: https://www.eventbrite.co.uk/developer/v3/endpoints/events/

        // get potential parameters from the {{ statamicbrite }} tag
        $q =                                     $this->getParam('q');
        $sort_by =                               $this->getParam('sort_by');
        $location_address =                      $this->getParam('location_address');
        $location_within =                       $this->getParam('location_within');
        $location_latitude =                     $this->getParam('location_latitude');
        $location_longitude =                    $this->getParam('location_longitude');
        $location_viewport_northeast_latitude =  $this->getParam('location_viewport_northeast_latitude');
        $location_viewport_northeast_longitude = $this->getParam('location_viewport_northeast_longitude');
        $location_viewport_southwest_latitude =  $this->getParam('location_viewport_southwest_latitude');
        $location_viewport_southwest_longitude = $this->getParam('location_viewport_southwest_longitude');
        $organizer_id =                          $this->getParam('organizer_id');
        $user_id =                               $this->getParam('user_id');
        $tracking_code =                         $this->getParam('tracking_code');
        $categories =                            $this->getParam('categories');
        $subcategories =                         $this->getParam('subcategories');
        $formats =                               $this->getParam('formats');
        $price =                                 $this->getParam('price');
        $start_date_range_start =                $this->getParam('start_date_range_start');
        $start_date_range_end =                  $this->getParam('start_date_range_end');
        $start_date_keyword =                    $this->getParam('start_date_keyword');
        $date_modified_range_start =             $this->getParam('date_modified_range_start');
        $date_modified_range_end =               $this->getParam('date_modified_range_end');
        $date_modified_keyword =                 $this->getParam('date_modified_keyword');
        $search_type =                           $this->getParam('search_type');
        $include_all_series_instances =          $this->getParamBool('include_all_series_instances');
        $include_unavailable_events =            $this->getParamBool('include_unavailable_events');
        $incorporate_user_affinities =           $this->getParamBool('incorporate_user_affinities');
        $high_affinity_categories =              $this->getParam('high_affinity_categories');



        // run a search using these parameters
        $params_array = [
            'q'                                     => $q,
            'sort_by'                               => $sort_by,
            'location.address'                      => $location_address,
            'location.within'                       => $location_within,
            'location.latitude'                     => $location_latitude,
            'location.longitude'                    => $location_longitude,
            'location.viewport.northeast.latitude'  => $location_viewport_northeast_latitude,
            'location.viewport.northeast.longitude' => $location_viewport_northeast_longitude,
            'location.viewport.southwest.latitude'  => $location_viewport_southwest_latitude,
            'location.viewport.southwest.longitude' => $location_viewport_southwest_longitude,
            'organizer.id'                          => $organizer_id,
            'user.id'                               => $user_id,
            'tracking_code'                         => $tracking_code,
            'categories'                            => $categories,
            'subcategories'                         => $subcategories,
            'formats'                               => $formats,
            'price'                                 => $price,
            'start_date.range_start'                => $start_date_range_start,
            'start_date.range_end'                  => $start_date_range_end,
            'start_date.keyword'                    => $start_date_keyword,
            'date_modified.range_start'             => $date_modified_range_start,
            'date_modified.range_end'               => $date_modified_range_end,
            'date_modified.keyword'                 => $date_modified_keyword,
            'search_type'                           => $search_type,
            'include_all_series_instances'          => $include_all_series_instances,
            'include_unavailable_events'            => $include_unavailable_events,
            'incorporate_user_affinities'           => $incorporate_user_affinities,
            'high_affinity_categories'              => $high_affinity_categories
        ];



        // load search results from the cache (this won't know if parameters have changed, it expects parameters to always be the same)
        if( $this->cache_results && $this->cache->exists( 'sb_search_results' ) ) {

            $search_results = $this->cache->get( 'sb_search_results' );

        // if this has not been cached, so ask Eventbrite for the results, and cache them for a minute
        } else {

            try {

                $call = 'events/search';
                $search_results = $this->eb_client->$call( $params_array );

            } catch( \Exception $e ) {

                throw new \Exception("Could not perform event search. Error details: ".$e );
                return;

            }

            $this->cache->put( 'sb_search_results', $search_results, $this->cache_length );

        }



        // return the contents of the search as variables
        // TODO - add in a way of supporting pagination (returned via the $search_results['pagination'] key)
        $return_array = [];

        foreach( $search_results->events as $e ) {

            array_push( $return_array, [
                'name'                      => $e->name->text,
                'description'               => $e->description->text,
                'id'                        => $e->id,
                'url'                       => $e->url,
                'start_timezone'            => $e->start->timezone,
                'start_local'               => $e->start->local,
                'start_utc'                 => $e->start->utc,
                'end_timezone'              => $e->end->timezone,
                'end_local'                 => $e->end->local,
                'end_utc'                   => $e->end->utc,
                'created'                   => $e->created,
                'changed'                   => $e->changed,
                'capacity'                  => $e->capacity,
                'capacity_is_custom'        => $e->capacity_is_custom,
                'status'                    => $e->status,
                'currency'                  => $e->currency,
                'listed'                    => $e->listed,
                'shareable'                 => $e->shareable,
                'online_event'              => $e->online_event,
                'tx_time_limit'             => $e->tx_time_limit,
                'hide_start_date'           => $e->hide_start_date,
                'hide_end_date'             => $e->hide_end_date,
                'locale'                    => $e->locale,
                'is_locked'                 => $e->is_locked,
                'privacy_setting'           => $e->privacy_setting,
                'is_series'                 => $e->is_series,
                'is_series_parent'          => $e->is_series_parent,
                'is_reserved_seating'       => $e->is_reserved_seating,
                'source'                    => $e->source,
                'logo_id'                   => $e->logo_id,
                'organizer_id'              => $e->organizer_id,
                'venue_id'                  => $e->venue_id,
                'category_id'               => $e->category_id,
                'subcategory_id'            => $e->subcategory_id,
                'format_id'                 => $e->format_id,
                'resource_uri'              => $e->resource_uri,
                'logo_crop_mask_top_left_x' => $e->logo->crop_mask->top_left->x,
                'logo_crop_mask_top_left_y' => $e->logo->crop_mask->top_left->y,
                'logo_crop_mask_width'      => $e->logo->crop_mask->width,
                'logo_crop_mask_height'     => $e->logo->crop_mask->height,
                'logo_original_url'         => $e->logo->original->url,
                'logo_original_width'       => $e->logo->original->width,
                'logo_original_height'      => $e->logo->original->height,
                'logo_id'                   => $e->logo->id,
                'logo_url'                  => $e->logo->url,
                'logo_aspect_ratio'         => $e->logo->aspect_ratio,
                'logo_edge_color'           => $e->logo->edge_color,
                'logo_edge_color_set'       => $e->logo->edge_color_set
            ]);

        }

        return $this->parseLoop( $return_array );

    }

    /**
    * The {{ statamicbrite:venue }} tag
    *
    * @return string|array
    */
    public function venue()
    {

        // get venue ID
        $venue_id = $this->getParam('venue_id');

        if( ! $venue_id || trim( $venue_id ) === '' ) {
            throw new \Exception("Venue ID has not been set");
            return;
        }



        // load venue results from the cache
        if( $this->cache_results && $this->cache->exists( 'sb_venue_'.$venue_id ) ) {

            $venue = $this->cache->get( 'sb_venue_'.$venue_id );

        // if this has not been cached, so ask Eventbrite for the results, and cache them for a minute
        } else {

            try {

                $call = 'venues/'.$venue_id;
                $venue = $this->eb_client->$call();

            } catch( \Exception $e ) {

                throw new \Exception("Could not retrieve venue details. Error details: ".$e );
                return;

            }

            $this->cache->put( 'sb_venue_'.$venue_id, $venue, $this->cache_length );

        }



        //return the venue details as variables
        $return = [
            'address_address1'                             => $venue->address->address_1,
            'address_address2'                             => $venue->address->address_2,
            'address_city'                                 => $venue->address->city,
            'address_region'                               => $venue->address->region,
            'address_postal_code'                          => $venue->address->postal_code,
            'address_country'                              => $venue->address->country,
            'address_latitude'                             => $venue->address->latitude,
            'address_longitude'                            => $venue->address->longitude,
            'address_localized_address_display'            => $venue->address->localized_address_display,
            'address_localized_area_display'               => $venue->address->localized_area_display,
            'address_localized_multi_line_address_display' => $venue->address->localized_multi_line_address_display, //array
            'resource_uri'                                 => $venue->resource_uri,
            'id'                                           => $venue->id,
            'name'                                         => $venue->name,
            'latitude'                                     => $venue->latitude,
            'longitude'                                    => $venue->longitude
        ];

        return $this->parse( $return );

    }

    /**
    * The {{ statamicbrite:ticket_classes }} tag
    *
    * @return string|array
    */
    public function ticketClasses()
    {

        // get venue ID
        $event_id = $this->getParam('event_id');

        if( ! $event_id || trim( $event_id ) === '' ) {
            throw new \Exception("Event ID has not been set");
            return;
        }



        // load venue results from the cache
        if( $this->cache_results && $this->cache->exists( 'sb_ticket_classes_'.$event_id ) ) {

            $ticket_classes = $this->cache->get( 'sb_ticket_classes_'.$event_id );

        // if this has not been cached, so ask Eventbrite for the results, and cache them for a minute
        } else {

            try {

                $call = 'events/'.$event_id.'/ticket_classes';
                $ticket_classes = $this->eb_client->$call();

            } catch( \Exception $e ) {

                throw new \Exception("Could not retrieve ticket classes. Error details: ".$e );
                return;

            }

            $this->cache->put( 'sb_ticket_classes_'.$event_id, $ticket_classes, $this->cache_length );

        }



        // return the venue details as variables
        // TODO - add in a way of supporting pagination (returned via the $ticket_classes['pagination'] key)
        $return_array = [];

        foreach( $ticket_classes->ticket_classes as $c ) {

            array_push( $return_array, [
                'cost_display'               => $c->cost->display,
                'cost_currency'              => $c->cost->currency,
                'cost_value'                 => $c->cost->value,
                'cost_major_value'           => $c->cost->major_value,
                'fee_display'                => $c->fee->display,
                'fee_currency'               => $c->fee->currency,
                'fee_value'                  => $c->fee->value,
                'fee_major_value'            => $c->fee->major_value,
                'tax_display'                => $c->tax->display,
                'tax_currency'               => $c->tax->currency,
                'tax_value'                  => $c->tax->value,
                'tax_major_value'            => $c->tax->major_value,
                'resource_uri'               => $c->resource_uri,
                'name'                       => $c->name,
                'donation'                   => $c->donation,
                'free'                       => $c->free,
                'minimum_quantity'           => $c->minimum_quantity,
                'maximum_quantity'           => $c->maximum_quantity,
                'maximum_quantity_per_order' => $c->maximum_quantity_per_order,
                'on_sale_status'             => $c->on_sale_status,
                'variants'                   => $c->variants, //array
                'has_pdf_ticket'             => $c->has_pdf_ticket,
                'sales_channels'             => $c->sales_channels, //array
                'event_id'                   => $c->event_id,
                'id'                         => $c->id,

                // it's handy to calculate this
                'total' => $c->cost->major_value + $c->fee->major_value + $c->tax->major_value
            ]);
        }

        return $this->parseLoop( $return_array );

    }
}