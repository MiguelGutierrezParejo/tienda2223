DROP TABLE IF EXISTS articulos CASCADE;

CREATE TABLE articulos(
    id          bigserial       PRIMARY KEY,
    codigo      varchar(23)     NOT NULL UNIQUE,
    descripcion varchar(255)    NOT NULL,
    precio      numeric(7, 2)   NOT NULL
);

INSERT INTO articulos(codigo, descripcion, precio)
       VALUES ('18273892389', 'Yogurt piña', 200.50),
              ('83745829273', 'Tigretón', 50.10),
              ('51786128495', 'Disco duro SSD 500 GB', 1500.30);