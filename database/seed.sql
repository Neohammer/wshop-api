INSERT INTO users (email, password_hash, created_at)
VALUES (
           'admin@example.com',
           '$2y$12$GgyNjH9UPJTd3kQeUspR8egYZZ5CqWe5i9LUH6BiNm9qDJMDz67ty',
           '2026-03-19T10:00:00+00:00'
       );

INSERT INTO stores (name, manager_name, phone, street, postal_code, city, created_at, updated_at)
VALUES
    ('Central Paris', 'Alice Martin', '0102030405', '10 rue de Rivoli', '75001', 'Paris', '2026-03-19T10:00:00+00:00', '2026-03-19T10:00:00+00:00'),
    ('Lyon Bellecour', 'Marc Dupont', '0203040506', '5 place Bellecour', '69002', 'Lyon', '2026-03-19T10:00:00+00:00', '2026-03-19T10:00:00+00:00'),
    ('Bordeaux Centre', 'Sophie Bernard', '0304050607', '22 cours Victor Hugo', '33000', 'Bordeaux', '2026-03-19T10:00:00+00:00', '2026-03-19T10:00:00+00:00');