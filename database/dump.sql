CREATE DATABASE `laravel` /*!40100 DEFAULT CHARACTER SET latin1 */

USE `laravel`;

create table classrooms
(
    id          bigint unsigned auto_increment primary key,
    name        varchar(255) not null,
    description text         null,
    type        int          not null,
    created_at  timestamp    null,
    updated_at  timestamp    null,
    deleted_at  timestamp    null
)
collate = utf8mb4_unicode_ci;

create table failed_jobs
(
    id         bigint unsigned auto_increment primary key,
    uuid       varchar(255)                        not null,
    connection text                                not null,
    queue      text                                not null,
    payload    longtext                            not null,
    exception  longtext                            not null,
    failed_at  timestamp default CURRENT_TIMESTAMP not null,
    constraint failed_jobs_uuid_unique
        unique (uuid)
)
collate = utf8mb4_unicode_ci;

create table migrations
(
    id        int unsigned auto_increment primary key,
    migration varchar(255) not null,
    batch     int          not null
)
collate = utf8mb4_unicode_ci;

create table password_resets
(
    email      varchar(255) not null,
    token      varchar(255) not null,
    created_at timestamp    null
)
collate = utf8mb4_unicode_ci;
create index password_resets_email_index
    on password_resets (email);

create table personal_access_tokens
(
    id             bigint unsigned auto_increment primary key,
    tokenable_type varchar(255)    not null,
    tokenable_id   bigint unsigned not null,
    name           varchar(255)    not null,
    token          varchar(64)     not null,
    abilities      text            null,
    last_used_at   timestamp       null,
    created_at     timestamp       null,
    updated_at     timestamp       null,
    constraint personal_access_tokens_token_unique
        unique (token)
)
collate = utf8mb4_unicode_ci;
create index personal_access_tokens_tokenable_type_tokenable_id_index
    on personal_access_tokens (tokenable_type, tokenable_id);

create table students
(
    id            bigint unsigned auto_increment primary key,
    name          varchar(255)    not null,
    date_of_birth date            null,
    user_id       bigint unsigned not null,
    created_at    timestamp       null,
    updated_at    timestamp       null,
    deleted_at    timestamp       null,
    constraint students_user_id_foreign
        foreign key (user_id) references users (id)
            on delete cascade
)
collate = utf8mb4_unicode_ci;

create table users
(
    id                bigint unsigned auto_increment primary key,
    name              varchar(255)         not null,
    email             varchar(255)         not null,
    email_verified_at timestamp            null,
    password          varchar(255)         not null,
    is_admin          tinyint(1) default 0 not null,
    remember_token    varchar(100)         null,
    created_at        timestamp            null,
    updated_at        timestamp            null,
    deleted_at        timestamp            null,
    constraint users_email_unique
        unique (email)
)
    collate = utf8mb4_unicode_ci;

INSERT INTO laravel.users (id, name, email, email_verified_at, password, is_admin, remember_token, created_at, updated_at, deleted_at) VALUES (1, 'Admin', 'admin@email.com', null, '$2y$10$MOSROREsAaTxVDpxPhwrjONw6olyEn/Jj/OS8gRbPeKNih.lI6GG.', 0, null, '2024-10-06 20:47:08', '2024-10-06 20:47:08', null);

create table student_classrooms
(
    id           bigint unsigned auto_increment primary key,
    student_id   bigint unsigned not null,
    classroom_id bigint unsigned not null,
    created_at   timestamp       null,
    updated_at   timestamp       null,
    deleted_at   timestamp       null,
    constraint student_classrooms_classroom_id_foreign
        foreign key (classroom_id) references classrooms (id)
            on delete cascade,
    constraint student_classrooms_student_id_foreign
        foreign key (student_id) references students (id)
            on delete cascade
)
collate = utf8mb4_unicode_ci;
