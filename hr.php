<?php
require 'config.php';
if(empty($_SESSION['user_id'])){header('Location:index.php');exit;}
$pageRole='hr';
$role=$_SESSION['role'];
$name=$_SESSION['full_name'];
if($role!=='admin' && $role!==$pageRole){http_response_code(403);exit('Access denied.');}
$admin=$role==='admin';
$canHR=in_array($role,['admin','hr'],true);
$canOrders=in_array($role,['admin','moderator','executive'],true);
$canAccounts=in_array($role,['admin','accounts'],true);
$canInv=in_array($role,['admin','inventory'],true);
$canAtt=!$admin;
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Green ERP | <?=htmlspecialchars(ucfirst($role))?></title><link rel="stylesheet" href="assets/style.css"></head><body>
<aside class="sidebar" id="sidebar"><div class="side-brand">Green<span>ERP</span></div><button class="side-link active" onclick="openSection('dashboard',this)">Dashboard</button>
<?php if($canHR):?><button class="side-link" onclick="openSection('employees',this)">Employees</button><?php endif;?>
<?php if($canAtt):?><button class="side-link" onclick="openSection('attendance',this)">Attendance</button><?php endif;?>
<?php if($canOrders):?><button class="side-link" onclick="openSection('orders',this)">Orders</button><?php endif;?>
<?php if($canAccounts):?><button class="side-link" onclick="openSection('accounts',this)">Accounts</button><?php endif;?>
<?php if($canInv):?><button class="side-link" onclick="openSection('inventory',this)">Inventory</button><?php endif;?>
<?php if($admin):?><button class="side-link" onclick="openSection('admin-view',this)">All Dashboards</button><?php endif;?>
<button class="side-link" onclick="openSection('settings',this)">Settings</button><button class="side-link logout" onclick="logout()">Logout</button></aside>
<main class="main"><header class="topbar"><button class="menu-btn" onclick="toggleSidebar()">☰</button><div><span class="welcome">Welcome,</span> <strong><?=htmlspecialchars($name)?></strong></div><div class="profile-menu"><button class="profile-trigger" onclick="toggleProfileMenu()"><span class="avatar-wrap"><img id="topAvatar" src="" alt="Profile"><span id="topAvatarFallback"><?=htmlspecialchars(strtoupper(substr($name,0,1)))?></span></span><span class="top-user"><?=htmlspecialchars(strtoupper($role))?></span> ▾</button><div id="profileDropdown" class="profile-dropdown"><button onclick="openSection('settings');showSettingsTab('profile');closeProfileMenu()">Profile</button><button onclick="openSection('settings');showSettingsTab('settings');closeProfileMenu()">Settings</button><button onclick="logout()">Logout</button></div></div></header>
<section id="dashboard" class="section active"><div class="section-heading"><p class="eyebrow">GREEN ERP</p><h1><?=htmlspecialchars(ucfirst($role))?> Dashboard</h1><p>Simple, connected business management.</p></div><div class="stats" id="statsCards"></div><div class="grid-two"><div class="dashboard-card"><h2>Updates</h2><div id="updatesFeed" class="updates-list"><p class="muted">Loading updates...</p></div></div><div class="dashboard-card"><h2>Quick actions</h2><div class="action-row"><?php if($canOrders):?><button class="primary-btn" onclick="openSection('orders')">Create Order</button><?php endif;?><?php if($canHR):?><button class="primary-btn" onclick="openSection('employees')">Add Employee</button><?php endif;?><?php if($canAccounts):?><button class="primary-btn" onclick="openSection('accounts')">Accounts</button><?php endif;?><?php if($canInv):?><button class="primary-btn" onclick="openSection('inventory')">Stock</button><?php endif;?><?php if($canAtt):?><button class="primary-btn" onclick="openSection('attendance')">Attendance</button><?php endif;?></div></div></div></section>
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
<script>
(function(){
    function stepFieldsValid(step){
        var box=document.getElementById('employeeStep'+step);
        if(!box) return true;
        var fields=box.querySelectorAll('input,select,textarea');
        for(var i=0;i<fields.length;i++){
            if(!fields[i].checkValidity()){
                fields[i].reportValidity();
                return false;
            }
        }
        return true;
    }
    window.setEmployeeStep=function(step){
        for(var i=1;i<=4;i++){
            var el=document.getElementById('employeeStep'+i);
            if(el) el.style.display=(i===step?'block':'none');
        }
    };
    window.employeeNext=function(step){
        if(stepFieldsValid(step)) setEmployeeStep(Math.min(4,step+1));
    };
    window.employeeBack=function(step){
        setEmployeeStep(Math.max(1,step-1));
    };
    document.addEventListener('DOMContentLoaded',function(){
        var form=document.getElementById('employeeForm');
        if(form){
            form.addEventListener('reset',function(){setTimeout(function(){setEmployeeStep(1);},0);});
            setEmployeeStep(1);
        }
    });
})();
</script></div><div class="table-card"><div class="table-tools"><div><strong>Employee List</strong></div><div class="search-box"><input id="employeeSearch" placeholder="Search employees"><button class="secondary-btn" type="button" onclick="filterTable('employeeSearch','employeeRows')">Search</button><button class="secondary-btn" type="button" onclick="clearTableSearch('employeeSearch','employeeRows')">Clear</button></div></div><div class="table-wrap"><table><thead><tr><th>ID</th><th>Name</th><th>Mobile</th><th>Department</th><th>Designation</th><th>Role</th><th>Status</th><?php if(!$admin):?><th>Actions</th><?php endif;?></tr></thead><tbody id="employeeRows"></tbody></table></div></div></section>
<section id="attendance" class="section"><div class="section-heading"><h1>Attendance</h1><p>Check in, breaks and check out from every non-admin dashboard.</p></div><div class="dashboard-card"><div class="action-row"><button class="primary-btn" onclick="attendance('checkin')">Check In</button><button class="primary-btn" onclick="attendance('break')">Start Break</button><button class="primary-btn" onclick="attendance('resume')">Resume</button><button class="primary-btn" onclick="attendance('checkout')">Check Out</button></div></div><div class="table-card"><div class="form-grid compact"><div><label>From</label><input id="attFrom" type="date" value="<?=date('Y-m-01')?>"></div><div><label>To</label><input id="attTo" type="date" value="<?=date('Y-m-d')?>"></div><div class="align-end"><button class="primary-btn" onclick="loadAttendance()">Refresh</button></div></div><div class="table-tools"><strong>Attendance Records</strong><div class="search-box"><input id="attendanceSearch" placeholder="Search"><button class="secondary-btn" type="button" onclick="filterTable('attendanceSearch','attendanceRows')">Search</button><button class="secondary-btn" type="button" onclick="clearTableSearch('attendanceSearch','attendanceRows')">Clear</button></div></div><div class="table-wrap"><table><thead><tr><th>Date</th><th>Employee</th><th>Department</th><th>Check In</th><th>Break</th><th>Resume</th><th>Check Out</th><th>Work</th><th>Actions</th></tr></thead><tbody id="attendanceRows"></tbody></table></div></div></section>
<section id="settings" class="section"><div class="section-heading"><h1>Profile & Settings</h1><p>Update your profile picture, personal details and password.</p></div><div class="grid-two"><div class="dashboard-card"><h2>Profile</h2><form id="profileForm" enctype="multipart/form-data"><div class="profile-preview"><img id="profilePreview" src="" alt="Profile"><span id="profileFallback">Profile</span></div><div class="form-grid"><div><label>Profile Picture</label><input name="avatar" id="profileAvatar" type="file" accept="image/png,image/jpeg,image/webp"></div><div><label>Full Name</label><input name="full_name" id="profileName" required></div><div><label>Email</label><input name="email" id="profileEmail" type="email"></div><div><label>Phone</label><input name="phone" id="profilePhone"></div><div><label>Username</label><input id="profileUsername" disabled></div><div><label>Role</label><input id="profileRole" disabled></div></div><button class="primary-btn">Save Profile</button></form></div><div class="dashboard-card"><h2>Change Password</h2><form id="passwordForm"><label>Current Password</label><input name="old_password" type="password" required><label>New Password</label><input name="new_password" type="password" minlength="6" required><label>Confirm New Password</label><input name="confirm_password" type="password" minlength="6" required><button class="primary-btn">Change Password</button></form></div></div></section>
</main><div id="toast"></div><script>window.GREEN_ROLE=<?=json_encode($role)?>;window.GREEN_USER=<?=json_encode($name)?>;</script><script src="assets/app.js"></script></body></html>
