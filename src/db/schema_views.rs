//
// Views
//

diesel::table! {
    front_page (id) {
        id -> Int4,
        name -> Text,
        description -> Nullable<Text>,
        url -> Text,
        ping -> Float8,
        return_code -> Int4,
        is_restricted -> Bool,
        up -> Bool,
        test_reason -> Nullable<Text>,
        added_time -> Timestamptz,
        last_active -> Timestamptz,
        last_checked -> Timestamptz,
        categories -> Jsonb,
        clicks -> Int8,
        clicks_last_24h -> Int8,
        proxy_list -> Jsonb,
        comments -> Jsonb
    }
}

diesel::table! {
    website_info (id) {
        id -> Int4,
        web_id -> Int4,
        url -> Text,
        official -> Bool,
        ping -> Float8,
        return_code -> Int4,
        is_restricted -> Bool,
        up -> Bool,
        test_reason -> Nullable<Text>,
        added_time -> Timestamptz,
        last_active -> Timestamptz,
        last_checked -> Timestamptz,
        clicks -> Int8,
        clicks_last_24h -> Int8,
        comments -> Jsonb
    }
}