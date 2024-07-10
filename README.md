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
            "updated_at": "2024-07-10T17:28:20.000000Z"
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
            "updated_at": "2024-07-09T19:02:32.000000Z"
        },
        {
            "id": 14,
            "group_key": "",
            "event_zone_id": 4,
            "event_category_id": 3,
            "event_type_id": 4,
            "title": "testx001",
            "start": "2024-05-30 00:00:00",
            "end": "2024-05-30 00:00:00",
            "audience": "male",
            "booking_url": "",
            "thumb": "\/images\/event02.jpg",
            "images": null,
            "short": "",
            "description": "",
            "color": "#8e44ad",
            "price": null,
            "currency": null,
            "capacity": 50,
            "status": 1,
            "deleted_at": null,
            "created_at": "2024-07-06T16:26:48.000000Z",
            "updated_at": "2024-07-09T20:28:18.000000Z"
        },
        {
            "id": 13,
            "group_key": "",
            "event_zone_id": 4,
            "event_category_id": 3,
            "event_type_id": 4,
            "title": "testx001",
            "start": "2024-05-30 00:00:00",
            "end": "2024-05-30 00:00:00",
            "audience": "male",
            "booking_url": "",
            "thumb": "\/images\/cursor-sprite.jpg",
            "images": null,
            "short": "",
            "description": "",
            "color": "#f1c40f",
            "price": null,
            "currency": null,
            "capacity": 50,
            "status": 1,
            "deleted_at": null,
            "created_at": "2024-07-06T16:25:52.000000Z",
            "updated_at": "2024-07-09T17:05:44.000000Z"
        },
        {
            "id": 12,
            "group_key": "",
            "event_zone_id": 4,
            "event_category_id": 3,
            "event_type_id": 4,
            "title": "testx001",
            "start": "2024-05-30 00:00:00",
            "end": "2024-05-30 00:00:00",
            "audience": "male",
            "booking_url": "",
            "thumb": "\/images\/art-slide01.jpg",
            "images": null,
            "short": "",
            "description": "",
            "color": "#27ae60",
            "price": null,
            "currency": null,
            "capacity": 50,
            "status": 1,
            "deleted_at": null,
            "created_at": "2024-07-06T16:24:06.000000Z",
            "updated_at": "2024-07-09T20:27:47.000000Z"
        },
        {
            "id": 10,
            "group_key": "",
            "event_zone_id": 4,
            "event_category_id": 3,
            "event_type_id": 3,
            "title": "Lorem Ipsum",
            "start": "2024-05-29 00:00:00",
            "end": "2024-05-29 00:00:00",
            "audience": "male",
            "booking_url": "",
            "thumb": "\/images\/image04.jpg",
            "images": null,
            "short": "",
            "description": "",
            "color": "#d35400",
            "price": null,
            "currency": null,
            "capacity": 50,
            "status": 1,
            "deleted_at": null,
            "created_at": "2024-07-06T12:55:26.000000Z",
            "updated_at": "2024-07-09T17:15:53.000000Z"
        },
        {
            "id": 7,
            "group_key": "t001",
            "event_zone_id": 4,
            "event_category_id": 3,
            "event_type_id": 4,
            "title": "Test Theater",
            "start": "2024-05-28 00:00:00",
            "end": "2024-05-28 06:30:00",
            "audience": "male",
            "booking_url": "",
            "thumb": "\/images\/side-imag01.jpg",
            "images": null,
            "short": "",
            "description": "<p>pending<\/p>",
            "color": "#8e44ad",
            "price": null,
            "currency": null,
            "capacity": 50,
            "status": 1,
            "deleted_at": null,
            "created_at": "2024-07-06T12:23:44.000000Z",
            "updated_at": "2024-07-09T17:17:15.000000Z"
        }
    ],
    "first_page_url": "https:\/\/october.tulparstudyo.com\/swordbros\/api\/search?page=1",
    "from": 1,
    "last_page": 1,
    "last_page_url": "https:\/\/october.tulparstudyo.com\/swordbros\/api\/search?page=1",
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
            "url": null,
            "label": "pagination.next",
            "active": false
        }
    ],
    "next_page_url": null,
    "path": "https:\/\/october.tulparstudyo.com\/swordbros\/api\/search",
    "per_page": 15,
    "prev_page_url": null,
    "to": 7,
    "total": 7
    }


