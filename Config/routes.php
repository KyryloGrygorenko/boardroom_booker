<?php

use Library\Route;

return  array(
    // site routes
    'default' => new Route('/', 'Default', 'index'),
    'error' => new Route('/error', 'Exception', 'handle'),

    'book' => new Route('/book_appointment', 'Appointment', 'create'),
    'appointment' => new Route('/appointment', 'Appointment', 'show'),
    'update' => new Route('/update', 'Appointment', 'update'),
    'save' => new Route('/save', 'Appointment', 'save'),
    'save_update' => new Route('/save_update', 'Appointment', 'saveUpdate'),
    'delete' => new Route('/delete', 'Appointment', 'delete'),

    'employees' => new Route('/employees', 'Employee', 'show'),
    'employee_delete' => new Route('/employee/delete', 'Employee', 'delete'),
    'employee_add' => new Route('/employee/add', 'Employee', 'add'),
    'employee_edit' => new Route('/employee/edit', 'Employee', 'edit'),
    'employee_save' => new Route('/employee/save', 'Employee', 'save'),
    'employee_edited_save' => new Route('/employee/edited/save', 'Employee', 'saveEdited'),


    'login' => new Route('/login', 'Security', 'login'),
    'logout' => new Route('/logout', 'Security', 'logout'),


);