-- Tworzenie nowej bazy danych
CREATE DATABASE IF NOT EXISTS firma_db;
USE firma_db;

-- Tworzenie tabeli clients
CREATE TABLE clients
(
    id                  INT AUTO_INCREMENT PRIMARY KEY,
    company_name        VARCHAR(255),
    bank_account_number VARCHAR(255),
    tax_id              VARCHAR(255)
);

-- Tworzenie tabeli invoices
CREATE TABLE invoices
(
    id               INT AUTO_INCREMENT PRIMARY KEY,
    issue_date       DATE,
    payment_due_date DATE,
    total_amount     DECIMAL(10, 2),
    client_id        INT,
    FOREIGN KEY (client_id) REFERENCES clients (id)
);

-- Tworzenie tabeli invoice_items
CREATE TABLE invoice_items
(
    id           INT AUTO_INCREMENT PRIMARY KEY,
    product_name VARCHAR(255),
    quantity     INT,
    price        DECIMAL(10, 2),
    invoice_id   INT,
    FOREIGN KEY (invoice_id) REFERENCES invoices (id)
);

-- Tworzenie tabeli payments
CREATE TABLE payments
(
    id                  INT AUTO_INCREMENT PRIMARY KEY,
    payment_title       VARCHAR(255),
    amount              DECIMAL(10, 2),
    payment_date        DATE,
    bank_account_number VARCHAR(255),
    invoice_id          INT,
    FOREIGN KEY (invoice_id) REFERENCES invoices (id)
);

-- Wstawianie danych do tabeli clients
INSERT INTO clients (company_name, bank_account_number, tax_id)
VALUES ('Przedsiębiorstwo Alfa', 'PL10105000997603123456789123', '1234567890'),
       ('Firma Beta', 'PL10105000997603123456789124', '2345678901');

-- Wstawianie danych do tabeli invoices
INSERT INTO invoices (issue_date, payment_due_date, total_amount, client_id)
VALUES ('2023-08-01', '2023-08-15', 1234.56, 1),
       ('2023-08-10', '2023-08-24', 789.10, 2);

-- Wstawianie danych do tabeli invoice_items
INSERT INTO invoice_items (product_name, quantity, price, invoice_id)
VALUES ('Laptop', 10, 123.45, 1),
       ('Myszka', 50, 15.79, 2);

-- Wstawianie danych do tabeli payments
INSERT INTO payments (payment_title, amount, payment_date, bank_account_number, invoice_id)
VALUES ('Płatność za fakturę nr 1', 1234.56, '2023-08-05', 'PL10105000997603123456789123', 1),
       ('Płatność za fakturę nr 2', 789.10, '2023-08-20', 'PL10105000997603123456789124', 2);
