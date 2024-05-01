
create table `groups`  (
	`id` int PRIMARY KEY AUTO_INCREMENT,
    `title` varchar(100),
    `access` tinyint
);

INSERT INTO `groups`(`title`, `access`) VALUES ('Cliente', 1), ('Entregador', 2), ('Atendente', 3), ('Gerente', 4), ('Administrador', 5);

CREATE TABLE accounts (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `username` VARCHAR(255) NOT NULL,
    `password` VARCHAR(255),
    `email` VARCHAR(255) NOT NULL,
    `group_id` int NOT NULL,
    `ip` VARCHAR(255),
    `rules` BOOLEAN DEFAULT 1,
    `block` BOOLEAN DEFAULT 0,
    `created` DATE NOT NULL,
    FOREIGN KEY (`group_id`) REFERENCES `groups`(`id`)
);

INSERT INTO `accounts`(`id`, `username`, `password`, `email`, `group_id`, `rules`, `block`, `created`) VALUES (1, 
    'moratech', 'eacd55f94ae1b2956a05eb1d21c6e335', 'moratech@gmail.com', 5, 1, 0, '2024-01-01');


create table users (
    `id` int PRIMARY KEY AUTO_INCREMENT,
    `account_id` int not null,
    `name` varchar(255) NOT NULL,
    `phone` varchar(15) not null,
    `photo` VARCHAR(255) NULL DEFAULT
    FOREIGN KEY (`account_id`) REFERENCES `accounts`(`id`)
);

INSERT INTO `users`(`id`, `account_id`, `name`, `phone`) VALUES (1, 1, 'Administrador', '11994489463');

create table address (
    `id` int PRIMARY KEY AUTO_INCREMENT,
    `user_id` int,
    `zip_code` varchar(14),
    `publicplace` varchar(50),
    `neighborhood` varchar(50),
    `number` varchar(10),
    `complement` varchar(100),
    `city` varchar(100),
    `state` varchar(5),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
);



create table status (
    `id` int PRIMARY KEY AUTO_INCREMENT,
    `title` varchar(50)
);

INSERT INTO `status`(`title`) VALUES ('Desativado'), ('Ativo'), ('Bloqueado'), ('Pendente'), ('Invalidado'), ('Cancelado'), ('Finalizado');

create table tickets (
    `id` int PRIMARY KEY AUTO_INCREMENT,
    `code` varchar(10),
    `amount` int,
    `amount_used` int,
    `created` datetime NOT NULL,
    `created_by` int NOT NULL,
    `expiration` datetime,
    `end` datetime,
    `finished_by` int NULL,
    `value` varchar(20),
    `status` int,
    `reason` text,
    FOREIGN KEY (`created_by`) REFERENCES `users`(`id`),
    FOREIGN KEY (`finished_by`) REFERENCES `users`(`id`),
    FOREIGN KEY (`status`) REFERENCES `status`(`id`)
);




create table measure (
    `id` int PRIMARY KEY AUTO_INCREMENT,
    `title` varchar(50)
);

INSERT INTO `measure`(`title`) VALUES ('Kilograma'), ('Grama'), ('Porção'), ('Unidade'), ('Centimetro');



create table categorys (
    `id` int PRIMARY KEY AUTO_INCREMENT,
    `title` varchar(50) NOT NULL UNIQUE,
    `icon_id` int,
    FOREIGN KEY (`icon_id`) REFERENCES `icons`(`id`)
);

INSERT INTO `categorys`(`title`) VALUES ('Outros'), ()'Comida'), ('Bebida');


create table complements (
    `id` int PRIMARY KEY AUTO_INCREMENT,
    `code` varchar(10) NULL,
    `category_id` int NOT NULL,
    `description` varchar(255) NOT NULL,
    `created` datetime NOT NULL,
    `created_by` int NOT NULL,
    `status` int NOT NULL DEFAULT 2,
    FOREIGN KEY (`status`) REFERENCES `status`(`id`),
    FOREIGN KEY (`category_id`) REFERENCES `categorys`(`id`),
    FOREIGN KEY (`created_by`) REFERENCES `users`(`id`)
);

INSERT INTO `complements` (`category_id`, `description`, `created`, `created_by`, `status`) VALUES (1, 'Nenhum', '0000-00-00 00:00:00', 1, 2);

create table additional (
    `id` int PRIMARY KEY AUTO_INCREMENT,
    `code` varchar(10) NULL,
    `category_id` int NOT NULL,
    `cost_price` float(5, 2) NOT NULL,
    `sale_price` float(5,2) NOT NULL,
    `description` varchar(255),
    `created` datetime NOT NULL,
    `created_by` int NOT NULL,
    `status` int NOT NULL DEFAULT 2,
    FOREIGN KEY (`status`) REFERENCES `status`(`id`),
    FOREIGN KEY (`category_id`) REFERENCES `categorys`(`id`),
    FOREIGN KEY (`created_by`) REFERENCES `users`(`id`)
);

create table products (
    `id` int PRIMARY KEY AUTO_INCREMENT,
    `code` varchar(10),
    `category_id` int NOT NULL,
    `name` varchar(100) NOT NULL,
    `description` text,
    `photo` varchar(255),
    `created` datetime NOT NULL,
    `price_distinct` boolean NOT NULL default 0,
    `created_by` int NOT NULL,
    `status` int NOT NULL DEFAULT 2,
    `stock_status` int NOT NULL default 0,
    FOREIGN KEY (`status`) REFERENCES `status`(`id`),
    FOREIGN KEY (`category_id`) REFERENCES `categorys`(`id`),
    FOREIGN KEY (`created_by`) REFERENCES `users`(`id`)
);

create table products_price (
    `id` int PRIMARY KEY AUTO_INCREMENT,
    `product_id` int NOT NULL,
    `size_measure_id` int NOT NULL,
    `size` varchar(50) NOT NULL,
    `description` varchar(255),
    `price` float(5, 2) NOT NULL,
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`),
    FOREIGN KEY (`size_measure_id`) REFERENCES `measure`(`id`)
);

create table stock (
    `id` int PRIMARY KEY AUTO_INCREMENT,
    `product_id` int,
    `min` varchar(50),
    `actual` varchar(255),
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`)
);

create table products_additional (
    `id` int PRIMARY KEY AUTO_INCREMENT,
    `product_id` int NOT NULL,
    `additional_id` int NOT NULL,
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`),
    FOREIGN KEY (`additional_id`) REFERENCES `additional`(`id`)
);

create table products_complements (
    `id` int PRIMARY KEY AUTO_INCREMENT,
    `product_id` int NOT NULL,
    `complement_id` int NOT NULL,
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`),
    FOREIGN KEY (`complement_id`) REFERENCES `complements`(`id`)
);

create table products_question (
    `id` int PRIMARY KEY AUTO_INCREMENT,
    `product_id` int not null,
    `question` varchar(255) not null,
    `multiple_response` boolean not null default 1,
    `response_free` boolean not null default 0,
    `deleted` int not null default 0,
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`)
);

create table products_question_reponse (
    `id` int PRIMARY KEY AUTO_INCREMENT,
    `question_id` int not null,
    `response` text null,
    `deleted` int not null default 0,
    FOREIGN KEY (`question_id`) REFERENCES `products_question`(`id`)
);

create table stock_actions (
    `id` int PRIMARY KEY AUTO_INCREMENT,
    `title` varchar(50)
);

insert into `stock_actions` (`title`) VALUES ('Entrada'), ('Saida'), ('Devolução');


create table logs_stock (
    `id` int PRIMARY KEY AUTO_INCREMENT,
    `product_id` int NOT NULL,
    `action_id` int not null,
    `user_id` int not null,
    `amount` int not null,
    `reason` text not null,
    `date` datetime not null,
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`),
    FOREIGN KEY (`action_id`) REFERENCES `stock_actions`(`id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
);


create table `promotions` (
    `id` int PRIMARY KEY AUTO_INCREMENT,
    `title` varchar(20)
);

insert into `promotions` (`title`) VALUES ('Percentual'), ('Reais');

create table product_promotion (
    `id` int PRIMARY KEY AUTO_INCREMENT,
    `product_id` int not null,
    `promotion_id` int not null default 0,
    `cumulative` boolean default 0,
    `created` datetime NOT NULL,
    `created_by` int NOT NULL,
    `value` int NOT NULL,
    `status` int NOT NULL default 2,
    `expiration` datetime NULL,
    `end` datetime NULL,
    `finished_by` int NULL,
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`),
    FOREIGN KEY (`promotion_id`) REFERENCES `promotions`(`id`),
    FOREIGN KEY (`created_by`) REFERENCES `users`(`id`),
    FOREIGN KEY (`finished_by`) REFERENCES `users`(`id`),
    FOREIGN KEY (`status`) REFERENCES `status`(`id`)
);


create table product_fee_exemption (
    `id` int PRIMARY KEY AUTO_INCREMENT,
    `product_id` int not null,
    `created` datetime NOT NULL,
    `created_by` int NOT NULL,
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`),
    FOREIGN KEY (`created_by`) REFERENCES `users`(`id`)
);


create table icons (
    `id` int PRIMARY KEY AUTO_INCREMENT,
    `title` varchar(100) not null
);

INSERT INTO icons (`title`) VALUES 
('fa-cart-shopping'),
('fa-car'),
('fa-magnifying-glass'),
('fa-user'),
('fa-star'),
('fa-heart'),
('fa-gift'),
('fa-briefcase'),
('fa-shirt')


create table request_order (
    `id` int PRIMARY KEY AUTO_INCREMENT,
    `cart_id` int not null,
    `status` int not null,
    `deliveryman` int null,
    `address_id_select` int null,
    `ticket_id_select` int null,
    `pay_id` int null,
    `change_of` float(5, 2) null,
    FOREIGN KEY (`status`) REFERENCES `status`(`id`),
    FOREIGN KEY (`deliveryman`) REFERENCES `users`(`id`),
    FOREIGN KEY (`address_id_select`) REFERENCES `address`(`id`),
    FOREIGN KEY (`ticket_id_select`) REFERENCES `tickets`(`id`),
    FOREIGN KEY (`cart_id`) REFERENCES `carts`(`id`),
    FOREIGN KEY (`pay_id`) REFERENCES `settings_pay`(`id`)
);

create table request_order_available (
    `id` int PRIMARY KEY AUTO_INCREMENT,
    `request_order_id` int not null,
    `created` datetime not null,
    `food` tinyint not null,
    `box` tinyint not null,
    `deliverytime` tinyint not null,
    `costbenefit` tinyint not null,
    `comment` text null
);

create table delivery (
    `id` int PRIMARY KEY AUTO_INCREMENT,
    `title` varchar(100) not null
);

insert into delivery (`title`) VALUES ('Retirada no Local'), ('Entrega');


create table request_order_logs (
    `id` int PRIMARY KEY AUTO_INCREMENT,
    `request_order_id` int not null,
    `status_delivery` int not null,
    `created` datetime not null,
    FOREIGN KEY (`status_delivery`) REFERENCES `status_delivery`(`id`),
    FOREIGN KEY (`request_order_id`) REFERENCES `request_order`(`id`)
);

create table carts (
    `id` int PRIMARY KEY AUTO_INCREMENT,
    `user_id` int not null,
    `status` int not null,
    `created` datetime,
    FOREIGN KEY (`status`) REFERENCES `status`(`id`)
);

create table cart_products (
    `id` int PRIMARY KEY AUTO_INCREMENT,
    `cart_id` int not null,
    `product_id` int not null,
    `amount` int not null,
    `product_price_id` int not null,
    `observation` text,
    FOREIGN KEY (`cart_id`) REFERENCES `carts`(`id`),
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`),
    FOREIGN KEY (`product_price_id`) REFERENCES `products_price`(`id`)
);

create table cart_product_complements (
    `id` int PRIMARY KEY AUTO_INCREMENT,
    `cart_product_id` int not null,
    `complement_id` int not null,
    FOREIGN KEY (`cart_product_id`) REFERENCES `cart_products`(`id`),
    FOREIGN KEY (`complement_id`) REFERENCES `complements`(`id`)
);

create table cart_product_additional (
    `id` int PRIMARY KEY AUTO_INCREMENT,
    `cart_product_id` int not null,
    `additional_id` int not null,
    FOREIGN KEY (`cart_product_id`) REFERENCES `cart_products`(`id`),
    FOREIGN KEY (`additional_id`) REFERENCES `additional`(`id`)
);


create table cart_product_questions (
    `id` int PRIMARY KEY AUTO_INCREMENT,
    `cart_product_id` int not null,
    `question_id` int not null,
    FOREIGN KEY (`cart_product_id`) REFERENCES `cart_products`(`id`),
    FOREIGN KEY (`question_id`) REFERENCES `products_question`(`id`)
);

create table cart_product_question_responses (
    `id` int PRIMARY KEY AUTO_INCREMENT,
    `cart_product_question_id` int not null,
    `response_id` int null,
    `response_text` varchar(255) null,
    FOREIGN KEY (`cart_product_question_id`) REFERENCES `cart_product_questions`(`id`)
);


create table user_select (
    `id` int PRIMARY KEY AUTO_INCREMENT,
    `address_id` int null,
    `pay_id` int not null default 1,
    `user_id` int null,
    FOREIGN KEY (`address_id`) REFERENCES `address`(`id`),
    FOREIGN KEY (`pay_id`) REFERENCES `settings_pay`(`id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
);

create table cart_ticket_select (
    `id` int PRIMARY KEY AUTO_INCREMENT,
    `ticket_id` int not null default 1,
    `user_id` int not null,
    `cart_id` int null,
    `used` boolean not null default 0,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`),
    FOREIGN KEY (`ticket_id`) REFERENCES `tickets`(`id`),
    FOREIGN KEY (`cart_id`) REFERENCES `carts`(`id`)
);

create table user_select (
    `id` int PRIMARY KEY AUTO_INCREMENT,
    `address_id` int null,
    `pay_id` int not null default 1,
    `user_id` int null,
    FOREIGN KEY (`address_id`) REFERENCES `address`(`id`),
    FOREIGN KEY (`pay_id`) REFERENCES `settings_pay`(`id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
);


create table settings_images (
    `id` int PRIMARY KEY AUTO_INCREMENT,
    `icon_name` varchar(255) null,
    `background_name` varchar(255) null,
    `logo_name` varchar(255) null,
    `login_name` varchar(255) null
);




create table settings_info (
    `id` int PRIMARY KEY AUTO_INCREMENT,
    `title` varchar(255) null,
    `description` varchar(255) null,
    `main_color` varchar(10) null default '#000000',
    `cnpj` varchar(15) null
);


create table settings_delivery (
    `id` int PRIMARY KEY AUTO_INCREMENT,
    `order_withdrawal` boolean not null default 0,
    `address_api` varchar(255) null,
    `order_min` float(5, 2) not null default 0,
    `fee` float(5, 2)  not null default 0,
    `time_min` tinyint not null,
    `time_max` tinyint not null
);


create table settings_pay (
    `id` int PRIMARY KEY AUTO_INCREMENT,
    `type` varchar(255) not null,
    `pay_key` varchar(255)  null default '',
    `icon_pay` int  not null default 1,
    `disabled` boolean  not null default 0,
    FOREIGN KEY (`icon_pay`) REFERENCES `icons`(`id`)
);

insert into `settings_pay` (`type`, `pay_key`, `disabled`) VALUES ('Dinheiro', NULL, 0), ('Crédito', NULL, 0), ('Débito', NULL, 0), ('Pix', NULL, 0);

create table settings_social (
    `id` int PRIMARY KEY AUTO_INCREMENT,
    `whatsapp_status` boolean not null default 0,
    `whatsapp_contact` varchar(50) null,
    `instagram_status` boolean not null default 0,
    `instagram_contact` varchar(50) null,
    `facebook_status` boolean not null default 0,
    `facebook_contact` varchar(50) null 
);


create table settings_horary (
    `id` int PRIMARY KEY AUTO_INCREMENT,
    `monday_status` boolean not null default 0,
    `monday_start` time null,
    `monday_end` time null,
    
    `tuesday_status` boolean not null default 0,
    `tuesday_start` time null,
    `tuesday_end` time null,
    
    `wednesday_status` boolean not null default 0,
    `wednesday_start` time null,
    `wednesday_end` time null,
    
    `thursday_status` boolean not null default 0,
    `thursday_start` time null,
    `thursday_end` time null,
    
    `friday_status` boolean not null default 0,
    `friday_start` time null,
    `friday_end` time null,
    
    `saturday_status` boolean not null default 0,
    `saturday_start` time null,
    `saturday_end` time null,
    
    `sunday_status` boolean not null default 0,
    `sunday_start` time null,
    `sunday_end` time null
);









create table requests (
    `id` int PRIMARY KEY AUTO_INCREMENT,
    `product_id` int not null,
    `amount` int not null,
    `user_id` int not null,
    `observation` text,
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
);

create table requests_complements (
    `id` int PRIMARY KEY AUTO_INCREMENT,
    `request_id` int not null,
    `complement_id` int not null,
    FOREIGN KEY (`request_id`) REFERENCES `requests`(`id`),
    FOREIGN KEY (`complement_id`) REFERENCES `complements`(`id`)
);

create table requests_additional (
    `id` int PRIMARY KEY AUTO_INCREMENT,
    `request_id` int not null,
    `additional_id` int not null,
    FOREIGN KEY (`request_id`) REFERENCES `requests`(`id`),
    FOREIGN KEY (`additional_id`) REFERENCES `additional`(`id`)
);

create table status_delivery (
    `id` int PRIMARY KEY AUTO_INCREMENT,
    `title` varchar(50)
);


INSERT INTO `status_delivery`(`title`) VALUES 
('Aguardando Pagamento'), 
('Aguardando Confirmação do Pedido'), 
('Pedindo em Preparo'), 
('Pedido saiu para entrega'), 
('Pedido Entregue'), 
('Pedido Cancelado'), 
('Pedido Pronto');


create table requests_status (
    `id` int PRIMARY KEY AUTO_INCREMENT,
    `request_id` int not null,
    `status_delivery` int not null,
    `created` int not null,
    FOREIGN KEY (`request_id`) REFERENCES `requests`(`id`),
    FOREIGN KEY (`status_delivery`) REFERENCES `status_delivery`(`id`)
);


create table status_pay (
    `id` int PRIMARY KEY AUTO_INCREMENT,
    `title` varchar(50)
);


INSERT INTO `status_pay`(`title`) VALUES ('Pensando ainda');

create table requests_pay (
    `id` int PRIMARY KEY AUTO_INCREMENT,
    `request_id` int not null,
    `value` int not null,
    `discount` int null,
    `ticket_id` int null,
    `status_pay` int null,
    FOREIGN KEY (`request_id`) REFERENCES `requests`(`id`),
    FOREIGN KEY (`ticket_id`) REFERENCES `tickets`(`id`),
    FOREIGN KEY (`status_pay`) REFERENCES `status_pay`(`id`)
);





































-- 

create table settings (
    -- ALTER USER 'root'@'localhost' IDENTIFIED WITH caching_sha2_password BY '@dm!n123!@#';
	id int PRIMARY KEY AUTO_INCREMENT,
    site_title varchar(50),
    site_description varchar(50),
    password boolean,
    cpf boolean,
    address boolean,
    baseboard boolean,
    baseboard_text text,
    game_register boolean,
    reward_per_product boolean,
    reward_per_totalpurchase boolean,
    value_for_exp float(5,2),
    value_from_exp int,

    url varchar(255),

    n_email varchar(255),
    b_email varchar(255),
    b_password varchar(255),

    whatsapp_status boolean,
    whatsapp_contact varchar(100),
    whatsapp_message text,
    
    facebook_status boolean,
    facebook_contact varchar(100),
    
    instagram_status boolean,
    instagram_contact varchar(100),

    
    pg_title varchar(40) NOT NULL,
    pg_subtitle varchar(40) NOT NULL,
    pg_about varchar(160) NOT NULL,
    pg_about_tp1 varchar(15) NOT NULL,
    pg_about_dp1 text NOT NULL,
    pg_about_ip1 tinyint NOT NULL,
    pg_about_tp2 varchar(15) NOT NULL,
    pg_about_dp2 text NOT NULL,
    pg_about_ip2 tinyint NOT NULL,
    pg_about_tp3 varchar(15) NOT NULL,
    pg_about_dp3 text NOT NULL,
    pg_about_ip3 tinyint NOT NULL,
    pg_nvl varchar(160) NOT NULL,
    pg_clientD varchar(160) NOT NULL,
    pg_addressURL varchar(255)
);

INSERT INTO settings (
    `site_title`,
`site_description`,
`password`,
`cpf`,
`address`,
`baseboard`,
`baseboard_text`,
`game_register`,
`reward_per_product`,
`reward_per_totalpurchase`,
`value_for_exp`,
`value_from_exp`,
`url`,`n_email`,
`b_email`,
`b_password`,
`whatsapp_status`,
`whatsapp_contact`,
`whatsapp_message`,
`facebook_status`,
`facebook_contact`,
`instagram_status`,
`instagram_contact`,
`pg_title`,
`pg_subtitle`,
`pg_about`,
`pg_about_tp1`,
`pg_about_dp1`,
`pg_about_ip1`,
`pg_about_tp2`,
`pg_about_dp2`,
`pg_about_ip2`,
`pg_about_tp3`,
`pg_about_dp3`,
`pg_about_ip3`,
`pg_nvl`,
`pg_clientD`,
`pg_addressURL`

) VALUES (
    'Init Title','Init Desription',0,0,0,0,'',0,0,1,'30.00',5,'localhost', 'usuario@gmail.com', 'usuario@gmail.com', '1213121', 0, '', 
    '', 0, '', 0, '', 
    'Seja bem vindo a uma nova aventura!', 
    'ESTE É O COMEÇO DE UMA LONGA PARCERIA', 
    'Somos uma empresa que vai além de simplesmente oferecer um produto ao cliente; nosso objetivo é transformar cada compra em uma experiência única e memorável.', 
    'Produto',
    'Seu apoio ao escolher nossos produtos é crucial. A cada R$ 30.00 em compras, ganhe 5% de experiência para tornar sua jornada de compras ainda mais gratificante.',
    1, 
    'Produto',
    'Seu apoio ao escolher nossos produtos é crucial. A cada R$ 30.00 em compras, ganhe 5% de experiência para tornar sua jornada de compras ainda mais gratificante.',
    2, 
    'Produto',
    'Seu apoio ao escolher nossos produtos é crucial. A cada R$ 30.00 em compras, ganhe 5% de experiência para tornar sua jornada de compras ainda mais gratificante.',
    3,
    'As recompensas são reservadas aos clientes que fazem suas compras na loja física. Não se esqueça de conferir sempre os termos de participação.',
    'Nossos clientes mais fiéis brilham no topo, destacando-se como os três principais apoiadores da loja.',
    ''
    );

create table accounts_cb (
	id int PRIMARY KEY AUTO_INCREMENT,
    account_id int NULL,
    code varchar(20) NOT NULL,
    date_created datetime NOT NULL
);

create table rewards (
    id int PRIMARY KEY AUTO_INCREMENT,
    level tinyint not null,
    name varchar(255) NOT NULL,
    photo varchar(255) NOT NULL,
    team_id int NOT NULL,
    price float(5,2) NOT NULL,
    FOREIGN KEY (team_id) REFERENCES users(`id`)
);


create table rewards_check (
    id int PRIMARY KEY AUTO_INCREMENT,
    reward_id int NOT NULL,
    user_id int NOT NULL,
    team_id int,
    date_limit date,
    date_received date,
    date_expiration date,
    status varchar(100),
    code varchar(100),

    FOREIGN KEY (reward_id) REFERENCES rewards(`id`),
    FOREIGN KEY (user_id) REFERENCES users(`id`)
);

create table products_purchase (
    id int PRIMARY KEY AUTO_INCREMENT,
    user_id int NOT NULL,
    amount int NOT NULL,
    total_price float(5,2) NOT NULL,
    date_purchase date not null,
    team_id int not null,
    FOREIGN KEY (user_id) REFERENCES users(`id`),
    FOREIGN KEY (team_id) REFERENCES users(`id`)
);



-- GAMES
create table game_types (
    id int PRIMARY KEY AUTO_INCREMENT,
    title varchar(100)
);

INSERT INTO `game_types`(`title`) VALUES ('Raspadinha');

create table game_status (
    id int PRIMARY KEY AUTO_INCREMENT,
    title varchar(50)
);

INSERT INTO `game_status`(`title`) VALUES ('Aberto'), ('Encerrado');

create table games (
    id int PRIMARY KEY AUTO_INCREMENT,
    game_type_id int NOT NULL,
    title varchar(255) NOT NULL,
    created date NOT NULL,
    team_id int NOT NULL,
    value_to_participate float(5,2) NULL,
    close_date date,
    register_game boolean,
    game_status_id int NOT NULL,

    FOREIGN KEY (game_type_id) REFERENCES game_types(`id`),
    FOREIGN KEY (team_id) REFERENCES users(`id`),
    FOREIGN KEY (game_status_id) REFERENCES game_status(`id`)
);

create table games_rewards (
    id int PRIMARY KEY AUTO_INCREMENT,
    title varchar(255) NOT NULL,
    game_id int NOT NULL,
    team_id int NOT NULL,
    price float(5,2) NOT NULL,
    created date,
    photo varchar(255),
    blocked boolean default 0,
    FOREIGN KEY (game_id) REFERENCES games(`id`),
    FOREIGN KEY (team_id) REFERENCES users(`id`)
);


create table games_scratch_card (
    id int PRIMARY KEY AUTO_INCREMENT,
    game_id int NOT NULL,
    reward_id int NOT NULL,
    code varchar(10) NOT NULL,
    user_id int,
    open date,
    blocked boolean default 0,
    received boolean default 0,
    team_id int,
    date_received date,
    FOREIGN KEY (reward_id) REFERENCES games_rewards(`id`),
    FOREIGN KEY (game_id) REFERENCES games(`id`),
    FOREIGN KEY (user_id) REFERENCES users(`id`)
);

-- SOCIAL MIDIA

create table users_social (
    id int PRIMARY KEY AUTO_INCREMENT,
    user_id int not null,
    inst_status boolean,
    inst_data varchar(100),
    whats_status boolean,
    whats_data varchar(100),
    face_status boolean,
    face_data varchar(100),
    FOREIGN KEY (user_id) REFERENCES users(`id`)
);


INSERT INTO `users_social`(`user_id`, `inst_status`, `inst_data`, `whats_status`, `whats_data`, `face_status`, `face_data`) VALUES (1, 0, '', 0, '', 0, '');

create table games_telesena (
    id int PRIMARY KEY AUTO_INCREMENT,
    game_id int NOT NULL,
    code varchar(10) NOT NULL,
    user_id int,
    b1_reward_id int not null,
    b1_open date,
    b2_reward_id int not null,
    b2_open date,
    b3_reward_id int not null,
    b3_open date,
    b4_reward_id int not null,
    b4_open date,
    b5_reward_id int not null,
    b5_open date,
    b6_reward_id int not null,
    b6_open date,
    b7_reward_id int,
    open date,
    team_id int,
    received boolean default 0,
    date_received date,
    FOREIGN KEY (`b1_reward_id`) REFERENCES games_rewards(`id`),
    FOREIGN KEY (`b2_reward_id`) REFERENCES games_rewards(`id`),
    FOREIGN KEY (`b3_reward_id`) REFERENCES games_rewards(`id`),
    FOREIGN KEY (`b4_reward_id`) REFERENCES games_rewards(`id`),
    FOREIGN KEY (`b5_reward_id`) REFERENCES games_rewards(`id`),
    FOREIGN KEY (`b6_reward_id`) REFERENCES games_rewards(`id`),
    FOREIGN KEY (game_id) REFERENCES games(`id`),
    FOREIGN KEY (user_id) REFERENCES users(`id`)
);

create table phrases_day (
    id int PRIMARY KEY AUTO_INCREMENT,
    text varchar(255) not null,
    actual tinyint null
);

INSERT INTO `phrases_day` (`id`, `text`, `actual`) VALUES
(1, 'Acredite no poder dos seus sonhos e transforme-os em realidade.', NULL),
(2, 'Cada novo dia é uma oportunidade para sermos melhores do que éramos ontem.', NULL),
(3, 'Seja a mudança que deseja ver no mundo - Gandhi.', NULL),
(4, 'O sucesso vem para aqueles que perseveram apesar dos desafios.', 1),
(5, 'A vida é uma jornada, não uma corrida; aproveite cada passo do caminho.', NULL),
(6, 'Não tema o fracasso, veja-o como uma oportunidade para aprender e crescer.', NULL),
(7, 'Seja gentil, pois cada pequeno gesto pode fazer uma grande diferença.', 2),
(8, 'O otimismo é a chave para enfrentar os obstáculos com coragem e determinação.', NULL),
(9, 'Acredite na sua capacidade de superar qualquer adversidade que surgir.', NULL),
(10, 'Grandes coisas nunca vêm de zonas de conforto; arrisque-se e cresça.', NULL),
(11, 'O sucesso é a soma de pequenos esforços repetidos dia após dia.', 3),
(12, 'Encontre inspiração nas pequenas coisas e você descobrirá beleza em todo lugar.', NULL),
(13, 'A gratidão transforma o que temos em suficiente e mais.', NULL),
(14, 'O segredo da felicidade está em apreciar o presente e sonhar com o futuro.', NULL),
(15, 'Permita-se falhar, mas nunca desista de tentar alcançar seus objetivos.', NULL),
(16, 'A confiança em si mesmo é a chave para desbloquear seu potencial ilimitado.', NULL),
(17, 'Seja paciente; o progresso não acontece da noite para o dia.', NULL),
(18, 'Aprenda a se levantar depois de cada queda; é assim que você se torna mais forte.', NULL),
(19, 'Sua atitude determina sua direção; escolha ser positivo e proativo.', NULL),
(20, 'Encontre a alegria no processo de crescimento e autodescoberta.', NULL),
(21, 'Não se compare aos outros; concentre-se em ser a melhor versão de si mesmo.', NULL),
(22, 'Cada obstáculo é uma oportunidade disfarçada de crescimento e aprendizado.', NULL),
(23, 'Mantenha sua mente aberta para novas possibilidades e experiências.', NULL),
(24, 'A vida é curta demais para desperdiçar com arrependimentos; viva com paixão e propósito.', NULL),
(25, 'Aceite os desafios como oportunidades para fortalecer sua resiliência.', NULL),
(26, 'O amor e a compaixão são as forças mais poderosas do universo.', NULL),
(27, 'Seja grato pelo que você tem enquanto trabalha pelo que deseja.', NULL),
(28, 'Não deixe que o medo paralise seus sonhos; enfrente-o com coragem.', NULL),
(29, 'A jornada mais gratificante é aquela em que você se torna quem realmente é.', NULL),
(30, 'Cultive relacionamentos positivos que nutrem seu crescimento pessoal.', NULL),
(31, 'Cada passo em direção aos seus sonhos é um passo na direção certa.', NULL),
(32, 'Mantenha-se firme em sua visão, mesmo quando os outros duvidam de você.', NULL),
(33, 'Sua história ainda está sendo escrita; faça dela uma que você se orgulhe.', NULL),
(34, 'O sucesso não é definido por dinheiro ou status, mas pela felicidade e realização pessoal.', NULL),
(35, 'A vida é uma aventura; abrace o desconhecido e cresça com ele.', NULL),
(36, 'Acredite na sua capacidade de superar qualquer obstáculo que apareça em seu caminho.', NULL),
(37, 'Valorize cada momento como uma oportunidade única de crescimento e aprendizado.', NULL),
(38, 'O poder está dentro de você; liberte-o e alcance o impossível.', NULL),
(39, 'Seja gentil consigo mesmo; o autoperdão é essencial para o crescimento pessoal.', NULL),
(40, 'Confie no processo da vida; tudo acontece no momento certo.', NULL),
(41, 'O sucesso não é uma linha reta; é uma série de altos e baixos que moldam sua jornada.', NULL),
(42, 'A autodisciplina é a chave para transformar metas em realizações tangíveis.', NULL),
(43, 'Nunca subestime o impacto positivo que você pode ter na vida de alguém.', NULL),
(44, 'Seja grato pelo que você tem enquanto trabalha para o que deseja alcançar.', NULL),
(45, 'Sua mentalidade determina sua realidade; escolha pensar grande e alcançar grande.', NULL),
(46, 'Não deixe que o passado defina seu futuro; aprenda com ele e siga em frente.', NULL),
(47, 'A persistência é o caminho para o sucesso; nunca desista de perseguir seus sonhos.', NULL),
(48, 'O amor próprio é a base para construir relacionamentos saudáveis ​​e gratificantes.', NULL),
(49, 'Acredite na sua capacidade de superar qualquer desafio que a vida lhe apresente.', NULL),
(50, 'A vida é uma jornada de autodescoberta; aproveite cada momento e aprenda com ele.', NULL),
(51, 'A gratidão transforma o que temos em suficiente e mais.', NULL),
(52, 'Mantenha-se firme em sua visão, mesmo quando os outros duvidam de você.', NULL),
(53, 'Cultive uma mentalidade de abundância; há infinitas possibilidades esperando por você.', NULL),
(54, 'Seja gentil consigo mesmo; o amor próprio é essencial para o bem-estar emocional.', NULL),
(55, 'Encontre beleza nas imperfeições; é isso que torna a vida interessante e bonita.', NULL),
(56, 'Acredite em si mesmo e em seu potencial ilimitado para alcançar grandes coisas.', NULL),
(57, 'Não deixe que o medo do fracasso o impeça de perseguir seus sonhos mais ousados.', NULL),
(58, 'Valorize cada obstáculo como uma oportunidade de crescimento e autodesenvolvimento.', NULL),
(59, 'Celebre suas conquistas, por menores que sejam; cada passo conta para o sucesso.', NULL),
(60, 'A vida é uma jornada de altos e baixos; encontre equilíbrio e resiliência em ambos.', NULL),
(61, 'Aceite os desafios como oportunidades para descobrir sua verdadeira força interior.', NULL),
(62, 'Cultive uma mentalidade de gratidão; é a chave para uma vida plena e feliz.', NULL),
(63, 'Acredite na beleza dos seus sonhos e na força do seu espírito para realizá-los.', NULL),
(64, 'Seja paciente consigo mesmo; o crescimento pessoal leva tempo e dedicação.', NULL),
(65, 'Não deixe que o passado dite seu futuro; você tem o poder de criar sua própria história.', NULL),
(66, 'Valorize cada experiência como uma oportunidade de aprendizado e crescimento.', NULL),
(67, 'A vida é um presente precioso; viva com gratidão, propósito e alegria.', NULL),
(68, 'Não se compare aos outros; sua jornada é única e digna de ser vivida plenamente.', NULL),
(69, 'Encontre inspiração nas pequenas coisas; elas têm o poder de transformar sua vida.', NULL),
(70, 'Seja gentil consigo mesmo; você merece amor, compaixão e perdão.', NULL),
(71, 'Acredite na sua capacidade de superar qualquer desafio que a vida lhe apresente.', NULL),
(72, 'Mantenha-se firme em sua visão, mesmo quando enfrentar adversidades.', NULL),
(73, 'Cultive relacionamentos positivos que apoiam e incentivam seu crescimento pessoal.', NULL),
(74, 'Não tenha medo de falhar; veja cada obstáculo como uma oportunidade de aprendizado.', NULL),
(75, 'A vida é uma jornada de autodescoberta; aproveite cada momento e aprenda com ele.', NULL),
(76, 'Valorize cada experiência como uma oportunidade de crescimento e evolução.', NULL),
(77, 'Acredite no poder dos seus sonhos e no seu potencial para realizá-los.', NULL),
(78, 'Seja grato pelo que você tem enquanto trabalha para o que deseja alcançar.', NULL),
(79, 'Mantenha-se positivo e focado em seus objetivos, independentemente dos desafios que enfrentar.', NULL),
(80, 'A persistência e a determinação são as chaves para superar qualquer obstáculo.', NULL),
(81, 'A vida é uma jornada de altos e baixos; encontre força e resiliência em ambos.', NULL),
(82, 'Não deixe que o medo do fracasso o impeça de perseguir seus sonhos mais ousados.', NULL),
(83, 'Celebre suas conquistas, por menores que sejam; cada passo conta para o sucesso.', NULL),
(84, 'Seja paciente consigo mesmo; o crescimento pessoal leva tempo e dedicação.', NULL),
(85, 'Encontre alegria nas pequenas coisas; elas são a essência da vida.', NULL),
(86, 'Acredite na sua capacidade de superar qualquer desafio que a vida lhe apresente.', NULL),
(87, 'Mantenha-se firme em sua visão, mesmo quando enfrentar adversidades.', NULL),
(88, 'Cultive relacionamentos positivos que apoiam e incentivam seu crescimento pessoal.', NULL),
(89, 'Não tenha medo de falhar; veja cada obstáculo como uma oportunidade de aprendizado.', NULL),
(90, 'A vida é uma jornada de autodescoberta; aproveite cada momento e aprenda com ele.', NULL),
(91, 'Valorize cada experiência como uma oportunidade de crescimento e evolução.', NULL),
(92, 'Acredite no poder dos seus sonhos e no seu potencial para realizá-los.', NULL),
(93, 'Seja grato pelo que você tem enquanto trabalha para o que deseja alcançar.', NULL),
(94, 'Mantenha-se positivo e focado em seus objetivos, independentemente dos desafios que enfrentar.', NULL),
(95, 'A persistência e a determinação são as chaves para superar qualquer obstáculo.', NULL),
(96, 'A vida é uma jornada de altos e baixos; encontre força e resiliência em ambos.', NULL),
(97, 'Não deixe que o medo do fracasso o impeça de perseguir seus sonhos mais ousados.', NULL),
(98, 'Celebre suas conquistas, por menores que sejam; cada passo conta para o sucesso.', NULL),
(99, 'Seja paciente consigo mesmo; o crescimento pessoal leva tempo e dedicação.', NULL),
(100, 'Encontre alegria nas pequenas coisas; elas são a essência da vida.', NULL),
(101, 'Acredite na sua capacidade de superar qualquer desafio que a vida lhe apresente.', NULL),
(102, 'Mantenha-se firme em sua visão, mesmo quando enfrentar adversidades.', NULL),
(103, 'Cultive relacionamentos positivos que apoiam e incentivam seu crescimento pessoal.', NULL),
(104, 'Não tenha medo de falhar; veja cada obstáculo como uma oportunidade de aprendizado.', NULL),
(105, 'A vida é uma jornada de autodescoberta; aproveite cada momento e aprenda com ele.', NULL),
(106, 'Valorize cada experiência como uma oportunidade de crescimento e evolução.', NULL),
(107, 'Acredite no poder dos seus sonhos e no seu potencial para realizá-los.', NULL),
(108, 'Seja grato pelo que você tem enquanto trabalha para o que deseja alcançar.', NULL),
(109, 'Mantenha-se positivo e focado em seus objetivos, independentemente dos desafios que enfrentar.', NULL),
(110, 'A persistência e a determinação são as chaves para superar qualquer obstáculo.', NULL),
(111, 'A vida é uma jornada de altos e baixos; encontre força e resiliência em ambos.', NULL),
(112, 'Não deixe que o medo do fracasso o impeça de perseguir seus sonhos mais ousados.', NULL),
(113, 'Celebre suas conquistas, por menores que sejam; cada passo conta para o sucesso.', NULL),
(114, 'Seja paciente consigo mesmo; o crescimento pessoal leva tempo e dedicação.', NULL),
(115, 'Encontre alegria nas pequenas coisas; elas são a essência da vida.', NULL),
(116, 'Acredite na sua capacidade de superar qualquer desafio que a vida lhe apresente.', NULL),
(117, 'Mantenha-se firme em sua visão, mesmo quando enfrentar adversidades.', NULL),
(118, 'Cultive relacionamentos positivos que apoiam e incentivam seu crescimento pessoal.', NULL),
(119, 'Não tenha medo de falhar; veja cada obstáculo como uma oportunidade de aprendizado.', NULL),
(120, 'A vida é uma jornada de autodescoberta; aproveite cada momento e aprenda com ele.', NULL),
(121, 'Valorize cada experiência como uma oportunidade de crescimento e evolução.', NULL),
(122, 'Acredite no poder dos seus sonhos e no seu potencial para realizá-los.', NULL),
(123, 'Seja grato pelo que você tem enquanto trabalha para o que deseja alcançar.', NULL),
(124, 'Mantenha-se positivo e focado em seus objetivos, independentemente dos desafios que enfrentar.', NULL),
(125, 'A persistência e a determinação são as chaves para superar qualquer obstáculo.', NULL),
(126, 'A vida é uma jornada de altos e baixos; encontre força e resiliência em ambos.', NULL),
(127, 'Não deixe que o medo do fracasso o impeça de perseguir seus sonhos mais ousados.', NULL),
(128, 'Celebre suas conquistas, por menores que sejam; cada passo conta para o sucesso.', NULL),
(129, 'Seja paciente consigo mesmo; o crescimento pessoal leva tempo e dedicação.', NULL),
(130, 'Encontre alegria nas pequenas coisas; elas são a essência da vida.', NULL),
(131, 'Acredite na sua capacidade de superar qualquer desafio que a vida lhe apresente.', NULL),
(132, 'Mantenha-se firme em sua visão, mesmo quando enfrentar adversidades.', NULL),
(133, 'Cultive relacionamentos positivos que apoiam e incentivam seu crescimento pessoal.', NULL),
(134, 'Não tenha medo de falhar; veja cada obstáculo como uma oportunidade de aprendizado.', NULL),
(135, 'A vida é uma jornada de autodescoberta; aproveite cada momento e aprenda com ele.', NULL),
(136, 'Valorize cada experiência como uma oportunidade de crescimento e evolução.', NULL),
(137, 'Acredite no poder dos seus sonhos e no seu potencial para realizá-los.', NULL),
(138, 'Seja grato pelo que você tem enquanto trabalha para o que deseja alcançar.', NULL),
(139, 'Mantenha-se positivo e focado em seus objetivos, independentemente dos desafios que enfrentar.', NULL),
(140, 'A persistência e a determinação são as chaves para superar qualquer obstáculo.', NULL),
(141, 'A vida é uma jornada de altos e baixos; encontre força e resiliência em ambos.', NULL),
(142, 'Não deixe que o medo do fracasso o impeça de perseguir seus sonhos mais ousados.', NULL),
(143, 'Celebre suas conquistas, por menores que sejam; cada passo conta para o sucesso.', NULL),
(144, 'Seja paciente consigo mesmo; o crescimento pessoal leva tempo e dedicação.', NULL),
(145, 'Encontre alegria nas pequenas coisas; elas são a essência da vida.', NULL),
(146, 'Acredite na sua capacidade de superar qualquer desafio que a vida lhe apresente.', NULL),
(147, 'Mantenha-se firme em sua visão, mesmo quando enfrentar adversidades.', NULL),
(148, 'Cultive relacionamentos positivos que apoiam e incentivam seu crescimento pessoal.', NULL),
(149, 'Não tenha medo de falhar; veja cada obstáculo como uma oportunidade de aprendizado.', NULL),
(150, 'A vida é uma jornada de autodescoberta; aproveite cada momento e aprenda com ele.', NULL),
(151, 'Valorize cada experiência como uma oportunidade de crescimento e evolução.', NULL),
(152, 'Acredite no poder dos seus sonhos e no seu potencial para realizá-los.', NULL),
(153, 'Seja grato pelo que você tem enquanto trabalha para o que deseja alcançar.', NULL),
(154, 'Mantenha-se positivo e focado em seus objetivos, independentemente dos desafios que enfrentar.', NULL),
(155, 'A persistência e a determinação são as chaves para superar qualquer obstáculo.', NULL),
(156, 'A vida é uma jornada de altos e baixos; encontre força e resiliência em ambos.', NULL),
(157, 'Não deixe que o medo do fracasso o impeça de perseguir seus sonhos mais ousados.', NULL),
(158, 'Celebre suas conquistas, por menores que sejam; cada passo conta para o sucesso.', NULL),
(159, 'Seja paciente consigo mesmo; o crescimento pessoal leva tempo e dedicação.', NULL),
(160, 'Encontre alegria nas pequenas coisas; elas são a essência da vida.', NULL),
(161, 'Acredite na sua capacidade de superar qualquer desafio que a vida lhe apresente.', NULL),
(162, 'Mantenha-se firme em sua visão, mesmo quando enfrentar adversidades.', NULL),
(163, 'Cultive relacionamentos positivos que apoiam e incentivam seu crescimento pessoal.', NULL),
(164, 'Não tenha medo de falhar; veja cada obstáculo como uma oportunidade de aprendizado.', NULL),
(165, 'A vida é uma jornada de autodescoberta; aproveite cada momento e aprenda com ele.', NULL),
(166, 'Valorize cada experiência como uma oportunidade de crescimento e evolução.', NULL),
(167, 'Acredite no poder dos seus sonhos e no seu potencial para realizá-los.', NULL),
(168, 'Seja grato pelo que você tem enquanto trabalha para o que deseja alcançar.', NULL),
(169, 'Mantenha-se positivo e focado em seus objetivos, independentemente dos desafios que enfrentar.', NULL),
(170, 'A persistência e a determinação são as chaves para superar qualquer obstáculo.', NULL),
(171, 'A vida é uma jornada de altos e baixos; encontre força e resiliência em ambos.', NULL),
(172, 'Não deixe que o medo do fracasso o impeça de perseguir seus sonhos mais ousados.', NULL),
(173, 'Celebre suas conquistas, por menores que sejam; cada passo conta para o sucesso.', NULL),
(174, 'Seja paciente consigo mesmo; o crescimento pessoal leva tempo e dedicação.', NULL),
(175, 'Encontre alegria nas pequenas coisas; elas são a essência da vida.', NULL),
(176, 'Acredite na sua capacidade de superar qualquer desafio que a vida lhe apresente.', NULL),
(177, 'Mantenha-se firme em sua visão, mesmo quando enfrentar adversidades.', NULL),
(178, 'Cultive relacionamentos positivos que apoiam e incentivam seu crescimento pessoal.', NULL),
(179, 'Não tenha medo de falhar; veja cada obstáculo como uma oportunidade de aprendizado.', NULL),
(180, 'A vida é uma jornada de autodescoberta; aproveite cada momento e aprenda com ele.', NULL),
(181, 'Valorize cada experiência como uma oportunidade de crescimento e evolução.', NULL),
(182, 'Acredite no poder dos seus sonhos e no seu potencial para realizá-los.', NULL),
(183, 'Seja grato pelo que você tem enquanto trabalha para o que deseja alcançar.', NULL),
(184, 'Mantenha-se positivo e focado em seus objetivos, independentemente dos desafios que enfrentar.', NULL),
(185, 'A persistência e a determinação são as chaves para superar qualquer obstáculo.', NULL),
(186, 'A vida é uma jornada de altos e baixos; encontre força e resiliência em ambos.', NULL),
(187, 'Não deixe que o medo do fracasso o impeça de perseguir seus sonhos mais ousados.', NULL),
(188, 'Celebre suas conquistas, por menores que sejam; cada passo conta para o sucesso.', NULL),
(189, 'Seja paciente consigo mesmo; o crescimento pessoal leva tempo e dedicação.', NULL),
(190, 'Encontre alegria nas pequenas coisas; elas são a essência da vida.', NULL),
(191, 'Acredite na sua capacidade de superar qualquer desafio que a vida lhe apresente.', NULL),
(192, 'Mantenha-se firme em sua visão, mesmo quando enfrentar adversidades.', NULL),
(193, 'Cultive relacionamentos positivos que apoiam e incentivam seu crescimento pessoal.', NULL),
(194, 'Não tenha medo de falhar; veja cada obstáculo como uma oportunidade de aprendizado.', NULL),
(195, 'A vida é uma jornada de autodescoberta; aproveite cada momento e aprenda com ele.', NULL),
(196, 'Valorize cada experiência como uma oportunidade de crescimento e evolução.', NULL),
(197, 'Acredite no poder dos seus sonhos e no seu potencial para realizá-los.', NULL),
(198, 'Seja grato pelo que você tem enquanto trabalha para o que deseja alcançar.', NULL),
(199, 'Mantenha-se positivo e focado em seus objetivos, independentemente dos desafios que enfrentar.', NULL),
(200, 'A persistência e a determinação são as chaves para superar qualquer obstáculo.', NULL),
(201, 'A vida é uma jornada de altos e baixos; encontre força e resiliência em ambos.', NULL),
(202, 'Não deixe que o medo do fracasso o impeça de perseguir seus sonhos mais ousados.', NULL),
(203, 'Celebre suas conquistas, por menores que sejam; cada passo conta para o sucesso.', NULL),
(204, 'Seja paciente consigo mesmo; o crescimento pessoal leva tempo e dedicação.', NULL),
(205, 'Encontre alegria nas pequenas coisas; elas são a essência da vida.', NULL),
(206, 'Acredite na sua capacidade de superar qualquer desafio que a vida lhe apresente.', NULL),
(207, 'Mantenha-se firme em sua visão, mesmo quando enfrentar adversidades.', NULL),
(208, 'Cultive relacionamentos positivos que apoiam e incentivam seu crescimento pessoal.', NULL),
(209, 'Não tenha medo de falhar; veja cada obstáculo como uma oportunidade de aprendizado.', NULL),
(210, 'A vida é uma jornada de autodescoberta; aproveite cada momento e aprenda com ele.', NULL),
(211, 'Valorize cada experiência como uma oportunidade de crescimento e evolução.', NULL),
(212, 'Acredite no poder dos seus sonhos e no seu potencial para realizá-los.', NULL),
(213, 'Seja grato pelo que você tem enquanto trabalha para o que deseja alcançar.', NULL),
(214, 'Mantenha-se positivo e focado em seus objetivos, independentemente dos desafios que enfrentar.', NULL),
(215, 'A persistência e a determinação são as chaves para superar qualquer obstáculo.', NULL),
(216, 'A vida é uma jornada de altos e baixos; encontre força e resiliência em ambos.', NULL),
(217, 'Não deixe que o medo do fracasso o impeça de perseguir seus sonhos mais ousados.', NULL),
(218, 'Celebre suas conquistas, por menores que sejam; cada passo conta para o sucesso.', NULL),
(219, 'Seja paciente consigo mesmo; o crescimento pessoal leva tempo e dedicação.', NULL),
(220, 'Encontre alegria nas pequenas coisas; elas são a essência da vida.', NULL),
(221, 'Acredite na sua capacidade de superar qualquer desafio que a vida lhe apresente.', NULL),
(222, 'Mantenha-se firme em sua visão, mesmo quando enfrentar adversidades.', NULL),
(223, 'Cultive relacionamentos positivos que apoiam e incentivam seu crescimento pessoal.', NULL),
(224, 'Não tenha medo de falhar; veja cada obstáculo como uma oportunidade de aprendizado.', NULL),
(225, 'A vida é uma jornada de autodescoberta; aproveite cada momento e aprenda com ele.', NULL),
(226, 'Valorize cada experiência como uma oportunidade de crescimento e evolução.', NULL),
(227, 'Acredite no poder dos seus sonhos e no seu potencial para realizá-los.', NULL),
(228, 'Seja grato pelo que você tem enquanto trabalha para o que deseja alcançar.', NULL),
(229, 'Mantenha-se positivo e focado em seus objetivos, independentemente dos desafios que enfrentar.', NULL),
(230, 'A persistência e a determinação são as chaves para superar qualquer obstáculo.', NULL),
(231, 'A vida é uma jornada de altos e baixos; encontre força e resiliência em ambos.', NULL),
(232, 'Não deixe que o medo do fracasso o impeça de perseguir seus sonhos mais ousados.', NULL),
(233, 'Celebre suas conquistas, por menores que sejam; cada passo conta para o sucesso.', NULL),
(234, 'Seja paciente consigo mesmo; o crescimento pessoal leva tempo e dedicação.', NULL),
(235, 'Encontre alegria nas pequenas coisas; elas são a essência da vida.', NULL),
(236, 'Acredite na sua capacidade de superar qualquer desafio que a vida lhe apresente.', NULL),
(237, 'Mantenha-se firme em sua visão, mesmo quando enfrentar adversidades.', NULL),
(238, 'Cultive relacionamentos positivos que apoiam e incentivam seu crescimento pessoal.', NULL),
(239, 'Não tenha medo de falhar; veja cada obstáculo como uma oportunidade de aprendizado.', NULL),
(240, 'A vida é uma jornada de autodescoberta; aproveite cada momento e aprenda com ele.', NULL),
(241, 'Valorize cada experiência como uma oportunidade de crescimento e evolução.', NULL),
(242, 'Acredite no poder dos seus sonhos e no seu potencial para realizá-los.', NULL),
(243, 'Seja grato pelo que você tem enquanto trabalha para o que deseja alcançar.', NULL),
(244, 'Mantenha-se positivo e focado em seus objetivos, independentemente dos desafios que enfrentar.', NULL),
(245, 'A persistência e a determinação são as chaves para superar qualquer obstáculo.', NULL),
(246, 'A vida é uma jornada de altos e baixos; encontre força e resiliência em ambos.', NULL),
(247, 'Não deixe que o medo do fracasso o impeça de perseguir seus sonhos mais ousados.', NULL),
(248, 'Celebre suas conquistas, por menores que sejam; cada passo conta para o sucesso.', NULL),
(249, 'Seja paciente consigo mesmo; o crescimento pessoal leva tempo e dedicação.', NULL),
(250, 'Encontre alegria nas pequenas coisas; elas são a essência da vida.', NULL),
(251, 'Acredite na sua capacidade de superar qualquer desafio que a vida lhe apresente.', NULL),
(252, 'Mantenha-se firme em sua visão, mesmo quando enfrentar adversidades.', NULL),
(253, 'Cultive relacionamentos positivos que apoiam e incentivam seu crescimento pessoal.', NULL),
(254, 'Não tenha medo de falhar; veja cada obstáculo como uma oportunidade de aprendizado.', NULL),
(255, 'A vida é uma jornada de autodescoberta; aproveite cada momento e aprenda com ele.', NULL),
(256, 'Valorize cada experiência como uma oportunidade de crescimento e evolução.', NULL),
(257, 'Acredite no poder dos seus sonhos e no seu potencial para realizá-los.', NULL),
(258, 'Seja grato pelo que você tem enquanto trabalha para o que deseja alcançar.', NULL),
(259, 'Mantenha-se positivo e focado em seus objetivos, independentemente dos desafios que enfrentar.', NULL),
(260, 'A persistência e a determinação são as chaves para superar qualquer obstáculo.', NULL),
(261, 'A vida é uma jornada de altos e baixos; encontre força e resiliência em ambos.', NULL),
(262, 'Não deixe que o medo do fracasso o impeça de perseguir seus sonhos mais ousados.', NULL),
(263, 'Celebre suas conquistas, por menores que sejam; cada passo conta para o sucesso.', NULL),
(264, 'Seja paciente consigo mesmo; o crescimento pessoal leva tempo e dedicação.', NULL),
(265, 'Encontre alegria nas pequenas coisas; elas são a essência da vida.', NULL),
(266, 'Acredite na sua capacidade de superar qualquer desafio que a vida lhe apresente.', NULL),
(267, 'Mantenha-se firme em sua visão, mesmo quando enfrentar adversidades.', NULL),
(268, 'Cultive relacionamentos positivos que apoiam e incentivam seu crescimento pessoal.', NULL),
(269, 'Não tenha medo de falhar; veja cada obstáculo como uma oportunidade de aprendizado.', NULL),
(270, 'A vida é uma jornada de autodescoberta; aproveite cada momento e aprenda com ele.', NULL),
(271, 'Valorize cada experiência como uma oportunidade de crescimento e evolução.', NULL),
(272, 'Acredite no poder dos seus sonhos e no seu potencial para realizá-los.', NULL),
(273, 'Seja grato pelo que você tem enquanto trabalha para o que deseja alcançar.', NULL),
(274, 'Mantenha-se positivo e focado em seus objetivos, independentemente dos desafios que enfrentar.', NULL),
(275, 'A persistência e a determinação são as chaves para superar qualquer obstáculo.', NULL),
(276, 'A vida é uma jornada de altos e baixos; encontre força e resiliência em ambos.', NULL),
(277, 'Não deixe que o medo do fracasso o impeça de perseguir seus sonhos mais ousados.', NULL),
(278, 'Celebre suas conquistas, por menores que sejam; cada passo conta para o sucesso.', NULL),
(279, 'Seja paciente consigo mesmo; o crescimento pessoal leva tempo e dedicação.', NULL),
(280, 'Encontre alegria nas pequenas coisas; elas são a essência da vida.', NULL),
(281, 'Acredite na sua capacidade de superar qualquer desafio que a vida lhe apresente.', NULL),
(282, 'Mantenha-se firme em sua visão, mesmo quando enfrentar adversidades.', NULL),
(283, 'Cultive relacionamentos positivos que apoiam e incentivam seu crescimento pessoal.', NULL),
(284, 'Não tenha medo de falhar; veja cada obstáculo como uma oportunidade de aprendizado.', NULL),
(285, 'A vida é uma jornada de autodescoberta; aproveite cada momento e aprenda com ele.', NULL),
(286, 'Valorize cada experiência como uma oportunidade de crescimento e evolução.', NULL),
(287, 'Acredite no poder dos seus sonhos e no seu potencial para realizá-los.', NULL),
(288, 'Seja grato pelo que você tem enquanto trabalha para o que deseja alcançar.', NULL),
(289, 'Mantenha-se positivo e focado em seus objetivos, independentemente dos desafios que enfrentar.', NULL),
(290, 'A persistência e a determinação são as chaves para superar qualquer obstáculo.', NULL),
(291, 'A vida é uma jornada de altos e baixos; encontre força e resiliência em ambos.', NULL),
(292, 'Não deixe que o medo do fracasso o impeça de perseguir seus sonhos mais ousados.', NULL),
(293, 'Celebre suas conquistas, por menores que sejam; cada passo conta para o sucesso.', NULL),
(294, 'Seja paciente consigo mesmo; o crescimento pessoal leva tempo e dedicação.', NULL),
(295, 'Encontre alegria nas pequenas coisas; elas são a essência da vida.', NULL),
(296, 'Acredite na sua capacidade de superar qualquer desafio que a vida lhe apresente.', NULL),
(297, 'Mantenha-se firme em sua visão, mesmo quando enfrentar adversidades.', NULL),
(298, 'Cultive relacionamentos positivos que apoiam e incentivam seu crescimento pessoal.', NULL),
(299, 'Não tenha medo de falhar; veja cada obstáculo como uma oportunidade de aprendizado.', NULL),
(300, 'A vida é uma jornada de autodescoberta; aproveite cada momento e aprenda com ele.', NULL),
(301, 'Valorize cada experiência como uma oportunidade de crescimento e evolução.', NULL),
(302, 'Acredite no poder dos seus sonhos e no seu potencial para realizá-los.', NULL),
(303, 'Seja grato pelo que você tem enquanto trabalha para o que deseja alcançar.', NULL),
(304, 'Mantenha-se positivo e focado em seus objetivos, independentemente dos desafios que enfrentar.', NULL),
(305, 'A persistência e a determinação são as chaves para superar qualquer obstáculo.', NULL),
(306, 'A vida é uma jornada de altos e baixos; encontre força e resiliência em ambos.', NULL),
(307, 'Não deixe que o medo do fracasso o impeça de perseguir seus sonhos mais ousados.', NULL),
(308, 'Celebre suas conquistas, por menores que sejam; cada passo conta para o sucesso.', NULL),
(309, 'Seja paciente consigo mesmo; o crescimento pessoal leva tempo e dedicação.', NULL),
(310, 'Encontre alegria nas pequenas coisas; elas são a essência da vida.', NULL),
(311, 'Acredite na sua capacidade de superar qualquer desafio que a vida lhe apresente.', NULL),
(312, 'Mantenha-se firme em sua visão, mesmo quando enfrentar adversidades.', NULL),
(313, 'Cultive relacionamentos positivos que apoiam e incentivam seu crescimento pessoal.', NULL),
(314, 'Não tenha medo de falhar; veja cada obstáculo como uma oportunidade de aprendizado.', NULL),
(315, 'A vida é uma jornada de autodescoberta; aproveite cada momento e aprenda com ele.', NULL),
(316, 'Valorize cada experiência como uma oportunidade de crescimento e evolução.', NULL),
(317, 'Acredite no poder dos seus sonhos e no seu potencial para realizá-los.', NULL),
(318, 'Seja grato pelo que você tem enquanto trabalha para o que deseja alcançar.', NULL),
(319, 'Mantenha-se positivo e focado em seus objetivos, independentemente dos desafios que enfrentar.', NULL),
(320, 'A persistência e a determinação são as chaves para superar qualquer obstáculo.', NULL),
(321, 'A vida é uma jornada de altos e baixos; encontre força e resiliência em ambos.', NULL),
(322, 'Não deixe que o medo do fracasso o impeça de perseguir seus sonhos mais ousados.', NULL),
(323, 'Celebre suas conquistas, por menores que sejam; cada passo conta para o sucesso.', NULL),
(324, 'Seja paciente consigo mesmo; o crescimento pessoal leva tempo e dedicação.', NULL),
(325, 'Encontre alegria nas pequenas coisas; elas são a essência da vida.', NULL),
(326, 'Acredite na sua capacidade de superar qualquer desafio que a vida lhe apresente.', NULL),
(327, 'Mantenha-se firme em sua visão, mesmo quando enfrentar adversidades.', NULL),
(328, 'Cultive relacionamentos positivos que apoiam e incentivam seu crescimento pessoal.', NULL),
(329, 'Não tenha medo de falhar; veja cada obstáculo como uma oportunidade de aprendizado.', NULL),
(330, 'A vida é uma jornada de autodescoberta; aproveite cada momento e aprenda com ele.', NULL),
(331, 'Valorize cada experiência como uma oportunidade de crescimento e evolução.', NULL),
(332, 'Acredite no poder dos seus sonhos e no seu potencial para realizá-los.', NULL),
(333, 'Seja grato pelo que você tem enquanto trabalha para o que deseja alcançar.', NULL),
(334, 'Mantenha-se positivo e focado em seus objetivos, independentemente dos desafios que enfrentar.', NULL),
(335, 'A persistência e a determinação são as chaves para superar qualquer obstáculo.', NULL),
(336, 'A vida é uma jornada de altos e baixos; encontre força e resiliência em ambos.', NULL),
(337, 'Não deixe que o medo do fracasso o impeça de perseguir seus sonhos mais ousados.', NULL),
(338, 'Celebre suas conquistas, por menores que sejam; cada passo conta para o sucesso.', NULL),
(339, 'Seja paciente consigo mesmo; o crescimento pessoal leva tempo e dedicação.', NULL),
(340, 'Encontre alegria nas pequenas coisas; elas são a essência da vida.', NULL),
(341, 'Acredite na sua capacidade de superar qualquer desafio que a vida lhe apresente.', NULL),
(342, 'Mantenha-se firme em sua visão, mesmo quando enfrentar adversidades.', NULL),
(343, 'Cultive relacionamentos positivos que apoiam e incentivam seu crescimento pessoal.', NULL),
(344, 'Não tenha medo de falhar; veja cada obstáculo como uma oportunidade de aprendizado.', NULL),
(345, 'A vida é uma jornada de autodescoberta; aproveite cada momento e aprenda com ele.', NULL),
(346, 'Valorize cada experiência como uma oportunidade de crescimento e evolução.', NULL),
(347, 'Acredite no poder dos seus sonhos e no seu potencial para realizá-los.', NULL),
(348, 'Seja grato pelo que você tem enquanto trabalha para o que deseja alcançar.', NULL),
(349, 'Mantenha-se positivo e focado em seus objetivos, independentemente dos desafios que enfrentar.', NULL),
(350, 'A persistência e a determinação são as chaves para superar qualquer obstáculo.', NULL),
(351, 'A vida é uma jornada de altos e baixos; encontre força e resiliência em ambos.', NULL),
(352, 'Não deixe que o medo do fracasso o impeça de perseguir seus sonhos mais ousados.', NULL),
(353, 'Celebre suas conquistas, por menores que sejam; cada passo conta para o sucesso.', NULL),
(354, 'Seja paciente consigo mesmo; o crescimento pessoal leva tempo e dedicação.', NULL),
(355, 'Encontre alegria nas pequenas coisas; elas são a essência da vida.', NULL),
(356, 'Acredite na sua capacidade de superar qualquer desafio que a vida lhe apresente.', NULL),
(357, 'Mantenha-se firme em sua visão, mesmo quando enfrentar adversidades.', NULL),
(358, 'Cultive relacionamentos positivos que apoiam e incentivam seu crescimento pessoal.', NULL),
(359, 'Não tenha medo de falhar; veja cada obstáculo como uma oportunidade de aprendizado.', NULL),
(360, 'A vida é uma jornada de autodescoberta; aproveite cada momento e aprenda com ele.', NULL),
(361, 'Valorize cada experiência como uma oportunidade de crescimento e evolução.', NULL),
(362, 'Acredite no poder dos seus sonhos e no seu potencial para realizá-los.', NULL),
(363, 'Seja grato pelo que você tem enquanto trabalha para o que deseja alcançar.', NULL),
(364, 'Mantenha-se positivo e focado em seus objetivos, independentemente dos desafios que enfrentar.', NULL),
(365, 'A persistência e a determinação são as chaves para superar qualquer obstáculo.', NULL);

___

create table plans (
    id int PRIMARY KEY AUTO_INCREMENT,
    title varchar(255) not null,
    description varchar(255) not null,
    price float(5, 2) null,
    url varchar(255) not null,
    discount float(5, 2) null
);

create table users_plans (
    id int PRIMARY KEY AUTO_INCREMENT,
    plan_id int not null,
    user_id int not null,
    
    FOREIGN KEY (plan_id) REFERENCES game_types(`id`),
    FOREIGN KEY (user_id) REFERENCES users(`id`)
);


create table plans_pay (
    id int PRIMARY KEY AUTO_INCREMENT,
    user_id int not null,
    plan_id int not null,
    status varchar(20) not null,
    price_quotas DECIMAL(10, 2),
    discount_value DECIMAL(10, 2),
    date_created datetime not null,
    date_approved datetime,
    date_expiration datetime not null,
    
    FOREIGN KEY (plan_id) REFERENCES game_types(`id`),
    FOREIGN KEY (user_id) REFERENCES users(`id`)
);


create table guest_purchase_logs (
    id int PRIMARY KEY AUTO_INCREMENT,
    user_id int not null,
    invite_user_id int not null,
    purchase_id int not null,
    date_created date not null,
    FOREIGN KEY (`invite_user_id`) REFERENCES users(`id`),
    FOREIGN KEY (`user_id`) REFERENCES users(`id`)
);


create table pg_icons (
    id int PRIMARY KEY AUTO_INCREMENT,
    title varchar(100) not null
);
INSERT INTO pg_icons (`title`) VALUES 
('fa-cart-shopping'),
('fa-car'),
('fa-magnifying-glass'),
('fa-user'),
('fa-star'),
('fa-heart'),
('fa-gift'),
('fa-briefcase'),
('fa-shirt')