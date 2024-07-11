### Quick Start Installation

If you have composer installed, run this in your terminal to install October CMS from command line.

    git clone https://github.com/swordbros/inspera.git .

for update.

    git pull origin main

Please request auth.json and .env files

### End Point For Event listing Page
    GET: /swordbros/api/search

usable url patameters

    type_id=1,2,3...&zone_id=1,2,4...&category_id=1,2,3...&audience=1,2,3...&start=any_mysql_format_data&end=any_mysql_format_data&text=anytext&sort=sortable_field&dir=direction_asc_or_desc&page=1

Example Response

    {
    "current_page": 1,
    "data": [
        {
            "id": 16,
            "group_key": "",
            "event_zone_id": 4,
            "event_category_id": 3,
            "event_type_id": 4,
            "title": "Book on Biletix",
            "start": "2024-05-30 00:00:00",
            "end": "2024-05-30 00:00:00",
            "audience": "male",
            "booking_url": "https:\/\/biletinial.com\/tr-tr\/tiyatro\/inspera-talks-yasemin-sefik-napicaz-bu-lovebombingleri",
            "thumb": "\/images\/art-slide02.jpg",
            "images": [
                {
                    "image": "\/images\/event01.jpg"
                }
            ],
            "short": "",
            "description": "",
            "color": null,
            "price": null,
            "currency": null,
            "capacity": 50,
            "status": 1,
            "deleted_at": null,
            "created_at": "2024-07-06T16:30:26.000000Z",
            "updated_at": "2024-07-10T17:28:20.000000Z",
            "event_zone": {
                "id": 4,
                "code": "theaterhall",
                "name": "Theater Hall",
                "description": "Theater Hall Description",
                "thumb": "\/images\/event02.jpg",
                "color": "#c0392b",
                "icon": null,
                "capacity": 50,
                "status": 1,
                "deleted_at": null,
                "created_at": "2024-07-05T07:19:09.000000Z",
                "updated_at": "2024-07-08T17:14:50.000000Z"
            },
            "event_type": {
                "id": 4,
                "code": "theater",
                "name": "Theater",
                "description": "Theater description",
                "thumb": "\/images\/art-slide03.jpg",
                "color": "#f1c40f",
                "icon": null,
                "status": 1,
                "deleted_at": null,
                "created_at": "2024-07-05T07:20:58.000000Z",
                "updated_at": "2024-07-08T17:14:28.000000Z"
            },
            "event_category": {
                "id": 3,
                "code": "cat01",
                "name": "General",
                "description": "General Category",
                "thumb": "\/images\/icon02.png",
                "color": "#16a085",
                "icon": null,
                "status": 1,
                "deleted_at": null,
                "created_at": "2024-07-06T12:23:04.000000Z",
                "updated_at": "2024-07-10T08:24:26.000000Z"
            }
        },
        {
            "id": 15,
            "group_key": "",
            "event_zone_id": 4,
            "event_category_id": 3,
            "event_type_id": 4,
            "title": "testx001",
            "start": "2024-05-30 00:00:00",
            "end": "2024-05-30 00:00:00",
            "audience": "male",
            "booking_url": "",
            "thumb": "\/images\/event01.jpg",
            "images": null,
            "short": "",
            "description": "",
            "color": null,
            "price": null,
            "currency": null,
            "capacity": 50,
            "status": 1,
            "deleted_at": null,
            "created_at": "2024-07-06T16:27:52.000000Z",
            "updated_at": "2024-07-09T19:02:32.000000Z",
            "event_zone": {
                "id": 4,
                "code": "theaterhall",
                "name": "Theater Hall",
                "description": "Theater Hall Description",
                "thumb": "\/images\/event02.jpg",
                "color": "#c0392b",
                "icon": null,
                "capacity": 50,
                "status": 1,
                "deleted_at": null,
                "created_at": "2024-07-05T07:19:09.000000Z",
                "updated_at": "2024-07-08T17:14:50.000000Z"
            },
            "event_type": {
                "id": 4,
                "code": "theater",
                "name": "Theater",
                "description": "Theater description",
                "thumb": "\/images\/art-slide03.jpg",
                "color": "#f1c40f",
                "icon": null,
                "status": 1,
                "deleted_at": null,
                "created_at": "2024-07-05T07:20:58.000000Z",
                "updated_at": "2024-07-08T17:14:28.000000Z"
            },
            "event_category": {
                "id": 3,
                "code": "cat01",
                "name": "General",
                "description": "General Category",
                "thumb": "\/images\/icon02.png",
                "color": "#16a085",
                "icon": null,
                "status": 1,
                "deleted_at": null,
                "created_at": "2024-07-06T12:23:04.000000Z",
                "updated_at": "2024-07-10T08:24:26.000000Z"
            }
        }
    ],
    "first_page_url": "https:\/\/october.tulparstudyo.com\/swordbros\/api\/search?page=1",
    "from": 1,
    "last_page": 4,
    "last_page_url": "https:\/\/october.tulparstudyo.com\/swordbros\/api\/search?page=4",
    "links": [
        {
            "url": null,
            "label": "pagination.previous",
            "active": false
        },
        {
            "url": "https:\/\/october.tulparstudyo.com\/swordbros\/api\/search?page=1",
            "label": "1",
            "active": true
        },
        {
            "url": "https:\/\/october.tulparstudyo.com\/swordbros\/api\/search?page=2",
            "label": "2",
            "active": false
        },
        {
            "url": "https:\/\/october.tulparstudyo.com\/swordbros\/api\/search?page=3",
            "label": "3",
            "active": false
        },
        {
            "url": "https:\/\/october.tulparstudyo.com\/swordbros\/api\/search?page=4",
            "label": "4",
            "active": false
        },
        {
            "url": "https:\/\/october.tulparstudyo.com\/swordbros\/api\/search?page=2",
            "label": "pagination.next",
            "active": false
        }
    ],
    "next_page_url": "https:\/\/october.tulparstudyo.com\/swordbros\/api\/search?page=2",
    "path": "https:\/\/october.tulparstudyo.com\/swordbros\/api\/search",
    "per_page": 2,
    "prev_page_url": null,
    "to": 2,
    "total": 7
    }
#### Examples:
    /swordbros/api/search?text=biletix
    /swordbros/api/search?text=biletix&event_type=3
    /swordbros/api/search?text=biletix&event_type=3&event_zone=3,4

## Tagged Events
usable tags ```feature, recommended, bestseller```
how to get Tagged events
```\Swordbros\Base\Controllers\Amele::get_tagged_events('featured')```
