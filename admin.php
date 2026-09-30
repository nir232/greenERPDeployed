<?php
require 'config.php';
if(empty($_SESSION['user_id'])){header('Location:index.php');exit;}
$pageRole='admin';
$role=$_SESSION['role'];
$name=$_SESSION['full_name'];
if($role!==$pageRole && !($pageRole==='admin' && $role==='admin')){http_response_code(403);exit('Access denied.');}
$admin=$role==='admin';
$canHR=in_array($role,['admin','hr'],true);
$canOrders=in_array($role,['admin','moderator','executive'],true);
$canAccounts=in_array($role,['admin','accounts'],true);
$canInv=in_array($role,['admin','inventory'],true);
$canAtt=!$admin;
?>
<!doctype html><html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Green ERP | <?=htmlspecialchars(ucfirst($role))?></title>
    <link rel="stylesheet" href="assets/admin.css">
</head>
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.3.1/css/all.min.css">
    <body>
<aside class="sidebar" id="sidebar"><div class="side-brand">Green<span>ERP</span></div><button class="side-link active" onclick="openSection('dashboard',this)">Dashboard</button>
<?php if($canHR):?><button class="side-link" onclick="openSection('employees')">Employees</button><?php endif;?>
<?php if($canAtt):?><button class="side-link" onclick="openSection('attendance')">Attendance</button><?php endif;?>
<?php if($canOrders):?><button class="side-link" onclick="openSection('orders')">Orders</button><?php endif;?>
<?php if($canAccounts):?><button class="side-link" onclick="openSection('accounts')">Accounts</button><?php endif;?>
<?php if($canInv):?><button class="side-link" onclick="openSection('inventory')">Inventory</button><?php endif;?>
<?php if($admin):?><button class="side-link" onclick="openSection('admin-view')">All Dashboards</button><?php endif;?>
<button class="side-link" onclick="openSection('settings')">Settings</button>
<button class="side-link logout" onclick="logout()">Logout</button></aside>
<main class="main">
    <header class="topbar">
        <button class="menu-btn" onclick="toggleSidebar()">☰</button>
        <div><span class="welcome">Welcome,</span> <strong><?=htmlspecialchars($name)?></strong></div>

        <div class="notification"><h2 id="click" onclick="openUpdates()"><i class="fa-solid fa-bell"></i></h2>
           <div id="updatesFeed" class="updates-list" style="display:none">
           <p class="muted">Loading updates...</p>
           </div>
        </div>   
        <div class="profile-menu"><button class="profile-trigger" onclick="toggleProfileMenu()"><span class="avatar-wrap"><img id="topAvatar" src="" alt="Profile"><span id="topAvatarFallback"><?=htmlspecialchars(strtoupper(substr($name,0,1)))?></span></span><span class="top-user"><?=htmlspecialchars(strtoupper($role))?></span> ▾</button>
        
        <div id="profileDropdown" class="profile-dropdown">
            <button onclick="openSection('settings');showSettingsTab('profile');closeProfileMenu()">Profile</button>
            <button onclick="openSection('settings');showSettingsTab('settings');closeProfileMenu()">Settings</button>
            <button onclick="logout()">Logout</button></div></div>
    </header>
<section id="dashboard" class="section active"><div class="section-heading"><p class="eyebrow">GREEN ERP</p><h1><?=htmlspecialchars(ucfirst($role))?> Dashboard</h1><p>Simple, connected business management.</p></div><div class="stats" id="statsCards"></div><div class="grid-two">
    
<div class="dashboard-card"><h2>Quick actions</h2><div class="action-row"><?php if($canOrders):?><button class="primary-btn" onclick="openSection('orders')">Create Order</button><?php endif;?><?php if($canHR):?><button class="primary-btn" onclick="openSection('employees')">Add Employee</button><?php endif;?><?php if($canAccounts):?><button class="primary-btn" onclick="openSection('accounts')">Accounts</button><?php endif;?><?php if($canInv):?><button class="primary-btn" onclick="openSection('inventory')">Stock</button><?php endif;?><?php if($canAtt):?><button class="primary-btn" onclick="openSection('attendance')">Attendance</button><?php endif;?></div></div></div></section>
<section id="employees" class="section"><div class="section-heading"><h1>Employees</h1><p><?php if($admin):?>Admin can add new employees and create their login credentials.<?php else:?>HR can add, edit and remove employee records.<?php endif;?></p></div><div class="dashboard-card"><form id="employeeForm" enctype="multipart/form-data">
<input type="hidden" name="id">

<div id="employeeStep1" class="employee-step">
    <div class="section-heading"><h2>Step 1 of 4 — Basic Information</h2><p>Enter the employee's basic personal information.</p></div>
    <div class="form-grid">
        <div><label>Employee ID</label><input name="employee_id" required></div>
        <div><label>Employee Code</label><input name="employee_code"></div>
        <div><label>Employee Image</label><input name="avatar" type="file" accept="image/png,image/jpeg,image/webp"></div>
        <div><label>Name</label><input name="name" required></div>
        <div><label>Mobile</label><input name="mobile" required></div>
        <div><label>Email</label><input name="email" type="email"></div>
    </div>
    <div class="action-row">
        <button class="primary-btn" type="button" onclick="employeeNext(1)">Next</button>
    </div>
</div>

<div id="employeeStep2" class="employee-step" style="display:none">
    <div class="section-heading"><h2>Step 2 of 4 — Job Information</h2><p>Enter the employee's job and employment details.</p></div>
    <div class="form-grid">
        <div><label>Department</label><input name="department" required></div>
        <div><label>Designation</label><input name="designation" required></div>
        <div><label>Employee Type</label><input name="employee_type"></div>
        <div><label>Joining Date</label><input name="joining_date" type="date"></div>
        <div><label>Status</label><select name="status"><option>Active</option><option>Inactive</option></select></div>
    </div>
    <div class="action-row">
        <button class="secondary-btn" type="button" onclick="employeeBack(2)">Back</button>
        <button class="primary-btn" type="button" onclick="employeeNext(2)">Next</button>
    </div>
</div>

<div id="employeeStep3" class="employee-step" style="display:none">
    <div class="section-heading"><h2>Step 3 of 4 — Login & Salary</h2><p>Set login credentials and salary information.</p></div>
    <div class="form-grid">
        <div><label>Username</label><input name="username"></div>
        <div><label>Login Role</label><select name="role"><option value="moderator">Moderator</option><option value="accounts">Accounts</option><option value="inventory">Inventory</option><option value="hr">HR</option><option value="executive">Executive</option></select></div>
        <div><label>Login Password</label><input name="password" type="password" placeholder="For new login"></div>
        <div><label>Basic Salary</label><input name="basic" type="number" step="0.01"></div>
        <div><label>Gross Salary</label><input name="gross" type="number" step="0.01"></div>
    </div>
    <div class="action-row">
        <button class="secondary-btn" type="button" onclick="employeeBack(3)">Back</button>
        <button class="primary-btn" type="button" onclick="employeeNext(3)">Next</button>
    </div>
</div>

<div id="employeeStep4" class="employee-step" style="display:none">
    <div class="section-heading"><h2>Step 4 of 4 — Additional Information & Save</h2><p>Add the remaining information, then save the employee.</p></div>
    <div class="form-grid">
        <div class="full-width"><label>Present Address</label><textarea name="present_address"></textarea></div>
        <div class="full-width"><label>Notes</label><textarea name="notes"></textarea></div>
    </div>
    <div class="action-row">
        <button class="secondary-btn" type="button" onclick="employeeBack(4)">Back</button>
        <button class="primary-btn" type="submit">Save Employee</button>
        <button class="secondary-btn" type="reset">Clear</button>
    </div>
</div>
</form>


</div>
<div class="table-card"><div class="table-tools"><div><strong>Employee List</strong></div>
<div class="search-box"><input id="employeeSearch" placeholder="Search employees"><button class="secondary-btn" type="button" onclick="filterTable('employeeSearch','employeeRows')">Search</button><button class="secondary-btn" type="button" onclick="clearTableSearch('employeeSearch','employeeRows')">Clear</button></div></div><div class="table-wrap"><table><thead><tr><th>ID</th><th>Name</th><th>Mobile</th><th>Department</th><th>Designation</th><th>Role</th><th>Status</th><?php if(!$admin):?><th>Actions</th><?php endif;?></tr></thead><tbody id="employeeRows"></tbody></table></div></div></section>
<section id="orders" class="section"><div class="section-heading"><h1>Orders</h1><p>Create normal orders or use AI to fill the order form.</p></div><?php if(!$admin):?><div class="dashboard-card ai-card"><h2>Create Order with AI</h2><textarea id="aiText" placeholder="Example: customer: Rahim, mobile: 01712345678, address: Mirpur Dhaka, product: Rice, quantity: 2, amount: 1200"></textarea><button class="primary-btn" type="button" onclick="aiOrder()">Create with AI</button><p class="muted">With OPENAI_API_KEY configured, full AI extraction is used. Without it, clear labelled messages can still be parsed locally.</p></div><div class="dashboard-card"><form id="orderForm"><input type="hidden" name="id"><div class="form-grid"><div><label>Customer</label><input name="customer_name" required></div><div><label>Mobile</label><input name="mobile"></div><div class="full-width"><label>Address</label><textarea name="address"></textarea></div><div><label>Product</label><input name="product"></div><div><label>Quantity</label><input name="quantity" type="number" step="0.01" value="1"></div><div><label>Unit Price</label><input name="unit_price" type="number" step="0.01" value="0"></div><div><label>Package Type</label><input name="package_type"></div><div><label>Delivery Date</label><input name="delivery_date" type="date"></div><div><label>Status</label><select name="status"><option>Pending</option><option>Processing</option><option>Confirmed</option><option>Hold</option><option>Cancelled</option><option>Returned</option></select></div><div class="full-width"><label>Notes</label><textarea name="notes"></textarea></div></div><button class="primary-btn">Save Order</button></form></div></div><?php endif;?><div class="table-card"><div class="table-tools"><strong>All Orders</strong><div class="search-box"><input id="orderSearch" placeholder="Search order/customer/product"><button class="secondary-btn" type="button" onclick="filterTable('orderSearch','orderRows')">Search</button><button class="secondary-btn" type="button" onclick="clearTableSearch('orderSearch','orderRows')">Clear</button></div></div>
<div class="table-wrap">
<table>
    <thead>
        <tr>
        <th>Order</th>
        <th>Customer</th>
        <th>Mobile</th>
        <th>Product</th>
        <th>Qty</th>
        <th>Total</th>
        <th>Status</th>
        <th>Pathao</th>
        <th>Actions</th>
        </tr>
    </thead>
    <tbody id="orderRows"></tbody>
</table>
</div>
</div>
</section>
<section id="accounts" class="section">
    <div class="section-heading"><h1>Accounts</h1><p>Suppliers, purchases, transactions and financial sheet.</p></div>
    <div class="account-cards">
        <div class="metric-card"><span>Expense</span><strong id="expenseTotal">৳ 0.00</strong></div>
        <div class="metric-card"><span>Income</span><strong id="incomeTotal">৳ 0.00</strong></div>
        <div class="metric-card"><span>Transactions</span><strong id="transactionTotal">0</strong></div>
        <div class="metric-card"><span>Revenue</span><strong id="revenueTotal">৳ 0.00</strong></div></div>
        <?php if(!$admin):?><div class="grid-two"><div class="dashboard-card"><h2>Add Supplier</h2>
            <form id="supplierForm" enctype="multipart/form-data"><input type="hidden" name="id">
            <div class="form-grid"><div><label>Supplier Name</label><input name="name" required></div>
            <div><label>Supplier Image</label><input name="avatar" type="file" accept="image/png,image/jpeg,image/webp"></div>
            <div><label>Company</label><input name="company"></div>
            <div><label>Mobile</label><input name="mobile"></div>
            <div><label>Email</label><input name="email" type="email"></div>
            <div class="full-width">
            <label>Address</label>
            <textarea name="address"></textarea></div></div>
            <button class="primary-btn">Add Supplier</button>
            <button class="secondary-btn" type="reset">Clear</button>
        </form></div><div class="dashboard-card"><h2>Purchase From Supplier</h2>
        <form id="purchaseForm"><input type="hidden" name="id"><div class="form-grid"><div><label>Supplier</label><select name="supplier_id" id="purchaseSupplier" required></select></div><div><label>Product / Item</label><input name="product" required></div><div><label>Quantity</label><input name="quantity" type="number" min="0.01" step="0.01" required></div><div><label>Unit Price</label><input name="unit_cost" type="number" min="0" step="0.01" required></div><div><label>Total</label><input id="purchaseTotal" type="number" step="0.01" readonly></div><div><label>Purchase Date</label><input name="purchase_date" type="date" value="<?=date('Y-m-d')?>" required></div><div><label>Transaction Method</label><select name="transaction_method" required><option value="Cash">Cash</option><option value="bKash">bKash</option><option value="Bank">Bank</option></select></div><div><label>Status</label><select name="status"><option>Received</option><option>Pending</option><option>Partial</option><option>Cancelled</option></select></div><div class="full-width"><label>Notes</label><textarea name="notes"></textarea></div></div><button class="primary-btn">Save Purchase</button><button class="secondary-btn" type="button" onclick="clearPurchaseForm()">Clear</button></form></div><div class="dashboard-card"><h2>New Transaction</h2><form id="transactionForm"><input type="hidden" name="id"><div class="form-grid"><div><label>Type</label><select name="type"><option value="income">Income</option><option value="expense">Expense</option></select></div><div><label>Category</label><input name="category"></div><div><label>Amount</label><input name="amount" type="number" step="0.01" required></div><div><label>Date</label><input name="transaction_date" type="date" value="<?=date('Y-m-d')?>"></div><div><label>Transaction Method</label><select name="transaction_method"><option value="Cash">Cash</option><option value="bKash">bKash</option><option value="Bank">Bank</option></select></div><div class="full-width"><label>Description</label><input name="description"></div></div><button class="primary-btn">Save Transaction</button><button class="secondary-btn" type="button" onclick="clearTransactionForm()">Clear</button></form></div></div><?php endif;?><div class="table-card"><div class="table-tools"><strong>Transaction Details</strong><div class="search-box"><input id="accountSearch" placeholder="Search transactions"><button class="secondary-btn" type="button" onclick="filterTable('accountSearch','transactionRows')">Search</button><button class="secondary-btn" type="button" onclick="clearTableSearch('accountSearch','transactionRows')">Clear</button><input id="accountMonth" type="month" value="<?=date('Y-m')?>"><button class="secondary-btn" type="button" onclick="loadAccounts()">Load Month</button></div></div><div class="table-wrap"><table><thead><tr><th>Date</th><th>Type</th><th>Category</th><th>Description</th><th>Method</th><th>Amount</th><th>Actions</th></tr></thead><tbody id="transactionRows"></tbody></table></div></div><div class="table-card"><div class="table-tools"><strong>Purchase History</strong><div class="search-box"><input id="purchaseSearch" placeholder="Search purchases"><button class="secondary-btn" type="button" onclick="filterTable('purchaseSearch','purchaseRows')">Search</button><button class="secondary-btn" type="button" onclick="clearTableSearch('purchaseSearch','purchaseRows')">Clear</button></div></div><div class="table-wrap"><table><thead><tr><th>Purchase</th><th>Supplier</th><th>Product</th><th>Qty</th><th>Unit Price</th><th>Total</th><th>Date</th><th>Method</th><th>Status</th><th>Actions</th></tr></thead><tbody id="purchaseRows"></tbody></table></div></div><div class="table-card"><div class="table-tools"><strong>Suppliers</strong><div class="search-box"><input id="supplierSearch" placeholder="Search suppliers"><button class="secondary-btn" type="button" onclick="filterTable('supplierSearch','supplierRows')">Search</button><button class="secondary-btn" type="button" onclick="clearTableSearch('supplierSearch','supplierRows')">Clear</button></div></div><div class="table-wrap"><table><thead><tr><th>Image</th><th>Name</th><th>Company</th><th>Mobile</th><th>Email</th><th>Address</th><th>Status</th><th>Buy</th><th>Actions</th></tr></thead><tbody id="supplierRows"></tbody></table></div></div>
    </section>
<section id="inventory" class="section"><div class="section-heading"><h1>Inventory</h1><p>Products, stock in and stock out.</p></div><?php if(!$admin):?><div class="dashboard-card"><form id="inventoryForm"><input type="hidden" name="id"><div class="form-grid"><div><label>SKU</label><input name="sku" required></div><div><label>Product</label><input name="name" required></div><div><label>Category</label><input name="category"></div><div><label>Unit</label><input name="unit" value="pcs"></div><div><label>Minimum Stock</label><input name="minimum_stock" type="number" step="0.01"></div><div><label>Purchase Price</label><input name="purchase_price" type="number" step="0.01"></div><div><label>Selling Price</label><input name="selling_price" type="number" step="0.01"></div><div><label>Supplier ID</label><input name="supplier_id" type="number"></div></div><button class="primary-btn">Save Product</button><button class="secondary-btn" type="button" onclick="clearInventoryForm()">Clear</button></form></div><div class="grid-two"><div class="dashboard-card"><h2>Stock In</h2><form id="stockInForm"><input type="hidden" name="id"><input type="hidden" name="movement_type" value="in"><div class="form-grid"><div><label>Inventory ID</label><input name="inventory_id" type="number" required></div><div><label>Quantity</label><input name="quantity" type="number" step="0.01" required></div><div><label>Date</label><input name="movement_date" type="date" value="<?=date('Y-m-d')?>"></div><div><label>Reference</label><input name="reference"></div><div class="full-width"><label>Notes</label><input name="notes"></div></div><button class="primary-btn">Stock In</button></form></div><div class="dashboard-card"><h2>Stock Out</h2><form id="stockOutForm"><input type="hidden" name="id"><input type="hidden" name="movement_type" value="out"><div class="form-grid"><div><label>Inventory ID</label><input name="inventory_id" type="number" required></div><div><label>Quantity</label><input name="quantity" type="number" step="0.01" required></div><div><label>Date</label><input name="movement_date" type="date" value="<?=date('Y-m-d')?>"></div><div><label>Reference</label><input name="reference"></div><div class="full-width"><label>Notes</label><input name="notes"></div></div><button class="primary-btn">Stock Out</button></form></div></div><?php endif;?><div class="table-card"><div class="table-tools"><strong>Stock List</strong><div class="search-box"><input id="inventorySearch" placeholder="Search SKU/product"><button class="secondary-btn" type="button" onclick="filterTable('inventorySearch','inventoryRows')">Search</button><button class="secondary-btn" type="button" onclick="clearTableSearch('inventorySearch','inventoryRows')">Clear</button></div></div><div class="table-wrap"><table><thead><tr><th>ID</th><th>SKU</th><th>Product</th><th>Stock</th><th>Minimum</th><th>Supplier</th><th>Status</th><th>Actions</th></tr></thead><tbody id="inventoryRows"></tbody></table></div></div><div class="table-card"><div class="table-tools"><strong>Stock Movements</strong><div class="search-box"><input id="movementSearch" placeholder="Search movements"><button class="secondary-btn" type="button" onclick="filterTable('movementSearch','movementRows')">Search</button><button class="secondary-btn" type="button" onclick="clearTableSearch('movementSearch','movementRows')">Clear</button></div></div><div class="table-wrap"><table><thead><tr><th>Date</th><th>SKU</th><th>Product</th><th>Type</th><th>Quantity</th><th>Reference</th><th>Actions</th></tr></thead><tbody id="movementRows"></tbody></table></div></div></section>
<section id="admin-view" class="section"><div class="section-heading"><h1>All Dashboards</h1><p>Admin can open the operational dashboards. Write controls remain restricted to the responsible roles.</p></div><div class="dashboard-card"><div class="dashboard-links"><button class="module-tile" onclick="location.href='moderator.php'">Moderator<br><small>View</small></button><button class="module-tile" onclick="location.href='accounts.php'">Accounts<br><small>View</small></button><button class="module-tile" onclick="location.href='inventory.php'">Inventory<br><small>View</small></button><button class="module-tile" onclick="location.href='hr.php'">HR<br><small>View</small></button><button class="module-tile" onclick="location.href='executive.php'">Executive<br><small>View</small></button><button class="module-tile" onclick="location.href='admin.php'">Admin<br><small>View</small></button></div></div></section>
<section id="settings" class="section"><div class="section-heading"><h1>Profile & Settings</h1><p>Update your profile picture, personal details and password.</p></div><div class="grid-two"><div class="dashboard-card"><h2>Profile</h2><form id="profileForm" enctype="multipart/form-data"><div class="profile-preview"><img id="profilePreview" src="" alt="Profile"><span id="profileFallback">Profile</span></div><div class="form-grid"><div><label>Profile Picture</label><input name="avatar" id="profileAvatar" type="file" accept="image/png,image/jpeg,image/webp"></div><div><label>Full Name</label><input name="full_name" id="profileName" required></div><div><label>Email</label><input name="email" id="profileEmail" type="email"></div><div><label>Phone</label><input name="phone" id="profilePhone"></div><div><label>Username</label><input id="profileUsername" disabled></div><div><label>Role</label><input id="profileRole" disabled></div></div><button class="primary-btn">Save Profile</button></form></div><div class="dashboard-card"><h2>Change Password</h2><form id="passwordForm"><label>Current Password</label><input name="old_password" type="password" required><label>New Password</label><input name="new_password" type="password" minlength="6" required><label>Confirm New Password</label><input name="confirm_password" type="password" minlength="6" required><button class="primary-btn">Change Password</button></form></div></div></section>
</main><div id="toast"></div><script>window.GREEN_ROLE=<?=json_encode($role)?>;window.GREEN_USER=<?=json_encode($name)?>;</script><script src="assets/app.js"></script>
<script src="assets/admin.js"></script>
</body></html>
