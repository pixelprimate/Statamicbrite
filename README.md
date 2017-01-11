*Statamicbrite*
==
Statamicbrite is a Statamic Eventbrite plugin. Written by Danny Richardson for [Pixel Primate](http://www.pixelprimate.com), V1.0

> Licensed under the [MIT licence](https://opensource.org/licenses/MIT)
>
> Copyright 2017 Pixel Primate Ltd.  danny@pixelprimate.com
> 
> Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
> 
> The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
> 
> THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

*Installation*
==

Copy the files over to `/site/addons/Statamicbrite`.

Go to the Control Panel, and go to `Configure` > `Addons` > `Statamicbrite` and enter your Oauth token (get this from Eventbrite's API)


*Usage*
==
Use as a tag pair, i.e.
```
{{ statamicbrite organizer_id="123456789" }}

	Name: {{ name }}
	From: {{ start_local }} - {{ start_local }}
	See more: {{ url }}

{{ /statamicbrite }}
```

Any of the parameters (see below) can be used in the tag as parameters, i.e.
```
{{ statamicbrite q="foo" location_address="bar" sort_by="-date" }}
...
{{ /statamicbrite }}
```

*Eventbrite class*
==

API Class from `https://github.com/ryanjarvinen/eventbrite.php/blob/master/Eventbrite.php`  

> Licensed under the [MIT licence](https://opensource.org/licenses/MIT)
> 
> MIT License
> Copyright (c) 2011 - Ryan Jarvinen <ryanj@eventbrite.com> 
> 
> Permission is hereby granted, free of charge, to any person obtaining a copy
> of this software and associated documentation files (the "Software"), to deal
> in the Software without restriction, including without limitation the rights
> to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
> copies of the Software, and to permit persons to whom the Software is
> furnished to do so, subject to the following conditions:
> 
> The above copyright notice and this permission notice shall be included in 
> all copies or substantial portions of the Software.
> 
> THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
> IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
> FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
> AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
> LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
> OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
> THE SOFTWARE.
> 
> The latest version of this API client is available here:
> https://github.com/ryanjarvinen/eventbrite.php
> 
> Author: @ryanjarvinen

-------------------
Attribution notice:
This work was originally derived from Stas Sușcov's php-eventbrite - 
https://github.com/stas/php-eventbrite (Copyright (c) 2011 Stas Sușcov)

*Eventbrite API reference*
==

See here for full reference: https://www.eventbrite.co.uk/developer/v3/endpoints/events/

> `q`  
> Type: string, Desc: Return events matching the given keywords. This parameter will accept any string as a keyword.
> 
> `sort_by`  
> Type: string, Desc: Parameter you want to sort by - options are “date”, “distance” and “best”. Prefix with a hyphen to reverse the order, e.g. “-date”.
> 
> `location_address`  
> Type: string, Desc: The address of the location you want to search for events around.
> 
> `location_within`  
> Type: string, Desc: The distance you want to search around the given location. This should be an integer followed by “mi” or “km”.
> 
> `location_latitude`  
> Type: string, Desc: The latitude of of the location you want to search for events around.
> 
> `location_longitude`  
> Type: string, Desc: The longitude of the location you want to search for events around.
> 
> `location_viewport_northeast_latitude`  
> Type: string, Desc: The latitude of the northeast corner of a viewport.
> 
> `location_viewport_northeast_longitude`  
> Type: string, Desc: The longitude of the northeast corner of a viewport.
> 
> `location_viewport_southwest_latitude`  
> Type: string, Desc: The latitude of the southwest corner of a viewport.
> 
> `location_viewport_southwest_longitude`  
> Type: string, Desc: The longitude of the southwest corner of a viewport.
> 
> `organizer_id`  
> Type: string, Desc: Only return events organized by the given Organizer ID.
> 
> `user_id`  
> Type: string, Desc: Only return events owned by the given User ID.
> 
> `tracking_code`  
> Type: string, Desc: Append the given tracking_code to the event URLs returned.
> 
> `categories`  
> Type: string, Desc: Only return events under the given category IDs. This should be a comma delimited string of category IDs.
> 
> `subcategories`  
> Type: string, Desc: Only return events under the given subcategory IDs. This should be a comma delimited string of subcategory IDs.
> 
> `formats`  
> Type: string, Desc: Only return events with the given format IDs. This should be a comma delimited string of format IDs.
> 
> `price`  
> Type: string, Desc: Only return events that are “free” or “paid”
> 
> `start_date_range_start`  
> Type: local datetime, Desc: Only return events with start dates after the given date.
> 
> `start_date_range_end`  
> Type: local datetime, Desc: Only return events with start dates before the given date.
> 
> `start_date_keyword`  
> Type: string, Desc: Only return events with start dates within the given keyword date range. Keyword options are “this_week”, “next_week”, “this_weekend”, “next_month”, “this_month”, 
> “tomorrow”, “today”
> 
> `date_modified_range_start`  
> Type: datetime, Desc: Only return events with modified dates after the given UTC date.
> 
> `date_modified_range_end`  
> Type: datetime, Desc: Only return events with modified dates before the given UTC date.
> 
> `date_modified_keyword`  
> Type: string, Desc: Only return events with modified dates within the given keyword date range. Keyword options are “this_week”, “next_week”, “this_weekend”, “next_month”, “this_month”, 
> “tomorrow”, “today”
> 
> `search_type`  
> Type: string, Desc: Use the preconfigured settings for this type of search - Current option is “promoted”
> 
> `include_all_series_instances`  
> Type: boolean, Desc: Boolean for whether or not you want to see all instances of repeating events in search results.
> 
> `include_unavailable_events`  
> Type: boolean, Desc: Boolean for whether or not you want to see events without tickets on sale.
> 
> `incorporate_user_affinities`  
> Type: boolean, Desc: Incorporate additional information from the user’s historic preferences.
> 
> `high_affinity_categories`  
> Type: string, Desc: Make search results prefer events in these categories. This should be a comma delimited string of category IDs.