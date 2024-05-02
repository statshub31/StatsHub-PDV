SET sql_mode = 'NO_ZERO_DATE'; -- Altera temporariamente o modo de tratamento de datas e horas

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

INSERT INTO `accounts`
(`username`, `password`, `email`, `group_id`, `rules`, `block`, `created`) VALUES 
('moratech', 'eacd55f94ae1b2956a05eb1d21c6e335', 'moratech@gmail.com', 5, 1, 0, '2024-01-01');


create table users (
    `id` int PRIMARY KEY AUTO_INCREMENT,
    `account_id` int not null,
    `name` varchar(255) NOT NULL,
    `phone` varchar(15) not null,
    `photo` VARCHAR(255) NULL,
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


create table icons (
    `id` int PRIMARY KEY AUTO_INCREMENT,
    `title` varchar(100) not null
);

INSERT INTO `icons` (`title`) VALUES ('fa-solid fa-cart-shopping'),
('fa-solid fa-car'),
('fa-solid fa-magnifying-glass'),
('fa-solid fa-user'),
('fa-solid fa-star'),
('fa-solid fa-heart'),
('fa-solid fa-gift'),
('fa-solid fa-briefcase'),
('fa-solid fa-shirt'),
('fa-solid fa-money-bill-wave'),
('fa-solid fa-money-check-dollar'),
('fa-solid fa-credit-card'),
('fa-regular fa-credit-card'),
('fa-solid fa-money-bill'),
('fa-brands fa-pix');


create table categorys (
    `id` int PRIMARY KEY AUTO_INCREMENT,
    `title` varchar(50) NOT NULL UNIQUE,
    `icon_id` int,
    FOREIGN KEY (`icon_id`) REFERENCES `icons`(`id`)
);

INSERT INTO `categorys`(`title`) VALUES ('Outros'), ('Comida'), ('Bebida');


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

create table products_favorites (
    `id` int PRIMARY KEY AUTO_INCREMENT,
    `product_id` int not null,
    `user_id` int not null,
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
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



create table carts (
    `id` int PRIMARY KEY AUTO_INCREMENT,
    `user_id` int not null,
    `status` int not null,
    `created` datetime,
    FOREIGN KEY (`status`) REFERENCES `status`(`id`)
);

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

insert into `settings_info` () VALUES ();

create table settings_delivery (
    `id` int PRIMARY KEY AUTO_INCREMENT,
    `order_withdrawal` boolean not null default 0,
    `address_api` text null,
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

insert into `settings_pay` (`type`, `pay_key`, `icon_pay`, `disabled`) VALUES 
('Dinheiro', NULL, 10, 0), 
('Crédito', NULL, 12, 0), 
('Débito', NULL, 13, 0), 
('Pix', NULL, 15, 0), 
('VR', NULL, 12, 0), 
('VA', NULL, 12, 0);

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





























SET sql_mode = ''; -- Restaura o modo de tratamento de datas e horas para o padrão
