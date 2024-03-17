# Cafeteria-System-PHP

To configure the PHP mail() function to use SMTP with Gmail, you need to modify the php.ini configuration file.
Here's how you can set it to use SMTP server smtp.gmail.com and port 587:

Open your php.ini file in a text editor.
This file is usually located in your PHP installation directory.
Find the section for [mail function].
Update the SMTP parameter to smtp.gmail.com and the smtp_port parameter to 587.
Save the changes to the php.ini file.
Restart your web server for the changes to take effect.

add this view so the project can work

```
    CREATE VIEW order_details_view AS
    SELECT
        o.id AS order_id,
        o.date AS order_date,
        u.name AS user_name,
        u.id AS user_id,
        o.room_no AS room_no,
        r.ext AS extension_number,
        r.room_no AS room_number,
        o.status AS status,
        SUM((op.quantity * p.price)) AS total_amount,
        GROUP_CONCAT(p.image SEPARATOR ',') AS item_images,
        GROUP_CONCAT(op.quantity SEPARATOR ',') AS item_quantities,
        GROUP_CONCAT(p.price SEPARATOR ',') AS item_prices
    FROM
        orders o
    JOIN
        user u ON o.user_id = u.id
    JOIN
        rooms r ON o.room_no = r.room_no
    JOIN
        orders_product op ON o.id = op.order_id
    JOIN
        product p ON op.product_id = p.id
    GROUP BY
        o.id, u.id;

```
