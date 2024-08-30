<?php

namespace App\Libraries\EnumsAndConstants;

// enum ModelNames: int
// {
//     case User = 0;
//     case Order = 1;
//     case Task = 2;
//     case Input = 3;
//     case Access = 4;
//case Stock=4;
// }


abstract class ModelNames
{
    const Order = 'Order';
    const User = 'User';
    const Task = 'Task';
    const Input = 'Input';
    const Access = 'Access';
    const Stock="Stock";
    const Employee="Employee";
    const TaskDetail="TaskDetail";
    const QualityCheck="QualityCheck";
    const OrderStockMap="OrderStockMap";

    const WtStd="Wtstd";
    const Department="Department";
    const DeptEmpMap="DeptEmpMap";
}

// enum ProductType: int
// {
//     const Poly = 0;
//     const Weft = 1;
//     const Cylider = 2;
// }

// enum WorkStatus: string
// {
//     const NS = "Not Started";
//     const IP = "In Progress";
//     const C = "Completed";
// }

abstract class WorkStatus
{
    const NS = "Not started";
    const IP = "In progress";
    const C = "Completed";
}


// enum Status: int{
//     const Active=1;
//     const Inactive=0;
// }

abstract class Status{
    const Active="1";
    const Inactive="0";
}
// enum Roles: int
// {
//     const Admin = "Admin";
//     const Supervisor = "Supervisor";
//     const Employee = "Employee";
//     const Guest = "Guest";
// }

// enum Roles:string
// {
//     const Admin = "Admin";
//     const Supervisor = "Supervisor";
//     const Employee = "Employee";
//     const Guest = "Guest";
// }

abstract class Roles
{
    const Admin = "1";
    const Supervisor = "2";
    const Employee = "3";
    const Guest = "4";
}



