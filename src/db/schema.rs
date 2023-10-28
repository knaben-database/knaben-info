// @generated automatically by Diesel CLI.

diesel::table! {
    categories (id) {
        id -> Int4,
        #[max_length = 128]
        name -> Varchar,
        description -> Nullable<Text>,
        added_time -> Timestamptz,
    }
}

diesel::table! {
    clicks (identifier, proxy_id) {
        #[max_length = 64]
        identifier -> Bpchar,
        proxy_id -> Int4,
        time -> Timestamptz,
    }
}

diesel::table! {
    comments (id) {
        id -> Int4,
        content -> Text,
        identifier -> Nullable<Text>,
        time -> Timestamptz,
        web_id -> Nullable<Int4>,
        proxy_id -> Nullable<Int4>,
    }
}

diesel::table! {
    proxy_list (id) {
        id -> Int4,
        web_id -> Int4,
        url -> Text,
        test_path -> Nullable<Text>,
        test_match -> Nullable<Text>,
        ping -> Float8,
        return_code -> Int4,
        up -> Bool,
        test_reason -> Nullable<Text>,
        added_time -> Timestamptz,
        last_active -> Timestamptz,
        last_checked -> Timestamptz,
        official -> Bool,
    }
}

diesel::table! {
    website (id) {
        id -> Int4,
        name -> Text,
        url -> Text,
        description -> Nullable<Text>,
        ping -> Float8,
        return_code -> Int4,
        up -> Bool,
        test_match -> Text,
        test_path -> Text,
        test_reason -> Nullable<Text>,
        added_time -> Timestamptz,
        last_active -> Timestamptz,
        last_checked -> Timestamptz,
    }
}

diesel::table! {
    website_categories (web_id, cat_id) {
        cat_id -> Int4,
        web_id -> Int4,
    }
}

diesel::joinable!(clicks -> proxy_list (proxy_id));
diesel::joinable!(proxy_list -> website (web_id));
diesel::joinable!(website_categories -> categories (cat_id));
diesel::joinable!(website_categories -> website (web_id));

diesel::allow_tables_to_appear_in_same_query!(
    categories,
    clicks,
    comments,
    proxy_list,
    website,
    website_categories,
);


// Views
diesel::table! {
    front_page (id) {
        id -> Int4,
        name -> Text,
        description -> Nullable<Text>,
        url -> Text,
        ping -> Float8,
        return_code -> Int4,
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