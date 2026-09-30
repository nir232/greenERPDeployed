CREATE TABLE IF NOT EXISTS users(
 id INT AUTO_INCREMENT PRIMARY KEY, full_name VARCHAR(120) NOT NULL, username VARCHAR(60) NOT NULL UNIQUE,
 email VARCHAR(150) DEFAULT '', phone VARCHAR(30) DEFAULT '', password VARCHAR(255) NOT NULL,
 role ENUM('admin','moderator','accounts','inventory','hr','executive') NOT NULL DEFAULT 'executive',
 avatar VARCHAR(255) DEFAULT '', created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS employees(
 id INT AUTO_INCREMENT PRIMARY KEY, employee_id VARCHAR(50) NOT NULL UNIQUE, employee_code VARCHAR(50) DEFAULT '',
 name VARCHAR(120) NOT NULL, mobile VARCHAR(30) NOT NULL, email VARCHAR(150) DEFAULT '', department VARCHAR(80) NOT NULL,
 designation VARCHAR(100) NOT NULL, employee_type VARCHAR(30) DEFAULT '', joining_date DATE NULL,
 present_address TEXT, permanent_address TEXT, work_location VARCHAR(120) DEFAULT '', shift_name VARCHAR(80) DEFAULT '',
 status VARCHAR(30) DEFAULT 'Active', username VARCHAR(60) DEFAULT '', role VARCHAR(60) DEFAULT 'Employee',
 basic DECIMAL(12,2) DEFAULT 0, house_rent DECIMAL(12,2) DEFAULT 0, medical DECIMAL(12,2) DEFAULT 0,
 transport DECIMAL(12,2) DEFAULT 0, food DECIMAL(12,2) DEFAULT 0, other_allowance DECIMAL(12,2) DEFAULT 0,
 gross DECIMAL(12,2) DEFAULT 0, notes TEXT, avatar VARCHAR(255) DEFAULT '', created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
ALTER TABLE employees ADD COLUMN IF NOT EXISTS avatar VARCHAR(255) DEFAULT '';
ALTER TABLE users ADD COLUMN IF NOT EXISTS avatar VARCHAR(255) DEFAULT '';

CREATE TABLE IF NOT EXISTS suppliers(
 id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(150) NOT NULL, mobile VARCHAR(30) DEFAULT '', email VARCHAR(150) DEFAULT '',
 address TEXT, company VARCHAR(150) DEFAULT '', avatar VARCHAR(255) DEFAULT '', status VARCHAR(30) DEFAULT 'Active', created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
ALTER TABLE suppliers ADD COLUMN IF NOT EXISTS avatar VARCHAR(255) DEFAULT '';

CREATE TABLE IF NOT EXISTS attendance(
 id INT AUTO_INCREMENT PRIMARY KEY, employee_id INT NOT NULL, work_date DATE NOT NULL, check_in DATETIME NULL,
 break_start DATETIME NULL, break_end DATETIME NULL, check_out DATETIME NULL, work_minutes INT DEFAULT 0,
 break_minutes INT DEFAULT 0, status VARCHAR(30) DEFAULT 'Present', notes VARCHAR(255) DEFAULT '',
 UNIQUE KEY employee_day(employee_id,work_date), FOREIGN KEY(employee_id) REFERENCES employees(id) ON DELETE CASCADE
);
CREATE TABLE IF NOT EXISTS purchases(
 id INT AUTO_INCREMENT PRIMARY KEY, purchase_no VARCHAR(60) NOT NULL UNIQUE, supplier_id INT NOT NULL, product VARCHAR(160) NOT NULL,
 quantity DECIMAL(12,2) NOT NULL DEFAULT 1, unit_cost DECIMAL(12,2) NOT NULL DEFAULT 0, total_amount DECIMAL(12,2) NOT NULL DEFAULT 0,
 purchase_date DATE NOT NULL, transaction_method VARCHAR(30) DEFAULT 'Cash', transaction_id INT NULL, status VARCHAR(30) DEFAULT 'Received', notes TEXT, created_by INT NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY(supplier_id) REFERENCES suppliers(id) ON DELETE RESTRICT, FOREIGN KEY(created_by) REFERENCES users(id) ON DELETE SET NULL
);
CREATE TABLE IF NOT EXISTS orders(
 id INT AUTO_INCREMENT PRIMARY KEY, order_no VARCHAR(60) NOT NULL UNIQUE, customer_name VARCHAR(120) NOT NULL,
 mobile VARCHAR(30) DEFAULT '', address TEXT, product VARCHAR(160) DEFAULT '', quantity DECIMAL(12,2) DEFAULT 1,
 unit_price DECIMAL(12,2) DEFAULT 0, total_amount DECIMAL(12,2) DEFAULT 0, package_type VARCHAR(60) DEFAULT '',
 delivery_date DATE NULL, status VARCHAR(30) DEFAULT 'Pending', notes TEXT, pathao_consignment VARCHAR(100) DEFAULT '',
 pathao_status VARCHAR(100) DEFAULT '', created_by INT NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, FOREIGN KEY(created_by) REFERENCES users(id) ON DELETE SET NULL
);
CREATE TABLE IF NOT EXISTS inventory(
 id INT AUTO_INCREMENT PRIMARY KEY, sku VARCHAR(60) NOT NULL UNIQUE, name VARCHAR(150) NOT NULL, category VARCHAR(100) DEFAULT '',
 unit VARCHAR(30) DEFAULT 'pcs', current_stock DECIMAL(12,2) DEFAULT 0, minimum_stock DECIMAL(12,2) DEFAULT 0,
 purchase_price DECIMAL(12,2) DEFAULT 0, selling_price DECIMAL(12,2) DEFAULT 0, supplier_id INT NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 FOREIGN KEY(supplier_id) REFERENCES suppliers(id) ON DELETE SET NULL
);
CREATE TABLE IF NOT EXISTS stock_movements(
 id INT AUTO_INCREMENT PRIMARY KEY, inventory_id INT NOT NULL, movement_type ENUM('in','out') NOT NULL, quantity DECIMAL(12,2) NOT NULL,
 reference VARCHAR(120) DEFAULT '', notes VARCHAR(255) DEFAULT '', movement_date DATE NOT NULL, created_by INT NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, FOREIGN KEY(inventory_id) REFERENCES inventory(id) ON DELETE CASCADE,
 FOREIGN KEY(created_by) REFERENCES users(id) ON DELETE SET NULL
);
CREATE TABLE IF NOT EXISTS transactions(
 id INT AUTO_INCREMENT PRIMARY KEY, type ENUM('income','expense') NOT NULL, category VARCHAR(100) DEFAULT '',
 description VARCHAR(255) DEFAULT '', amount DECIMAL(12,2) NOT NULL, transaction_date DATE NOT NULL, transaction_method VARCHAR(30) DEFAULT 'Cash',
 created_by INT NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, FOREIGN KEY(created_by) REFERENCES users(id) ON DELETE SET NULL
);
ALTER TABLE purchases ADD COLUMN IF NOT EXISTS transaction_method VARCHAR(30) DEFAULT 'Cash';
ALTER TABLE purchases ADD COLUMN IF NOT EXISTS transaction_id INT NULL;
ALTER TABLE transactions ADD COLUMN IF NOT EXISTS transaction_method VARCHAR(30) DEFAULT 'Cash';

CREATE TABLE IF NOT EXISTS updates_feed(
 id INT AUTO_INCREMENT PRIMARY KEY, title VARCHAR(150) NOT NULL, details TEXT, module VARCHAR(60) DEFAULT '',
 created_by INT NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, FOREIGN KEY(created_by) REFERENCES users(id) ON DELETE SET NULL
);

INSERT INTO users(full_name,username,email,phone,password,role) VALUES
('System Administrator','admin','admin@green.local','01700000000','$2y$12$UEa18XUcdYO5ZLkHiUeFSOH5y9zEYQr.tH6bJR5V6YfB.9u27r6ny','admin'),
('Moderator User','moderator','moderator@green.local','01700000001','$2y$12$MkthwAF.VQLFgyXNEA8cleJ4DvWpH6mINanrNI85XNYwrSyqbzLOG','moderator'),
('Accounts User','accounts','accounts@green.local','01700000002','$2y$12$8tp66v2IMQTEPfHmwvBk6eC2FEQ/Mu6KmoHQoiK2biVU7GEujowNS','accounts'),
('Inventory User','inventory','inventory@green.local','01700000003','$2y$12$VDTEj1HR.QkSpKt2DdLk1OVv7euko9bYSQ4D/oBL/UESUaxeMWsGS','inventory'),
('HR User','hr','hr@green.local','01700000004','$2y$12$Ux1YWgdpI.iIyjInaSpHpOH4ba6Nt0gcyLDaycJbcXLYmvsnlahYq','hr'),
('Executive User','executive','executive@green.local','01700000005','$2y$12$ZsTUr.KB0beHqb1J8MJAj.gAXKufPFiZiqzqphswisOQ1QqIzJKaK','executive')
ON DUPLICATE KEY UPDATE full_name=VALUES(full_name),password=VALUES(password),role=VALUES(role);

INSERT INTO employees(employee_id,employee_code,name,mobile,department,designation,status,username,role,joining_date) VALUES
('EMP-001','MOD-001','Moderator User','01700000001','Management','Moderator','Active','moderator','Moderator',CURDATE()),
('EMP-002','ACC-001','Accounts User','01700000002','Accounts','Accounts Officer','Active','accounts','Accounts',CURDATE()),
('EMP-003','INV-001','Inventory User','01700000003','Inventory','Inventory Officer','Active','inventory','Inventory',CURDATE()),
('EMP-004','HR-001','HR User','01700000004','HR','HR Officer','Active','hr','HR',CURDATE()),
('EMP-005','EXE-001','Executive User','01700000005','Sales','Call Executive','Active','executive','Executive',CURDATE())
ON DUPLICATE KEY UPDATE name=VALUES(name),mobile=VALUES(mobile),username=VALUES(username),role=VALUES(role);
