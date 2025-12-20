`local-deals`-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `category_icon` varchar(191) NOT NULL,
  `category_name` varchar(191) NOT NULL,
  `category_slug` varchar(191) DEFAULT NULL,
  `category_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category_icon`, `category_name`, `category_slug`, `category_image`) VALUES
(1, 'fa-audio-description', 'Advertising', 'advertising', 'advertising-3e28dacc4bc7a4d190dc88459ddab926-b.jpg'),
(2, 'fa-certificate', 'Home & Garden', 'home-garden', 'home-garden-efa1822ab4a88864ecc0109aca7971bd-b.jpg'),
(3, 'fa-shopping-cart', 'E-Commerce', 'e-commerce', 'e-commerce-4f8963c1d8c2040dceaa332d6b6859a3-b.jpg'),
(4, 'fa-book', 'Education', 'education', 'education-c1b7b2d740af1671b76fb1c323a30d18-b.jpg'),
(5, 'fa-film', 'Entertainment', 'entertainment', 'entertainment-b6e1133704d3f3a95e25bc483dfc46e4-b.jpg'),
(6, 'fa-industry', 'Industry', 'industry', 'industry-df30e456c5b54cc032b12cbb58b4db88-b.jpg'),
(7, 'fa-home', 'Real Estate', 'real-estate', 'real-estate-303ac3008d28faf08c9c7d1b978ebdd2-b.jpg'),
(8, 'fa-coffee', 'Restaurants', 'restaurants', 'restaurants-fbf109a3de58a57fa6b13c1cc627ca8f-b.jpg'),
(9, 'fa-heartbeat', 'Beauty', 'hair-salon', 'beauty-68d0283d590b3a67a55442ed2289e93c-b.jpg'),
(10, 'fa-hard-of-hearing', 'Health and Medical', 'health-and-medical', 'health-and-medical-ca5f22df859d1d79f74a8b7a0ff824d2-b.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `listings`
--

CREATE TABLE `listings` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `cat_id` int(11) NOT NULL,
  `sub_cat_id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL,
  `featured_listing` int(11) NOT NULL DEFAULT 0,
  `title` varchar(191) NOT NULL,
  `listing_slug` varchar(191) DEFAULT NULL,
  `description` longtext NOT NULL,
  `video` text DEFAULT NULL,
  `address` text NOT NULL,
  `google_map_code` varchar(500) DEFAULT NULL,
  `amenities` text DEFAULT NULL,
  `working_hours_mon` varchar(191) DEFAULT NULL,
  `working_hours_tue` varchar(191) DEFAULT NULL,
  `working_hours_wed` varchar(191) DEFAULT NULL,
  `working_hours_thurs` varchar(191) DEFAULT NULL,
  `working_hours_fri` varchar(191) DEFAULT NULL,
  `working_hours_sat` varchar(191) DEFAULT NULL,
  `working_hours_sun` varchar(191) DEFAULT NULL,
  `featured_image` varchar(191) DEFAULT NULL,
  `review_avg` varchar(191) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `listings`
--

INSERT INTO `listings` (`id`, `user_id`, `cat_id`, `sub_cat_id`, `location_id`, `featured_listing`, `title`, `listing_slug`, `description`, `video`, `address`, `google_map_code`, `amenities`, `working_hours_mon`, `working_hours_tue`, `working_hours_wed`, `working_hours_thurs`, `working_hours_fri`, `working_hours_sat`, `working_hours_sun`, `featured_image`, `review_avg`, `status`, `created_at`, `updated_at`) VALUES
(10, 1, 2, 18, 5, 1, 'Tasty Hand-Pulled Noodles', 'tasty-hand-pulled-noodles', '<p>Nemo ucxqui officia voluptatem accu santium doloremque laudantium, totam rem ape dicta sunt dose explicabo. Nemo enim ipsam voluptatem quia voluptas. Excepteur sint occaecat cupidatat non proident, sunt in culpa kequi officia deserunt mollit anim id est laborum. Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusan tium dolorem que laudantium, totam rem aperiam the eaque ipsa quae abillo was inventore veritatis keret quasi aperiam architecto beatae vitae dicta sunt explicabo. Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>', NULL, '101 East Parkview Road, New York', NULL, 'Free WiFi,Parking,Pet allowed,Spa and fitness center,Swimming pool,Bar', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'tasty-hand-pulled-noodles_1675771845', '0', 1, '2023-02-07 12:10:45', '2023-02-07 12:19:37'),
(11, 1, 5, 5, 7, 1, 'Favorite Place Food Bank', 'favorite-place-food-bank', '<p>Nemo ucxqui officia voluptatem accu santium doloremque laudantium, totam rem ape dicta sunt dose explicabo. Nemo enim ipsam voluptatem quia voluptas. Excepteur sint occaecat cupidatat non proident, sunt in culpa kequi officia deserunt mollit anim id est laborum. Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusan tium dolorem que laudantium, totam rem aperiam the eaque ipsa quae abillo was inventore veritatis keret quasi aperiam architecto beatae vitae dicta sunt explicabo. Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>', NULL, '101 East Parkview Road, New York', NULL, 'Free WiFi,Parking,Pet allowed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'favorite-place-food-bank_1675771961', '0', 1, '2023-02-07 12:12:41', '2023-02-07 12:19:38'),
(12, 1, 8, 32, 2, 1, 'Clarance Hotel', 'clarance-hotel', '<p>This hotel sits in central Nice, just 1,150 feet from the ferry port and a 20-minute walk from the Promenade des Anglais. Its rooms are air-conditioned with free Wi-Fi access. The guest rooms are equipped with flat-screen TVs with satellite channels and a private bathroom. Each is serviced by a lift. It has a 24-hour reception, which is hosted by a multilingual team. The hotel serves a buffet breakfast every morning.&nbsp;</p>', '<iframe frameborder=\"0\" scrolling=\"no\" marginheight=\"0\" marginwidth=\"0\"width=\"740\" height=\"440\" type=\"text/html\" src=\"https://www.youtube.com/embed/DBXH9jJRaDk?autoplay=0&fs=0&iv_load_policy=3&showinfo=0&rel=0&cc_load_policy=0&start=0&end=0&origin=http://youtubeembedcode.com\"><div><small><a href=\"https://youtubeembedcode.com/nl/\">youtubeembedcode.com/nl/</a></small></div><div><small><a href=\"https://harpangratis.se/\">harpangratis.se</a></small></div><div><small><a href=\"https://youtubeembedcode.com/en\">youtubeembedcode en</a></small></div><div><small><a href=\"https://spindelharpan.nu/\">spindelharpan gamla</a></small></div><div><small><a href=\"https://youtubeembedcode.com/de/\">youtubeembedcode de</a></small></div><div><small><a href=\"https://harpangratis.se/\">spela harpan</a></small></div></iframe>', '205 East Parkview Road, Paris', NULL, 'Free WiFi,Parking,Pet allowed,Swimming pool,Bar', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'clarance-hotel_1675772134', '0', 1, '2023-02-07 12:15:34', '2023-02-07 12:29:09'),
(13, 1, 8, 29, 7, 0, 'La Cour Berbisey', 'la-cour-berbisey', '<p>Nemo ucxqui officia voluptatem accu santium doloremque laudantium, totam rem ape dicta sunt dose explicabo. Nemo enim ipsam voluptatem quia voluptas. Excepteur sint occaecat cupidatat non proident, sunt in culpa kequi officia deserunt mollit anim id est laborum. Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusan tium dolorem que laudantium, totam rem aperiam the eaque ipsa quae abillo was inventore veritatis keret quasi aperiam architecto beatae vitae dicta sunt explicabo. Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>', '<iframe frameborder=\"0\" scrolling=\"no\" marginheight=\"0\" marginwidth=\"0\"width=\"740\" height=\"440\" type=\"text/html\" src=\"https://www.youtube.com/embed/DBXH9jJRaDk?autoplay=0&fs=0&iv_load_policy=3&showinfo=0&rel=0&cc_load_policy=0&start=0&end=0&origin=http://youtubeembedcode.com\"><div><small><a href=\"https://youtubeembedcode.com/nl/\">youtubeembedcode.com/nl/</a></small></div><div><small><a href=\"https://harpangratis.se/\">harpangratis.se</a></small></div><div><small><a href=\"https://youtubeembedcode.com/en\">youtubeembedcode en</a></small></div><div><small><a href=\"https://spindelharpan.nu/\">spindelharpan gamla</a></small></div><div><small><a href=\"https://youtubeembedcode.com/de/\">youtubeembedcode de</a></small></div><div><small><a href=\"https://harpangratis.se/\">spela harpan</a></small></div></iframe>', '205 East Parkview Road, France', '<iframe scrolling=\"no\" marginheight=\"0\" marginwidth=\"0\" src=\"https://maps.google.com/maps?width=100%25&amp;height=600&amp;hl=en&amp;q=205%20Parkview%20Road,%20Springfield,%20VT,%20USA+(205%20Parkview%20Road,%20Springfield,%20VT,%20USA)&amp;t=&amp;z=14&amp;ie=UTF8&amp;iwloc=B&amp;output=embed\" width=\"100%\" height=\"600\" frameborder=\"0\"><a href=\"https://www.maps.ie/distance-area-calculator.html\">area maps</a></iframe>', 'Free WiFi,Parking,BBQ facilities,Smoke allowed,Bar', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'la-cour-berbisey_1675772811', '0', 1, '2023-02-07 12:26:51', '2023-02-07 12:28:48'),
(14, 4, 3, 3, 4, 1, 'Vente Luxe Mademoiselle', 'vente-luxe-mademoiselle', '<p>Nemo ucxqui officia voluptatem accu santium doloremque laudantium, totam rem ape dicta sunt dose explicabo. Nemo enim ipsam voluptatem quia voluptas. Excepteur sint occaecat cupidatat non proident, sunt in culpa kequi officia deserunt mollit anim id est laborum. Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusan tium dolorem que laudantium, totam rem aperiam the eaque ipsa quae abillo was inventore veritatis keret quasi aperiam architecto beatae vitae dicta sunt explicabo. Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>', '<iframe frameborder=\"0\" scrolling=\"no\" marginheight=\"0\" marginwidth=\"0\"width=\"740\" height=\"440\" type=\"text/html\" src=\"https://www.youtube.com/embed/DBXH9jJRaDk?autoplay=0&fs=0&iv_load_policy=3&showinfo=0&rel=0&cc_load_policy=0&start=0&end=0&origin=http://youtubeembedcode.com\"><div><small><a href=\"https://youtubeembedcode.com/nl/\">youtubeembedcode.com/nl/</a></small></div><div><small><a href=\"https://harpangratis.se/\">harpangratis.se</a></small></div><div><small><a href=\"https://youtubeembedcode.com/en\">youtubeembedcode en</a></small></div><div><small><a href=\"https://spindelharpan.nu/\">spindelharpan gamla</a></small></div><div><small><a href=\"https://youtubeembedcode.com/de/\">youtubeembedcode de</a></small></div><div><small><a href=\"https://harpangratis.se/\">spela harpan</a></small></div></iframe>', 'Lorem ipsum dolor sit amet consectetur.', '<iframe scrolling=\"no\" marginheight=\"0\" marginwidth=\"0\" src=\"https://maps.google.com/maps?width=100%25&amp;height=350&amp;hl=en&amp;q=205%20Parkview%20Road,%20Springfield,%20VT,%20USA+(205%20Parkview%20Road,%20Springfield,%20VT,%20USA)&amp;t=&amp;z=14&amp;ie=UTF8&amp;iwloc=B&amp;output=embed\" width=\"100%\" height=\"500\" frameborder=\"0\"><a href=\"https://www.maps.ie/distance-area-calculator.html\">area maps</a></iframe>', 'Good for kids,Parking,Pet allowed,Music', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'depot-vente-luxe-mademoiselle_1675830486', '0', 1, '2023-02-08 04:01:14', '2023-02-08 04:53:22'),
(15, 4, 3, 3, 5, 0, 'Printemps Point Resort & Marina', 'printemps-point-resort-marina', '<p>Nemo ucxqui officia voluptatem accu santium doloremque laudantium, totam rem ape dicta sunt dose explicabo. Nemo enim ipsam voluptatem quia voluptas. Excepteur sint occaecat cupidatat non proident, sunt in culpa kequi officia deserunt mollit anim id est laborum. Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusan tium dolorem que laudantium, totam rem aperiam the eaque ipsa quae abillo was inventore veritatis keret quasi aperiam architecto beatae vitae dicta sunt explicabo. Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>', '<iframe frameborder=\"0\" scrolling=\"no\" marginheight=\"0\" marginwidth=\"0\"width=\"740\" height=\"440\" type=\"text/html\" src=\"https://www.youtube.com/embed/DBXH9jJRaDk?autoplay=0&fs=0&iv_load_policy=3&showinfo=0&rel=0&cc_load_policy=0&start=0&end=0&origin=http://youtubeembedcode.com\"><div><small><a href=\"https://youtubeembedcode.com/nl/\">youtubeembedcode.com/nl/</a></small></div><div><small><a href=\"https://harpangratis.se/\">harpangratis.se</a></small></div><div><small><a href=\"https://youtubeembedcode.com/en\">youtubeembedcode en</a></small></div><div><small><a href=\"https://spindelharpan.nu/\">spindelharpan gamla</a></small></div><div><small><a href=\"https://youtubeembedcode.com/de/\">youtubeembedcode de</a></small></div><div><small><a href=\"https://harpangratis.se/\">spela harpan</a></small></div></iframe>', '205 East Parkview Road, France', '<iframe scrolling=\"no\" marginheight=\"0\" marginwidth=\"0\" src=\"https://maps.google.com/maps?width=100%25&amp;height=600&amp;hl=en&amp;q=205%20Parkview%20Road,%20Springfield,%20VT,%20USA+(205%20Parkview%20Road,%20Springfield,%20VT,%20USA)&amp;t=&amp;z=14&amp;ie=UTF8&amp;iwloc=B&amp;output=embed\" width=\"100%\" height=\"600\" frameborder=\"0\"><a href=\"https://www.maps.ie/distance-area-calculator.html\">area maps</a></iframe>', 'Music,Pet allowed,Parking,Good for kids', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'printemps-de-marnie_1675829129', '0', 1, '2023-02-08 04:05:29', '2023-02-08 04:29:24'),
(16, 4, 9, 33, 1, 1, 'Beauty Paris Mix', 'beauty-paris-mix', '<p>Nemo ucxqui officia voluptatem accu santium doloremque laudantium, totam rem ape dicta sunt dose explicabo. Nemo enim ipsam voluptatem quia voluptas. Excepteur sint occaecat cupidatat non proident, sunt in culpa kequi officia deserunt mollit anim id est laborum. Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusan tium dolorem que laudantium, totam rem aperiam the eaque ipsa quae abillo was inventore veritatis keret quasi aperiam architecto beatae vitae dicta sunt explicabo. Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>', '<iframe frameborder=\"0\" scrolling=\"no\" marginheight=\"0\" marginwidth=\"0\"width=\"740\" height=\"440\" type=\"text/html\" src=\"https://www.youtube.com/embed/DBXH9jJRaDk?autoplay=0&fs=0&iv_load_policy=3&showinfo=0&rel=0&cc_load_policy=0&start=0&end=0&origin=http://youtubeembedcode.com\"><div><small><a href=\"https://youtubeembedcode.com/nl/\">youtubeembedcode.com/nl/</a></small></div><div><small><a href=\"https://harpangratis.se/\">harpangratis.se</a></small></div><div><small><a href=\"https://youtubeembedcode.com/en\">youtubeembedcode en</a></small></div><div><small><a href=\"https://spindelharpan.nu/\">spindelharpan gamla</a></small></div><div><small><a href=\"https://youtubeembedcode.com/de/\">youtubeembedcode de</a></small></div><div><small><a href=\"https://harpangratis.se/\">spela harpan</a></small></div></iframe>', '101 East Parkview Road, New York', '<iframe scrolling=\"no\" marginheight=\"0\" marginwidth=\"0\" src=\"https://maps.google.com/maps?width=100%25&amp;height=600&amp;hl=en&amp;q=205%20Parkview%20Road,%20Springfield,%20VT,%20USA+(205%20Parkview%20Road,%20Springfield,%20VT,%20USA)&amp;t=&amp;z=14&amp;ie=UTF8&amp;iwloc=B&amp;output=embed\" width=\"100%\" height=\"600\" frameborder=\"0\"><a href=\"https://www.maps.ie/distance-area-calculator.html\">area maps</a></iframe>', 'Television,Wheelchair accessible,Free WiFi,Parking,Music,Spa and fitness center', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'mix-beauty-paris_1675829860', '0', 1, '2023-02-08 04:17:40', '2023-02-08 04:30:36'),
(17, 4, 9, 35, 2, 1, 'Luxary Hotel Spa', 'luxary-hotel-spa', '<p>Nemo ucxqui officia voluptatem accu santium doloremque laudantium, totam rem ape dicta sunt dose explicabo. Nemo enim ipsam voluptatem quia voluptas. Excepteur sint occaecat cupidatat non proident, sunt in culpa kequi officia deserunt mollit anim id est laborum. Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusan tium dolorem que laudantium, totam rem aperiam the eaque ipsa quae abillo was inventore veritatis keret quasi aperiam architecto beatae vitae dicta sunt explicabo. Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>', '<iframe frameborder=\"0\" scrolling=\"no\" marginheight=\"0\" marginwidth=\"0\"width=\"740\" height=\"440\" type=\"text/html\" src=\"https://www.youtube.com/embed/DBXH9jJRaDk?autoplay=0&fs=0&iv_load_policy=3&showinfo=0&rel=0&cc_load_policy=0&start=0&end=0&origin=http://youtubeembedcode.com\"><div><small><a href=\"https://youtubeembedcode.com/nl/\">youtubeembedcode.com/nl/</a></small></div><div><small><a href=\"https://harpangratis.se/\">harpangratis.se</a></small></div><div><small><a href=\"https://youtubeembedcode.com/en\">youtubeembedcode en</a></small></div><div><small><a href=\"https://spindelharpan.nu/\">spindelharpan gamla</a></small></div><div><small><a href=\"https://youtubeembedcode.com/de/\">youtubeembedcode de</a></small></div><div><small><a href=\"https://harpangratis.se/\">spela harpan</a></small></div></iframe>', '205 East Parkview Road, Paris', '<iframe scrolling=\"no\" marginheight=\"0\" marginwidth=\"0\" src=\"https://maps.google.com/maps?width=100%25&amp;height=600&amp;hl=en&amp;q=205%20Parkview%20Road,%20Springfield,%20VT,%20USA+(205%20Parkview%20Road,%20Springfield,%20VT,%20USA)&amp;t=&amp;z=14&amp;ie=UTF8&amp;iwloc=B&amp;output=embed\" width=\"100%\" height=\"600\" frameborder=\"0\"><a href=\"https://www.maps.ie/distance-area-calculator.html\">area maps</a></iframe>', 'pa and fitness center,Parking,Free WiFi,Wheelchair accessible', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'luxary-hotel-spa_1675830870', '0', 1, '2023-02-08 04:34:30', '2023-02-08 04:34:40'),
(18, 4, 5, 6, 3, 0, 'Saybrook Fitness Point', 'saybrook-fitness-point', '<p>Lorem ipsum dolor sit amet, suscipit dissentiunt usu at, eu nam veri vidit signiferumque. Ad mea erat fabellas, et facete everti eum, tation consul ea ius. Autem feugiat maiorum id sea. Est omnis mediocrem assentior ea. Nam ubique possit verterem ea, cum facer scriptorem an.</p>\r\n<p>Equidem legendos duo ei, et legimus offendit mei. Mea amet tibique explicari ne. Nam blandit patrioque comprehensam an, sed in errem omnes partem. No quo impedit percipit comprehensam, ei dolores intellegam pro, et sed quaeque temporibus referrentur. Quodsi causae dissentias in pri, idque ridens cum an. Vis in facilisi conclusionemque, eu est erant affert veritus. Id qui quodsi iriure quaestio, omittam praesent ne sea, postulant consetetur definitiones an nec.</p>\r\n<p>Probo animal interpretaris ea mea. Mea ad nostrud urbanitas inciderint, sea no noluisse incorrupte. His democritum vituperatoribus no, ad cum offendit rationibus vituperatoribus, eos te quodsi interesset. Regione bonorum no quo. Lobortis torquatos constituto ne per, ferri facete ea duo. Usu molestie complectitur eu, euismod forensibus moderatius sed no.</p>', '<iframe frameborder=\"0\" scrolling=\"no\" marginheight=\"0\" marginwidth=\"0\"width=\"740\" height=\"440\" type=\"text/html\" src=\"https://www.youtube.com/embed/DBXH9jJRaDk?autoplay=0&fs=0&iv_load_policy=3&showinfo=0&rel=0&cc_load_policy=0&start=0&end=0&origin=http://youtubeembedcode.com\"><div><small><a href=\"https://youtubeembedcode.com/nl/\">youtubeembedcode.com/nl/</a></small></div><div><small><a href=\"https://harpangratis.se/\">harpangratis.se</a></small></div><div><small><a href=\"https://youtubeembedcode.com/en\">youtubeembedcode en</a></small></div><div><small><a href=\"https://spindelharpan.nu/\">spindelharpan gamla</a></small></div><div><small><a href=\"https://youtubeembedcode.com/de/\">youtubeembedcode de</a></small></div><div><small><a href=\"https://harpangratis.se/\">spela harpan</a></small></div></iframe>', '255 Queensberry Street, North Melbourne Australia.', '<iframe scrolling=\"no\" marginheight=\"0\" marginwidth=\"0\" src=\"https://maps.google.com/maps?width=100%25&amp;height=600&amp;hl=en&amp;q=205%20Parkview%20Road,%20Springfield,%20VT,%20USA+(205%20Parkview%20Road,%20Springfield,%20VT,%20USA)&amp;t=&amp;z=14&amp;ie=UTF8&amp;iwloc=B&amp;output=embed\" width=\"100%\" height=\"600\" frameborder=\"0\"><a href=\"https://www.maps.ie/distance-area-calculator.html\">area maps</a></iframe>', 'Free Wifi,Car Parking Lot,Television,Fitness Center,Gaming Corner', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'saybrook-fitness-point_1675832908', '0', 1, '2023-02-08 05:08:28', '2023-02-08 06:42:39'),
(19, 5, 10, 38, 3, 1, 'Tokyo Medical Center', 'tokyo-medical-center', '<p>Lorem ipsum dolor sit amet, suscipit dissentiunt usu at, eu nam veri vidit signiferumque. Ad mea erat fabellas, et facete everti eum, tation consul ea ius. Autem feugiat maiorum id sea. Est omnis mediocrem assentior ea. Nam ubique possit verterem ea, cum facer scriptorem an.</p>\r\n<p><span style=\"font-size: 1rem;\">Equidem legendos duo ei, et legimus offendit mei. Mea amet tibique explicari ne. Nam blandit patrioque comprehensam an, sed in errem omnes partem. No quo impedit percipit comprehensam, ei dolores intellegam pro, et sed quaeque temporibus referrentur. Quodsi causae dissentias in pri, idque ridens cum an. Vis in facilisi conclusionemque, eu est erant affert veritus. Id qui quodsi iriure quaestio, omittam praesent ne sea, postulant consetetur definitiones an nec.</span></p>\r\n<p><span style=\"font-size: 1rem;\">Probo animal interpretaris ea mea. Mea ad nostrud urbanitas inciderint, sea no noluisse incorrupte. His democritum vituperatoribus no, ad cum offendit rationibus vituperatoribus, eos te quodsi interesset. Regione bonorum no quo. Lobortis torquatos constituto ne per, ferri facete ea duo. Usu molestie complectitur eu, euismod forensibus moderatius sed no.</span></p>', NULL, '255 Queensberry Street Australia.', '<iframe scrolling=\"no\" marginheight=\"0\" marginwidth=\"0\" src=\"https://maps.google.com/maps?width=100%25&amp;height=600&amp;hl=en&amp;q=205%20Parkview%20Road,%20Springfield,%20VT,%20USA+(205%20Parkview%20Road,%20Springfield,%20VT,%20USA)&amp;t=&amp;z=14&amp;ie=UTF8&amp;iwloc=B&amp;output=embed\" width=\"100%\" height=\"600\" frameborder=\"0\"><a href=\"https://www.maps.ie/distance-area-calculator.html\">area maps</a></iframe>', 'Free Wifi,Currency Exchange,Television,Fitness Center', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'tokyo-medical-center_1675834343', '0', 1, '2023-02-08 05:32:23', '2023-02-08 05:41:27'),
(20, 5, 7, 26, 2, 1, 'Ruby Wright Realty', 'ruby-wright-realty', '<p>Lorem ipsum dolor sit amet, suscipit dissentiunt usu at, eu nam veri vidit signiferumque. Ad mea erat fabellas, et facete everti eum, tation consul ea ius. Autem feugiat maiorum id sea. Est omnis mediocrem assentior ea. Nam ubique possit verterem ea, cum facer scriptorem an.</p>\r\n<p>Equidem legendos duo ei, et legimus offendit mei. Mea amet tibique explicari ne. Nam blandit patrioque comprehensam an, sed in errem omnes partem. No quo impedit percipit comprehensam, ei dolores intellegam pro, et sed quaeque temporibus referrentur. Quodsi causae dissentias in pri, idque ridens cum an. Vis in facilisi conclusionemque, eu est erant affert veritus. Id qui quodsi iriure quaestio, omittam praesent ne sea, postulant consetetur definitiones an nec.</p>\r\n<p>Probo animal interpretaris ea mea. Mea ad nostrud urbanitas inciderint, sea no noluisse incorrupte. His democritum vituperatoribus no, ad cum offendit rationibus vituperatoribus, eos te quodsi interesset. Regione bonorum no quo. Lobortis torquatos constituto ne per, ferri facete ea duo. Usu molestie complectitur eu, euismod forensibus moderatius sed no.</p>', '<iframe frameborder=\"0\" scrolling=\"no\" marginheight=\"0\" marginwidth=\"0\"width=\"740\" height=\"440\" type=\"text/html\" src=\"https://www.youtube.com/embed/DBXH9jJRaDk?autoplay=0&fs=0&iv_load_policy=3&showinfo=0&rel=0&cc_load_policy=0&start=0&end=0&origin=http://youtubeembedcode.com\"><div><small><a href=\"https://youtubeembedcode.com/nl/\">youtubeembedcode.com/nl/</a></small></div><div><small><a href=\"https://harpangratis.se/\">harpangratis.se</a></small></div><div><small><a href=\"https://youtubeembedcode.com/en\">youtubeembedcode en</a></small></div><div><small><a href=\"https://spindelharpan.nu/\">spindelharpan gamla</a></small></div><div><small><a href=\"https://youtubeembedcode.com/de/\">youtubeembedcode de</a></small></div><div><small><a href=\"https://harpangratis.se/\">spela harpan</a></small></div></iframe>', '205 East Parkview Road, Paris', '<iframe scrolling=\"no\" marginheight=\"0\" marginwidth=\"0\" src=\"https://maps.google.com/maps?width=100%25&amp;height=600&amp;hl=en&amp;q=205%20Parkview%20Road,%20Springfield,%20VT,%20USA+(205%20Parkview%20Road,%20Springfield,%20VT,%20USA)&amp;t=&amp;z=14&amp;ie=UTF8&amp;iwloc=B&amp;output=embed\" width=\"100%\" height=\"600\" frameborder=\"0\"><a href=\"https://www.maps.ie/distance-area-calculator.html\">area maps</a></iframe>', 'Good for kids,Parking,Pet allowed,Music', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ruby-wright-realty_1675834675', '0', 1, '2023-02-08 05:37:55', '2023-02-08 05:41:24'),
(21, 5, 4, 9, 6, 0, 'Brook Point Book Store', 'brook-point-book-store', '<p>Lorem ipsum dolor sit amet, suscipit dissentiunt usu at, eu nam veri vidit signiferumque. Ad mea erat fabellas, et facete everti eum, tation consul ea ius. Autem feugiat maiorum id sea. Est omnis mediocrem assentior ea. Nam ubique possit verterem ea, cum facer scriptorem an.</p>\r\n<p>Equidem legendos duo ei, et legimus offendit mei. Mea amet tibique explicari ne. Nam blandit patrioque comprehensam an, sed in errem omnes partem. No quo impedit percipit comprehensam, ei dolores intellegam pro, et sed quaeque temporibus referrentur. Quodsi causae dissentias in pri, idque ridens cum an. Vis in facilisi conclusionemque, eu est erant affert veritus. Id qui quodsi iriure quaestio, omittam praesent ne sea, postulant consetetur definitiones an nec.</p>\r\n<p>Probo animal interpretaris ea mea. Mea ad nostrud urbanitas inciderint, sea no noluisse incorrupte. His democritum vituperatoribus no, ad cum offendit rationibus vituperatoribus, eos te quodsi interesset. Regione bonorum no quo. Lobortis torquatos constituto ne per, ferri facete ea duo. Usu molestie complectitur eu, euismod forensibus moderatius sed no.</p>', '<iframe frameborder=\"0\" scrolling=\"no\" marginheight=\"0\" marginwidth=\"0\"width=\"740\" height=\"440\" type=\"text/html\" src=\"https://www.youtube.com/embed/DBXH9jJRaDk?autoplay=0&fs=0&iv_load_policy=3&showinfo=0&rel=0&cc_load_policy=0&start=0&end=0&origin=http://youtubeembedcode.com\"><div><small><a href=\"https://youtubeembedcode.com/nl/\">youtubeembedcode.com/nl/</a></small></div><div><small><a href=\"https://harpangratis.se/\">harpangratis.se</a></small></div><div><small><a href=\"https://youtubeembedcode.com/en\">youtubeembedcode en</a></small></div><div><small><a href=\"https://spindelharpan.nu/\">spindelharpan gamla</a></small></div><div><small><a href=\"https://youtubeembedcode.com/de/\">youtubeembedcode de</a></small></div><div><small><a href=\"https://harpangratis.se/\">spela harpan</a></small></div></iframe>', '101 East Parkview Road, New York', '<iframe scrolling=\"no\" marginheight=\"0\" marginwidth=\"0\" src=\"https://maps.google.com/maps?width=100%25&amp;height=600&amp;hl=en&amp;q=205%20Parkview%20Road,%20Springfield,%20VT,%20USA+(205%20Parkview%20Road,%20Springfield,%20VT,%20USA)&amp;t=&amp;z=14&amp;ie=UTF8&amp;iwloc=B&amp;output=embed\" width=\"100%\" height=\"600\" frameborder=\"0\"><a href=\"https://www.maps.ie/distance-area-calculator.html\">area maps</a></iframe>', 'Television,Free WiFi,Parking,Music', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'brook-point-book-store_1675835303', '0', 1, '2023-02-08 05:48:23', '2023-02-08 06:42:30'),
(22, 5, 1, 7, 3, 1, 'Best Home Appliance Dealers', 'best-home-appliance-dealers', '<p>Lorem ipsum dolor sit amet, suscipit dissentiunt usu at, eu nam veri vidit signiferumque. Ad mea erat fabellas, et facete everti eum, tation consul ea ius. Autem feugiat maiorum id sea. Est omnis mediocrem assentior ea. Nam ubique possit verterem ea, cum facer scriptorem an.</p>\r\n<p>Equidem legendos duo ei, et legimus offendit mei. Mea amet tibique explicari ne. Nam blandit patrioque comprehensam an, sed in errem omnes partem. No quo impedit percipit comprehensam, ei dolores intellegam pro, et sed quaeque temporibus referrentur. Quodsi causae dissentias in pri, idque ridens cum an. Vis in facilisi conclusionemque, eu est erant affert veritus. Id qui quodsi iriure quaestio, omittam praesent ne sea, postulant consetetur definitiones an nec.</p>\r\n<p>Probo animal interpretaris ea mea. Mea ad nostrud urbanitas inciderint, sea no noluisse incorrupte. His democritum vituperatoribus no, ad cum offendit rationibus vituperatoribus, eos te quodsi interesset. Regione bonorum no quo. Lobortis torquatos constituto ne per, ferri facete ea duo. Usu molestie complectitur eu, euismod forensibus moderatius sed no.</p>', '<iframe frameborder=\"0\" scrolling=\"no\" marginheight=\"0\" marginwidth=\"0\"width=\"740\" height=\"440\" type=\"text/html\" src=\"https://www.youtube.com/embed/DBXH9jJRaDk?autoplay=0&fs=0&iv_load_policy=3&showinfo=0&rel=0&cc_load_policy=0&start=0&end=0&origin=http://youtubeembedcode.com\"><div><small><a href=\"https://youtubeembedcode.com/nl/\">youtubeembedcode.com/nl/</a></small></div><div><small><a href=\"https://harpangratis.se/\">harpangratis.se</a></small></div><div><small><a href=\"https://youtubeembedcode.com/en\">youtubeembedcode en</a></small></div><div><small><a href=\"https://spindelharpan.nu/\">spindelharpan gamla</a></small></div><div><small><a href=\"https://youtubeembedcode.com/de/\">youtubeembedcode de</a></small></div><div><small><a href=\"https://harpangratis.se/\">spela harpan</a></small></div></iframe>', '255 Queensberry Street Australia.', '<iframe scrolling=\"no\" marginheight=\"0\" marginwidth=\"0\" src=\"https://maps.google.com/maps?width=100%25&amp;height=600&amp;hl=en&amp;q=205%20Parkview%20Road,%20Springfield,%20VT,%20USA+(205%20Parkview%20Road,%20Springfield,%20VT,%20USA)&amp;t=&amp;z=14&amp;ie=UTF8&amp;iwloc=B&amp;output=embed\" width=\"100%\" height=\"600\" frameborder=\"0\"><a href=\"https://www.maps.ie/distance-area-calculator.html\">area maps</a></iframe>', 'Parking,Music,Free WiFi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'best-home-appliance-dealers_1675837023', '0', 1, '2023-02-08 06:17:03', '2023-02-08 06:17:08'),
(23, 5, 6, 20, 6, 0, 'Cleaning Services Company', 'cleaning-services-company', '<p>Lorem ipsum dolor sit amet, suscipit dissentiunt usu at, eu nam veri vidit signiferumque. Ad mea erat fabellas, et facete everti eum, tation consul ea ius. Autem feugiat maiorum id sea. Est omnis mediocrem assentior ea. Nam ubique possit verterem ea, cum facer scriptorem an.</p>\r\n<p>Equidem legendos duo ei, et legimus offendit mei. Mea amet tibique explicari ne. Nam blandit patrioque comprehensam an, sed in errem omnes partem. No quo impedit percipit comprehensam, ei dolores intellegam pro, et sed quaeque temporibus referrentur. Quodsi causae dissentias in pri, idque ridens cum an. Vis in facilisi conclusionemque, eu est erant affert veritus. Id qui quodsi iriure quaestio, omittam praesent ne sea, postulant consetetur definitiones an nec.</p>\r\n<p>Probo animal interpretaris ea mea. Mea ad nostrud urbanitas inciderint, sea no noluisse incorrupte. His democritum vituperatoribus no, ad cum offendit rationibus vituperatoribus, eos te quodsi interesset. Regione bonorum no quo. Lobortis torquatos constituto ne per, ferri facete ea duo. Usu molestie complectitur eu, euismod forensibus moderatius sed no.</p>', '<iframe frameborder=\"0\" scrolling=\"no\" marginheight=\"0\" marginwidth=\"0\"width=\"740\" height=\"440\" type=\"text/html\" src=\"https://www.youtube.com/embed/DBXH9jJRaDk?autoplay=0&fs=0&iv_load_policy=3&showinfo=0&rel=0&cc_load_policy=0&start=0&end=0&origin=http://youtubeembedcode.com\"><div><small><a href=\"https://youtubeembedcode.com/nl/\">youtubeembedcode.com/nl/</a></small></div><div><small><a href=\"https://harpangratis.se/\">harpangratis.se</a></small></div><div><small><a href=\"https://youtubeembedcode.com/en\">youtubeembedcode en</a></small></div><div><small><a href=\"https://spindelharpan.nu/\">spindelharpan gamla</a></small></div><div><small><a href=\"https://youtubeembedcode.com/de/\">youtubeembedcode de</a></small></div><div><small><a href=\"https://harpangratis.se/\">spela harpan</a></small></div></iframe>', '255 Queensberry Street, North Melbourne Australia.', '<iframe scrolling=\"no\" marginheight=\"0\" marginwidth=\"0\" src=\"https://maps.google.com/maps?width=100%25&amp;height=600&amp;hl=en&amp;q=205%20Parkview%20Road,%20Springfield,%20VT,%20USA+(205%20Parkview%20Road,%20Springfield,%20VT,%20USA)&amp;t=&amp;z=14&amp;ie=UTF8&amp;iwloc=B&amp;output=embed\" width=\"100%\" height=\"600\" frameborder=\"0\"><a href=\"https://www.maps.ie/distance-area-calculator.html\">area maps</a></iframe>', 'Television,Free WiFi,Parking', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'top-5-cleaning-services-management-company_1675837250', '0', 1, '2023-02-08 06:20:50', '2023-02-08 06:42:34'),
(24, 5, 10, 38, 3, 1, 'Hershey Medical Center', 'hershey-medical-center', '<p>Lorem ipsum dolor sit amet, suscipit dissentiunt usu at, eu nam veri vidit signiferumque. Ad mea erat fabellas, et facete everti eum, tation consul ea ius. Autem feugiat maiorum id sea. Est omnis mediocrem assentior ea. Nam ubique possit verterem ea, cum facer scriptorem an.</p>\r\n<p>Equidem legendos duo ei, et legimus offendit mei. Mea amet tibique explicari ne. Nam blandit patrioque comprehensam an, sed in errem omnes partem. No quo impedit percipit comprehensam, ei dolores intellegam pro, et sed quaeque temporibus referrentur. Quodsi causae dissentias in pri, idque ridens cum an. Vis in facilisi conclusionemque, eu est erant affert veritus. Id qui quodsi iriure quaestio, omittam praesent ne sea, postulant consetetur definitiones an nec.</p>\r\n<p>Probo animal interpretaris ea mea. Mea ad nostrud urbanitas inciderint, sea no noluisse incorrupte. His democritum vituperatoribus no, ad cum offendit rationibus vituperatoribus, eos te quodsi interesset. Regione bonorum no quo. Lobortis torquatos constituto ne per, ferri facete ea duo. Usu molestie complectitur eu, euismod forensibus moderatius sed no.</p>', '<iframe frameborder=\"0\" scrolling=\"no\" marginheight=\"0\" marginwidth=\"0\"width=\"740\" height=\"440\" type=\"text/html\" src=\"https://www.youtube.com/embed/DBXH9jJRaDk?autoplay=0&fs=0&iv_load_policy=3&showinfo=0&rel=0&cc_load_policy=0&start=0&end=0&origin=http://youtubeembedcode.com\"><div><small><a href=\"https://youtubeembedcode.com/nl/\">youtubeembedcode.com/nl/</a></small></div><div><small><a href=\"https://harpangratis.se/\">harpangratis.se</a></small></div><div><small><a href=\"https://youtubeembedcode.com/en\">youtubeembedcode en</a></small></div><div><small><a href=\"https://spindelharpan.nu/\">spindelharpan gamla</a></small></div><div><small><a href=\"https://youtubeembedcode.com/de/\">youtubeembedcode de</a></small></div><div><small><a href=\"https://harpangratis.se/\">spela harpan</a></small></div></iframe>', '205 East Parkview Road, France', '<iframe scrolling=\"no\" marginheight=\"0\" marginwidth=\"0\" src=\"https://maps.google.com/maps?width=100%25&amp;height=600&amp;hl=en&amp;q=205%20Parkview%20Road,%20Springfield,%20VT,%20USA+(205%20Parkview%20Road,%20Springfield,%20VT,%20USA)&amp;t=&amp;z=14&amp;ie=UTF8&amp;iwloc=B&amp;output=embed\" width=\"100%\" height=\"600\" frameborder=\"0\"><a href=\"https://www.maps.ie/distance-area-calculator.html\">area maps</a></iframe>', 'Parking,Free WiFi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'hershey-medical-center_1675839199', '5', 1, '2023-02-08 06:53:19', '2023-02-08 06:43:14');

-- --------------------------------------------------------

--
-- Table structure for table `listings_reviews`
--

CREATE TABLE `listings_reviews` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `listing_id` int(11) NOT NULL,
  `review` text DEFAULT NULL,
  `rating` int(11) NOT NULL,
  `date` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `listings_reviews`
--

INSERT INTO `listings_reviews` (`id`, `user_id`, `listing_id`, `review`, `rating`, `date`) VALUES
(5, 5, 8, 'Nemo ucxqui officia voluptatem accu santium doloremque laudantium, totam rem ape dicta sunt dose explicabo.', 1, 1675209600),
(6, 1, 9, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', 1, 1675641600),
(7, 1, 9, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', 2, 1675728000),
(8, 1, 9, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', 1, 1675728000),
(9, 4, 8, 'This is test review for this listing', 5, 1675728000),
(10, 1, 9, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', 3, 1675728000),
(11, 1, 9, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', 2, 1675728000),
(12, 5, 24, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', 5, 1675814400);

-- --------------------------------------------------------

--
-- Table structure for table `listing_gallery`
--

CREATE TABLE `listing_gallery` (
  `id` int(10) UNSIGNED NOT NULL,
  `listing_id` int(11) NOT NULL,
  `image_name` varchar(191) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `listing_gallery`
--

INSERT INTO `listing_gallery` (`id`, `listing_id`, `image_name`) VALUES
(3, 4, 'test-listing-1_2980-b.jpg'),
(5, 4, 'test-listing-1_1559-b.jpg'),
(6, 4, 'test-listing-1_1748-b.jpg'),
(10, 5, 'test-listing-1_1965-b.jpg'),
(11, 5, 'test-listing-1_7069-b.jpg'),
(12, 5, 'test-listing-1_6995-b.jpg'),
(15, 12, 'clarance-hotel_6636-b.jpg'),
(16, 12, 'clarance-hotel_8787-b.jpg'),
(17, 12, 'clarance-hotel_1253-b.jpg'),
(18, 13, 'la-cour-berbisey_142-b.jpg'),
(19, 13, 'la-cour-berbisey_347-b.jpg'),
(20, 13, 'la-cour-berbisey_1775-b.jpg'),
(21, 14, 'depot-vente-luxe-mademoiselle_2154-b.jpg'),
(23, 14, 'depot-vente-luxe-mademoiselle_2412-b.jpg'),
(26, 15, 'printemps-de-marnie_3405-b.jpg'),
(28, 15, 'printemps-de-marnie_8682-b.jpg'),
(29, 16, 'mix-beauty-paris_7552-b.jpg'),
(30, 16, 'mix-beauty-paris_4539-b.jpg'),
(32, 16, 'mix-beauty-paris_9086-b.jpg'),
(33, 17, 'luxary-hotel-spa_2289-b.jpg'),
(34, 17, 'luxary-hotel-spa_6344-b.jpg'),
(35, 17, 'luxary-hotel-spa_3526-b.jpg'),
(36, 17, 'luxary-hotel-spa_7295-b.jpg'),
(37, 18, 'saybrook-fitness-point_7545-b.jpg'),
(38, 18, 'saybrook-fitness-point_4839-b.jpg'),
(39, 18, 'saybrook-fitness-point_7972-b.jpg'),
(40, 20, 'ruby-wright-realty_7542-b.jpg'),
(41, 20, 'ruby-wright-realty_3754-b.jpg'),
(42, 20, 'ruby-wright-realty_1396-b.jpg'),
(43, 21, 'brook-point-book-store_4275-b.jpg'),
(44, 21, 'brook-point-book-store_19-b.jpg'),
(45, 21, 'brook-point-book-store_3415-b.jpg'),
(46, 22, 'best-home-appliance-dealers_1269-b.jpg'),
(47, 22, 'best-home-appliance-dealers_8315-b.jpg'),
(48, 22, 'best-home-appliance-dealers_5088-b.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `location`
--

CREATE TABLE `location` (
  `id` int(10) UNSIGNED NOT NULL,
  `location_name` varchar(191) NOT NULL,
  `location_slug` varchar(191) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `location`
--

INSERT INTO `location` (`id`, `location_name`, `location_slug`) VALUES
(1, 'Lyon', 'lyon'),
(2, 'Paris', 'paris'),
(3, 'Nice', 'nice'),
(4, 'Marseille', 'marseille'),
(5, 'Bordeaux', 'bordeaux'),
(6, 'Toulouse', 'toulouse'),
(7, 'Lille', 'lille');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(191) NOT NULL,
  `token` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_gateway`
--

CREATE TABLE `payment_gateway` (
  `id` int(11) NOT NULL,
  `gateway_name` varchar(255) NOT NULL,
  `gateway_info` text DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment_gateway`
--

INSERT INTO `payment_gateway` (`id`, `gateway_name`, `gateway_info`, `status`) VALUES
(1, 'Paypal', '{\"mode\":\"sandbox\",\"paypal_client_id\":\"AcHrwP4VHD8x4EOB1UlIcof3bMB0oYYYjHfwO8STmh4JtncocJ3HK03lqy3YXYVGC3i6P-XdyqXQ-Aq2\",\"paypal_secret\":\"EJwVTBGDKymCNfoKi5PEmOyo-Ipdrl18K3RpetS1UB_hTyYNSZ92a3ysB8Sjo2Dpie7yfesGl3GB8VJW\"}', 1),
(2, 'Stripe', '{\"stripe_secret_key\":\"sk_test_wfKUSBelSMhSeYOIGATJRYYc\",\"stripe_publishable_key\":\"pk_test_HrjNdMEV34CRkC8YHqxhDF9t\"}', 1),
(3, 'Razorpay', '{\"razorpay_key\":\"rzp_test_3Xn9o5IRWFjKR4\",\"razorpay_secret\":\"9GExgEs8mWk2j5b8KswuNzIx\"}', 1),
(4, 'Paystack', '{\"paystack_secret_key\":\"sk_test_b3a005e485d55c4dc47696c29f27705918f98a15\",\"paystack_public_key\":\"pk_test_03ee87c23e8815638f5c4ef582aca392e8b3c39b\"}', 1);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `site_name` varchar(191) NOT NULL,
  `site_email` varchar(191) NOT NULL,
  `site_logo` varchar(191) DEFAULT NULL,
  `site_favicon` varchar(191) DEFAULT NULL,
  `site_description` text NOT NULL,
  `time_zone` varchar(255) DEFAULT NULL,
  `styling` varchar(255) DEFAULT NULL,
  `currency_code` varchar(191) DEFAULT NULL,
  `site_header_code` text DEFAULT NULL,
  `site_footer_code` text DEFAULT NULL,
  `site_footer_logo` varchar(255) DEFAULT NULL,
  `site_footer_text` varchar(500) DEFAULT NULL,
  `site_copyright` varchar(191) DEFAULT NULL,
  `addthis_share_code` text DEFAULT NULL,
  `disqus_comment_code` text DEFAULT NULL,
  `facebook_comment_code` text DEFAULT NULL,
  `home_title` varchar(255) DEFAULT NULL,
  `home_sub_title` varchar(500) DEFAULT NULL,
  `home_categories_ids` varchar(255) DEFAULT NULL,
  `home_bg_image` varchar(255) DEFAULT NULL,
  `about_title` varchar(191) DEFAULT NULL,
  `about_description` longtext DEFAULT NULL,
  `contact_title` varchar(191) DEFAULT NULL,
  `contact_address` text DEFAULT NULL,
  `contact_email` varchar(191) DEFAULT NULL,
  `contact_number` varchar(191) DEFAULT NULL,
  `terms_of_title` varchar(191) DEFAULT NULL,
  `terms_of_description` longtext DEFAULT NULL,
  `privacy_policy_title` varchar(191) DEFAULT NULL,
  `privacy_policy_description` longtext DEFAULT NULL,
  `facebook_url` varchar(191) DEFAULT NULL,
  `twitter_url` varchar(191) DEFAULT NULL,
  `instagram_url` varchar(191) DEFAULT NULL,
  `linkedin_url` varchar(191) DEFAULT NULL,
  `smtp_host` varchar(255) DEFAULT NULL,
  `smtp_port` varchar(255) DEFAULT NULL,
  `smtp_email` varchar(255) DEFAULT NULL,
  `smtp_password` varchar(255) DEFAULT NULL,
  `smtp_encryption` varchar(255) DEFAULT NULL,
  `google_login` varchar(255) DEFAULT NULL,
  `facebook_login` varchar(255) DEFAULT NULL,
  `google_client_id` varchar(255) DEFAULT NULL,
  `google_client_secret` varchar(255) DEFAULT NULL,
  `google_redirect` varchar(255) DEFAULT NULL,
  `facebook_app_id` varchar(255) DEFAULT NULL,
  `facebook_client_secret` varchar(255) DEFAULT NULL,
  `facebook_redirect` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `site_name`, `site_email`, `site_logo`, `site_favicon`, `site_description`, `time_zone`, `styling`, `currency_code`, `site_header_code`, `site_footer_code`, `site_footer_logo`, `site_footer_text`, `site_copyright`, `addthis_share_code`, `disqus_comment_code`, `facebook_comment_code`, `home_title`, `home_sub_title`, `home_categories_ids`, `home_bg_image`, `about_title`, `about_description`, `contact_title`, `contact_address`, `contact_email`, `contact_number`, `terms_of_title`, `terms_of_description`, `privacy_policy_title`, `privacy_policy_description`, `facebook_url`, `twitter_url`, `instagram_url`, `linkedin_url`, `smtp_host`, `smtp_port`, `smtp_email`, `smtp_password`, `smtp_encryption`, `google_login`, `facebook_login`, `google_client_id`, `google_client_secret`, `google_redirect`, `facebook_app_id`, `facebook_client_secret`, `facebook_redirect`) VALUES
(1, 'Directory Listings', 'info@example.com', 'site_logo-1675840054.png', 'favicon-1675849091.png', 'Viavi - Local Business Directory Listings Script', 'Asia/Kolkata', 'style-three', 'USD', NULL, NULL, 'footer_logo-1675840054.png', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text.', 'Copyright Â© 2023 Viavi - Local Business Directory Script. All Rights Reserved.', NULL, NULL, NULL, 'Find Nearby Place In', 'You can\'t imagine, what is waiting for you around your city.', '1,3,4,5,2,6,7,8', 'home_bg_image-1675245851.jpg', 'About Us', '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>\r\n<p>&nbsp;</p>\r\n<p>It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>', 'Contact Us', 'Lorem ipsum dolor sit amet consectetur.', 'info@example.com', '+ 61 23 8093 3400', 'Terms and Condition', '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>\r\n<p>&nbsp;</p>\r\n<p>It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>', 'Privacy Policy', '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>\r\n<p>&nbsp;</p>\r\n<p>It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>', '#', '#', '#', '#', NULL, '465', NULL, NULL, 'SSL', '0', '0', NULL, NULL, 'http://localhost/envato/directory_listing_script/auth/google/callback', NULL, NULL, 'http://localhost/envato/directory_listing_script/auth/facebook/callback');

-- --------------------------------------------------------

--
-- Table structure for table `subscription_plan`
--

CREATE TABLE `subscription_plan` (
  `id` int(11) NOT NULL,
  `plan_name` varchar(255) NOT NULL,
  `plan_days` int(11) NOT NULL,
  `plan_duration` varchar(255) NOT NULL,
  `plan_duration_type` varchar(255) NOT NULL,
  `plan_price` decimal(11,2) NOT NULL,
  `plan_listing_limit` int(2) NOT NULL DEFAULT 1,
  `plan_featured_option` int(1) NOT NULL DEFAULT 0,
  `plan_business_hours_option` int(1) NOT NULL DEFAULT 0,
  `plan_amenities_option` int(1) NOT NULL DEFAULT 0,
  `plan_gallery_images_option` int(1) NOT NULL DEFAULT 0,
  `plan_video_option` int(1) NOT NULL DEFAULT 0,
  `plan_enquiry_form` int(1) NOT NULL DEFAULT 0,
  `plan_recommended` int(1) NOT NULL DEFAULT 0,
  `status` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `subscription_plan`
--

INSERT INTO `subscription_plan` (`id`, `plan_name`, `plan_days`, `plan_duration`, `plan_duration_type`, `plan_price`, `plan_listing_limit`, `plan_featured_option`, `plan_business_hours_option`, `plan_amenities_option`, `plan_gallery_images_option`, `plan_video_option`, `plan_enquiry_form`, `plan_recommended`, `status`) VALUES
(1, 'Free Plan', 30, '1', '30', '0.00', 1, 0, 0, 0, 0, 0, 0, 0, 1),
(2, 'Standard Plan', 180, '6', '30', '29.00', 5, 0, 1, 1, 0, 0, 1, 0, 1),
(3, 'Premium Plan', 365, '1', '365', '49.00', 15, 1, 1, 1, 1, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `sub_categories`
--

CREATE TABLE `sub_categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `cat_id` int(11) NOT NULL,
  `sub_category_name` varchar(191) NOT NULL,
  `sub_category_slug` varchar(191) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sub_categories`
--

INSERT INTO `sub_categories` (`id`, `cat_id`, `sub_category_name`, `sub_category_slug`) VALUES
(1, 3, 'Bags', 'bags'),
(2, 3, 'Beauty Products', 'beauty-products'),
(3, 3, 'Clothing', 'clothing'),
(4, 3, 'Jewelry', 'jewelry'),
(5, 5, 'Activities', 'activities'),
(6, 5, 'Fitness', 'fitness'),
(7, 1, 'Home Electronics', 'home-electronics'),
(8, 1, 'Technical Services', 'technical-services'),
(9, 4, 'Accounts Jobs', 'accounts-jobs'),
(10, 4, 'Data Entry', 'data-entry'),
(11, 4, 'Design & Code', 'design-code'),
(12, 4, 'Finance Jobs', 'finance-jobs'),
(13, 5, 'Activities', 'activities'),
(14, 5, 'Fitness', 'fitness'),
(15, 5, 'Gym', 'gym'),
(16, 2, 'Art & Photography', 'art-photography'),
(17, 2, 'Fittings', 'fittings'),
(18, 2, 'Home Furniture', 'home-furniture'),
(19, 2, 'Office Furniture', 'office-furniture'),
(20, 6, 'Cleaning Services', 'cleaning-services'),
(21, 6, 'Food Services', 'food-services'),
(22, 6, 'Medical', 'medical'),
(23, 7, 'Commercial & Local', 'commercial-local'),
(24, 7, 'Farms', 'farms'),
(25, 7, 'Flats', 'flats'),
(26, 7, 'Home & Office', 'home-office'),
(27, 7, 'Plaza & Property', 'plaza-property'),
(28, 8, 'Burgers', 'burgers'),
(29, 8, 'Cafe Or Bistro', 'cafe-or-bistro'),
(30, 8, 'Fast Casual', 'fast-casual'),
(31, 8, 'Fast Food', 'fast-food'),
(32, 8, 'Fine Dinning', 'fine-dinning'),
(33, 9, 'Hair Salon', 'hair-salon'),
(34, 9, 'Tattoo', 'tattoo'),
(35, 9, 'Med Spa', 'med-spa'),
(36, 9, 'Nail Care', 'nail-care'),
(37, 9, 'Facial', 'facial'),
(38, 10, 'Medical Center', 'medical-center');

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE `transaction` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `email` varchar(191) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `gateway` varchar(191) NOT NULL,
  `payment_amount` varchar(191) NOT NULL,
  `payment_id` varchar(191) NOT NULL,
  `date` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `login_with` varchar(191) DEFAULT NULL,
  `usertype` varchar(20) DEFAULT NULL,
  `google_id` varchar(255) DEFAULT NULL,
  `facebook_id` varchar(255) DEFAULT NULL,
  `first_name` varchar(191) NOT NULL,
  `last_name` varchar(191) NOT NULL,
  `email` varchar(191) NOT NULL,
  `password` varchar(60) NOT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `image_icon` varchar(191) DEFAULT NULL,
  `mobile` varchar(191) DEFAULT NULL,
  `contact_email` varchar(191) DEFAULT NULL,
  `website` varchar(191) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `facebook_url` varchar(191) DEFAULT NULL,
  `twitter_url` varchar(191) DEFAULT NULL,
  `linkedin_url` varchar(191) DEFAULT NULL,
  `dribbble_url` varchar(191) DEFAULT NULL,
  `instagram_url` varchar(191) DEFAULT NULL,
  `plan_id` int(11) DEFAULT NULL,
  `plan_amount` varchar(255) DEFAULT NULL,
  `start_date` int(11) DEFAULT NULL,
  `exp_date` int(11) DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `login_with`, `usertype`, `google_id`, `facebook_id`, `first_name`, `last_name`, `email`, `password`, `gender`, `image_icon`, `mobile`, `contact_email`, `website`, `address`, `facebook_url`, `twitter_url`, `linkedin_url`, `dribbble_url`, `instagram_url`, `plan_id`, `plan_amount`, `start_date`, `exp_date`, `status`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Admin', NULL, NULL, 'John', 'Deo', 'admin@admin.com', '$2y$10$3PA6vb8CbM1EJ05ErtR5AuzJe0cCCzZAN87edclg9LIO.G5Pw5sg2', NULL, 'john-7d7bcf3b9b5c6a392c9b07f3cbb8d76f-b.jpg', '1234567890', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, '49.00', 1675728000, 1691280000, 1, 'oTrIMOJxt1ScjA0kWp8JyFhwC5985IkIirZXmIxqNQuZVihUVRM9DqyCPI2s', '2018-10-01 22:40:21', '2023-02-07 12:06:16'),
(3, NULL, 'User', NULL, NULL, 'John', 'Deo', 'john@gmail.com', '$2y$10$Z/hXo90MGG.VCnHO0RlgqurFjSJAT0umncv7aJ3z0ehE6UJ96H3je', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 1, NULL, '2022-10-06 23:17:16', '2023-01-31 04:49:09'),
(4, NULL, 'User', NULL, NULL, 'Demo', 'Viaviweb', 'demo@gmail.com', '$2y$10$yFAjhclmTmKUoKn4Xo9Z2.hnLAqpOxBGEJn7o5Zy55mp4pTBWGZ9m', NULL, NULL, '9874561233', 'kuldip.viaviweb@gmail.com', '#', 'This is test address', '#', '#', NULL, NULL, NULL, 3, '0.00', 1675123200, 1707264000, 1, 'xnkhluULgu9gTxcJ86mTDbhpKt28JPZP4tPxBbgHhzyDJrT8I1aHa30DUV3q', '2023-01-31 12:14:50', '2023-02-08 04:49:32'),
(5, NULL, 'User', NULL, NULL, 'Test', 'User', 'testuser@gmail.com', '$2y$10$cYwL/7PnfS/AHG2t9jV.fOEpe3Bl25tKLjD2cQx7JXaiImImTBSOW', NULL, NULL, '1234567890', 'admin@admin.com', 'www.website.com', '101 East Parkview Road, New York', NULL, NULL, NULL, NULL, NULL, 3, '49.00', 1675814400, 1707350400, 1, NULL, '2023-01-31 12:31:59', '2023-02-08 05:35:32');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `listings`
--
ALTER TABLE `listings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `listings_reviews`
--
ALTER TABLE `listings_reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `listing_gallery`
--
ALTER TABLE `listing_gallery`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `location`
--
ALTER TABLE `location`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `payment_gateway`
--
ALTER TABLE `payment_gateway`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscription_plan`
--
ALTER TABLE `subscription_plan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sub_categories`
--
ALTER TABLE `sub_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `listings`
--
ALTER TABLE `listings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `listings_reviews`
--
ALTER TABLE `listings_reviews`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `listing_gallery`
--
ALTER TABLE `listing_gallery`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `location`
--
ALTER TABLE `location`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `payment_gateway`
--
ALTER TABLE `payment_gateway`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `subscription_plan`
--
ALTER TABLE `subscription_plan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `sub_categories`
--
ALTER TABLE `sub_categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;