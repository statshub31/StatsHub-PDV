


create table requests (
    `id` int PRIMARY KEY AUTO_INCREMENT,
    `product_id` int not null,
    `amount` int not null,
    `user_id` int not null,
    'observation' text,
);

create table requests_complements (
    `id` int PRIMARY KEY AUTO_INCREMENT,
    `request_id` int not null,
    `complement_id` int not null,
);

create table requests_additional (
    `id` int PRIMARY KEY AUTO_INCREMENT,
    `request_id` int not null,
    `additional_id` int not null,
);

create table requests_status (
    `id` int PRIMARY KEY AUTO_INCREMENT,
    `request_id` int not null,
    `status` int not null,
    `created` int not null,
);

