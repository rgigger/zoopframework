begin;

create table session_form
(
    id serial primary key,
    session_id text not null,
    fields text not null
);

commit;